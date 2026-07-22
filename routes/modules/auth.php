<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('auth.login.create');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('auth.login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('auth.register.create');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('auth.register.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['auth', 'not_locked'])
    ->name('auth.logout');
