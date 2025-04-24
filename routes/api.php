<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// مسارات المصادقة العامة
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);

// مسارات المصادقة المحمية
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/me', [AuthController::class, 'me']);
    
    // مسارات الموظفين
    Route::apiResource('employees', EmployeeController::class);
    Route::get('employees/{employee}/courses', [EmployeeController::class, 'courses']);

    // مسارات الدورات التدريبية
    Route::apiResource('courses', CourseController::class);
    Route::get('courses/{course}/employees', [CourseController::class, 'employees']);
    Route::post('courses/{course}/enroll', [CourseController::class, 'enrollEmployee']);

    // مسارات الدروس (متداخلة مع الدورات)
    Route::prefix('courses/{course}')->group(function () {
        Route::apiResource('lessons', LessonController::class);
        Route::post('lessons/reorder', [LessonController::class, 'reorder']);
    });

    // مسارات الاختبارات (متداخلة مع الدورات)
    Route::prefix('courses/{course}')->group(function () {
        Route::apiResource('quizzes', QuizController::class);
        Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit']);
    });

    // مسارات الأسئلة (متداخلة مع الدورات والاختبارات)
    Route::prefix('courses/{course}/quizzes/{quiz}')->group(function () {
        Route::apiResource('questions', QuestionController::class);
    });
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


