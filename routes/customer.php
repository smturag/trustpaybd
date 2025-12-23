<?php

use App\Http\Controllers\CustomerPanel\CustomerController;
use App\Http\Controllers\CustomerPanel\CustomerLogin;
use App\Http\Controllers\CustomerPanel\CustomerPaymentRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerPanel\CustomerSupportController;
use App\Http\Controllers\CustomerPanel\CustomerBasicController;
use App\Http\Controllers\Mobecash\McMemberTransaction;
use App\Http\Controllers\Mobecash\McSupportController;
use App\Http\Controllers\Mobecash\McUserLogin;

Route::group(['prefix' => 'mc'], function () {

    Route::any('/', [McUserLogin::class, 'loginfrm'])->name('mclogin');
    Route::any('/login', [McUserLogin::class, 'loginfrm'])->name('mclogin');
    Route::post('/mcloginaction', [McUserLogin::class, 'loginAction'])->name('mcloginaction');
    Route::any('/logout', [McUserLogin::class, 'logout'])->name('mclogout');

    Route::get('create-account', [McUserLogin::class, 'view_create_customer'])->name('mc.view_create_account');
    Route::post('mccreate', [McUserLogin::class, 'customer_sign_up'])->name('mc.sign_up');
    



    Route::middleware(['customer'])->group(function () {

        Route::any('/testmc', [McMemberTransaction::class, 'testmc'])->name('testmc');
        Route::any('/dashboard', [McMemberTransaction::class, 'dashboard'])->name('mc_dashboard');
        Route::any('/add_fund', [McMemberTransaction::class, 'add_fund'])->name('mc_add_fund');
        Route::any('/withdraw_fund', [McMemberTransaction::class, 'withdraw_fund'])->name('mc_withdraw_fund');
        Route::any('/mc_deposit', [McMemberTransaction::class, 'mc_deposit'])->name('mc_deposit');
        Route::any('/mc_withdraw', [McMemberTransaction::class, 'mc_withdraw'])->name('mc_withdraw');
        Route::any('/transfer_fund', [McMemberTransaction::class, 'transfer_fund'])->name('mc_transfer_fund');

         // support
         Route::get('/support_list', [McSupportController::class, 'support_list'])->name('mc.support_list_view');
         Route::get('/create_support', [McSupportController::class, 'create_support_view'])->name('mc.create_support_view');
         Route::post('/support_submit', [McSupportController::class, 'support_submit'])->name('mc.support_submit');
         Route::any('/support/reply/{ticket}', [McSupportController::class, 'ticketReply'])->name('mc.ticket_customer_reply');
         Route::any('/comment/close/{ticket}', [McSupportController::class, 'ticketClose'])->name('mc.ticketClose');
         Route::any('/support/store/{ticket}', [McSupportController::class, 'ticketReplyStore'])->name('mc.ticketReplyStore');
 
    });


});



