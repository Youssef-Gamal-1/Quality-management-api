<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\user\auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('/users',\App\Http\Controllers\user\UserController::class)
        ->except('store');
    Route::apiResource('/programs',\App\Http\Controllers\user\ProgramController::class)
        ->except(['store','destroy']);
    Route::apiResource('/programs/{program}/standards',\App\Http\Controllers\user\StandardController::class);
    Route::apiResource('/programs/{program}/standards/{standard}/indicators',
        \App\Http\Controllers\user\IndicatorController::class)
        ->only(['index','show']);
    Route::put('/programs/{program}/standards/{standard}/indicators/{indicator}/forms/{form}',
        [\App\Http\Controllers\user\FormController::class,'update']);

});

