<?php

namespace App\Notifications;

use App\Models\Stage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ConventionPreparee extends Notification
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

        return [
            'type' => 'convention_preparee',
            'titre' => 'Convention prête à signer',
            'message' => "La convention du stage \"{$offre->title}\" est prête. Merci de la signer puis de déposer le PDF signé.",
            'url' => route('entreprise.candidatures.show', $this->stage->candidature),
            'stage_id' => $this->stage->id,
        ];
    }
}
