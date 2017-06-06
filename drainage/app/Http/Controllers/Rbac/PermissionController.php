<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\PermissionLogic;
use App\Http\Logic\Rbac\RbacLogic;
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

    protected $rbacLogic;

    /**
     * PermissionController constructor.
     * @param PermissionLogic $permissionLogic
     * @param PermissionValidation $permissionValidation
     */
    public function __construct(PermissionLogic $permissionLogic,PermissionValidation $permissionValidation,RbacLogic $rbacLogic)
    {
        $this->middleware('auth');

        $this->permissionLogic = $permissionLogic;
        $this->permissionValidation = $permissionValidation;
        $this->rbacLogic=$rbacLogic;
    }

    /**
     * 显示添加权限窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddPermissionForm()
    {
        return view('rbac.addPermission');
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
        return view('rbac.updatePermission',$param);
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
        $input = $this->permissionValidation->permissionPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $permissionPaginate = $this->permissionLogic->getPermissions($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['permissions' => $permissionPaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了权限信息']);

        return view('rbac.permissionList',$param);
    }

    /**
     * 添加权限
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewPermission()
    {
        $input = $this->permissionValidation->storeNewPermission();
        $result = $this->permissionLogic->createPermission($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了权限行为']);

            return redirect('/permission/lists');
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

    /**
     * 编辑权限
     *
     * @param $permissionID
     * @return bool
     */
    public function updatePermission($permissionID)
    {
        $input = $this->permissionValidation->updatePermission($permissionID);
        $result = $this->permissionLogic->updatePermission($permissionID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了权限行为']);

            return redirect('/permission/lists');
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

    /**
     * 删除权限
     *
     * @return bool
     */
    public function deletePermission($permissionID)
    {
//        $permissionID = $this->permissionValidation->deletePermission();
        $result = $this->permissionLogic->deletePermission($permissionID);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '删除成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了权限行为']);
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '删除失败!',
                'message'   => '数据未删除成功,请稍后重试!',
                'level'     => 'error'
            ]);
        }

        return redirect('/permission/lists');
    }

    public function check()
    {
        return $this->rbacLogic->check(3,'user-add');
    }
}
