<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    /**
     * Get the users that have this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id')
                    ->where('model_type', User::class);
    }

    /**
     * Assign permission to role
     */
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->syncWithoutDetaching($permission);
        return $this;
    }

    /**
     * Remove permission from role
     */
    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        $this->permissions()->detach($permission);
        return $this;
    }

    /**
     * Check if role has permission
     */
    public function hasPermissionTo($permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->where('name', $permission)->exists();
        }

        return $this->permissions()->where('id', $permission->id)->exists();
    }

    /**
     * Sync permissions
     */
    public function syncPermissions($permissions)
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            return is_numeric($permission) ? $permission : Permission::where('name', $permission)->firstOrFail()->id;
        });

        $this->permissions()->sync($permissionIds);
        return $this;
    }
}
