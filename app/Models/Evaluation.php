<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'evaluator_id',
        'evaluator_type',
        'technical_score',
        'behavior_score',
        'communication_score',
        'autonomy_score',
        'overall_score',
        'comments',
        'is_final',
    ];

    protected $casts = [
        'is_final' => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function getComputedOverallAttribute(): float
    {
        $scores = array_filter([
            $this->technical_score,
            $this->behavior_score,
            $this->communication_score,
            $this->autonomy_score,
        ]);

        return count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
    }
}
