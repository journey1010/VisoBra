<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Prueba;

Route::get('/prueba', [Prueba::class, 'testHttpObra']);
Route::get('/prueba2', [Prueba::class, 'httException']);
Route::get('/prueba3', [Prueba::class, 'prueba']);
