<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;

Route::apiResource('orders', OrderController::class);
Route::apiResource('products', ProductController::class)->only(['index', 'store']);