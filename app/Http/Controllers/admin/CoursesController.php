<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\course\CourseCollection;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function __invoke(): CourseCollection
    {
        return new CourseCollection(Course::all());
    }
}
