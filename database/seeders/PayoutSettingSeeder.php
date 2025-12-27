<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PayoutSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('payout_settings')) {
            $this->command->warn('Table payout_settings does not exist. Skipping seeder.');
            return;
        }

        $settings = [
            ['key' => 'crypto_payout_fee_percentage', 'value' => '1', 'description' => 'Crypto payout fee percentage (e.g., 1 for 1%)'],
            ['key' => 'min_payout_amount', 'value' => '100', 'description' => 'Minimum payout amount allowed'],
            ['key' => 'max_payout_amount', 'value' => '100000', 'description' => 'Maximum payout amount allowed'],
            ['key' => 'payout_processing_time', 'value' => '24', 'description' => 'Payout processing time in hours'],
            ['key' => 'auto_approve_limit', 'value' => '5000', 'description' => 'Auto approve payout under this amount'],
        ];

        foreach ($settings as $setting) {
            DB::table('payout_settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Payout settings seeded successfully!');
    }
}
