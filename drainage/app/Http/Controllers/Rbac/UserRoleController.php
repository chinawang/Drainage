<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Logic\Rbac\UserRoleLogic;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Rbac\UserRoleValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * UserRoleController constructor.
     * @param UserRoleLogic $userRoleLogic
     * @param RoleLogic $roleLogic
     * @param UserRoleValidation $userRoleValidation
     * @param UserLogic $userLogic
     */
    public function __construct(UserRoleLogic $userRoleLogic,RoleLogic $roleLogic,UserRoleValidation $userRoleValidation,UserLogic $userLogic)
    {
        $this->middleware('auth');

        $this->userRoleLogic = $userRoleLogic;
        $this->roleLogic = $roleLogic;
        $this->userRoleValidation = $userRoleValidation;
        $this->userLogic = $userLogic;
    }

    /**
     * @param $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSetUserRoleForm($userID)
    {
        $roles = $this->roleLogic->getAllRoles();
        $assignRoleIDs = $this->userRoleLogic->getRoleIDsByUserID($userID);
        $user = $this->userLogic->findUser($userID);
        $param = ['user' => $user ,'roles' => $roles,'assignRoleIDs' => $assignRoleIDs];

        return view('rbac.setUserRole',$param);
    }

    /**
     * @return bool
     */
    public function setUserRole($userID)
    {
        $input = $this->userRoleValidation->setUserRole();
//        $userID = $input['user_id'];
        $roleIDs = $input['roles'];

        $result = $this->userRoleLogic->setUserRoles($userID,$roleIDs);


        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '设置了用户角色']);

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
