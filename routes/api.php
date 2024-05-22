<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Prueba;
use App\Http\Controllers\Obras;

Route::get('/obras/search', [Obras::class, 'searchObras']);
Route::get('/obras/search/by', [Obras::class, 'searchById']);
Route::get('/obras/search/totales', [Obras::class, 'searchTotals']);
