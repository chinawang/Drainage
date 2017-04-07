<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\PermissionLogic;
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
     * @var PermissionLogic
     */
    protected $permissionLogic;

    /**
     * @var RolePermissionValidation
     */
    protected $rolePermissionValidation;

    /**
     * RolePermissionController constructor.
     * @param RolePermissionLogic $rolePermissionLogic
     * @param PermissionLogic $permissionLogic
     * @param RolePermissionValidation $rolePermissionValidation
     */
    public function __construct(RolePermissionLogic $rolePermissionLogic,PermissionLogic $permissionLogic,RolePermissionValidation $rolePermissionValidation)
    {
        $this->rolePermissionLogic = $rolePermissionLogic;
        $this->permissionLogic = $permissionLogic;
        $this->rolePermissionValidation = $rolePermissionValidation;
    }

    /**
     * @param $roleID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSetRolePermissionForm($roleID)
    {
        $permissions = $this->permissionLogic->getAllPermissions();
        $assignPermissionIDs = $this->rolePermissionLogic->getPermissionIDsByRoleID($roleID);
        $param = ['permissions' => $permissions->toJson(),'assignPermissionIDs' => $assignPermissionIDs->toJson()];

        return view('views.rbac.setRolePermission',$param);
    }

    /**
     * @return bool
     */
    public function setRolePermission()
    {
        $input = $this->rolePermissionValidation->setRolePermission();
        $roleID = $input['role_id'];
        $permissionIDs = array_column($input,'id');
        return $this->rolePermissionLogic->setRolePermissions($roleID,$permissionIDs);
    }

}
