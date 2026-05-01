<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidatureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'statut'  => $this->status,
            'statut_label'=> match($this->status) {
                'pending'   => 'En attente',
                'reviewing' => 'En cours',
                'accepted'  => 'Acceptée',
                'rejected'  => 'Refusée',
                'withdrawn' => 'Retirée',
                default     => $this->status,
            },
            'feedback'  => $this->company_feedback,
            'date_candidature' => $this->created_at->format('d/m/Y'),
            'offre' => [
                'id'=> $this->offre->id,
                'titre' => $this->offre->title,
                'ville' => $this->offre->city,
                'entreprise' => $this->offre->entreprise->company_name ?? '—',
            ],
        ];
    }
}