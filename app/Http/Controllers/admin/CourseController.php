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
        return new CourseCollection($program->courses);
    }

    public function store(StoreCourseRequest $request, Program $program)
    {
        $validated = $request->validated();

        try {
            $course = Course::create($validated);

            // Sync programs
            $programs = $validated['programs'] ?? [$program->id];
            $course->programs()->sync($programs);

            return new CourseResource($course);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function show(Program $program, Course $course)
    {
        try {
            $this->validateCourse($program, $course);
            return new CourseResource($course);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
    public function update(UpdateCourseRequest $request, Program $program, Course $course)
    {
        try {
            $this->validateCourse($program, $course);
            $validated = $request->validated();
            // Sync programs
            $programs = $validated['programs'] ?? [$program->id];
            $course->programs()->sync($programs);
            $course->update($validated);
            return new CourseResource($course);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function destroy(Program $program, Course $course)
    {
        try {
            $this->validateCourse($program, $course);
            foreach($course->users()->get() as $user) {
                $user->TS = false;
            }
            $course->delete();
            return response()->json(['success' => 'Course deleted successfully!'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

}
