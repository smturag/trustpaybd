<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class McUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'userid' => 'mcuser001',
                'password' => Hash::make('password123'),
                'workcode' => 'WC001',
                'currency' => 'BDT',
                'location' => 'Dhaka',
                'appguid' => 'app-guid-001',
                'status' => 1,
            ],
        ];

        foreach ($users as $user) {
            DB::table('mc_users')->updateOrInsert(
                ['userid' => $user['userid']],
                [
                    'password' => $user['password'],
                    'workcode' => $user['workcode'],
                    'currency' => $user['currency'],
                    'location' => $user['location'],
                    'appguid' => $user['appguid'],
                    'status' => $user['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('MC users seeded successfully!');
    }
}
