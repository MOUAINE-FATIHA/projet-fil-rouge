<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilEntreprise extends Model
{
    use HasFactory;

    protected $table = 'company_profiles';

    protected $fillable = [
        'user_id',
        'company_name',
        'industry',
        'size',
        'website',
        'logo_path',
        'description',
        'address',
        'city',
        'country',
        'rc_number',
        'validation_status',
        'rejection_reason',
        'validated_at',
        'validated_by',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offres()
    {
        return $this->hasMany(Offre::class, 'company_id');
    }

    public function offresPubliees()
    {
        return $this->offres()->where('status', 'published');
    }

    public function estValidee(): bool
    {
        return $this->validation_status === 'approved';
    }

    public function estEnAttente(): bool
    {
        return $this->validation_status === 'pending';
    }
}