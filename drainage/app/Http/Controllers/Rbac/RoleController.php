<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Validations\Rbac\RoleValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * @var RoleValidation
     */
    protected $roleValidation;

    /**
     * RoleController constructor.
     * @param RoleLogic $roleLogic
     * @param RoleValidation $roleValidation
     */
    public function __construct(RoleLogic $roleLogic,RoleValidation $roleValidation)
    {
        $this->roleLogic = $roleLogic;
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
        return view('rbac.updateRole',$param);
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

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $rolePaginate = $this->roleLogic->getRoles($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['roles' => $rolePaginate->toJson()];
        return view('rbac.roleList',$param);
    }

    /**
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewRole()
    {
        $input = $this->roleValidation->storeNewRole();
        return $this->roleLogic->createRole($input);
    }

    /**
     * @param $roleID
     * @return bool
     */
    public function updateRole($roleID)
    {
        $input = $this->roleValidation->updateRole($roleID);
        return $this->roleLogic->updateRole($roleID,$input);
    }

    /**
     * @return bool
     */
    public function deleteRole()
    {
        $roleID = $this->roleValidation->deleteRole();
        return $this->roleLogic->deleteRole($roleID);
    }
}
