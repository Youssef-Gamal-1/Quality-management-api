<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\user\auth\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    // users route
    Route::apiResource('/users',\App\Http\Controllers\user\UserController::class)
        ->except('store');
    // user permissions
    Route::get('/users/{user}/permissions',[\App\Http\Controllers\admin\UserPermissionsController::class,'getUserPermissions']);
    // special route to help manage frontend authorization process
    Route::get('/userAuth/{user}', [\App\Http\Controllers\user\UserController::class,'authorizeUser']);
    // programs route
    Route::apiResource('/programs',\App\Http\Controllers\user\ProgramController::class)
        ->except(['store','destroy']);
    // standards route
    Route::apiResource('/programs/{program}/standards',\App\Http\Controllers\user\StandardController::class);
    // indicators route
    Route::apiResource('/programs/{program}/standards/{standard}/indicators',
        \App\Http\Controllers\user\IndicatorController::class)
        ->only(['index','show']);
    // update form {upload file, fill inputs}
    Route::put('/programs/{program}/standards/{standard}/indicators/{indicator}/forms/{form}',
        [\App\Http\Controllers\user\FormController::class,'update']);
    Route::post('/programs/{program}/standards/{standard}/indicators/{indicator}/forms/{form}',
        [\App\Http\Controllers\user\FormController::class,'uploadFile']);
    // courses route
    Route::get('/programs/{program}/courses',[\App\Http\Controllers\user\CourseController::class,'index']);
    // course Files routes
    Route::apiResource('/programs/{program}/courses/{course}/files',\App\Http\Controllers\user\CourseFilesController::class);
    // download course report
    Route::get('/programs/{program}/courses/{course}/download',[\App\Http\Controllers\user\CourseFilesController::class,'downloadReport']);
    // download course file
    Route::get('/programs/{program}/courses/{course}/files/{fileId}/download',[\App\Http\Controllers\user\CourseFilesController::class,'download']);
    // Course Degrees
    Route::apiResource('/programs/{program}/courses/{course}/degrees',\App\Http\Controllers\user\CourseDegreeController::class)
        ->only(['index','store','destroy']);
    // general course degree store
    Route::post('/courses/{course}/degrees',[\App\Http\Controllers\user\CourseDegreeController::class,'storeGeneralCoursesDegree']);
    // general course degree list
    Route::get('/courses/{course}/degrees',[\App\Http\Controllers\user\CourseDegreeController::class,'getGeneralCoursesDegree']);
    Route::get('/download/degree/{id}', [\App\Http\Controllers\user\CourseDegreeController::class, 'download']);
    // Questionnaire routes
    Route::apiResource('/questionnaires',\App\Http\Controllers\user\QuestionnaireController::class);
    Route::post('/questionnaires/{questionnaire}/studentAnswers',\App\Http\Controllers\user\StudentAnswerController::class);
    Route::apiResource('/questionnaires/{questionnaire}/questions',\App\Http\Controllers\user\QuestionController::class)
        ->only(['index','store','destroy']);
    Route::apiResource('/questionnaires/{questionnaire}/questions/{question}/answers',
        \App\Http\Controllers\user\AnswerController::class)
        ->except('show');
    // Invokable class to get all courses of the system
    Route::get('/courses',\App\Http\Controllers\admin\CoursesController::class);
    Route::get('/generalCourses',[\App\Http\Controllers\user\CourseController::class,'getGeneralCourses']);
    // Questionnaire report
    Route::get('/questionnaires/{questionnaire}/report',[\App\Http\Controllers\user\QuestionnaireController::class,'getReport']);
    // user standard
    Route::get('/standards/{standard}',[\App\Http\Controllers\user\StandardController::class,'getStandards']);
    // user forms
    Route::get('/forms/{form}',[\App\Http\Controllers\user\FormController::class,'getForms']);
    Route::get('/latestForms',[\App\Http\Controllers\user\FormController::class,'latestForms']);
    // short route for uploading forms
    Route::post('/indicators/{indicator}/forms/{form}',[\App\Http\Controllers\user\FormController::class,'quickUpload']);
    // short route for updating forms
    Route::put('/indicators/{indicator}/forms/{form}',[\App\Http\Controllers\user\FormController::class,'quickUpdate']);
    // college files
    Route::apiResource('/collegeFiles',\App\Http\Controllers\CollegeFilesController::class);
    Route::get('/collegeFiles/{file}/download',[\App\Http\Controllers\CollegeFilesController::class,'download']);
    // user logout
    Route::post('/logout/{user}', [AuthController::class, 'logout']);
});

