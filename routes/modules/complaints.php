<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not_locked'])->prefix('complaints')->name('complaints.')->group(function (): void {
    Route::view('/', 'complaints.index')->name('index');
    Route::view('/create', 'complaints.create')->name('create');
    Route::post('/', fn () => abort(501, 'Complaint creation is not implemented in foundation.'))->name('store');
    Route::view('/{complaint}', 'complaints.show')->whereNumber('complaint')->name('show');
});
