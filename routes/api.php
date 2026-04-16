<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'Register']);
Route::post('/verify-email', [UserController::class, 'verifyEmail']);
Route::post('/complete-profile', [UserController::class, 'completeProfile']);
Route::post('/login', [UserController::class, 'Login']);
Route::post('/logout', [UserController::class, 'Logout'])->middleware('auth:sanctum');
