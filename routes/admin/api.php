<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\auth\AuthController;

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

Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/users',\App\Http\Controllers\admin\UserController::class);
    Route::apiResource('/programs',\App\Http\Controllers\admin\ProgramController::class);
    Route::apiResource('/programs/{program}/standards',\App\Http\Controllers\admin\StandardController::class);
    Route::apiResource('/programs/{program}/standards/{standard}/indicators',
        \App\Http\Controllers\admin\IndicatorController::class);
});



