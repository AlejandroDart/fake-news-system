<?php

use App\Http\Controllers\FakeNewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FakeNewsController::class, 'index'])->name('index');
Route::post('/analizar', [FakeNewsController::class, 'analizar'])->name('analizar');
Route::get('/reset', [FakeNewsController::class, 'reset'])->name('reset');

Route::get('/history', [FakeNewsController::class, 'history'])->name('history');