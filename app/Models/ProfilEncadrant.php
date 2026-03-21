<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilEncadrant extends Model
{
    use HasFactory;
    protected $table = 'supervisor_profiles';
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

    public function stages()
    {
        return $this->hasMany(Stage::class, 'supervisor_id');
    }

    public function stagesEnCours()
    {
        return $this->stages()->where('status', 'in_progress');
    }

    public function aDeCapacite(): bool
    {
        return $this->stagesEnCours()->count() < $this->max_students;
    }
}