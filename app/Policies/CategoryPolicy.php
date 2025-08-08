<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->can('categories:viewAny')) {
            return true;
        } else {
            return false;
        }
    }

    public function view(User $user): bool
    {
        if ($user->can('categories:view')) {
            return true;
        } else {
            return false;
        }
    }

    public function create(User $user): bool
    {
        if ($user->can('categories:create')) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user): bool
    {
        if ($user->can('categories:update')) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user): bool
    {
        if ($user->can('categories:delete')) {
            return true;
        } else {
            return false;
        }
    }
}
