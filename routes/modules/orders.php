<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not_locked'])->group(function (): void {
    Route::prefix('orders')->name('orders.')->group(function (): void {
        Route::view('/', 'orders.index')->name('index');
        Route::view('/{order}', 'orders.show')->whereNumber('order')->name('show');
        Route::post('/', fn () => abort(501, 'Checkout is not implemented in foundation.'))->name('store');
        Route::patch('/{order}/cancel', fn () => abort(501, 'Order cancellation is not implemented in foundation.'))->whereNumber('order')->name('cancel');
    });

    Route::prefix('seller/orders')->name('seller.orders.')->group(function (): void {
        Route::view('/', 'seller.orders.index')->name('index');
        Route::view('/{order}', 'seller.orders.show')->whereNumber('order')->name('show');
    });

    Route::prefix('seller/order-items')->name('seller.order-items.')->group(function (): void {
        Route::patch('/{orderItem}/confirm', fn () => abort(501, 'Seller order item confirmation is not implemented in foundation.'))->whereNumber('orderItem')->name('confirm');
        Route::patch('/{orderItem}/cancel', fn () => abort(501, 'Seller order item cancellation is not implemented in foundation.'))->whereNumber('orderItem')->name('cancel');
    });

    Route::prefix('invoices')->name('invoices.')->group(function (): void {
        Route::view('/{order}', 'orders.invoice')->whereNumber('order')->name('show');
        Route::get('/{order}/download', fn () => abort(501, 'Invoice download is not implemented in foundation.'))->whereNumber('order')->name('download');
    });
});
