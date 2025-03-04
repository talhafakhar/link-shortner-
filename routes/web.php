<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

Route::get('/', [UrlController::class, 'index'])->name('home');
Route::post('/shorten', [UrlController::class, 'create'])->name('url.create')->middleware('limit');
Route::get('/{slug}', [UrlController::class, 'redirect'])->name('redirect');
Route::get('/analytics/{slug}', [UrlController::class, 'analytics'])->name('analytics');
// Route::get('/error', [UrlController::class, 'error'])->name('error');
