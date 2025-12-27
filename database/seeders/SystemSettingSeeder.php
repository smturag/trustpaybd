<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('system_settings')) {
            $this->command->warn('Table system_settings does not exist. Skipping seeder.');
            return;
        }

        $settings = [
            ['key' => 'maintenance_mode', 'value' => '0', 'description' => 'System maintenance mode status (0=OFF, 1=ON)'],
            ['key' => 'site_name', 'value' => 'TrustPay', 'description' => 'Site name'],
            ['key' => 'site_email', 'value' => 'support@trustpay.com', 'description' => 'Site support email'],
            ['key' => 'site_phone', 'value' => '+880 1234567890', 'description' => 'Site support phone'],
            ['key' => 'currency', 'value' => 'BDT', 'description' => 'Default currency'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'description' => 'Default timezone'],
            ['key' => 'max_transaction_limit', 'value' => '100000', 'description' => 'Maximum transaction limit'],
            ['key' => 'min_transaction_limit', 'value' => '10', 'description' => 'Minimum transaction limit'],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('System settings seeded successfully!');
    }
}
