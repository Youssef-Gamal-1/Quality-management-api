<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\DegreesCollection;
use App\Http\Resources\DegreesResource;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Program;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseDegreeController extends Controller
{
    public function index(Program $program, Course $course)
    {
        abort_unless($program->courses()->where('course_id', $course->id)->exists(), 404);
        $degrees = $course->degrees;

        return new DegreesCollection($degrees);
    }

    public function store(Request $request, Program $program, Course $course)
    {
        abort_unless($program->courses()->where('course_id', $course->id)->exists(), 404);
        $validated = $request->validate([
            'year' => 'required|integer',
            'semester' => 'required|string|max:255',
            'success_ratio' => 'required|string',
            'path' => 'file|mimes:pdf,docx,xsv|max:2048'
        ]);

        $degree = Degree::create([
            'year' => $validated['year'],
            'semester' => $validated['semester'],
            'success_ratio' => $validated['success_ratio'],
            'path' => $request->file('path')->store('degrees', 'public'),
            'course_id' => $course->id,
        ]);

        return response()->json([
            'success' => 'Degrees saved successfully!',
            new DegreesResource($degree)
        ],201);
    }

    public function destroy(Program $program, Course $course, Degree $degree)
    {
        abort_unless($program->courses()->where('course_id', $course->id)->exists(), 404);
        $degree = $course->degrees()->findOrFail($degree->id);
        Storage::disk('public')->delete($degree->path);
        $degree->delete();

        return response()->json(['success' => 'Deleted successfully!'],200);
    }
}
