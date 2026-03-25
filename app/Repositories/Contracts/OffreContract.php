<?php

namespace App\Repositories\Contracts;
interface OffreContract
{
    public function toutesOuvertes(array $filtres = []);
    public function trouverParId(int $id);
    public function parEntreprise(int $entrepriseId);
    public function parEntrepriseAvecStats(int $entrepriseId);
    public function creer(array $donnees);
    public function modifier(int $id, array $donnees);
    public function supprimer(int $id): bool;
}