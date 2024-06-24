<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index(Questionnaire $questionnaire, Question $question)
    {
        abort_unless($question->questionnaire_id === $questionnaire->id, 404);
        $answers = $question->answers;

        return response([
            'data' => $answers
        ], 200);
    }

    public function store(Request $request, Questionnaire $questionnaire, Question $question)
    {
        abort_unless($question->questionnaire_id === $questionnaire->id, 404);
        $validated = $request->validate([
            'content' => 'required|string'
        ]);
        $answer = $question->answers()->create([
            'content' => $validated['content'],
            'question_id' => $question->id
        ]);

        return response()->json([
            'success' => 'Answer Created Successfully!',
            'data' => $answer
        ], 201);
    }
    public function update(Request $request, Questionnaire $questionnaire, Question $question, Answer $answer)
    {
        abort_unless($question->questionnaire_id === $questionnaire->id, 404);
        $validated = $request->validate([
            'content' => 'sometimes|string'
        ]);
        $answer = $question->answers()->update($validated);

        return response()->json([
            'success' => 'Answer Updated Successfully!'
        ], 200);
    }

    public function destroy(Questionnaire $questionnaire, Question $question, Answer $answer)
    {
        abort_unless($question->questionnaire_id === $questionnaire->id, 404);
        $answer->delete();

        return response()->json([
            'success' => 'Answer deleted successfully!'
        ], 200);
    }
}
