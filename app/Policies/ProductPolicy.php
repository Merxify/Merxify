<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if ($user->can('products:viewAny')) {
            return true;
        } else {
            return false;
        }
    }

    public function view(User $user): bool
    {
        if ($user->can('products:view')) {
            return true;
        } else {
            return false;
        }
    }

    public function create(User $user): bool
    {
        if ($user->can('products:create')) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user): bool
    {
        if ($user->can('products:update')) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user): bool
    {
        if ($user->can('products:delete')) {
            return true;
        } else {
            return false;
        }
    }
}
