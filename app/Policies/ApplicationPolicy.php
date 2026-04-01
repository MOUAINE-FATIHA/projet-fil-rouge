<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function withdraw(User $user, Application $application): bool
    {
        return $user->isStudent()
            && $user->studentProfile?->id === $application->student_id;
    }

    public function updateStatus(User $user, Application $application): bool
    {
        return $user->isAdmin()
            || ($user->isCompany()
                && $user->companyProfile?->id === $application->offer->company_id);
    }
}
