<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('orders.index');
});

Route::resource('orders', OrderController::class)->only(['index', 'show']);
Route::patch('/orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');