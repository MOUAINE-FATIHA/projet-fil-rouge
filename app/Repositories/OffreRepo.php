<?php

namespace App\Repositories;

use App\Models\Offre;
use App\Repositories\Contracts\OffreContract;

class OffreRepo implements OffreContract
{
    public function __construct(private Offre $model) {}

    public function toutesOuvertes(array $filtres = [])
    {
        $query = $this->model->with('entreprise.user')->ouvertes();

        if (!empty($filtres['domaine'])) {
            $query->where('domain', $filtres['domaine']);
        }
        if (!empty($filtres['ville'])) {
            $query->where('city', 'like', "%{$filtres['ville']}%");
        }

        if (!empty($filtres['type'])) {
            $query->where('type', $filtres['type']);
        }

        if (!empty($filtres['recherche'])) {
            $query->where(function ($q) use ($filtres) {
                $q->where('title', 'like', "%{$filtres['recherche']}%")
                  ->orWhere('description', 'like', "%{$filtres['recherche']}%");
            });
        }

        return $query->latest()->paginate(12);
    }

    public function trouverParId(int $id)
    {
        return $this->model->with('entreprise.user')->findOrFail($id);
    }

    public function parEntreprise(int $entrepriseId)
    {
        return $this->model
            ->where('company_id', $entrepriseId)
            ->latest()
            ->paginate(10);
    }

    public function parEntrepriseAvecStats(int $entrepriseId)
    {
        return $this->model
            ->where('company_id', $entrepriseId)
            ->withCount([
                'candidatures',
                'candidatures as en_attente'  => fn ($q) => $q->where('status', 'pending'),
                'candidatures as acceptees'   => fn ($q) => $q->where('status', 'accepted'),
            ])
            ->latest()
            ->paginate(10);
    }

    public function creer(array $donnees)
    {
        return $this->model->create($donnees);
    }
    public function modifier(int $id, array $donnees)
    {
        $offre = $this->model->findOrFail($id);
        $offre->update($donnees);
        return $offre->fresh();
    }
    public function supprimer(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}