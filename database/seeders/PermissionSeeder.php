<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Deposits
            'view-deposits',
            'create-deposits',
            'edit-deposits',
            'delete-deposits',
            'approve-deposits',
            'reject-deposits',
            
            // Withdraws
            'view-withdraws',
            'create-withdraws',
            'edit-withdraws',
            'approve-withdraws',
            'delete-withdraws',
            'reject-withdraws',
            
            // Merchant Payouts
            'view-merchant-payouts',
            'approve-merchant-payouts',
            'reject-merchant-payouts',
            'delete-merchant-payouts',
            'update-merchant-payout-status',
            
            // Crypto Payouts
            'view-crypto-payouts',
            'approve-crypto-payouts',
            'delete-crypto-payouts',
            
            // Balance Management
            'view-balance',
            'manage-balance',
            'view-balance-manager',
            'approve-balance-manager',
            'reject-balance-manager',
            
            // SMS
            'view-sms',
            'send-sms',
            'view-sms-inbox',
            
            // Merchants
            'view-merchants',
            'create-merchants',
            'edit-merchants',
            'delete-merchants',
            'approve-merchants',
            'manage-merchant-balance',
            'login-as-merchant',
            'manage-merchant-fees',
            
            // Payment Requests
            'view-payment-requests',
            'approve-payment-requests',
            'reject-payment-requests',
            'mark-payment-spam',
            'pending-payment-requests',
            
            // Customers
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            'manage-customer-balance',
            
            // Users
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-user-balance',
            'manage-user-fees',
            'activate-users',
            
            // Payment Methods
            'view-payment-methods',
            'create-payment-methods',
            'edit-payment-methods',
            'delete-payment-methods',
            
            // Withdraw Methods
            'view-withdraw-methods',
            'create-withdraw-methods',
            'edit-withdraw-methods',
            'delete-withdraw-methods',
            
            // MFS (Mobile Financial Service)
            'view-mfs',
            'manage-mfs',
            
            // Crypto
            'view-crypto',
            'manage-crypto',
            
            // Currency
            'view-currency',
            'create-currency',
            'edit-currency',
            'delete-currency',
            'manage-currency',
            
            // Modems
            'view-modems',
            'manage-modems',
            'delete-modems',
            'set-modem-merchant',
            'change-modem-status',
            
            // Wallet
            'view-wallet',
            'manage-wallet',
            'view-wallet-transactions',
            'delete-wallet-transactions',
            'change-wallet-transaction-status',
            
            // Service Requests
            'view-service-requests',
            'approve-service-requests',
            'reject-service-requests',
            'resend-service-requests',
            'view-service-request-details',
            'service-multiple-actions',
            
            // Reports
            'view-reports',
            'view-transaction-reports',
            'view-financial-reports',
            'view-sim-reports',
            'export-reports',
            
            // Support
            'view-tickets',
            'manage-tickets',
            
            // Roles & Permissions
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            
            // Admin Management
            'view-admins',
            'create-admins',
            'edit-admins',
            'delete-admins',
            
            // Settings
            'view-settings',
            'edit-settings',
            'view-system-logs',
            'manage-system',
            
            // Activity Logs
            'view-activity-logs',
            
            // Reset Balance
            'reset-balance',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission, 'guard_name' => 'web'],
                [
                    'name' => $permission,
                    'guard_name' => 'web',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $this->command->info('Permissions seeded successfully!');
        $this->command->info('Total permissions created: ' . count($permissions));
    }
}
