<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\course\CourseCollection;
use App\Models\Program;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function index(Program $program)
    {
        $courses = $program->courses;

        return new CourseCollection($courses);
    }

}
