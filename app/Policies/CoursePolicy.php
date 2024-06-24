<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->QM
            || $user->PC
            || $user->TS;
    }

    public function view(User $user, Course $course): bool
    {
        return true;
    }


    public function create(User $user): bool
    {
        return $user->QM
            || $user->PC;
    }


    public function update(User $user, Course $course): bool
    {
        return $user->QM
            || $user->PC
            || $user->courses()->where('id',$course->id)->exists();
    }


    public function delete(User $user): bool
    {
        return $user->QM
            || $user->PC;
    }


}
