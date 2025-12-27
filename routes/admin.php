<?php

use App\Http\Controllers\Admin\AdminBm;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLogin;
use App\Http\Controllers\Admin\AdminMerchant;
use App\Http\Controllers\Admin\AdminModem;
use App\Http\Controllers\Admin\AdminSmsInbox;
use App\Http\Controllers\Admin\DocsReportsController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MerchantPaymentRequestController;
use App\Http\Controllers\Admin\MFS_Controller;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\Report\AllReportController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SmsSettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Admin\MerchantServiceController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\MobcashController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\DatabaseUpdateController;
use App\Http\Controllers\Admin\MigrationController;

Route::middleware(['verify'])->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::any('/', [AdminLogin::class, 'loginfrm'])->name('admin');
        Route::get('/login', [AdminLogin::class, 'loginfrm'])->name('adminlogin');
        Route::get('/adminlogin', [AdminLogin::class, 'loginfrm'])->name('adminlogin');
        Route::post('/adminloginaction', [AdminLogin::class, 'loginAction'])->name('adminloginAction');

        Route::any('/admin_login_pin', [AdminLogin::class, 'loginPin'])->name('admin_login_pin');
        Route::any('/adminPinVerify', [AdminLogin::class, 'adminPinVerify'])->name('adminPinVerify');

        Route::any('/admin_login_pass_pin_change', [AdminLogin::class, 'passPinChange'])->name('admin_passPinChange');
        Route::any('/adminPassPIN', [AdminLogin::class, 'adminPassPinChnage'])->name('adminPassPinChnage');

        Route::any('/profile', [AdminLogin::class, 'profile'])->name('admin.profile');
        Route::post('/update_profile', [AdminLogin::class, 'update_profile'])->name('admin.update_profile');
        Route::post('/update_password', [AdminLogin::class, 'update_password'])->name('admin.update_password');

        Route::middleware(['admin'])->group(function () {
            /// user management
            Route::any('/dashboard', [AdminController::class, 'dashboard'])->name('admin_dashboard');
            Route::any('/reset_balance', [AdminController::class, 'reset_balance'])->name('reset_balance');

            Route::any('/adminlogout', [AdminLogin::class, 'logout'])->name('adminlogout');

            Route::controller(MemberController::class)->group(function () {
                Route::any('/userList', 'index')->name('userList');
                Route::any('/agent_active/{agent_id}', 'agent_active')->name('agent_active');
                Route::any('/userAdd', 'userFrm')->name('userAdd');
                Route::any('/userAddAction', 'userAddAction')->name('userAddAction');
                Route::any('/user_edit/{id}', 'edit')->name('user_edit');
                Route::post('/user-update/{id}', 'update')->name('user_update');
                Route::delete('/user-delete/{id}', 'delete')->name('admin_user_delete');
                Route::any('/agent_add_balance/{id}', 'agent_add_balance')->name('agent_add_balance');
                Route::get('/user-charge/{user_id}', 'editFees')->name('userCharge');
                Route::post('user/{id}/fees', 'updateFees')->name('member.updateFees');

            });

            Route::controller(\App\Http\Controllers\Admin\AdminCustomerController::class)->group(function () {
                Route::any('/customerList', 'index')->name('customerList');
                Route::any('/customerAdd', 'customerFrm')->name('customerAdd');
                Route::any('/customerAddAction', 'customerAddAction')->name('customerAddAction');
                Route::any('/customer_edit/{id}', 'edit')->name('customer_edit');
                Route::post('/customer_update/{id}', 'update')->name('customer_update');
                Route::delete('/customer-delete/{id}', 'delete')->name('customer_delete');
                Route::any('/customer_add_balance/{id}', 'customer_add_balance')->name('customer_add_balance');
            });

            //merchant
            Route::any('/merchantList', [AdminMerchant::class, 'index'])->name('merchantList');
            Route::any('/merchantAdd', [AdminMerchant::class, 'merchantAdd'])->name('merchantAdd');
            Route::any('/merchantAddAction', [AdminMerchant::class, 'AddAction'])->name('merchantAddAction');
            Route::get('/loginAsMerchant/{id}', [AdminMerchant::class, 'loginAsMerchant'])->name('admin.loginAsMerchant');

            Route::any('/merchant_charge/{merchantId}', [AdminMerchant::class, 'editFees'])->name('merchant_charge');

            Route::post('merchant/{id}/fees', [AdminMerchant::class, 'updateFees'])->name('updateFees');

            Route::any('/merchant_edit/{id}', [AdminMerchant::class, 'edit'])->name('merchant_edit');
            Route::post('/merchant_update/{id}', [AdminMerchant::class, 'update'])->name('merchant_update');

            Route::delete('/merchant_delete/{id}', [AdminMerchant::class, 'delete'])->name('merchant_delete');
            Route::any('/merchant_add_balance/{id}', [AdminMerchant::class, 'merchant_add_balance'])->name('merchant_add_balance');

            Route::controller(\App\Http\Controllers\Admin\WalletTransactionController::class)->group(function () {
                Route::get('wallet-transaction-list', 'index')->name('admin.wallet.transactions');
                Route::delete('/transaction-delete/{id}', 'delete')->name('admin.wallet.transaction_delete');
                Route::any('/change-transaction-status/{id}', 'change_transaction_status')->name('admin.wallet.change-transaction-status');
                Route::post('change_wallet_status', 'change_wallet_status')->name('admin.wallet.change_status');
            });

            Route::get('merchant/payment-request', [MerchantPaymentRequestController::class, 'index'])->name('admin.merchant.payment-request');
            // Route::get('merchant/payment-request/approved-payment-request/{id}', [MerchantPaymentRequestController::class, 'approved_payment_request'])->name('admin.merchant.approved_payment_request');
            // Route::post('/merchant/payment-request/reject-payment-request/{id}', [MerchantPaymentRequestController::class, 'reject_payment_request'])->name('reject_payment_request');
            // Route::post('merchant/payment-request/approve-payment-request/{id}', [MerchantPaymentRequestController::class, 'approve_payment_request'])->name('admin.merchant.approve_payment_request');
            Route::post('/reject-payment', [MerchantPaymentRequestController::class, 'reject_payment_request'])->name('reject-payment-request');
            Route::post('/approve-payment/{id}', [MerchantPaymentRequestController::class, 'approve_payment_request'])->name('approve-payment-request');
            Route::get('/pending-payment/{id}', [MerchantPaymentRequestController::class, 'pending_payment_request'])->name('pending-payment-request');
            Route::post('/payment/spam', [MerchantPaymentRequestController::class, 'markAsSpam'])->name('payment.spam');
            
            // Merchant Crypto Payout Routes
            Route::controller(\App\Http\Controllers\Admin\MerchantPayoutController::class)->prefix('merchant-payout')->group(function () {
                Route::get('/', 'index')->name('admin.merchant-payout.index');

                Route::get('/{id}', 'show')->name('admin.merchant-payout.show');
                Route::get('/{id}/approve-form', 'approveForm')->name('admin.merchant-payout.approve-form');
                Route::post('/{id}/approve', 'approve')->name('admin.merchant-payout.approve');
                Route::get('/{id}/reject-form', 'rejectForm')->name('admin.merchant-payout.reject-form');
                Route::post('/{id}/reject', 'reject')->name('admin.merchant-payout.reject');
                Route::post('/{id}/update-status', 'updateStatus')->name('admin.merchant-payout.update-status');
            });
            
            // Currency Management Routes
            Route::controller(\App\Http\Controllers\Admin\CurrencyRateController::class)->prefix('currency')->group(function () {
                Route::get('/', 'index')->name('admin.currency.index');
                Route::get('/create', 'create')->name('admin.currency.create');
                Route::post('/', 'store')->name('admin.currency.store');
                Route::get('/{id}/edit', 'edit')->name('admin.currency.edit');
                Route::put('/{id}', 'update')->name('admin.currency.update');
                Route::delete('/{id}', 'destroy')->name('admin.currency.destroy');
            });
            
            //modem inbox
            Route::get('/modem_list', [AdminModem::class, 'modemList'])->name('admin_modemList');
            Route::delete('/modem_delete/{id}', [AdminModem::class, 'delete'])->name('modem_delete');
            Route::any('/modem_set_merchant/{id}', [AdminModem::class, 'modem_set_merchant'])->name('modem_set_merchant');
            Route::any('/modem_for_merchant_saveAction', [AdminModem::class, 'modem_for_merchant_saveAction'])->name('modem_for_merchant_saveAction');
            Route::get('/modem_operating_status/{modem_id}/{status}', [AdminModem::class, 'modem_operating_status'])->name('admin.modem_operating_status');
            Route::get('/modem_operating_service_status/{modem_id}/{status}', [AdminModem::class, 'modem_operating_service_status'])->name('admin.modem_operating_service_status');
            //modem finish

            // balance manager
            Route::get('/balance-manager/{status}/{agent_number?}', [AdminBm::class, 'balance_manager'])->name('balance_manager');
            Route::any('/approved-balance-manager/{id}', [AdminBm::class, 'approved_balance_manager'])->name('approved_balance_manager');
            Route::post('/approved-balance-manager-save', [AdminBm::class, 'approved_balance_manager_save'])->name('approved_balance_manager_save');
            Route::post('/reject-balance-manager/{id}', [AdminBm::class, 'reject_balance_manager'])->name('reject_balance_manager');
            Route::any('/view-balance-manager/{id}', [AdminBm::class, 'view_balance_manager'])->name('view_balance_manager');
            // balance manager finish

            //sms inbox
            Route::get('/sms-inbox', [AdminSmsInbox::class, 'inbox_list'])->name('admin_sms_inbox');
            //sms inbox finish

            Route::get('/service-req/details/{id}', [ReportsController::class, 'serviceReqDetails'])->name('service_req_details');
            Route::get('/service-req/{status}/{agent_number?}', [ReportsController::class, 'serviceReq'])->name('serviceReq');
            Route::any('/approved_req/{id}', [ReportsController::class, 'approved_req'])->name('approved_req');
            Route::post('/approved-save', [ReportsController::class, 'approved_save'])->name('approved_save');
            Route::post('/approved-save', [ReportsController::class, 'approved_save'])->name('approved_save');
            Route::post('/reject-req/{id}', [ReportsController::class, 'reject_req'])->name('reject_req');
            Route::post('reject_req', [ReportsController::class, 'RejectRequest'])->name('service.reject_req');
            Route::get('/resend-req/{id}', [ReportsController::class, 'resend_req'])->name('resend_req');
            Route::POST('/admin_service_multiple_action', [ReportsController::class, 'service_multiple_action'])->name('admin.service_multiple_action');

            //Reports Module
            Route::group(['prefix' => 'report'], function () {
                Route::get('/sim_report', [DocsReportsController::class, 'sim_report'])->name('sim_report');
            });

            //deposit Module

            Route::group(['prefix' => 'deposit'], function () {
                Route::get('/', action: [DepositController::class, 'index'])->name('deposit');
                Route::post('/reject_deposit_request', [DepositController::class, 'reject_deposit_request'])->name('reject_deposit_request');
                Route::post('/approve_deposit_request', [DepositController::class, 'approve_deposit_request'])->name('approve_deposit_request');
            });

            //Payment Module

            Route::group(['prefix' => 'payment'], function () {
                Route::get('mobile_banking', [PaymentController::class, 'index_mobile_banking'])->name('payment.mobile_banking');
                Route::get('api_method_list', [PaymentController::class, 'api_method_list'])->name('payment.api_method_list');
                Route::get('add_api_method', [PaymentController::class, 'add_api_method'])->name('payment.add_api_method');
                Route::post('add_api_method_store', [PaymentController::class, 'add_api_method_store'])->name('payment.add_api_method_store');
                Route::get('api_method_edit/{id}', [PaymentController::class, 'api_method_edit'])->name('payment.api_method_edit');
                Route::post('api_method_update', [PaymentController::class, 'api_method_update'])->name('payment.api_method_update');

                // Route::get('create_mobile_banking',[PaymentController::class,'CreateMobileBankingPayment'])->name('payment.create_mobile_banking');
                Route::get('create_mobile_banking', [PaymentController::class, 'mobile_banking_create_view'])->name('payment.mobile_banking_create_view');
                Route::get('get_agent_modem/{id}', [PaymentController::class, 'get_agent_modems'])->name('payment.agents_modems');
                Route::post('create_payment_mobile_banking', [PaymentController::class, 'create_payment_method'])->name('payment.create_payment_mobile_banking');
                Route::post('edit_status_payment_mobile_banking', [PaymentController::class, 'edit_status_mobile_banking'])->name('payment.edit_status_mobile_banking');
                Route::post('destroy_pm', [PaymentController::class, 'pm_destroy'])->name('payment.destroy');
            });

            Route::controller(\App\Http\Controllers\Admin\WithdrawMethodController::class)
                ->prefix('withdraw')
                ->group(function () {
                    Route::get('mobile_banking', 'index')->name('withdraw.mobile_banking');
                    Route::get('create_mobile_banking', 'mobile_banking_create_view')->name('withdraw.mobile_banking_create_view');
                    Route::post('create_payment_method', 'create_payment_method')->name('withdraw.create_payment_method');
                    Route::post('edit_status', 'edit_status')->name('withdraw.edit_status');
                    Route::post('destroy_withdraw', 'withdraw_destroy')->name('withdraw.destroy');
                });

            Route::controller(\App\Http\Controllers\Admin\CryptoCurrencyController::class)
                ->prefix('crypto')
                ->group(function () {
                    Route::get('crypto-index', 'index')->name('crypto.index');
                    Route::get('create_currency_form', 'create_currency_form')->name('crypto.create_currency_form');
                    Route::post('save_currency_form', 'save_currency_form')->name('crypto.save_currency_form');
                    Route::post('edit_status', 'edit_status')->name('crypto.edit_status');
                    Route::post('crypto_destroy', 'crypto_destroy')->name('crypto.destroy');
                });

            //MFS Operations
            Route::group(['prefix' => 'mfs'], function () {
                Route::get('index', [MFS_Controller::class, 'index'])->name('mfs.index');
                Route::get('create_mfs', [MFS_Controller::class, 'create_mfs'])->name('mfs.create_mfs');
                Route::post('insert_mfs', [MFS_Controller::class, 'insert_mfs'])->name('mfs.insert_mfs');
                Route::get('edit_mfs_view/{id}', [MFS_Controller::class, 'edit_mfs_view'])->name('mfs.edit_mfs_view');
                Route::post('edit_mfs', [MFS_Controller::class, 'edit_mfs'])->name('mfs.edit_mfs');
                Route::post('destroy_mfs', [MFS_Controller::class, 'mfs_destroy'])->name('mfs.destroy');
                Route::post('update_status', [MFS_Controller::class, 'status_update'])->name('mfs.status_update');
                Route::post('/mfs/{id}/update', [MFS_Controller::class, 'update_mfs'])->name('mfs.update_mfs');
            });

            //mobcash

            Route::controller(MobcashController::class)->group(function () {
                Route::any('/mcuserlist', 'index')->name('mcuserlist');
                Route::any('/mcuseractive/{user_id}', 'mcuser_active')->name('mcuser_active');
                Route::any('/mcuserAdd', 'mcuserAdd')->name('mcuserAdd');
                Route::any('/mcuserAddAction', 'mcuserAddAction')->name('mcuserAddAction');

                Route::any('/mcRequest', 'mcrequest')->name('mcrequest');
                Route::any('/mcrequestwithdraw', 'mcrequestwithdraw')->name('mcrequestwithdraw');
            });

            //bulk_sms
            Route::post('bulk_sms/check_sms_connection', [SmsSettingController::class, 'check_sms_connection'])->name('bulk_sms.check_sms_connection');
            Route::get('bulk_sms/test_view', [SmsSettingController::class, 'test_view'])->name('bulk_sms.test_view');
            Route::resource('bulk_sms', SmsSettingController::class);
            Route::resource('settings', SettingsController::class);

            // Maintenance Mode Routes
            Route::post('/settings/toggle-maintenance', [SettingsController::class, 'toggleMaintenanceMode'])->name('admin.settings.toggle_maintenance');
            Route::get('/settings/maintenance-status', [SettingsController::class, 'getMaintenanceStatus'])->name('admin.settings.maintenance_status');

            // Database Update Routes
            Route::get('/database/update', [DatabaseUpdateController::class, 'index'])->name('admin.database.update');
            Route::post('/database/run-migrations', [DatabaseUpdateController::class, 'runMigrations'])->name('admin.database.run-migrations');
            Route::get('/database/check-status', [DatabaseUpdateController::class, 'checkStatus'])->name('admin.database.check-status');
            Route::post('/database/create-migrations-table', [DatabaseUpdateController::class, 'createMigrationsTable'])->name('admin.database.create-migrations-table');

            // Direct Migration URL (Browser accessible)
            Route::get('/run-migrations-now', [MigrationController::class, 'runMigrationsNow'])->name('admin.run-migrations-now');
            Route::get('/migration-status', [MigrationController::class, 'checkStatus'])->name('admin.migration-status');

            Route::get('service/change_status/{id}', [ServiceController::class, 'service_change_status'])->name('service.change_status');
            Route::resource('service', ServiceController::class);

            Route::group(['prefix' => 'support'], function () {
                Route::any('/', [AdminSupportController::class, 'index'])->name('admin.support_list');
                Route::get('view_ticket/{ticket}', [AdminSupportController::class, 'view_ticket'])->name('admin.view_ticket');
                Route::post('/submit_solution_ticket', [AdminSupportController::class, 'submitSolutionTicket'])->name('admin.submitSolutionTicket');
                Route::post('/close_ticket', [AdminSupportController::class, 'closeTicket'])->name('admin.closeTicket');
            });

            Route::group(['prefix' => 'report'], function () {
                Route::any('/payment', [AllReportController::class, 'PaymentReport'])->name('report.payment_report');
                Route::any('/service/{service?}', [AllReportController::class, 'ServiceReport'])->name('report.service_report');
                Route::any('/balance-summary', [AllReportController::class, 'BalanceSummary'])->name('report.balance_summary');
            });
        });
    });
});
