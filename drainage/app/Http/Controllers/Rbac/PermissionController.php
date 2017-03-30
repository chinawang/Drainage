<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\PermissionLogic;
use App\Http\Validations\Rbac\PermissionValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * @var PermissionLogic
     */
    protected $permissionLogic;

    /**
     * @var PermissionValidation
     */
    protected $permissionValidation;

    /**
     * PermissionController constructor.
     * @param PermissionLogic $permissionLogic
     * @param PermissionValidation $permissionValidation
     */
    public function __construct(PermissionLogic $permissionLogic,PermissionValidation $permissionValidation)
    {
        $this->permissionLogic = $permissionLogic;
        $this->permissionValidation = $permissionValidation;
    }
}
