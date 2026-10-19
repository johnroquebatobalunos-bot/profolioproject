<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'storeLogin'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegistration'])->middleware('throttle:5,1')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/create', [PortfolioController::class, 'create'])->name('portfolios.create');
Route::post('/portfolios', [PortfolioController::class, 'store'])->middleware('auth')->name('portfolios.store');

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
