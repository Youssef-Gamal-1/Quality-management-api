<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Program;

class DegreeController extends Controller
{
    public function index()
    {
        return response()->json(Degree::all(),200);
    }
    public function destroy(Program $program, Course $course, Degree $degree)
    {
        $program->courses()->findOrFail($course->id);
        $degree->delete();
        return response()->json(['success'=>'Successfully deleted'],204);
    }
}
