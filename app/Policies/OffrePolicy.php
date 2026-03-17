<?php

namespace App\Policies;

use App\Models\Offre;
use App\Models\User;

class OffrePolicy
{
    public function creer(User $user): bool
    {
        return $user->estEntreprise()
            && $user->profilEntreprise?->estValidee();
    }
    public function modifier(User $user, Offre $offre): bool
    {
        return $user->estEntreprise()
            && $user->profilEntreprise?->id === $offre->company_id;
    }

    public function supprimer(User $user, Offre $offre): bool
    {
        return $this->modifier($user, $offre);
    }
    public function voirCandidatures(User $user, Offre $offre): bool
    {
        return $this->modifier($user, $offre);
    }
}