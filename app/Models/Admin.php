<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Notifications\AdminResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guard_name = 'admin';

    protected $guarded = [];


    protected $hidden = [
        'password', 'remember_token',
    ];


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }

    /**
     * Get the role assigned to the admin.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if admin has a specific permission
     */
    public function hasPermission($permission)
    {
        // Super admin bypass (role_id = 1)
        if ($this->role_id == 1) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermissionTo($permission);
    }

    /**
     * Check if admin has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        // Super admin bypass
        if ($this->role_id == 1) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        foreach ((array) $permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if admin has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        // Super admin bypass
        if ($this->role_id == 1) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        foreach ((array) $permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all permissions for the admin
     */
    public function getAllPermissions()
    {
        if (!$this->role) {
            return collect([]);
        }

        return $this->role->permissions;
    }
}
