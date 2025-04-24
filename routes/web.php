<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\EmployeeController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    // Courses Routes
    Route::resource('courses', CourseController::class);

    // Departments Routes
    Route::resource('departments', DepartmentController::class);

    // Employees Routes
    Route::resource('employees', EmployeeController::class);
});
