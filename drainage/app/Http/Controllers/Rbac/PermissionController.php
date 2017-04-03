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

    /**
     * 显示添加权限窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddPermissionForm()
    {
        return view('views.rbac.addPermission');
    }

    /**
     * 显示编辑权限窗口
     *
     * @param $permissionID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdatePermissionForm($permissionID)
    {
        $permission = $this->permissionLogic->findPermission($permissionID);
        $param = ['permission' => $permission];
        return view('views.rbac.updatePermission',$param);
    }

    /**
     * 查询权限信息
     *
     * @param $permissionID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function permissionInfo($permissionID)
    {
        $permission = $this->permissionLogic->findPermission($permissionID);
        return $permission;
    }

    /**
     * 分页查询权限列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function permissionList()
    {
        $input = [];
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $permissionPaginate = $this->permissionLogic->getPermissions($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['permissions' => $permissionPaginate->toJson()];
        return view('views.rbac.permissionList',$param);
    }

    /**
     * 添加权限
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewPermission()
    {
        $input = [];
        return $this->permissionLogic->createPermission($input);
    }

    /**
     * 编辑权限
     *
     * @param $permissionID
     * @return bool
     */
    public function updatePermission($permissionID)
    {
        $input = [];
        return $this->permissionLogic->updatePermission($permissionID,$input);
    }

    /**
     * 删除权限
     *
     * @param $permissionID
     * @return bool
     */
    public function deletePermission($permissionID)
    {
        return $this->permissionLogic->deletePermission($permissionID);
    }
}
