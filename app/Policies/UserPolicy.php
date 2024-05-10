<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->SC;
    }
    public function view(User $user, User $model): bool
    {
        return $user->QM || $user->PC || $user->SC || $model->id === $user->id;
    }

    public function update(User $user, User $model): bool
    {
        return $model->id === $user->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $model->id === $user->id;
    }

    public function authorizeUser(User $user, User $model): bool
    {
        return $model->id === $user->id;
    }


}
