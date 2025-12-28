<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Admin;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Create or update Super Admin Role
        $superAdminRole = Role::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $this->command->info('Super Admin role created/updated successfully!');

        // Get all permissions
        $allPermissions = Permission::all();
        
        if ($allPermissions->isEmpty()) {
            $this->command->warn('No permissions found. Please run PermissionSeeder first.');
            return;
        }

        // Assign all permissions to Super Admin role
        $superAdminRole->permissions()->sync($allPermissions->pluck('id'));
        
        $this->command->info('All permissions assigned to Super Admin role!');
        $this->command->info('Total permissions: ' . $allPermissions->count());

        // Check if there's an admin with role_id = 1
        $adminCount = Admin::where('role_id', 1)->count();
        
        if ($adminCount > 0) {
            $this->command->info("Found {$adminCount} admin(s) with Super Admin role.");
        } else {
            $this->command->warn('No admin found with Super Admin role (role_id = 1).');
            $this->command->warn('Please assign role_id = 1 to your main admin account.');
        }
    }
}
