<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternshipOffer extends Model
{
    use HasFactory, SoftDeletes;

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
        'start_date'           => 'date',
        'end_date'             => 'date',
        'application_deadline' => 'date',
        'is_remote'            => 'boolean',
        'stipend'              => 'decimal:2',
        'required_skills'      => 'array',
    ];


    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'offer_id');
    }

    public function pendingApplications()
    {
        return $this->applications()->where('status', 'pending');
    }

    public function acceptedApplications()
    {
        return $this->applications()->where('status', 'accepted');
    }


    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOpen($query)
    {
        return $query->published()
                     ->where(function ($q) {
                         $q->whereNull('application_deadline')
                           ->orWhere('application_deadline', '>=', now());
                     });
    }

    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }


    public function isClosed(): bool
    {
        return $this->status === 'closed'
            || ($this->application_deadline && $this->application_deadline->isPast());
    }

    public function hasAvailableSlots(): bool
    {
        return $this->acceptedApplications()->count() < $this->slots;
    }
}
