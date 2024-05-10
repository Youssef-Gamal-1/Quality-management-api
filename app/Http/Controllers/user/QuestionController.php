<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Questionnaire $questionnaire)
    {
        $questions = $questionnaire->questions;

        return response()->json([
            'data' => $questions
        ], 200);
    }

    public function store(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'number' => 'required|integer',
            'numberOfAnswers' => 'required|integer'
        ]);
        $question = Question::create([
            'content' => $validated['content'],
            'number' => $validated['number'],
            'numberOfAnswers' => $validated['numberOfAnswers'],
            'questionnaire_id' => $questionnaire->id
        ]);

        return response()->json([
            'success' => 'Question created successfully!',
            'data' => $question
        ], 201);
    }

    public function destroy(Questionnaire $questionnaire, Question $question)
    {
        abort_unless($question->questionnaire_id === $questionnaire->id, 404);
        $question->delete();

        return response()->json([
            'success' => 'Question deleted successfully!'
        ], 200);
    }
}
