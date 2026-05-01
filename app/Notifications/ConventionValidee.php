<?php

namespace App\Notifications;

use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ConventionValidee extends Notification
{
    use Queueable;

    public function __construct(private Stage $stage) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $offre = $this->stage->candidature->offre;
        $stagiaire = $this->stage->candidature->stagiaire->user;

        return [
            'type' => 'convention_validee',
            'titre' => 'Convention validée',
            'message' => "La convention du stage \"{$offre->title}\" de {$stagiaire->name} a été validée.",
            'url' => route('dashboard'),
            'stage_id' => $this->stage->id,
        ];
    }
}
