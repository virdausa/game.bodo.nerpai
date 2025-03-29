<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Register
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout (harus login dulu)
Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy']);

// User info
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
