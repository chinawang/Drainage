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

        return $roles;
        return view('rbac.setUserRole',$param);
    }

    /**
     * @return bool
     */
    public function setUserRole($userID)
    {
        $input = $this->userRoleValidation->setUserRole();
//        $userID = $input['user_id'];
        $roleIDs = array_column($input,'id');

        $result = $this->userRoleLogic->setUserRoles($userID,$roleIDs);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
            return redirect('/user/lists');
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
