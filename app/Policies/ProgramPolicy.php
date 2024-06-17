<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->QM
            || $user->TS
            || $user->PC
            || $user->SC
            || $user->EC;
    }
    public function view(User $user, Program $program): bool
    {
        return $user->QM
            || $user->programs()->where('id',$program->id)->exists()
            || $user->standard()->where('user_id',$user->id)->exists();
    }

    public function update(User $user, Program $program): bool
    {
        $authorized = $user->QM;
        if($user->PC) {
            $authorized = $user->programs()->where('id',$program->id)->exists();
        }
        return $authorized;
    }

}
