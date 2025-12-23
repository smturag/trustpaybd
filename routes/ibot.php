<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IBot\iBotController;
use App\Http\Controllers\Api\IBot\CheckingController;



Route::post('callback/message', [CheckingController::class, 'dataSubmit']);

Route::middleware(['verify_ibot_token'])->group(function () {
    
    // Deposit Routes
    Route::prefix('deposit')->group(function () {
        Route::any('numbers', [iBotController::class, 'mfsList']);
        Route::post('create', [iBotController::class, 'createDeposit']);
        Route::any('/status-check/{referenceId}', [iBotController::class, 'checkPaymentStatus']);
        Route::any('/status-trace/{transactionId}', [iBotController::class, 'trackPaymentStatus']);
    });

    // Withdraw Routes
    Route::prefix('withdraw')->group(function () {
        Route::post('create', [iBotController::class, 'createWithdraw']);
        Route::get('status-check/{trnx_id}', [iBotController::class, 'checking_status']);
    });

});
