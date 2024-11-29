<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;


Route::post('/purchase', [PurchaseController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);  // POST method for creating products
Route::post('/registerSeller', [SellerController::class, 'registerSeller']);  
Route::post('/registerBuyer', [BuyerController::class, 'registerBuyer']);
Route::post('/login', [AuthController::class, 'login']);
