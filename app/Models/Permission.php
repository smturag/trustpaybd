<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id');
    }

    /**
     * Get the users that have this permission directly.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_permissions', 'permission_id', 'model_id')
                    ->where('model_type', User::class);
    }
}
