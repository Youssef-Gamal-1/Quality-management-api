<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Questionnaire;
use App\Models\StudentAnswer;
use App\Models\User;
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
            'title' => 'required|string|max:255',
            'course_id' => 'sometimes|integer|exists:courses,id',
        ]);

        $questionnaire = Questionnaire::create($validated);

        return response()->json([
            'success' => 'Questionnaire Created Successfully!',
            'data' => $questionnaire
        ], 201);
    }

    public function show(Questionnaire $questionnaire)
    {
        return response()->json([
            'data' => $questionnaire
        ], 200);
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'sometimes|integer|exists:courses,id',
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

    public function getReport(Questionnaire $questionnaire)
    {
        $numberOfStudents = StudentAnswer::where('questionnaire_id', $questionnaire->id)
            ->distinct('user_id')
            ->count('user_id');

        $numberOfAgreeStudentsOnEducator = $questionnaire->questions()
            ->where('category', 'Teacher')
            ->whereHas('answers', function ($query) {
                $query->where('content', 'Agree')
                    ->orWhere('content', 'Strongly agree')
                    ->whereHas('students',function($query){
                        $query->distinct('user_id');
                    });

            })
            ->count();

        $numberOfAgreeStudentsOnDeliverableGoals = $questionnaire->questions()
            ->where('category', 'Education Outcomes Stage')
            ->whereHas('answers', function ($query) {
                $query->where('content', 'Agree')
                    ->orWhere('content', 'Strongly agree')
                    ->whereHas('students',function($query){
                    $query->distinct('user_id');
                });
            })
            ->count();

        $numberOfAgreeStudentsOnCourseContent = $questionnaire->questions()
            ->where('category', 'Syllabus and book')
            ->whereHas('answers', function ($query) {
                $query->where('content', 'Agree')
                    ->orWhere('content', 'Strongly agree')
                    ->whereHas('students',function($query){
                        $query->distinct('user_id');
                    });
            })
            ->count();

        // Calculate ratios
        $ratioOfAgreeStudentsOnEducator = $numberOfStudents ? $numberOfAgreeStudentsOnEducator * $numberOfStudents : 0;
        $ratioOfAgreeStudentsOnDeliverableGoals = $numberOfStudents ? $numberOfAgreeStudentsOnDeliverableGoals * $numberOfStudents : 0;
        $ratioOfAgreeStudentsOnCourseContent = $numberOfStudents ? $numberOfAgreeStudentsOnCourseContent * $numberOfStudents : 0;

        return [
            'numberOfStudents' => $numberOfStudents,
            'ratioOfAgreeStudentsOnEducator' => $ratioOfAgreeStudentsOnEducator,
            'ratioOfAgreeStudentsOnDeliverableGoals' => $ratioOfAgreeStudentsOnDeliverableGoals,
            'ratioOfAgreeStudentsOnCourseContent' => $ratioOfAgreeStudentsOnCourseContent,
        ];
    }

}
