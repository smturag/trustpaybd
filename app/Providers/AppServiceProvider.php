<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $helpers = app_path('Helpers/helpers.php');
        if (file_exists($helpers)) {
            require_once $helpers;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        error_reporting(0);

        // Register custom Blade directives for permission checking
        \Illuminate\Support\Facades\Blade::directive('can', function ($permission) {
            return "<?php if(can({$permission})): ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('endcan', function () {
            return "<?php endif; ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('canany', function ($permissions) {
            return "<?php if(canAny({$permissions})): ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('endcanany', function () {
            return "<?php endif; ?>";
        });
    }
}
