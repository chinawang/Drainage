<?php

namespace App\Models\Rbac;

use Illuminate\Database\Eloquent\Model;

class RolePermissionModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_permissions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
