<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Logic\Rbac\UserRoleLogic;
use App\Http\Validations\Rbac\UserRoleValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    /**
     * @var UserRoleLogic
     */
    protected $userRoleLogic;

    /**
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * @var UserRoleValidation
     */
    protected $userRoleValidation;

    /**
     * UserRoleController constructor.
     * @param UserRoleLogic $userRoleLogic
     * @param RoleLogic $roleLogic
     * @param UserRoleValidation $userRoleValidation
     */
    public function __construct(UserRoleLogic $userRoleLogic,RoleLogic $roleLogic,UserRoleValidation $userRoleValidation)
    {
        $this->userRoleLogic = $userRoleLogic;
        $this->roleLogic = $roleLogic;
        $this->userRoleValidation = $userRoleValidation;
    }

    /**
     * @param $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSetUserRoleForm($userID)
    {
        $roles = $this->roleLogic->getAllRoles();
        $assignRoleIDs = $this->userRoleLogic->getRoleIDsByUserID($userID);
        $param = ['roles' => $roles,'assignRoleIDs' => $assignRoleIDs];

        return view('views.rbac.setUserRole',$param);
    }

    /**
     * @param $userID
     */
    public function setUserRole($userID)
    {
        $input = [];
        return $this->userRoleLogic->setUserRoles($userID,$input);
    }

}
