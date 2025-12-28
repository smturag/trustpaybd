<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class SyncSuperAdminPermissions extends Command
{
    protected $signature = 'permission:sync-superadmin';
    protected $description = 'Synchronize all permissions to Super Admin role';

    public function handle()
    {
        $this->info('Starting Super Admin permission synchronization...');
        
        $superAdminRole = Role::find(1);
        
        if (!$superAdminRole) {
            $this->error('Super Admin role (ID: 1) not found!');
            return 1;
        }

        $allPermissions = Permission::all();
        
        if ($allPermissions->isEmpty()) {
            $this->error('No permissions found in database!');
            return 1;
        }

        // Sync all permissions
        $superAdminRole->permissions()->sync($allPermissions->pluck('id'));
        
        $this->info('✓ Super Admin role: ' . $superAdminRole->name);
        $this->info('✓ Total permissions in database: ' . $allPermissions->count());
        $this->info('✓ Permissions assigned to Super Admin: ' . $superAdminRole->permissions()->count());
        
        if ($superAdminRole->permissions()->count() === $allPermissions->count()) {
            $this->info('✓ Status: FULLY SYNCHRONIZED');
            $this->info('');
            $this->info('All permissions have been successfully synchronized to Super Admin role!');
            return 0;
        } else {
            $this->error('✗ Status: SYNC FAILED');
            return 1;
        }
    }
}
