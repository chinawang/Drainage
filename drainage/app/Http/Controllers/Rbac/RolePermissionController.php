<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\RolePermissionLogic;
use App\Http\Validations\Rbac\RolePermissionValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolePermissionController extends Controller
{
    /**
     * @var RolePermissionLogic
     */
    protected $rolePermissionLogic;

    /**
     * @var RolePermissionValidation
     */
    protected $rolePermissionValidation;

    /**
     * RolePermissionController constructor.
     * @param RolePermissionLogic $rolePermissionLogic
     * @param RolePermissionValidation $rolePermissionValidation
     */
    public function __construct(RolePermissionLogic $rolePermissionLogic,RolePermissionValidation $rolePermissionValidation)
    {
        $this->rolePermissionLogic = $rolePermissionLogic;
        $this->rolePermissionValidation = $rolePermissionValidation;
    }
}
