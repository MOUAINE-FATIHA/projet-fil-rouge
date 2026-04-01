<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'field_of_study',
        'academic_level',
        'university',
        'city',
        'skills',
        'languages',
        'cv_path',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'graduation_year',
    ];

    protected $casts = [
        'skills'    => 'array',
        'languages' => 'array',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'student_id');
    }

    public function acceptedApplications()
    {
        return $this->applications()->where('status', 'accepted');
    }

    public function internships()
    {
        return $this->hasManyThrough(
            Internship::class,
            Application::class,
            'student_id',   // FK on applications
            'application_id' // FK on internships
        );
    }
}
