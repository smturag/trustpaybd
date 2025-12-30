<?php
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\AndroidModemController;
use App\Http\Controllers\Api\MFSRequestRNController;
use App\Http\Controllers\Api\PaymentRequestRNController;
use App\Http\Controllers\Api\MobCash;
use App\Http\Controllers\Api\SmsModem;
use App\Http\Controllers\Api\ServiceRequestReceiveWebhookController;
use App\Http\Controllers\Api\PaymentRequestReceiveWebhookController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MerchantPaymentController;
use App\Http\Controllers\Api\V2\MerchantPRController;
use App\Http\Controllers\PaymentMFSController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLogin;
use App\Http\Controllers\Api\Admin\ApiTelegramController;
use App\Http\Controllers\Api\IBot\CheckingController;
use App\Http\Controllers\Api\P2C\P2CAllController;

Route::any('/mcallback', [PaymentMFSController::class, 'merchantpaiback']);
Route::any('/excuteapi', [PaymentMFSController::class, 'excuteapi']);

Route::any('/customer_callback', [PaymentMFSController::class, 'customer_paiback']);

Route::any('/checking', [AdminLogin::class, 'checkingMember']);
Route::any('/webhook', [CheckingController::class, 'checkResponse']);
Route::post('/bm/mfs/webhook', [ServiceRequestReceiveWebhookController::class, 'handle']);
Route::post('/bm/payment/webhook', [PaymentRequestReceiveWebhookController::class, 'handle']);

