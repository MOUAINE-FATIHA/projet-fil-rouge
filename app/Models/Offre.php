<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offre extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'internship_offers';

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'domain',
        'type',
        'duration_months',
        'start_date',
        'end_date',
        'application_deadline',
        'city',
        'is_remote',
        'stipend',
        'required_skills',
        'required_level',
        'slots',
        'status',
    ];
    protected $casts = [
        'start_date'=> 'date',
        'end_date' => 'date',
        'application_deadline' => 'date',
        'is_remote' => 'boolean',
        'stipend' => 'decimal:2',
        'required_skills'=> 'array',
    ];

    public function entreprise()
    {
        return $this->belongsTo(ProfilEntreprise::class, 'company_id');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'offer_id');
    }

    public function candidaturesAcceptees()
    {
        return $this->candidatures()->where('status', 'accepted');
    }
    public function scopePubliees($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOuvertes($query)
    {
        return $query->publiees()->where(function ($q) {
            $q->whereNull('application_deadline')
              ->orWhere('application_deadline', '>=', now());
        });
    }
    public function estFermee(): bool
    {
        return $this->status === 'closed'
            || ($this->application_deadline && $this->application_deadline->isPast());
    }

    public function aDesPlacesDisponibles(): bool
    {
        return $this->candidaturesAcceptees()->count() < $this->slots;
    }
}