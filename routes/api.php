<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogoutUserController;
use App\Http\Controllers\Api\LoginUserController;
use App\Http\Controllers\Api\RegisteredUserController;
use App\Http\Controllers\Api\TaskController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest')
        ->name('register');

    Route::post('/login', [LoginUserController::class, 'store'])
        ->middleware('guest')
        ->name('login');

    Route::post('/logout', [LogoutUserController::class, 'destroy'])
        ->middleware('auth:sanctum')
        ->name('logout');
});

Route::prefix('tasks')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [TaskController::class, 'create']);

    Route::put('/{id}', [TaskController::class, 'update']);

    Route::delete('/{id}', [TaskController::class, 'delete']);

    Route::get('/', [TaskController::class, 'all']);
});
