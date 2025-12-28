<?php

use App\Http\Controllers\Merchant\DeveloperController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\MerchantLogin;
use App\Http\Controllers\Merchant\MerchantPaymentRequestController;
use App\Http\Controllers\Merchant\MerchantPayoutController;
use App\Http\Controllers\Merchant\Report\ReportPaymentController;
use App\Http\Controllers\Merchant\Report\AllReportController;
use App\Http\Controllers\Merchant\SubMerchantController;
use App\Http\Controllers\Merchant\MerchantBmController;
use App\Http\Controllers\Merchant\ModemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\P2C\P2CAllController;

Route::middleware(['verify'])->group(function () {

Route::group(['prefix' => 'merchant'], function () {
    Route::any('/', [MerchantLogin::class, 'loginfrm'])->name('merchant');
    Route::get('/login', [MerchantLogin::class, 'loginfrm'])->name('merchantlogin');
    Route::get('/merchantlogin', [MerchantLogin::class, 'loginfrm'])->name('merchantlogin');
    Route::post('/merchantloginaction', [MerchantLogin::class, 'loginAction'])->name('merchantloginAction');

    Route::get('/sign_up', function () {
        return view('merchant.sign_up');

    })->name('merchant.sign_up');

    Route::post('/sign_up', [MerchantLogin::class, 'merchant_sign_up'])->name('merchant.sign_up.submit');

    Route::any('/merchant_login_pin', [MerchantLogin::class, 'loginPin'])->name('merchant_login_pin');
    Route::any('/merchantPinVerify', [MerchantLogin::class, 'merchantPinVerify'])->name('merchantPinVerify');

    Route::any('/merchant_login_pass_pin_change', [merchantLogin::class, 'passPinChange'])->name('merchant_passPinChange');
    Route::any('/merchantPassPin', [MerchantLogin::class, 'merchantPassPinChange'])->name('merchantPassPinChange');

    Route::get('/forget-password', function () {
        return view('merchant.forget_pass_page');
    })->name('merchant.forget_pass_page');

    Route::post('/merchant_forget_password', [MerchantLogin::class, 'merchant_forget_password'])->name('merchant.merchant_forget_password');

    Route::middleware(['merchant'])->group(function () {
        Route::any('/merchantlogout', [MerchantLogin::class, 'logout'])->name('merchantlogout');
        Route::get('/returnToAdmin', [MerchantLogin::class, 'returnToAdmin'])->name('merchant.returnToAdmin');
        Route::any('/dashboard', [MerchantController::class, 'dashboard'])->name('merchant_dashboard');
        Route::any('/requestService', [MerchantController::class, 'requestService'])->name('requestService');
        Route::any('/allRequestService', [MerchantController::class, 'allRequestService'])->name('allRequestService');
        Route::any('/requestAction', [MerchantController::class, 'requestAction'])->name('requestAction');
        Route::get('/profile', [MerchantLogin::class, 'profile'])->name('merchant.profile');
        Route::post('/change-password', [MerchantLogin::class, 'change_password'])->name('merchantChangePassword');
        Route::post('/profile/update', [MerchantLogin::class, 'update_profile'])->name('merchantProfileUpdate');
        // Route::get('transaction', [MerchantController::class, 'TransactionContentIndex'])->name('merchant.transaction');

        Route::get('payment-request', [MerchantPaymentRequestController::class, 'index'])->name('merchant.payment-request');
        Route::get('payment-request/export', [MerchantPaymentRequestController::class, 'exportPaymentRequests'])->name('merchant.payment-request.export');
        Route::get('developer', [DeveloperController::class, 'index'])->name('merchant.developer-index');
        Route::get('developer/api-key-generate', [DeveloperController::class, 'apiKeyGenerate'])->name('merchant.developer.api-key-generate');
        Route::get('developer/service-rates', [DeveloperController::class, 'serviceRates'])->name('merchant.developer.service-rates');
        Route::get('payment-request/create', [MerchantPaymentRequestController::class, 'createNewDeposit'])->name('merchant.payment-request.create');
        Route::post('payment-request/store', [MerchantPaymentRequestController::class, 'depositRequestStore'])->name('merchant.deposit.store');

        // IP Whitelist routes
        Route::post('developer/ip-whitelist/add', [DeveloperController::class, 'addIpToWhitelist'])->name('merchant.developer.ip-whitelist.add');
        Route::get('developer/ip-whitelist/toggle/{id}', [DeveloperController::class, 'toggleIpStatus'])->name('merchant.developer.ip-whitelist.toggle');
        Route::get('developer/ip-whitelist/delete/{id}', [DeveloperController::class, 'deleteIpFromWhitelist'])->name('merchant.developer.ip-whitelist.delete');
        // Route::get('developer/docs', [DeveloperController::class, 'developer_docs'])->name('merchant.developer.docs');

        route::get('service-request', [MerchantPaymentRequestController::class, 'service_request_index'])->name('merchant.service-request');
        route::get('service-request/export', [MerchantPaymentRequestController::class, 'exportServiceRequests'])->name('merchant.service-request.export');

        Route::get('/withdraw-list', [MerchantController::class, 'withdraw_list'])->name('merchant.withdraw-list');
        Route::get('/withdraw', [MerchantController::class, 'withdraw'])->name('merchant.withdraw');
        Route::post('/withdraw-save', [MerchantController::class, 'withdraw_save'])->name('merchant.withdraw-save');

        // Crypto Payout Routes
        Route::get('/payout', [MerchantPayoutController::class, 'index'])->name('merchant.payout');
        Route::post('/payout-store', [MerchantPayoutController::class, 'store'])->name('merchant.payout-store');
        Route::get('/payout-history', [MerchantPayoutController::class, 'history'])->name('merchant.payout-history');
        Route::get('/payout-details/{id}', [MerchantPayoutController::class, 'show'])->name('merchant.payout-details');

        Route::get('/support_list', [MerchantController::class, 'support_list'])->name('merchant.support_list_view');
        Route::get('/create_support', [MerchantController::class, 'create_support_view'])->name('merchant.create_support_view');
        Route::post('/support_submit', [MerchantController::class, 'support_submit'])->name('merchant.support_submit');
        Route::any('/support/reply/{ticket}', [MerchantController::class, 'ticketReply'])->name('merchant.ticket_customer_reply');
        Route::any('/comment/close/{ticket}', [MerchantController::class, 'ticketClose'])->name('merchant.ticketClose');
        Route::any('/support/store/{ticket}', [MerchantController::class, 'ticketReplyStore'])->name('merchant.ticketReplyStore');
        Route::get('/support/attachment/{id}', [MerchantController::class, 'downloadAttachment'])->name('merchant.support.download');
        Route::get('/support/attachment/{id}/view', [MerchantController::class, 'viewAttachment'])->name('merchant.support.view');
        
        // Merchant notifications
        Route::get('/notifications', [MerchantController::class, 'getNotifications'])->name('merchant.notifications');
        Route::post('/notifications/{id}/read', [MerchantController::class, 'markNotificationAsRead'])->name('merchant.notifications.read');
        Route::post('/notifications/read-all', [MerchantController::class, 'markAllNotificationsAsRead'])->name('merchant.notifications.readAll');

        Route::get('/modem_list', [ModemController::class, 'modemList'])->name('merchant_modemList');
        Route::delete('/modem_delete/{id}', [ModemController::class, 'delete'])->name('merchant.modem_delete');
        Route::any('/modem_set_merchant/{id}', [ModemController::class, 'modem_set_merchant'])->name('merchant.modem_set_merchant');
        Route::any('/modem_for_merchant_saveAction', [ModemController::class, 'modem_for_merchant_saveAction'])->name('merchant.modem_for_merchant_saveAction');
        Route::get('/modem_operating_status/{modem_id}/{status}', [ModemController::class, 'modem_operating_status'])->name('merchant.modem_operating_status');
        Route::get('/modem_operating_service_status/{modem_id}/{status}', [ModemController::class, 'modem_operating_service_status'])->name('merchant.modem_operating_service_status');

        Route::prefix('sub-merchant')->group(function () {
            route::get('list', [SubMerchantController::class, 'index'])->name('sub_merchant.list');
            route::get('create', [SubMerchantController::class, 'create'])->name('sub_merchant.create');
            route::post('store', [SubMerchantController::class, 'store'])->name('sub_merchant.store');
            Route::post('merchant_add_balance', [SubMerchantController::class, 'merchant_add_balance'])->name('sub_merchant_add_balance');
            Route::get('edit/{id}', [SubMerchantController::class, 'merchantEdit'])->name('sub_merchant.edit');
            Route::get('update/{id}', [SubMerchantController::class, 'merchantEdit'])->name('sub_merchant.edit');
        });
        Route::group(['prefix' => 'report'], function () {
            Route::get('/payment', [ReportPaymentController::class, 'PaymentReport'])->name('report.merchant.payment_report');
            Route::any('/service/{service?}', [AllReportController::class, 'ServiceReport'])->name('report.merchant.service_report');
            Route::any('/balance-summary', [AllReportController::class, 'BalanceSummary'])->name('report.merchant.balance_summary');
        });


    Route::get('/balance-manager/{status}/{agent_number?}', [MerchantBmController::class, 'balance_manager'])->name('merchant.balance_manager');
        Route::any('/approved-balance-manager/{id}', [MerchantBmController::class, 'approved_balance_manager'])->name('merchant.approved_balance_manager');
        Route::post('/approved-balance-manager-save', [MerchantBmController::class, 'approved_balance_manager_save'])->name('merchant.approved_balance_manager_save');
        Route::post('/reject-balance-manager/{id}', [MerchantBmController::class, 'reject_balance_manager'])->name('merchant.reject_balance_manager');
        Route::any('/view-balance-manager/{id}', [MerchantBmController::class, 'view_balance_manager'])->name('merchant.view_balance_manager');

    });
});



Route::controller(App\Http\Controllers\PaymentMFSController::class)->group(function () {
    Route::get('/checkout/{invoice_id}', 'checkout')->name('checkout');
    Route::get('/checkout/payment/{invoice_id}/{method}/{number}/{type}', 'transaction_input')->name('merchant.transaction_input');
    Route::post('/checkout/payment/payment_save', 'payment_save')->name('payment_save');
    Route::post('/checkout/payment/payment_auto_processing', 'payment_auto_processing')->name('merchant.payment_auto_processing');
    Route::post('/checkout/payment/otp-send', 'otpSend')->name('otp_send');
    Route::post('/checkout/payment/otp-verify', 'otpVerify')->name('otp_verify');

    Route::post('/checkout/payment/cancelled', 'cancelled_payment')->name('cancelled_payment');
    Route::post('/checkout/payment/check-transaction-id', 'checkTransactionId')->name('check.transaction.id');
    Route::post('/checkout/payment/submit-transaction-id', 'submitTransactionId')->name('submit.transaction.id');
    Route::post('/checkout/payment/check-status', 'checkPaymentStatus')->name('check.payment.status');
    Route::post('/check-transaction', 'checkTransaction')->name('check.transaction');
    Route::post('/check-exist-bm', 'checkTransactionBM')->name('check.checkTransactionBM');
    //turag
    Route::get('check_bkash', 'check_bkash')->name('check_bkash');
    Route::post('live_api_submit', 'live_api_submit')->name('live_api_submit');
    Route::get('redirect_url', 'redirect_url')->name('redirect_url');
    Route::post('submit_redirect', 'submit_redirect')->name('submit_redirect');

    Route::get('/callback/{version}/{product}/{paymentID}/{status}/{signature}','handle')->name('live_callback');

    // merchant api
     Route::post('merchant_api_submit', 'merchant_api_submit')->name('merchant_api_submit');

});

 });

 Route::any('/v2-bkash-redirect', [P2CAllController::class, 'bkashRedirect'])->name('bkash_redirect');
