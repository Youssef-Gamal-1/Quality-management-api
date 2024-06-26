<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Course\StoreCourseRequest;
use App\Http\Requests\admin\Course\UpdateCourseRequest;
use App\Http\Resources\admin\course\CourseCollection;
use App\Http\Resources\admin\course\CourseResource;
use App\Http\Traits\ValidateProgram;
use App\Models\Course;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    use ValidateProgram;
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

    public function update(UpdateCourseRequest $request, Program $program, Course $course)
    {
        try {
            $this->validateCourse($program, $course);

            $validated = $request->validated();

            // Sync users
            $course->users()->sync($validated['users']);
            // Sync programs
            $programs = $validated['programs'] ?? [$program->id];
            $course->programs()->sync($programs);

            $course->update($validated);

            return new CourseResource($course);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function store(StoreCourseRequest $request, Program $program)
    {
        $validated = $request->validated();

        try {
            if(isset($validated['type'])) {
                $course = Course::create($validated);
            } else {
                $course = $program->courses()->create($validated);
            }
            // Sync users
            if(isset($validated['users'])) {
                $userIds = $validated['users'];
                $users = User::whereIn('id', $userIds)->get();
                foreach($users as $user) {
                    $user->update(['TS' => true]);
                    $user->courses()->syncWithoutDetaching([$course->id]);
                }
            }
            // Sync programs
            if(!isset($validated['type'])) {
                $programs = $validated['programs'] ?? [$program->id];
                $course->programs()->sync($programs);
            }

            return new CourseResource($course);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Failed to store course.'], 500);
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

    public function destroy(string $id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();
            return response()->json(['success' => 'Course deleted successfully!'], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
    public function getGeneralCourses()
    {
        $courses = Course::where('type','!=','program')->get();

        return new CourseCollection($courses);
    }


}
