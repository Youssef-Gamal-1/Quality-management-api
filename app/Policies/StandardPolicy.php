<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class StandardPolicy
{
    private $program;
    public function __construct()
    {
        $this->program = request()->route()->parameter('program');
    }
    public function viewAny(User $user)
    {
        return $user->QM
            || $user->SC
            || ($user->programs()->where('id',$this->program->id)->exists() && $user->PC === 1);
    }
    public function view(User $user, Standard $standard): bool
    {
        return $user->QM
            || $standard->user_id === $user->id
            || ($user->programs()->where('id',$this->program->id)->exists() && $user->PC === 1)
            || $standard->permissions()->where('user_id',$user->id)->exists();
    }

    public function update(User $user, Standard $standard): bool
    {
        return $user->QM
        || $standard->user_id === $user->id
        || ($user->programs()->where('id',$this->program->id)->exists() && $user->PC === 1);
    }


}
