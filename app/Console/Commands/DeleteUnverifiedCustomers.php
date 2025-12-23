<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;

class DeleteUnverifiedCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users who have not been verified within a certain period.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thresholdTime = now()->subMinutes(30);
        Customer::whereNull('email_verified_at')
            ->where('created_at', '<=', $thresholdTime)
            ->delete();
            $this->info('Success');

    }
}
