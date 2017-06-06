<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\PermissionLogic;
use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Logic\Rbac\RolePermissionLogic;
use App\Http\Validations\Rbac\RoleValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * @var RolePermissionLogic
     */
    protected $rolePermissionLogic;

    /**
     * @var PermissionLogic
     */
    protected $permissionLogic;

    /**
     * @var RoleValidation
     */
    protected $roleValidation;

    /**
     * RoleController constructor.
     * @param RoleLogic $roleLogic
     * @param PermissionLogic $permissionLogic
     * @param RolePermissionLogic $rolePermissionLogic
     * @param RoleValidation $roleValidation
     */
    public function __construct(RoleLogic $roleLogic, PermissionLogic $permissionLogic, RolePermissionLogic $rolePermissionLogic, RoleValidation $roleValidation)
    {
        $this->middleware('auth');

        $this->roleLogic = $roleLogic;
        $this->permissionLogic = $permissionLogic;
        $this->rolePermissionLogic = $rolePermissionLogic;
        $this->roleValidation = $roleValidation;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddRoleForm()
    {
        return view('rbac.addRole');
    }

    /**
     * @param $roleID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateRoleForm($roleID)
    {
        $role = $this->roleLogic->findRole($roleID);
        $param = ['role' => $role];
        return view('rbac.updateRole', $param);
    }

    /**
     * @param $roleID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function roleInfo($roleID)
    {
        $role = $this->roleLogic->findRole($roleID);
        return $role;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function roleList()
    {
        $input = $this->roleValidation->rolePaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $orderColumn = array_get($input, 'order_column', 'created_at');
        $orderDirection = array_get($input, 'order_direction', 'asc');
        $pageSize = array_get($input, 'page_size', 10);
        $rolePaginate = $this->roleLogic->getRoles($pageSize, $orderColumn, $orderDirection, $cursorPage);

        foreach ($rolePaginate as $role) {
            $assignPermissionIDs = $this->rolePermissionLogic->getPermissionIDsByRoleID($role['id']);
            $assignPermissions = $this->permissionLogic->getPermissionsByIDs($assignPermissionIDs);
            $role['assignPermissions'] = $assignPermissions;
        }

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了角色信息']);

        $param = ['roles' => $rolePaginate];
        return view('rbac.roleList', $param);
    }

    /**
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewRole()
    {
        $input = $this->roleValidation->storeNewRole();
        $result = $this->roleLogic->createRole($input);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '保存成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了角色信息']);

            return redirect('/role/lists');
        } else {
            session()->flash('flash_message_overlay', [
                'title' => '保存失败!',
                'message' => '数据未保存成功,请稍后重试!',
                'level' => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * @param $roleID
     * @return bool
     */
    public function updateRole($roleID)
    {
        $input = $this->roleValidation->updateRole($roleID);
        $result = $this->roleLogic->updateRole($roleID, $input);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '保存成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了角色信息']);

            return redirect('/role/lists');
        } else {
            session()->flash('flash_message_overlay', [
                'title' => '保存失败!',
                'message' => '数据未保存成功,请稍后重试!',
                'level' => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * @return bool
     */
    public function deleteRole($roleID)
    {
//        $roleID = $this->roleValidation->deleteRole();
        $result = $this->roleLogic->deleteRole($roleID);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '删除成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了角色信息']);

        } else {
            session()->flash('flash_message_overlay', [
                'title' => '删除失败!',
                'message' => '数据未删除成功,请稍后重试!',
                'level' => 'error'
            ]);
        }

        return redirect('/role/lists');
    }
}
