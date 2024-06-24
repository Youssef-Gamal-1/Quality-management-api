<?php

namespace App\Policies;

use App\Models\Indicator;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IndicatorPolicy
{
    private $program;
    private $standard;
    public function __construct()
    {
        $this->program = request()->route()->parameter('program');
        $this->standard = request()->route()->parameter('standard');
    }
    public function viewAny(User $user): bool
    {
        return $user->QM
                || $user->EC
            || ($user->programs()->where('id',$this->program->id)->exists() && $user->PC === 1)
            || $this->standard->user_id === $user->id
            || $this->standard->permissions()->where('user_id',$user->id)->exists();
    }
    public function view(User $user, Indicator $indicator): bool
    {
        return $user->QM
                || $user->EC
            || ($user->programs()->where('id',$this->program->id)->exists() && $user->PC === 1)
            || $this->standard->user_id === $user->id
            || $this->standard->permissions()->where('user_id',$user->id)->exists();
    }

}
