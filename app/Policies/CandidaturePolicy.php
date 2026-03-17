<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;

class CandidaturePolicy
{
    public function retirer(User $user, Candidature $candidature): bool
    {
        return $user->estStagiaire()
            && $user->profilStagiaire?->id === $candidature->student_id;
    }

    public function gerer(User $user, Candidature $candidature): bool
    {
        return $user->estEntreprise()
            && $user->profilEntreprise?->id === $candidature->offre->company_id;
    }
}