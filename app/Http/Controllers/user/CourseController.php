<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\course\CourseCollection;
use App\Models\Course;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Course::class,'course');
    }
    public function index(Request $request, Program $program)
    {
        $courses = null;
        if($request->has('user') && $request->query('user'))
        {
            $user = User::findOrFail($request->query('user'));
            $courses = $user->courses;
        } else {
            $courses = $program->courses;
        }

        return new CourseCollection($courses);
    }

    public function getGeneralCourses()
    {
        $courses = Course::where('type','!=','program')->get();

        return new CourseCollection($courses);
    }


}
