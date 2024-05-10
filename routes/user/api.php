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
    // user logout
    Route::post('/logout/{user}', [AuthController::class, 'logout']);
});

