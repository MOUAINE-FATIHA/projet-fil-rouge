<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCandidature extends Notification
{
    use Queueable;

    public function __construct(private Candidature $candidature) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'nouvelle_candidature',
            'titre' => 'Nouvelle candidature',
            'message' => "{$this->candidature->stagiaire->user->name} a postulé à l'offre \"{$this->candidature->offre->title}\".",
            'url' => route('entreprise.candidatures.index', $this->candidature->offre),
            'candidature_id' => $this->candidature->id,
            'offre_id' => $this->candidature->offer_id,
        ];
    }
}