Route::group(['prefix' => 'customer'], function () {
    Route::any('/', [CustomerLogin::class, 'loginfrm'])->name('customerlogin');
    Route::get('/login', [CustomerLogin::class, 'loginfrm'])->name('customerlogin');
    Route::get('/customerlogin', [CustomerLogin::class, 'loginfrm'])->name('customerlogin');
    Route::post('/customerloginaction', [CustomerLogin::class, 'loginAction'])->name('customerloginAction');
    Route::any('/customerlogout', [CustomerLogin::class, 'logout'])->name('customerlogout');

    

    
    Route::get('customer/verify/{token}', [CustomerLogin::class, 'verify_customer'])->name('customer.verify');
    Route::post('verify_new_token', [CustomerLogin::class, 'verify_new_token'])->name('customer.new_token_create');

    Route::get('forget-password', [CustomerLogin::class, 'forget_password'])->name('customer.forget_password');
    Route::POST('forget_password_customer', [CustomerLogin::class, 'forget_password_customer'])->name('customer.forget_password_customer');
    


    Route::any('/customer_login_pin', [CustomerLogin::class, 'loginPin'])->name('customer_login_pin');
    Route::any('/customerPinVerify', [CustomerLogin::class, 'customerPinVerify'])->name('customerPinVerify');

    Route::any('/customer_login_pass_pin_change', [customerLogin::class, 'passPinChange'])->name('customer_passPinChange');
    Route::any('/customerPassPin', [CustomerLogin::class, 'customerPassPinChange'])->name('customerPassPinChange');
    Route::get('create-account', [CustomerLogin::class, 'view_create_customer'])->name('customer.view_create_account');
    Route::post('customer/create', [CustomerLogin::class, 'customer_sign_up'])->name('customer.sign_up');
    


    Route::middleware(['customer'])->group(function () {
        Route::any('/dashboard', [CustomerController::class, 'dashboard'])->name('customer_dashboard');
        Route::get('/profile', [CustomerLogin::class, 'profile'])->name('customer.profile');
        Route::post('/change-password', [CustomerLogin::class, 'change_password'])->name('customerChangePassword');
        Route::post('/profile/update', [CustomerLogin::class, 'update_profile'])->name('customerProfileUpdate');


        Route::get('transactions', [CustomerPaymentRequestController::class, 'transactions'])->name('customer.transactions');
        Route::get('/deposit/{customer_id}', [CustomerPaymentRequestController::class, 'deposit'])->name('customer.deposit');
        Route::get('/deposit/payment/{customer_id}', [CustomerPaymentRequestController::class, 'deposit_payment'])->name('deposit_payment');
        Route::post('/deposit-form-submit', [CustomerPaymentRequestController::class, 'deposit_form_submit'])->name('deposit_form_submit');
        Route::get('/deposit/payment/success-page/{payment_id}', [CustomerPaymentRequestController::class, 'deposit_payment_success_page'])->name('deposit.payment.success');
        Route::post('/deposit/payment/payment_save', [CustomerPaymentRequestController::class, 'payment_save'])->name('payment_save');
        Route::post('/get-deposit-success-status', [CustomerPaymentRequestController::class, 'get_deposit_success_status']);
        Route::get('send-money', [CustomerPaymentRequestController::class, 'sendMoney'])->name('customer.view_send_money');
        Route::post('submit_send_money', [CustomerPaymentRequestController::class, 'submit_send_money'])->name('customer.submit_send_money');


        Route::get('/withdraw-form', [CustomerPaymentRequestController::class, 'withdraw_form'])->name('customer.withdraw');
        Route::post('/withdraw-save', [CustomerPaymentRequestController::class, 'withdraw_save'])->name('customer.withdraw-save');



        Route::get('bet-money', [CustomerPaymentRequestController::class, 'betting'])->name('customer.view_betting');
        Route::post('submit_betting', [CustomerPaymentRequestController::class, 'submit_betting'])->name('customer.submit_betting');
        
        // support
        Route::get('/support_list', [CustomerSupportController::class, 'support_list'])->name('customer.support_list_view');
        Route::get('/create_support', [CustomerSupportController::class, 'create_support_view'])->name('customer.create_support_view');
        Route::post('/support_submit', [CustomerSupportController::class, 'support_submit'])->name('customer.support_submit');
        Route::any('/support/reply/{ticket}', [CustomerSupportController::class, 'ticketReply'])->name('customer.ticket_customer_reply');
        Route::any('/comment/close/{ticket}', [CustomerSupportController::class, 'ticketClose'])->name('customer.ticketClose');
        Route::any('/support/store/{ticket}', [CustomerSupportController::class, 'ticketReplyStore'])->name('customer.ticketReplyStore');


        //Basic

        Route::get('find_customer/{letter}', [CustomerBasicController::class, 'findCustomer'])->name('customer.find');
        Route::get('check_amount/{amount_qty}', [CustomerBasicController::class, 'check_amount'])->name('customer.amount_check');
        Route::get('check_customer/{name}', [CustomerBasicController::class, 'check_customer'])->name('customer.customer_check');


    });
});
