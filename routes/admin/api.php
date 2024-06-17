<?php

use App\Http\Controllers\admin\auth\AuthController;
use App\Http\Controllers\admin\CourseController;
use Illuminate\Support\Facades\Route;
//use App\Http\Middleware\Admin;


Route::post('/login',[AuthController::class,'login'])->name('login');

Route::middleware(['auth:sanctum',\App\Http\Middleware\Admin::class])->group(function () {
    Route::apiResource('/users',\App\Http\Controllers\admin\UserController::class);
    Route::apiResource('/users/{user}/permissions',\App\Http\Controllers\admin\UserPermissionsController::class)
        ->only(['index','store','destroy']);
    Route::apiResource('/programs',\App\Http\Controllers\admin\ProgramController::class);
    Route::get('/dashboard',\App\Http\Controllers\admin\DashboardController::class);
    // Group all program sub-resources
    Route::prefix('/programs/{program}')->group(function(){
        Route::apiResource('/standards',\App\Http\Controllers\admin\StandardController::class);
        // program report
        Route::get('/report',[\App\Http\Controllers\admin\ProgramController::class,'getReport']);

        // Group standard sub-resources
        Route::prefix('/standards/{standard}')->group(function(){
            // standard report
            Route::get('/report',[\App\Http\Controllers\admin\StandardController::class,'getReport']);
            Route::apiResource('/indicators',
                \App\Http\Controllers\admin\IndicatorController::class);

            // Group indicator sub-resources
            Route::prefix('/indicators/{indicator}')->group(function(){
                Route::post('/forms',[
                    \App\Http\Controllers\admin\FormController::class,'store']);
                Route::delete('/forms/{form}',[
                    \App\Http\Controllers\admin\FormController::class,'destroy']);
            });
        });
        Route::apiResource('/courses',CourseController::class);
        Route::apiResource('/courses/{course}/degrees',\App\Models\Degree::class)
            ->only(['index','destroy']);
    });
    // Invokable class to get all courses of the system
    Route::get('/courses',\App\Http\Controllers\admin\CoursesController::class);
    // Form download routes
    Route::get('/download/file/{id}', [\App\Http\Controllers\admin\FormController::class, 'download']);
    // form feedback
    Route::put('/forms/{form}',[
        \App\Http\Controllers\admin\FormController::class,'sendFeedback']);
    // form permissions
    Route::get('/forms',[\App\Http\Controllers\admin\FormController::class,'index']);
    // degree report
    Route::get('/degreesReport',\App\Http\Controllers\admin\DegreesReportController::class);
    // user roles
    Route::get('/userRoles',\App\Http\Controllers\admin\UserRolesController::class);
});




