<?php

use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Member\AllReportController;
use App\Http\Controllers\Member\MemberLogin;
use App\Http\Controllers\Member\MemberModem;
use App\Http\Controllers\Member\MemberProfile;
use App\Http\Controllers\Member\MemberReport;
use App\Http\Controllers\Member\MemberSms;
use App\Http\Controllers\Member\MemberSupport;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\PaymentRequestController;
use App\Http\Controllers\Member\MFSRequestController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Response;

Route::middleware(['verify'])->group(function () {

Route::get('/trigger-schedule', function () {
    Artisan::call('app:delete-unverified-customers');
    Artisan::call('app:check-payment-request');
    Artisan::call('app:send-s-m-s-to-agent');
    Artisan::call('app:bm-to-payment-check');
    Artisan::call('app:auto-assign-b-m-to-agent');
});



Route::get('/create-storage-link', function () {
    // Run the storage:link command
    Artisan::call('storage:link');

    return new Response('Storage link created successfully.', 200);
});




Route::get('/fcm', function () {

    $fcm = sendFCMNotificationAgent('ft4tv6q4QvCxPzo2hc_p1S:APA91bHTXPfiEK9hEAw_QGtACJxrnwPiPMwtLrjpPIqhzyuMuX1A9ll6jPuuHrKrKp9wiPLM8cjZ68UZVZFl0zB36e_TWs2Wp89PmePRHSqsMkeV3GNHvRg');

    return $fcm;

})->name('fcmhome');


Route::get('/', function () {
    $pricingPlans = \App\Models\PricingPlan::getActivePlans();
    return view('customer-panel.customer_welcome', compact('pricingPlans'));
})->name('home');


Route::get('/company', function () {
    return view('customer-panel.customer_company');
})->name('company');


// Route::get('/', function () {
//     return view('customer-panel.customer_welcome');
// });



Route::get('/privacy-policy', function () {
    return view('primary.privacy_policy');
})->name('privacy_policy');

Route::get('/terms&condition', function () {
    return view('primary.terms_and_condition');
})->name('terms_and_condition');

Route::get('/develop_docs', function () {
    // return view('merchant.developer.developer_docs');
    return view('customer-panel.customer_developers');

})->name('develop_docs');


//Auth::routes();
Route::any('/agent', [MemberLogin::class, 'login'])->name('agent');
Route::any('/login', [MemberLogin::class, 'login'])->name('login');
Route::any('/userlogout', [MemberLogin::class, 'userlogout'])->name('userlogout');
Route::any('/authentication', [MemberLogin::class, 'LoginAction'])->name('loginAction');


Route::middleware(['auth', 'membersecur'])->group(function () {

    Route::any('/dashboard', [MemberProfile::class, 'dashboard'])->name('memberdashboard');
    Route::get('/profile_view', [MemberProfile::class, 'profile_view'])->name('member.profile_view');
    Route::post('/profile_update/{id}', [MemberProfile::class, 'member_profile_update'])->name('member.profile_update');

    Route::any('/member_add', [MemberController::class, 'member_add'])->name('member_add');
    Route::any('/member_addAction', [MemberController::class, 'member_addAction'])->name('member_addAction');
    Route::any('/member_list', [MemberController::class, 'member_list'])->name('member_list');
    Route::any('/member_edit/{id}', [MemberController::class, 'member_edit'])->name('member_edit');
    Route::any('/member_update/{id}', [MemberController::class, 'member_update'])->name('member_update');
    Route::any('/member_delete/{id}', [MemberController::class, 'member_delete'])->name('member_delete');


    //api merchant

    Route::get('api_method_list', [MemberModem::class,'api_method_list'])->name('api_method_list');
    Route::get('add_api_method', [MemberModem::class, 'add_api_method'])->name('add_api_method');
    Route::post('add_api_method_store', [MemberModem::class, 'add_api_method_store'])->name('add_api_method_store');
    Route::get('api_method_edit/{id}', [MemberModem::class, 'api_method_edit'])->name('api_method_edit');
    Route::post('api_method_update', [MemberModem::class, 'api_method_update'])->name('api_method_update');



    Route::any('/member_sms_inbox', [MemberSms::class, 'member_sms_inbox'])->name('member_sms_inbox');
    Route::any('/modem', [MemberModem::class, 'member_modem_list'])->name('member_modem_list');
    Route::any('/member_modem_delete/{id}', [MemberModem::class, 'member_modem_delete'])->name('member_modem_delete');


    Route::any('/member_transaction', [MemberReport::class, 'member_transaction'])->name('member_transaction');

    Route::any('/approved-transaction/{id}', [MemberReport::class, 'approved_transaction'])->name('approved_transaction');
    Route::post('/approved-transaction-save', [MemberReport::class, 'approved_transaction_save'])->name('approved_transaction_save');
    Route::post('/reject-transaction/{id}', [MemberReport::class, 'reject_transaction'])->name('reject_transaction');
    Route::any('/view-transaction/{id}', [MemberReport::class, 'view_transaction'])->name('view_transaction');


    Route::any('/member_report', [MemberReport::class, 'member_report'])->name('member_report');
    Route::any('/transaction_report/{id}', [MemberReport::class, 'transaction_report_api'])->name('transaction_report_api');


    Route::any('/kyc', [MemberProfile::class, 'kyc'])->name('kyc');
    Route::any('/kycUpdate', [MemberProfile::class, 'kycUpdate'])->name('kycUpdate');
    Route::any('/change_password', [MemberProfile::class, 'change_password'])->name('change_password');
    Route::any('/changePassword', [MemberProfile::class, 'changePassword'])->name('changePassword');
    Route::any('/change_pain', [MemberProfile::class, 'change_pin'])->name('pin_change');
    Route::any('/changePIN', [MemberProfile::class, 'changePIN'])->name('changePIN');


    //Route::any('/membermodem', [MemberModem::class, 'modeList'])->name('modeList');
    Route::any('/support', [MemberSupport::class, 'supportList'])->name('supportList');
    Route::any('/ticketCreate', [MemberSupport::class, 'ticketCreate'])->name('add_new_ticket');
    Route::any('/ticketStore', [MemberSupport::class, 'ticketStore'])->name('ticketStore');
    //Route::any('/ticket_customer_reply', [MemberSupport::class, 'ticketReply'])->name('ticket_customer_reply');


    Route::any('/comment/close/{ticket}', [MemberSupport::class, 'ticketClose'])->name('ticketClose');
    Route::any('/support/reply/{ticket}', [MemberSupport::class, 'ticketReply'])->name('ticket_customer_reply');
    Route::any('/support/store/{ticket}', [MemberSupport::class, 'ticketReplyStore'])->name('ticketReplyStore');


    //Payment Request
    Route::get('payment-request', [PaymentRequestController::class, 'index'])->name('user.payment-request');
    Route::post('/approve-payment/{id}', [PaymentRequestController::class, 'approve_payment_request'])->name('agent.approve-payment-request');
    Route::post('/reject-payment', [PaymentRequestController::class, 'reject_payment_request'])->name('agent.reject-payment-request');


    Route::post('/agent/accept-mfs-request/{request_id}', [MFSRequestController::class, 'accept_mfs_request']);
    Route::post('/agent/approve-mfs-request', [MFSRequestController::class, 'approve_mfs_request']);
    Route::get('/service-req/{status}/{agent_number?}', [MFSRequestController::class, 'serviceReq'])->name('member.serviceReq');
    Route::post('/service_multiple_action', [MFSRequestController::class, 'service_multiple_action'])->name('member.service_multiple_action');


    Route::post('/agent/reject-mfs-request', [MFSRequestController::class, 'reject_mfs_request'])->name('rejectMfsRequest');
    Route::get('/resend-req/{id}', [MFSRequestController::class, 'resend_req'])->name('agent.resend_req');
    // Route::post('reject_req', [MFSRequestController::class, 'RejectRequest'])->name('agent.service.reject_req');

    Route::group(['prefix' => 'report'], function () {
        Route::any('/service/{service?}', [AllReportController::class, 'ServiceReport'])->name('report.member.service_report');

    });


});


Route::domain('{subdomain}.rapimfs.test')->group(function () {

});

});


