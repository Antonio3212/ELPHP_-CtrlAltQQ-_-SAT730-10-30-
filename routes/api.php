<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AuthController;

Route::post('/registerSeller', [SellerController::class, 'registerSeller']);  

Route::post('/registerBuyer', [BuyerController::class, 'registerBuyer']);

Route::post('login', [AuthController::class, 'login']);