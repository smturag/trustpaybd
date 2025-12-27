<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CurrencyRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('currency_rates')) {
            $this->command->warn('Table currency_rates does not exist. Skipping seeder.');
            return;
        }

        $currencies = [
            ['currency_code' => 'BDT', 'currency_name' => 'Bangladeshi Taka', 'currency_symbol' => '৳', 'exchange_rate_to_bdt' => 1.000000, 'status' => 1],
            ['currency_code' => 'USD', 'currency_name' => 'US Dollar', 'currency_symbol' => '$', 'exchange_rate_to_bdt' => 110.000000, 'status' => 1],
            ['currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€', 'exchange_rate_to_bdt' => 120.000000, 'status' => 1],
            ['currency_code' => 'GBP', 'currency_name' => 'British Pound', 'currency_symbol' => '£', 'exchange_rate_to_bdt' => 140.000000, 'status' => 1],
            ['currency_code' => 'INR', 'currency_name' => 'Indian Rupee', 'currency_symbol' => '₹', 'exchange_rate_to_bdt' => 1.320000, 'status' => 1],
            ['currency_code' => 'SAR', 'currency_name' => 'Saudi Riyal', 'currency_symbol' => '﷼', 'exchange_rate_to_bdt' => 29.350000, 'status' => 1],
            ['currency_code' => 'AED', 'currency_name' => 'UAE Dirham', 'currency_symbol' => 'د.إ', 'exchange_rate_to_bdt' => 30.000000, 'status' => 1],
        ];

        foreach ($currencies as $currency) {
            DB::table('currency_rates')->updateOrInsert(
                ['currency_code' => $currency['currency_code']],
                [
                    'currency_name' => $currency['currency_name'],
                    'currency_symbol' => $currency['currency_symbol'],
                    'exchange_rate_to_bdt' => $currency['exchange_rate_to_bdt'],
                    'status' => $currency['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Currency rates seeded successfully!');
    }
}
