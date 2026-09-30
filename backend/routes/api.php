<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Api Resource is a shortcut that automatically maps the standard HTTP requests.
// If we didn't use this, we would have to manually write out all 5 routes like this:
// Route::get('/tasks', [TaskController::class, 'index']);
// Route::post('/tasks', [TaskController::class, 'store']);
// Route::get('/tasks/{task}', [TaskController::class, 'show']);
// Route::put('/tasks/{task}', [TaskController::class, 'update']);
// Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
Route::apiResource('tasks', TaskController::class);
// TaskController::class assumes that the name "Task Controller" is getting called through the keyword use. Instead of typing App\Http\Controllers\TaskController
// The ::class gets the class of its attached named "TaskController".

// Route::get('/tasks', [TaskController::class, 'index']) -> Manual API routing.
//Http Method -> name of the API -> The class of the controller -> The function name inside the class.
