<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Cash In', 'status' => true],
            ['name' => 'Cash Out', 'status' => true],
            ['name' => 'Send Money', 'status' => true],
            ['name' => 'Payment', 'status' => true],
            ['name' => 'Mobile Recharge', 'status' => true],
            ['name' => 'Bill Pay', 'status' => true],
            ['name' => 'Add Money', 'status' => true],
            ['name' => 'Withdraw', 'status' => true],
        ];

        foreach ($services as $service) {
            DB::table('services')->updateOrInsert(
                ['name' => $service['name']],
                [
                    'status' => $service['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Services seeded successfully!');
    }
}
