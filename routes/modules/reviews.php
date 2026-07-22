<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not_locked'])->prefix('reviews')->name('reviews.')->group(function (): void {
    Route::view('/create', 'reviews.create')->name('create');
    Route::post('/', fn () => abort(501, 'Review creation is not implemented in foundation.'))->name('store');
});
