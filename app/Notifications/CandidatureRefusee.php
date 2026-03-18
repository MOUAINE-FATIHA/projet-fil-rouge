<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CandidatureRefusee extends Notification
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
            'type'=> 'candidature_refusee',
            'titre'=> 'Candidature non retenue',
            'message' => "Votre candidature pour \"{$this->candidature->offre->title}\" n'a pas été retenue.",
            'url'=> '/stagiaire/candidatures',
        ];
    }
}