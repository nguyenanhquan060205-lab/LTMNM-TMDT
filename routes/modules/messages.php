<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not_locked'])->prefix('messages')->name('messages.')->group(function (): void {
    Route::view('/', 'messages.index')->name('index');
    Route::view('/{thread}', 'messages.show')->whereNumber('thread')->name('show');
    Route::post('/', fn () => abort(501, 'Message creation is not implemented in foundation.'))->name('store');
    Route::patch('/{message}/read', fn () => abort(501, 'Message read state is not implemented in foundation.'))->whereNumber('message')->name('read');
    Route::delete('/{message}', fn () => abort(501, 'Message deletion is not implemented in foundation.'))->whereNumber('message')->name('destroy');
});
