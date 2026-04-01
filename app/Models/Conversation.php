<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'internship_id',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function getUnreadCountForUser(User $user): int
    {
        $pivot = $this->participants()->where('user_id', $user->id)->first()?->pivot;
        if (!$pivot || !$pivot->last_read_at) {
            return $this->messages()->count();
        }

        return $this->messages()
                    ->where('created_at', '>', $pivot->last_read_at)
                    ->where('sender_id', '!=', $user->id)
                    ->count();
    }

    public function markAsReadForUser(User $user): void
    {
        $this->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);
    }
}
