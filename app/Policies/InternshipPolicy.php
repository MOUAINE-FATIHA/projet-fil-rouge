<?php

namespace App\Policies;

use App\Models\Internship;
use App\Models\User;

class InternshipPolicy
{
    public function view(User $user, Internship $internship): bool
    {
        if ($user->isAdmin()) return true;

        $application = $internship->application;

        return match ($user->role) {
            'student'    => $user->studentProfile?->id === $application->student_id,
            'company'    => $user->companyProfile?->id === $application->offer->company_id,
            'supervisor' => $user->supervisorProfile?->id === $internship->supervisor_id,
            default      => false,
        };
    }

    public function updateStatus(User $user, Internship $internship): bool
    {
        return $user->isAdmin()
            || ($user->isCompany()
                && $user->companyProfile?->id === $internship->application->offer->company_id)
            || ($user->isSupervisor()
                && $user->supervisorProfile?->id === $internship->supervisor_id);
    }

    public function uploadDocument(User $user, Internship $internship): bool
    {
        return $this->view($user, $internship);
    }
}
