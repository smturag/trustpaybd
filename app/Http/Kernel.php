<?php

namespace App\Http;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\CheckMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
		'admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
        'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
		'ck2stepadmin' => \App\Http\Middleware\CheckSecurityAdmin::class,
		'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        'permission' => \App\Http\Middleware\CheckPermission::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
		'safepass' => \App\Http\Middleware\LastUserActivity::class,
		'membersecur' => \App\Http\Middleware\MemberSecurity::class,
		'merchant' => \App\Http\Middleware\RedirectIfNotMerchant::class,
        'merchant.guest' => \App\Http\Middleware\RedirectIfMerchant::class,
		'customer' => \App\Http\Middleware\RedirectIfNotCustomer::class,
        'customer.guest' => \App\Http\Middleware\RedirectIfCustomer::class,
        'apikey' => \App\Http\Middleware\ApiKeyAuth::class,
        'apikey2' => \App\Http\Middleware\ApiKeyAuth2::class,
        'verify' => \App\Http\Middleware\CheckApiStatus::class,
        'verify_ibot_token' => \App\Http\Middleware\CheckIbotToken::class,
        'telegram_token' => \App\Http\Middleware\TelegramToken::class,

    ];




}