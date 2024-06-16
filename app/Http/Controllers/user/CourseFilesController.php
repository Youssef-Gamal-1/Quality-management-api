<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseFiles;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseFilesController extends Controller
{
    public function index(Program $program, Course $course)
    {
        $course = $program->courses()->findOrFail($course->id);
        $courseFiles = $course->files()->where('title','!=','report')->get();

        return response()->json(['data' => $courseFiles],200);
    }

    public function store(Request $request, Program $program, Course $course)
    {
        // Ensure the course belongs to the program
        $course = $program->courses()->findOrFail($course->id);

        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5000',
        ]);

        // Store the file
        $file = $request->file('path');
        $filePath = $file->store('courses', 'public');

        $validated['path'] = $filePath;
        $validated['course_id'] = $course->id;
        $validated['uploaded'] = true;

        try {
            $courseFiles = CourseFiles::create($validated);
        } catch (\Exception $e) {
            return response()->json(['error' => 'File could not be saved'], 500);
        }

        return response()->json(['data' => $courseFiles], 201);
    }


    public function download(Request $request, Program $program, Course $course, string $id)
    {
        // Ensure the course belongs to the program
        $course = $program->courses()->findOrFail($course->id);
        $courseFile = $course->files()->findOrFail($id);

        $filePath = $courseFile->path;

        // Ensure the file exists in the storage
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Return the file as a download response
        return Storage::disk('public')->download($filePath, $courseFile->title);
    }

    public function downloadReport(Request $request, Program $program, Course $course)
    {
        $course = $program->courses()->findOrFail($course->id);
        $courseFile = $course->files()->where('title', 'report')->firstOrFail();
        $filePath = $courseFile->path;

        // Ensure the file exists in the storage
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Return the file as a download response
        return Storage::disk('public')->download($filePath, $courseFile->title);
    }


    public function destroy(Request $request, Program $program, Course $course, string $id)
    {
        // Ensure the course belongs to the program
        $course = $program->courses()->findOrFail($course->id);
        $courseFile = $course->files()->findOrFail($id);
        // Get the file path from the course file
        $filePath = $courseFile->path;
        // Delete the file from the storage
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $courseFile->delete();
        return response()->json(['message' => 'File deleted successfully'], 200);
    }

}
