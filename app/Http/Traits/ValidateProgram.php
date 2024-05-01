<?php

namespace App\Http\Traits;

use App\Models\Course;
use App\Models\Program;
use App\Models\Standard;

trait ValidateProgram
{
    public function validateStandard(Program $program, Standard $standard)
    {
        if($program->id !== $standard->program->id)
        {
            throw new \Exception('Standard does not belong to the specified program.', 404);
        }
    }
    public function validateCourse(Program $program, Course $course)
    {
        $course->programs()->findOrFail($program->id);
    }

}
