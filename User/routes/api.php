<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// This is User api Resource
// This is  login and Register  api
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware("auth:sanctum")->group(function () {
    Route::put('update/{id}', [UserController::class, 'update']);
    Route::put('user_update/{id}', [UserController::class, 'UserUpdate']);
    Route::delete('user_delete', [UserController::class, 'user_delete']);
    Route::get('users/show', [UserController::class, 'show']);
    Route::get('users/get', [UserController::class, 'index']);
    Route::delete('delete/{id}', [UserController::class, 'deleteUser']);
    Route::patch('AdminUser/{id}', [USerController::class, 'AdminUser']);
    Route::get('User/Id/{id}', [UserController::class, 'UserId']);
    Route::delete('UserDelete/{id}', [UserController::class, 'UserDelete']);
    Route::put('UserUpdate/{id}', [UserController::class, 'UserUpdate']);

});
