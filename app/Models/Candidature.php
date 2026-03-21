<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidature extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'applications';
    protected $fillable = [
        'student_id',
        'offer_id',
        'cover_letter',
        'cv_path',
        'status',
        'company_feedback',
        'reviewed_at',
    ];
    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function stagiaire(){
        return $this->belongsTo(ProfilStagiaire::class, 'student_id');
    }

    public function offre(){
        return $this->belongsTo(Offre::class, 'offer_id');
    }

    public function stage()
    {
        return $this->hasOne(Stage::class, 'application_id');
    }


    public function accepter(string $feedback = null): void
    {
        $this->update([
            'status'           => 'accepted',
            'company_feedback' => $feedback,
            'reviewed_at'      => now(),
        ]);

        $this->stage()->create(['status' => 'not_started']);
        $this->stagiaire->user->notify(
            new \App\Notifications\CandidatureAcceptee($this)
        );
    }

    public function refuser(string $feedback = null): void
    {
        $this->update([
            'status'           => 'rejected',
            'company_feedback' => $feedback,
            'reviewed_at'      => now(),
        ]);

        $this->stagiaire->user->notify(
            new \App\Notifications\CandidatureRefusee($this)
        );
    }

    public function estAcceptee(): bool { return $this->status === 'accepted'; }
    public function estEnAttente(): bool { return $this->status === 'pending'; }
}