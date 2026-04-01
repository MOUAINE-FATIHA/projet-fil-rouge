<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'supervisor_id',
        'actual_start_date',
        'actual_end_date',
        'status',
        'convention_path',
        'report_path',
        'student_feedback',
        'student_rating',
    ];

    protected $casts = [
        'actual_start_date' => 'date',
        'actual_end_date'   => 'date',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(SupervisorProfile::class, 'supervisor_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function companyEvaluation()
    {
        return $this->hasOne(Evaluation::class)->where('evaluator_type', 'company');
    }

    public function supervisorEvaluation()
    {
        return $this->hasOne(Evaluation::class)->where('evaluator_type', 'supervisor');
    }

    // Shortcut vers l'étudiant
    public function student()
    {
        return $this->hasOneThrough(
            StudentProfile::class,
            Application::class,
            'id',           // FK on applications
            'id',           // FK on student_profiles
            'application_id',
            'student_id'
        );
    }

    // Shortcut vers l'offre
    public function offer()
    {
        return $this->hasOneThrough(
            InternshipOffer::class,
            Application::class,
            'id',
            'id',
            'application_id',
            'offer_id'
        );
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getAverageScoreAttribute(): ?float
    {
        $evals = $this->evaluations;
        if ($evals->isEmpty()) return null;

        return $evals->avg('overall_score');
    }
}
