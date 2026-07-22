<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not_locked'])->prefix('profile')->name('profile.')->group(function (): void {
    Route::view('/', 'profile.show')->name('show');
    Route::view('/edit', 'profile.edit')->name('edit');
    Route::patch('/', fn () => abort(501, 'Profile update is not implemented in foundation.'))->name('update');
    Route::patch('/password', fn () => abort(501, 'Password update is not implemented in foundation.'))->name('password.update');
    Route::patch('/bank', fn () => abort(501, 'Bank update is not implemented in foundation.'))->name('bank.update');
});
