<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;
    protected $table = 'internships';
    protected $fillable = [
        'application_id',
        'supervisor_id',
        'school_name',
        'actual_start_date',
        'actual_end_date',
        'status',
        'convention_path',
        'convention_status',
        'convention_validated_at',
        'convention_place',
        'convention_tasks',
        'convention_notes',
        'convention_prepared_at',
        'report_path',
        'student_feedback',
        'student_rating',
    ];
    protected $casts = [
        'actual_start_date' => 'date',
        'actual_end_date'   => 'date',
        'convention_validated_at' => 'datetime',
        'convention_prepared_at' => 'datetime',
    ];

    public function candidature()
    {
        return $this->belongsTo(Candidature::class, 'application_id');
    }
    public function estEnCours(): bool  { return $this->status === 'in_progress'; }
    public function estTermine(): bool  { return $this->status === 'completed'; }
    public function conventionPreparee(): bool { return !empty($this->convention_prepared_at); }
    public function conventionDeposee(): bool { return !empty($this->convention_path); }
    public function conventionValidee(): bool { return $this->convention_status === 'validated'; }

    public function encadrant()
    {
        return $this->belongsTo(ProfilEncadrant::class, 'supervisor_id');
    }
}
