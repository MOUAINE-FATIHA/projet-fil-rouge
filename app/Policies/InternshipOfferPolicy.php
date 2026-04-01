<?php

namespace App\Policies;

use App\Models\InternshipOffer;
use App\Models\User;

class InternshipOfferPolicy
{
    public function create(User $user): bool
    {
        return $user->isCompany() && $user->companyProfile?->isApproved();
    }

    public function update(User $user, InternshipOffer $offer): bool
    {
        return $user->isAdmin()
            || ($user->isCompany() && $user->companyProfile?->id === $offer->company_id);
    }

    public function delete(User $user, InternshipOffer $offer): bool
    {
        return $this->update($user, $offer);
    }

    public function viewApplications(User $user, InternshipOffer $offer): bool
    {
        return $user->isAdmin()
            || ($user->isCompany() && $user->companyProfile?->id === $offer->company_id);
    }
}
