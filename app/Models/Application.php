<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

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


    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function offer()
    {
        return $this->belongsTo(InternshipOffer::class, 'offer_id');
    }

    public function internship()
    {
        return $this->hasOne(Internship::class);
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function accept(string $feedback = null): void
    {
        $this->update([
            'status'           => 'accepted',
            'company_feedback' => $feedback,
            'reviewed_at'      => now(),
        ]);

        $this->internship()->create([
            'status' => 'not_started',
        ]);
    }

    public function reject(string $feedback = null): void
    {
        $this->update([
            'status'           => 'rejected',
            'company_feedback' => $feedback,
            'reviewed_at'      => now(),
        ]);
    }
}
