<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminActivityLog;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first admin user or create sample data
        $admin = Admin::first();
        
        if (!$admin) {
            $this->command->warn('No admin users found. Please create an admin user first.');
            return;
        }

        $adminName = $admin->admin_name ?? $admin->username ?? 'Admin';

        $logs = [
            [
                'log_name' => 'authentication',
                'description' => "{$adminName} logged in",
                'subject_type' => 'App\Models\Admin',
                'subject_id' => $admin->id,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([]),
                'event' => 'login',
                'ip_address' => '103.149.130.10',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'device' => 'Desktop',
                'browser' => 'Chrome',
                'platform' => 'Windows 10',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Dhaka',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'log_name' => 'user',
                'description' => 'Created new user account',
                'subject_type' => 'App\Models\Member',
                'subject_id' => 1,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([
                    'name' => 'John Doe',
                    'email' => 'john@example.com'
                ]),
                'event' => 'created',
                'ip_address' => '103.149.130.15',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'device' => 'Desktop',
                'browser' => 'Chrome',
                'platform' => 'Mac OS X',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Chittagong',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
            [
                'log_name' => 'merchant',
                'description' => 'Updated merchant details',
                'subject_type' => 'App\Models\Merchant',
                'subject_id' => 1,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([
                    'changes' => ['status' => 'active']
                ]),
                'event' => 'updated',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
                'device' => 'Mobile',
                'browser' => 'Safari',
                'platform' => 'iOS 15.0',
                'country' => 'Local',
                'country_code' => 'LC',
                'city' => 'Local',
                'created_at' => now()->subMinutes(45),
                'updated_at' => now()->subMinutes(45),
            ],
            [
                'log_name' => 'payment_request',
                'description' => 'Payment request approved',
                'subject_type' => 'App\Models\MerchantPaymentRequest',
                'subject_id' => 1,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([
                    'amount' => 5000,
                    'method' => 'bkash'
                ]),
                'event' => 'approved',
                'ip_address' => '103.149.130.20',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Gecko/20100101 Firefox/95.0',
                'device' => 'Desktop',
                'browser' => 'Firefox',
                'platform' => 'Windows 10',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Sylhet',
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ],
            [
                'log_name' => 'payment_request',
                'description' => 'Payment request rejected',
                'subject_type' => 'App\Models\MerchantPaymentRequest',
                'subject_id' => 2,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([
                    'amount' => 3000,
                    'reason' => 'Insufficient balance'
                ]),
                'event' => 'rejected',
                'ip_address' => '103.149.130.25',
                'user_agent' => 'Mozilla/5.0 (Linux; Android 11) AppleWebKit/537.36',
                'device' => 'Mobile',
                'browser' => 'Chrome',
                'platform' => 'Android 11',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Rajshahi',
                'created_at' => now()->subMinutes(25),
                'updated_at' => now()->subMinutes(25),
            ],
            [
                'log_name' => 'settings',
                'description' => "Settings 'maintenance_mode' changed",
                'subject_type' => null,
                'subject_id' => null,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([
                    'setting' => 'maintenance_mode',
                    'old_value' => false,
                    'new_value' => true
                ]),
                'event' => 'settings_changed',
                'ip_address' => '103.149.130.30',
                'user_agent' => 'Mozilla/5.0 (Windows NT 11.0; Win64; x64) Edge/96.0',
                'device' => 'Desktop',
                'browser' => 'Edge',
                'platform' => 'Windows 11',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Dhaka',
                'created_at' => now()->subMinutes(20),
                'updated_at' => now()->subMinutes(20),
            ],
            [
                'log_name' => 'balance',
                'description' => 'Balance updated from 1000 to 1500 - Manual adjustment by admin',
                'subject_type' => 'App\Models\Member',
                'subject_id' => 1,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([
                    'old_balance' => 1000,
                    'new_balance' => 1500,
                    'difference' => 500,
                    'reason' => 'Manual adjustment by admin'
                ]),
                'event' => 'balance_updated',
                'ip_address' => '103.149.130.35',
                'user_agent' => 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/605.1.15',
                'device' => 'Tablet',
                'browser' => 'Safari',
                'platform' => 'iOS 14.0',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Khulna',
                'created_at' => now()->subMinutes(15),
                'updated_at' => now()->subMinutes(15),
            ],
            [
                'log_name' => 'user',
                'description' => 'Deleted user account',
                'subject_type' => 'App\Models\Member',
                'subject_id' => 5,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([
                    'name' => 'Test User',
                    'email' => 'test@example.com'
                ]),
                'event' => 'deleted',
                'ip_address' => '103.149.130.40',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64) Gecko/20100101 Firefox/95.0',
                'device' => 'Desktop',
                'browser' => 'Firefox',
                'platform' => 'Ubuntu',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Barisal',
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ],
            [
                'log_name' => 'merchant',
                'description' => 'Viewed merchant details',
                'subject_type' => 'App\Models\Merchant',
                'subject_id' => 2,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([]),
                'event' => 'viewed',
                'ip_address' => '103.149.130.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/96.0',
                'device' => 'Desktop',
                'browser' => 'Chrome',
                'platform' => 'Windows 10',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Dhaka',
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ],
            [
                'log_name' => 'authentication',
                'description' => "{$adminName} logged out",
                'subject_type' => 'App\Models\Admin',
                'subject_id' => $admin->id,
                'causer_type' => 'App\Models\Admin',
                'causer_id' => $admin->id,
                'properties' => json_encode([]),
                'event' => 'logout',
                'ip_address' => '103.149.130.10',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'device' => 'Desktop',
                'browser' => 'Chrome',
                'platform' => 'Windows 10',
                'country' => 'Bangladesh',
                'country_code' => 'BD',
                'city' => 'Dhaka',
                'created_at' => now()->subMinutes(2),
                'updated_at' => now()->subMinutes(2),
            ],
        ];

        foreach ($logs as $log) {
            AdminActivityLog::create($log);
        }

        $this->command->info('Activity logs seeded successfully!');
        $this->command->info('Total logs created: ' . count($logs));
    }
}
