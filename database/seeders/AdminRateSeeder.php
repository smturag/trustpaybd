<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if merchant_id column exists
        $hasMerchantId = Schema::hasColumn('admin_rate', 'merchant_id');
        
        $rates = [
            ['service_id' => 1, 'operator' => 'Bkash', 'type' => 'cash_in', 'rate' => 100, 'charge' => 1.50, 'commission' => 0.50, 'status' => true],
            ['service_id' => 2, 'operator' => 'Bkash', 'type' => 'cash_out', 'rate' => 100, 'charge' => 1.85, 'commission' => 0.70, 'status' => true],
            ['service_id' => 1, 'operator' => 'Nagad', 'type' => 'cash_in', 'rate' => 100, 'charge' => 1.20, 'commission' => 0.40, 'status' => true],
            ['service_id' => 2, 'operator' => 'Nagad', 'type' => 'cash_out', 'rate' => 100, 'charge' => 1.50, 'commission' => 0.60, 'status' => true],
            ['service_id' => 3, 'operator' => 'Rocket', 'type' => 'send_money', 'rate' => 100, 'charge' => 1.00, 'commission' => 0.30, 'status' => true],
        ];

        foreach ($rates as $rate) {
            $data = [
                'rate' => $rate['rate'],
                'charge' => $rate['charge'],
                'commission' => $rate['commission'],
                'status' => $rate['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Add merchant_id only if column exists
            if ($hasMerchantId) {
                $data['merchant_id'] = null;
            }
            
            DB::table('admin_rate')->updateOrInsert(
                [
                    'service_id' => $rate['service_id'],
                    'operator' => $rate['operator'],
                    'type' => $rate['type']
                ],
                $data
            );
        }

        $this->command->info('Admin rates seeded successfully!');
    }
}
