<?php

namespace App\Repositories\Contracts;
interface CandidatureContract
{
    public function parStagiaire(int $stagiaireId);
    public function parOffre(int $offreId, string $statut = null);
    public function dejaPostule(int $stagiaireId, int $offreId): bool;
    public function creer(array $donnees);
    public function accepter(int $id, string $feedback = null);
    public function refuser(int $id, string $feedback = null);
    public function retirer(int $id): bool;
}