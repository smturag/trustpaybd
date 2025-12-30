<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

     protected $commands = [
        \App\Console\Commands\DeleteUnverifiedCustomers::class,
         \App\Console\Commands\CheckPaymentRequest::class,
         \App\Console\Commands\RealMerchantTransactionCheck::class,
         \App\Console\Commands\SendTransactionToBalanceManager::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:delete-unverified-customers')->everyThirtyMinutes()->runInBackground();
        // $schedule->command('app:check-payment-request')->everyFiveSeconds()->runInBackground();
        $schedule->command('app:send-s-m-s-to-agent')->everyFiveSeconds()->runInBackground();
        $schedule->command('app:bm-to-payment-check')->everyTwoSeconds()->runInBackground();
        $schedule->command('app:auto-assign-b-m-to-agent')->everyFiveSeconds()->runInBackground();
        $schedule->command('app:api-to-payment-check')->everyTwoSeconds()->runInBackground();
        $schedule->command('app:reject-payment-request')->everyFiveSeconds()->runInBackground();
        $schedule->command('app:real-merchant-transaction-check')->everyFiveMinutes()->runInBackground();
        $schedule->command('app:send-transaction-to-bm')->everyMinute()->runInBackground();
        
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }


}
