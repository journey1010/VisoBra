<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Prueba;

Route::get('/prueba', [Prueba::class, 'prueba']);