Route::middleware(['verify'])->group(function () {
    Route::any('/index', [SmsModem::class, 'index']);

    Route::any('/deviceverify', [SmsModem::class, 'deviceverify']);
    Route::any('/testSms', [SmsModem::class, 'testSms']);

    Route::any('/smsin', [SmsModem::class, 'smsin']);
    Route::any('/android_modem_app_update/{version}', [SmsModem::class, 'android_modem_app_update']);

    //this route for irecharge old modem app
    Route::any('/newrequest', [AndroidModemController::class, 'pendingData']);
    Route::any('/ussdupdate', [AndroidModemController::class, 'ussdupdate']);
    //Route::any('/smsin', [AndroidModemController::class,'smsin']);

    Route::post('/verify_rn_device', [App\Http\Controllers\Api\ReactNativeModemController::class, 'verify_rn_device']);
    Route::post('/sendSmsToServer', [App\Http\Controllers\Api\ReactNativeModemController::class, 'sendSmsToServer']);
    Route::get('/update-rn-sms-app/{version}', [App\Http\Controllers\Api\ReactNativeModemController::class, 'update_rn_sms_app']);

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    /**
     * =====================================================================
     * Merchant API for Taking Payment Request
     * =====================================================================
     */

    Route::group(
        [
            'prefix' => 'v1',
            'namespace' => 'Api\V1',
        ],
        function () {
            Route::post('login', [AuthController::class, 'login']);

            Route::group(
                [
                    'prefix' => 'payment',
                    'middleware' => ['apikey'],
                ],
                function () {
                    Route::post('create-payment', [MerchantPaymentController::class, 'createPayment']);
                    Route::post('customer-payment-received', [MerchantPaymentController::class, 'customerPaymentReceived']);
                    Route::any('track-status/{referenceId}', [MerchantPaymentController::class, 'checkPaymentStatus']);
                },
            );

            Route::group(
                [
                    'prefix' => 'mfs',
                    'middleware' => ['apikey'],
                ],
                function () {
                    Route::post('create', [MerchantPaymentController::class, 'createCashIn']);
                    Route::post('status_check', [MerchantPaymentController::class, 'checking_status']);
                },
            );

            //Route::post('register', [AuthController::class, 'register']);
            // Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp']);
            // Route::post('/register/resend-otp', [AuthController::class, 'resendOtp']);
            // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
            // Route::post('/change-password-by-otp', [AuthController::class, 'changePasswordByOtp']);
        },
    );

    // Route::prefix('payment-request')->group(function () {
    //     Route::get('payment-list/{agent_code}/{sim_id}', [PaymentRequestRNController::class, 'index']);
    //     Route::post('/approve-payment', [PaymentRequestRNController::class, 'approve_payment_request']);
    //     Route::post('/reject-payment', [PaymentRequestRNController::class, 'reject_payment_request']);
    // });

    // Route::prefix('/mfs')->group(function () {
    //     Route::get('/service-req/{agent_code}/{sim_id}', [MFSRequestRNController::class, 'serviceReq']);
    //     Route::post('/agent/accept-mfs-request', [MFSRequestRNController::class, 'accept_mfs_request']);
    //     Route::post('/agent/approve-mfs-request', [MFSRequestRNController::class, 'approve_mfs_request']);
    //     Route::post('/agent/reject-mfs-request', [MFSRequestRNController::class, 'reject_mfs_request']);
    // });

    Route::prefix('/irobotic')->group(function () {
        Route::get('/service-req/{agent_code}', [MFSRequestRNController::class, 'getServiceReq']);
        Route::post('/data_submitted', [MFSRequestRNController::class, 'data_submitted']);
        Route::any('/update_modem', [MFSRequestRNController::class, 'update_modem']);
        // Route::post('/agent/accept-mfs-request', [MFSRequestRNController::class, 'accept_mfs_request']);
        // Route::post('/agent/approve-mfs-request', [MFSRequestRNController::class, 'approve_mfs_request']);
        // Route::post('/agent/reject-mfs-request', [MFSRequestRNController::class, 'reject_mfs_request']);
    });

    Route::prefix('/agent')->group(function () {
        Route::post('/login', [MemberController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', [MemberController::class, 'userInfo']);
            Route::post('/logout', [MemberController::class, 'logout']);
            Route::post('/total-request', [MemberController::class, 'totalRequest']);

            Route::prefix('/payment-request')->group(function () {
                Route::get('/payment-list/{agent_code}', [PaymentRequestRNController::class, 'index']);
                Route::post('/approve-payment', [PaymentRequestRNController::class, 'approve_payment_request']);
                Route::post('/reject-payment', [PaymentRequestRNController::class, 'reject_payment_request']);
            });

            Route::prefix('/mfs')->group(function () {
                Route::get('/service-req/{agent_code}', [MFSRequestRNController::class, 'serviceReq']);
                Route::post('/agent/accept-mfs-request', [MFSRequestRNController::class, 'accept_mfs_request']);
                Route::post('/agent/approve-mfs-request', [MFSRequestRNController::class, 'approve_mfs_request']);
                Route::post('/agent/reject-mfs-request', [MFSRequestRNController::class, 'reject_mfs_request']);
                Route::any('/resend-req/{mfs_id}', [MFSRequestRNController::class, 'resendServiceReq']);
            });
        });
    });

    Route::group(
        [
            'prefix' => 'admin-telegram',
            'middleware' => ['telegram_token'],
        ],
        function () {
            Route::get('deposit/track-status/{referenceId}', [ApiTelegramController::class, 'searchDepositTransaction']);
            Route::post('deposit/update_transaction/{referenceId}', [ApiTelegramController::class, 'updateTran saction']);
            Route::get('withdraw/track-status', [ApiTelegramController::class, 'checking_status']);
            Route::get('track_bm/{trxid}', [ApiTelegramController::class, 'checkBm']);
        },
    );

    Route::group(['prefix' => 'v2/payment/', 'namespace' => 'Api\V2', 'middleware' => ['apikey2']], function () {
        Route::any('/available-method', [MerchantPRController::class, 'mfsList']);
        Route::any('/create-payment', [MerchantPRController::class, 'makeTransaction']);
        Route::any('/status-track/{referenceId}', [MerchantPaymentController::class, 'checkPaymentStatus']);
    });

    Route::any('/v2-bkash-redirect', [P2CAllController::class, 'bkashRedirect']);

    Route::prefix('/mobcash')->group(function () {
        Route::get('/new-req', [MobCash::class, 'getReq']);
        Route::any('/data_submitted', [MobCash::class, 'data_submitted']);
    });
});
