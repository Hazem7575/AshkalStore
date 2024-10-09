<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');
Route::get('/grid', [\App\Http\Controllers\IndexController::class, 'grid']);
