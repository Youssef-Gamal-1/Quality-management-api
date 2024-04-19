<?php

use App\Http\Controllers\admin\auth\AuthController;
use App\Http\Controllers\admin\CourseController;
use Illuminate\Support\Facades\Route;


Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/users',\App\Http\Controllers\admin\UserController::class);
    Route::apiResource('/programs',\App\Http\Controllers\admin\ProgramController::class);
    Route::apiResource('/programs/{program}/standards',\App\Http\Controllers\admin\StandardController::class);
    Route::apiResource('/programs/{program}/standards/{standard}/indicators',
        \App\Http\Controllers\admin\IndicatorController::class);
    Route::apiResource('/programs/{program}/courses',CourseController::class);
    Route::apiResource('/programs/{program}/courses/{course}/degrees',\App\Models\Degree::class);
    // Form special routes
    Route::post('/programs/{program}/standards/{standard}/indicators/{indicator}/forms',[
        \App\Http\Controllers\admin\FormController::class,'store']);
    Route::put('/programs/{program}/standards/{standard}/indicators/{indicator}/forms/{form}',[
        \App\Http\Controllers\admin\FormController::class,'update']);
    Route::delete('/programs/{program}/standards/{standard}/indicators/{indicator}/forms/{form}',[
        \App\Http\Controllers\admin\FormController::class,'destroy']);
    Route::get('/download/file/{id}', [\App\Http\Controllers\admin\FormController::class, 'download']);

});




