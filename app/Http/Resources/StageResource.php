<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'statut'            => $this->status,
            'statut_label'      => match($this->status) {
                'not_started' => 'Non commencé',
                'in_progress' => 'En cours',
                'completed'   => 'Terminé',
                'interrupted' => 'Interrompu',
                default       => $this->status,
            },
            'date_debut'        => $this->actual_start_date?->format('d/m/Y'),
            'date_fin'          => $this->actual_end_date?->format('d/m/Y'),
            'convention'        => !is_null($this->convention_path),
            'rapport'           => !is_null($this->report_path),
            'offre'             => [
                'titre'      => $this->candidature->offre->title ?? '—',
                'entreprise' => $this->candidature->offre->entreprise->company_name ?? '—',
                'ville'      => $this->candidature->offre->city ?? null,
            ],
        ];
    }
}