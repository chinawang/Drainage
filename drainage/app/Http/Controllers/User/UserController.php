<?php

namespace App\Http\Controllers\User;

use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Logic\Rbac\UserRoleLogic;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\User\UserValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * @var UserRoleLogic
     */
    protected $userRoleLogic;

    /**
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * @var UserValidation
     */
    protected $userValidation;


    /**
     * UserController constructor.
     * @param UserLogic $userLogic
     * @param UserRoleLogic $userRoleLogic
     * @param RoleLogic $roleLogic
     * @param UserValidation $userValidation
     */
    public function __construct(UserLogic $userLogic,UserRoleLogic $userRoleLogic,RoleLogic $roleLogic,UserValidation $userValidation)
    {
        $this->middleware('auth');

        $this->userLogic = $userLogic;
        $this->userRoleLogic = $userRoleLogic;
        $this->roleLogic = $roleLogic;
        $this->userValidation = $userValidation;
    }

    /**
     * 显示新增用户窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddUserForm()
    {
        return view('user.addUser');
    }

    /**
     * 显示修改用户信息窗口
     *
     * @param $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateUserForm($userID)
    {
        $userInfo = $this->userLogic->findUser($userID);
        $param = ['user' => $userInfo];
        return view('user.updateUser',$param);
    }

    /**
     * 显示重置密码窗口
     *
     * @param $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetPasswordForm($userID)
    {
        $userInfo = $this->userLogic->findUser($userID);
        $param = ['user' => $userInfo];
        return view('user.resetPassword',$param);
    }

    /**
     * 显示用户信息
     *
     * @param $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userProfile($userID)
    {
        $userInfo = $this->userLogic->findUser($userID);
        $param = ['user' => $userInfo];
        return view('user.userProfile',$param);
    }

    /**
     * 显示用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userList()
    {
        $input = $this->userValidation->userPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $userPaginate = $this->userLogic->getUsers($pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach ($userPaginate as $user)
        {
            $assignRoleIDs = $this->userRoleLogic->getRoleIDsByUserID($user['id']);
            $assignRoles = $this->roleLogic->getRolesByIDs($assignRoleIDs);
            $user['assignRoles'] = $assignRoles;
        }

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了账户信息']);

        $param = ['users' => $userPaginate];
        return view('user.list',$param);
    }

    /**
     * 创建新用户
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewUser()
    {
        $input = $this->userValidation->storeNewUser();
        $result = $this->userLogic->createUser($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了账户信息']);

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

    /**
     * 修改用户信息
     *
     * @param $userID
     * @return bool
     */
    public function updateUser($userID)
    {
        $input = $this->userValidation->updateUser($userID);
        $result = $this->userLogic->updateUser($userID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了账户信息']);

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

    /**
     * 删除用户
     *
     * @return bool
     */
    public function deleteUser($userID)
    {
//        $userID = $this->userValidation->deleteUser();
        $result = $this->userLogic->deleteUser($userID);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '删除成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了账户信息']);

        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '删除失败!',
                'message'   => '数据未删除成功,请稍后重试!',
                'level'     => 'error'
            ]);
        }

        return redirect('/user/lists');
    }

    /**
     * 重置用户密码
     *
     * @param $userID
     * @return bool
     */
    public function resetUserPassword($userID)
    {
        $data = $this->userValidation->resetPassword($userID);
        $result = $this->userLogic->resetPassword($userID,$data['newPassword']);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '修改了账户密码']);

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
