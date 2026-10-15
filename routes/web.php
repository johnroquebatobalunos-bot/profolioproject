<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::get('/create', [PortfolioController::class, 'create'])->name('portfolios.create');
Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');

Route::prefix('manage/{key}')->name('portfolios.manage.')->group(function () {
    Route::get('/', [PortfolioController::class, 'manage'])->name('show');
    Route::get('/edit', [PortfolioController::class, 'edit'])->name('edit');
    Route::put('/', [PortfolioController::class, 'update'])->name('update');
    Route::delete('/', [PortfolioController::class, 'destroy'])->name('destroy');
    Route::get('/templates', [PortfolioController::class, 'templates'])->name('templates');
    Route::put('/template', [PortfolioController::class, 'updateTemplate'])->name('template');
    Route::get('/preview', [PortfolioController::class, 'preview'])->name('preview');
});

Route::get('/p/{slug}', [PortfolioController::class, 'public'])->name('portfolios.public');
