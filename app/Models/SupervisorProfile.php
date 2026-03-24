<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'university',
        'specialization',
        'max_students',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internships()
    {
        return $this->hasMany(Internship::class, 'supervisor_id');
    }

    public function activeInternships()
    {
        return $this->internships()->where('status', 'in_progress');
    }

    public function evaluations()
    {
        return $this->hasManyThrough(
            Evaluation::class,
            Internship::class,
            'supervisor_id',
            'internship_id'
        );
    }

    public function hasCapacity(): bool
    {
        return $this->activeInternships()->count() < $this->max_students;
    }
}
