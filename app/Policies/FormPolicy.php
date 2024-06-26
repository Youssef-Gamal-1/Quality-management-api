<?php

namespace App\Policies;

use App\Models\Form;
use App\Models\Indicator;
use App\Models\Program;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormPolicy
{

    public function update(User $user, Form $form): bool
    {
        $this->program = request()->route()->parameter("program");
        $this->standard = request()->route()->parameter("standard");
        return $user->QM
            || ($user->programs()->where('id',$this->program->id)->exists() && $user->PC === 1)
            || $this->standard->user_id === $user->id
            || $this->standard->permissions()->where('user_id',$user->id)->exists()
            || $form->permissions()->where('user_id',$user->id)->exists();
    }

}
