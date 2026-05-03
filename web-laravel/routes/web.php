<?php

use App\Http\Controllers\FakeNewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FakeNewsController::class, 'index'])->name('index');
Route::post('/analizar', [FakeNewsController::class, 'analizar'])->name('analizar');
Route::get('/reset', [FakeNewsController::class, 'reset'])->name('reset');

Route::get('/history', [FakeNewsController::class, 'history'])->name('history');

Route::post('/history/{id}/reescanear', [FakeNewsController::class, 'reescanear'])
    ->name('history.reescanear');

Route::delete('/history/{id}', [FakeNewsController::class, 'delete'])
    ->name('history.delete');

    
