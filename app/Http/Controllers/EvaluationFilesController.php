<?php

namespace App\Http\Controllers;

use App\Models\EvaluationFiles;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvaluationFilesController extends Controller
{
    public function upload(Request $request,Standard $standard)
    {
        $validated = $request->validate([
           'path' => 'required|file|mimes:pdf,docs,jpg,png,jpeg'
        ]);

        $validated['standard_id'] = $standard->id;
        $file = $request->file('path');
        $filePath = $file->store('evaluations', 'public');
        $validated['path'] = $filePath;

        $evaluationFile = EvaluationFiles::create($validated);

        return response()->json(['msg' => 'Evaluation file saved!',201]);
    }

    public function download(Standard $standard)
    {
        $evaluation = $standard->evaluation;
        if(!$evaluation) {
            return response()->json('Not uploaded yet!!',404);
        }
        $evaluationFile = $evaluation->path;

        return Storage::disk('public')->download($evaluationFile, "EV_FOR_STANDARD_" . $standard->id);
    }


}
