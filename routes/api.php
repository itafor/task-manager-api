<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::prefix('user')->middleware(['auth:user'])->controller(AuthController::class)->group(function () {
    Route::post('logout', 'logout');
    Route::get('me', 'me');
});

Route::prefix('task')->middleware(['auth:user'])->controller(TaskController::class)->group(function () {
    Route::post('create', 'createTask');
    Route::put('update', 'editTask');
    Route::get('list', 'getTasks');
    Route::get('filter-by-status', 'filterTaskByStatus');
    Route::get('details/{taskId}', 'viewTask');
    Route::delete('delete/{taskId}', 'deleteTask');
});
