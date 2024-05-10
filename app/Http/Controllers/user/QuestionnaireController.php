<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaires = Questionnaire::all();

        return response()->json([
            'data' => $questionnaires
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $questionnaire = Questionnaire::create($validated);

        return response()->json([
            'success' => 'Questionnaire Created Successfully!',
            'data' => $questionnaire
        ], 201);
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $questionnaire->update($validated);

        return response()->json([
            'success' => 'Questionnaire Updated Successfully!',
            'data' => $questionnaire
        ], 200);
    }
    public function destroy(Questionnaire $questionnaire)
    {
        $questionnaire->delete();

        return response()->json([
            'success' => 'Questionnaire deleted successfully!'
        ], 200);
    }
}
