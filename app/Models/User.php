<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      
        'is_active',
        'avatar',
        'phone',
        'bio',
    ];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'=> 'hashed',
        'is_active'=> 'boolean',
    ];
    public function profilStagiaire()
    {
        return $this->hasOne(ProfilStagiaire::class);
    }

    public function profilEntreprise()
    {
        return $this->hasOne(ProfilEntreprise::class);
    }
    public function profilEncadrant()
    {
        return $this->hasOne(ProfilEncadrant::class);
    }

    public function estStagiaire(): bool { return $this->role === 'stagiaire'; }
    public function estEntreprise(): bool { return $this->role === 'entreprise'; }
    public function estAdmin(): bool     { return $this->role === 'admin'; }
    public function estEncadrant(): bool
    {
        return $this->role === 'encadrant';
    }
}