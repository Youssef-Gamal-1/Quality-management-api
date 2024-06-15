<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAnswerController extends Controller
{
    public function __invoke(Request $request, Questionnaire $questionnaire)
    {
        $studentAnswersData = $request->input('data');

        DB::beginTransaction();
        try {
            foreach ($studentAnswersData as $answerData) {
                StudentAnswer::create([
                    'user_id' => $answerData['user_id'],
                    'questionnaire_id' => $questionnaire->id,
                    'question_id' => $answerData['question_id'],
                    'answer_id' => $answerData['answer_id']
                ]);
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred while processing the batch.'], 500);
        }
    }

}
