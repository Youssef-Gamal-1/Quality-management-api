<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Program;
use Illuminate\Http\Request;

class CourseFilesController extends Controller
{
    public function index(Program $program, Course $course)
    {
        $course = $program->courses()->findOrFail($course->id);
        $courseFiles = $course->files;

        return response()->json(['data' => $courseFiles],200);
    }

    public function store(Request $request, Program $program, Course $course)
    {

    }

    public function upload(Request $request, Program $program, Course $course)
    {

    }

    public function destroy(Request $request, Program $program, Course $course)
    {

    }
}
