<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not_locked'])->prefix('cart')->name('cart.')->group(function (): void {
    Route::view('/', 'cart.index')->name('index');
    Route::post('/items', fn () => abort(501, 'Cart item creation is not implemented in foundation.'))->name('items.store');
    Route::patch('/items/{cartItem}', fn () => abort(501, 'Cart item update is not implemented in foundation.'))->whereNumber('cartItem')->name('items.update');
    Route::delete('/items/{cartItem}', fn () => abort(501, 'Cart item deletion is not implemented in foundation.'))->whereNumber('cartItem')->name('items.destroy');
});
