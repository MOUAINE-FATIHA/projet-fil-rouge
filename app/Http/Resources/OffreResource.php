<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OffreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'titre'                => $this->title,
            'description'          => $this->description,
            'domaine'              => $this->domain,
            'type'                 => $this->type,
            'duree_mois'           => $this->duration_months,
            'date_debut'           => $this->start_date?->format('d/m/Y'),
            'date_limite'          => $this->application_deadline?->format('d/m/Y'),
            'ville'                => $this->city,
            'teletravail'          => $this->is_remote,
            'gratification'        => $this->stipend,
            'competences'          => $this->required_skills ?? [],
            'niveau_requis'        => $this->required_level,
            'places'               => $this->slots,
            'statut'               => $this->status,
            'entreprise'           => [
                'nom'     => $this->entreprise->company_name ?? '—',
                'ville'   => $this->entreprise->city ?? null,
                'secteur' => $this->entreprise->industry ?? null,
            ],
            'nb_candidatures'      => $this->candidatures()->count(),
            'created_at'           => $this->created_at->format('d/m/Y'),
        ];
    }
}