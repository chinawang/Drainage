<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\PermissionLogic;
use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Logic\Rbac\RolePermissionLogic;
use App\Http\Validations\Rbac\RolePermissionValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * @var RolePermissionValidation
     */
    protected $rolePermissionValidation;

    /**
     * RolePermissionController constructor.
     * @param RolePermissionLogic $rolePermissionLogic
     * @param PermissionLogic $permissionLogic
     * @param RoleLogic $roleLogic
     * @param RolePermissionValidation $rolePermissionValidation
     */
    public function __construct(RolePermissionLogic $rolePermissionLogic,PermissionLogic $permissionLogic,RoleLogic $roleLogic,RolePermissionValidation $rolePermissionValidation)
    {
        $this->middleware('auth');

        $this->rolePermissionLogic = $rolePermissionLogic;
        $this->permissionLogic = $permissionLogic;
        $this->roleLogic = $roleLogic;
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
        $role = $this->roleLogic->findRole($roleID);
        $param = ['role' => $role,'permissions' => $permissions,'assignPermissionIDs' => $assignPermissionIDs];

        return view('rbac.setRolePermission',$param);
    }

    /**
     * @return bool
     */
    public function setRolePermission($roleID)
    {
        $input = $this->rolePermissionValidation->setRolePermission();
//        $roleID = $input['role_id'];
        $permissionIDs = $input['permissions'];

        $result = $this->rolePermissionLogic->setRolePermissions($roleID,$permissionIDs);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '设置了角色权限']);

            return redirect('/role/lists');
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '保存失败!',
                'message'   => '数据未保存成功,请稍后重试!',
                'level'     => 'error'
            ]);
            return redirect()->back();
        }
    }

}
