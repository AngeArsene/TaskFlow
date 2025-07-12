<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;

/**
 * Route for displaying the list of tasks.
 */
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

/**
 * Routes for task management.
 */
Route::resource('tasks', TaskController::class)->except(['show', 'index']);
Route::resource('projects', ProjectController::class)->only(['store', 'destroy', 'update']);

/**
 * Route for reordering tasks.
 */
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
