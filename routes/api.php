<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Obras;
use App\Http\Controllers\FilterTotales;
use App\Http\Controllers\Fotos;
use App\Http\Controllers\Qr;
use App\Http\Controllers\Ubigeo;

Route::get('/obras/search', [Obras::class, 'searchObras']);
Route::get('/obras/search/by', [Obras::class, 'searchById']);
Route::get('/obras/search/totales', [Obras::class, 'searchTotals']);
Route::get('/obras/filters/totales', [FilterTotales::class, 'filterTotal']);
Route::get('/obras/fotos', [Fotos::class, 'colection']);
Route::get('/obras/qr', [Qr::class, 'make']);
Route::get('/ubigeo/departments', [Ubigeo::class, 'departments']);
Route::get('/ubigeo/province', [Ubigeo::class, 'provinces']);
Route::get('/ubigeo/districts',  [Ubigeo::class,  'districts']);
Route::get('/report/filter',  [Obras::class, 'reportFile']);