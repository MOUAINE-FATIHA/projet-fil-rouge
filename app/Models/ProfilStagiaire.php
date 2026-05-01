<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilStagiaire extends Model
{
    use HasFactory;

    protected $table = 'student_profiles';

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
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'student_id');
    }

    public function stages()
    {
        return $this->hasManyThrough(
            Stage::class,
            Candidature::class,
            'student_id',
            'application_id'
        );
    }
}
