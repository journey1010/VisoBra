<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Route::prefix('dashboard')->group(function(){
    Route::get('/', [AuthController::class, 'index']);
    Route::get('/login', [AuthController::class, 'index']);
    Route::post('/auth/horizon/dashboard', [AuthController::class, 'login'])->name('login.dashboard');
});