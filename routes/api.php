<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderItemController;

Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::apiResource('products', ProductController::class)->only(['index', 'store']);
Route::apiResource('order-items', OrderItemController::class)->only(['store', 'show', 'update', 'destroy']);
