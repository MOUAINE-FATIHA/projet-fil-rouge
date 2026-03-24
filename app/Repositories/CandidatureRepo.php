<?php

namespace App\Repositories;

use App\Models\Candidature;
use App\Repositories\Contracts\CandidatureContract;

class CandidatureRepo implements CandidatureContract
{
    public function __construct(private Candidature $model) {}
    public function parStagiaire(int $stagiaireId)
    {
        return $this->model
            ->where('student_id', $stagiaireId)
            ->with(['offre.entreprise.user', 'stage'])
            ->latest()
            ->paginate(10);
    }
    public function parOffre(int $offreId, string $statut = null)
    {
        return $this->model
            ->where('offer_id', $offreId)
            ->with('stagiaire.user')
            ->when($statut, fn ($q) => $q->where('status', $statut))
            ->latest()
            ->paginate(15);
    }
    public function dejaPostule(int $stagiaireId, int $offreId): bool
    {
        return $this->model
            ->where('student_id', $stagiaireId)
            ->where('offer_id', $offreId)
            ->exists();
    }
    public function creer(array $donnees)
    {
        return $this->model->create($donnees);
    }

    public function accepter(int $id, string $feedback = null)
    {
        $candidature = $this->model->findOrFail($id);
        $candidature->accepter($feedback);
        return $candidature->fresh('offre', 'stage');
    }

    public function refuser(int $id, string $feedback = null)
    {
        $candidature = $this->model->findOrFail($id);
        $candidature->refuser($feedback);
        return $candidature->fresh('offre');
    }

    public function retirer(int $id): bool
    {
        $candidature = $this->model->findOrFail($id);
        $candidature->update(['status' => 'withdrawn']);
        return true;
    }
}