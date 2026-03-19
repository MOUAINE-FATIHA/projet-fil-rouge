<?php

namespace App\Notifications;

use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StageAssigne extends Notification
{
    use Queueable;
    public function __construct(private Stage $stage) {}
    public function via(object $notifiable): array
    {
        return ['database'];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'stage_assigne',
            'titre'=> 'Nouveau stage à encadrer',
            'message' => "Un nouveau stage vous a été assigné : \"{$this->stage->candidature->offre->title}\" — {$this->stage->candidature->stagiaire->user->name}.",
            'url'=> '/encadrant/stages',
        ];
    }
}