<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'index']);
Route::get('/auth/horizon/dashboard', [AuthController::class, 'login'])->name('login.horizon');