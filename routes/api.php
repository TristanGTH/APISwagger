<?php

use App\Http\Controllers\ApiTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});


Route::post('auth/register', [ApiTokenController::class, 'register']);
Route::post('auth/login', [ApiTokenController::class, 'login']);

Route::middleware('auth:sanctum')->get('tasks', [\App\Http\Controllers\TaskController::class, 'showAll']);
Route::middleware('auth:sanctum')->post('task/create', [\App\Http\Controllers\TaskController::class, 'create']);
Route::middleware('auth:sanctum')->post('task/{id}', [\App\Http\Controllers\TaskController::class, 'update']);
Route::middleware('auth:sanctum')->delete('task/{id}', [\App\Http\Controllers\TaskController::class, 'delete']);
Route::middleware('auth:sanctum')->get('tasks/todo', [\App\Http\Controllers\TaskController::class, 'showToDo']);






