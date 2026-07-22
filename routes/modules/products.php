<?php

use Illuminate\Support\Facades\Route;

Route::prefix('products')->name('products.')->group(function (): void {
    Route::view('/', 'products.index')->name('index');
    Route::view('/{product}', 'products.show')->whereNumber('product')->name('show');
});

Route::middleware(['auth', 'not_locked'])->prefix('seller/products')->name('seller.products.')->group(function (): void {
    Route::view('/', 'seller.products.index')->name('index');
    Route::view('/create', 'seller.products.create')->name('create');
    Route::post('/', fn () => abort(501, 'Seller product creation is not implemented in foundation.'))->name('store');
    Route::view('/{product}/edit', 'seller.products.edit')->whereNumber('product')->name('edit');
    Route::patch('/{product}', fn () => abort(501, 'Seller product update is not implemented in foundation.'))->whereNumber('product')->name('update');
    Route::delete('/{product}', fn () => abort(501, 'Seller product deletion is not implemented in foundation.'))->whereNumber('product')->name('destroy');
});
