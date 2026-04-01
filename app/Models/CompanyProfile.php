<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

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

    // ─── Relations ───────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offers()
    {
        return $this->hasMany(InternshipOffer::class, 'company_id');
    }

    public function publishedOffers()
    {
        return $this->offers()->where('status', 'published');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function isApproved(): bool
    {
        return $this->validation_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->validation_status === 'pending';
    }
}
