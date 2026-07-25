<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'not_locked', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::view('/users', 'admin.users.index')->name('users.index');
    Route::patch('/users/{user}/lock', fn () => abort(501, 'User lock is not implemented in foundation.'))->whereNumber('user')->name('users.lock');
    Route::patch('/users/{user}/unlock', fn () => abort(501, 'User unlock is not implemented in foundation.'))->whereNumber('user')->name('users.unlock');
    Route::view('/products', 'admin.products.index')->name('products.index');
    Route::patch('/products/{product}/status', fn () => abort(501, 'Product status update is not implemented in foundation.'))->whereNumber('product')->name('products.update-status');
    Route::view('/orders', 'admin.orders.index')->name('orders.index');
    Route::view('/complaints', 'admin.complaints.index')->name('complaints.index');
    Route::patch('/complaints/{complaint}', fn () => abort(501, 'Complaint update is not implemented in foundation.'))->whereNumber('complaint')->name('complaints.update');
    Route::view('/categories', 'admin.categories.index')->name('categories.index');
    Route::post('/categories', fn () => abort(501, 'Category creation is not implemented in foundation.'))->name('categories.store');
    Route::patch('/categories/{category}', fn () => abort(501, 'Category update is not implemented in foundation.'))->whereNumber('category')->name('categories.update');
    Route::delete('/categories/{category}', fn () => abort(501, 'Category deletion is not implemented in foundation.'))->whereNumber('category')->name('categories.destroy');
});
