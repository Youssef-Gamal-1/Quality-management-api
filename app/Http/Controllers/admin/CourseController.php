<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Course\StoreCourseRequest;
use App\Http\Requests\admin\Course\UpdateCourseRequest;
use App\Http\Resources\admin\course\CourseCollection;
use App\Http\Resources\admin\course\CourseResource;
use App\Http\Traits\ValidateProgram;
use App\Models\Course;
use App\Models\Program;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ValidateProgram;
    public function index(Program $program)
    {
        return new CourseCollection(Course::all());
    }

    public function store(StoreCourseRequest $request, Program $program)
    {
        try {
            $validated = $request->validated();
            $course = Course::create($validated);
            $course->programs()->sync($program->id);
            return new CourseResource($course);
        } catch (\Exception $exception) {
            return new \Exception($exception->getMessage());
        }
    }

    public function show(Program $program, Course $course)
    {
        try {
            $this->validateCourse($program, $course);
            return new CourseResource($course);
        } catch (\Exception $exception) {
            return new \Exception($exception->getMessage());
        }
    }
    public function update(UpdateCourseRequest $request, Program $program, Course $course)
    {
        try {
            $this->validateCourse($program, $course);
            $validated = $request->validated();
            $course->update($validated);
            return new CourseResource($course);
        } catch (\Exception $exception) {
            return new \Exception($exception->getMessage());
        }
    }

    public function destroy(Program $program, Course $course)
    {
        try {
            $this->validateCourse($program, $course);
            $course->delete();
            return response()->json(['success','Course deleted successfully!'], 204);
        } catch (\Exception $exception) {
            return new \Exception($exception->getMessage());
        }
    }
}
