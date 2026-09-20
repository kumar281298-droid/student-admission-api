<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Filtered in controller query scope per role
    }

    public function view(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCollegeAdmin()) {
            return $user->college_id === $application->college_id;
        }

        if ($user->isStudent()) {
            return $user->student && $user->student->id === $application->student_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isStudent() && $user->student !== null;
    }

    public function approve(User $user, Application $application): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCollegeAdmin()) {
            return $user->college_id === $application->college_id;
        }

        return false;
    }

    public function reject(User $user, Application $application): bool
    {
        return $this->approve($user, $application);
    }
}
