<?php

namespace App\Http\Controllers;

use App\Models\CollegeFiles;
use Illuminate\Http\Request;

class CollegeFilesController extends Controller
{
    public function index()
    {
        $files = CollegeFiles::all();

        return response()->json(['data' => $files]);
    }

    public function download(string $id)
    {
        $file = CollegeFiles::findOrFail($id);
        if(!$file)
        {
            return response()->json([
                'error' => 'File does not exist'
            ],404);
        }
        return response()->download(storage_path('app/public/'.$file->path));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'path' => 'required|file|mimes:pdf,jpg,jpeg,png,docs'
        ]);

        $file = $request->file('path');
        $path = $file->store('college', 'public');
        $validated['path'] = $path;
        $file = CollegeFiles::create($validated);

        return response()->json(['data' => $file]);
    }
}
