<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'products.index')->name('home');

foreach ([
    'auth',
    'profile',
    'products',
    'cart',
    'orders',
    'reviews',
    'complaints',
    'messages',
    'admin',
] as $module) {
    require __DIR__."/modules/{$module}.php";
}
