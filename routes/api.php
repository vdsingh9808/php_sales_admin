<?php

use App\Http\Controllers\Api\EntityController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('api')->group(function () {
    Route::get('urls', UrlController::class);
    Route::get('entities/{id}', EntityController::class);
    Route::post('payment/process', [PaymentController::class, 'processPayment']);
    Route::post('payment/status', [PaymentController::class, 'getPaymentStatus']);
    Route::post('payment/callback', [PaymentController::class, 'callback'])->withoutMiddleware('token');


});
