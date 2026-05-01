<?php

namespace App\Notifications;

use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ConventionDeposee extends Notification
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
            'type' => 'convention_deposee',
            'titre' => 'Convention signée déposée',
            'message' => "La convention signée du stage \"{$offre->title}\" de {$stagiaire->name} est prête à valider.",
            'url' => route('encadrant.stages.show', $this->stage),
            'stage_id' => $this->stage->id,
        ];
    }
}
