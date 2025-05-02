<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TareaController;

Route::group([
    //'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::middleware(['auth:api', 'permission:CREATE_TASK'])->post('/tareas/save', [TareaController::class, 'save']);

Route::get('/tareas/all', [TareaController::class, 'index']);

Route::get('/tareas/find/{id}', [TareaController::class, 'find']);

Route::middleware(['auth:api', 'permission:DELETE_TASK'])->delete('/tareas/delete/{id}', [TareaController::class, 'delete']);