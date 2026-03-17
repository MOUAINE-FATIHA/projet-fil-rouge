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

    public function candidature()
    {
        return $this->belongsTo(Candidature::class, 'application_id');
    }

    public function estEnCours(): bool  { return $this->status === 'in_progress'; }
    public function estTermine(): bool  { return $this->status === 'completed'; }

    // Ajouter dans la section Relations
    public function encadrant()
    {
        return $this->belongsTo(ProfilEncadrant::class, 'supervisor_id');
    }
}