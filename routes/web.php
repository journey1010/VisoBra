<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/v1/', [AuthController::class, 'index']);
Route::get('/v1/login', [AuthController::class, 'index']);
Route::post('/v1/auth/horizon/dashboard', [AuthController::class, 'login'])->name('login.dashboard');