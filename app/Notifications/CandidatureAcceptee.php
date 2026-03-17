<?php

namespace App\Notifications;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CandidatureAcceptee extends Notification
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
            'type'       => 'candidature_acceptee',
            'titre'      => 'Candidature acceptée !',
            'message'    => "Votre candidature pour le poste \"{$this->candidature->offre->title}\" a été acceptée par {$this->candidature->offre->entreprise->company_name}.",
            'url'        => '/stagiaire/candidatures',
            'offre_id'   => $this->candidature->offer_id,
        ];
    }
}