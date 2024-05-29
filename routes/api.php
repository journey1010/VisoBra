<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Obras;
use App\Http\Controllers\FilterTotales;
use App\Http\Controllers\Fotos;

Route::get('/obras/search', [Obras::class, 'searchObras']);
Route::get('/obras/search/by', [Obras::class, 'searchById']);
Route::get('/obras/search/totales', [Obras::class, 'searchTotals']);
Route::get('/obras/filters/totales', [FilterTotales::class, 'filterTotal']);
Route::get('/obras/fotos', [Fotos::class, 'colection']);
