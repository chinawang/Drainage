<?php

namespace App\Http\Controllers\User;

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
     * @var UserValidation
     */
    protected $userValidation;


    /**
     * UserController constructor.
     * @param UserLogic $userLogic
     * @param UserValidation $userValidation
     */
    public function __construct(UserLogic $userLogic,UserValidation $userValidation)
    {
        $this->userLogic = $userLogic;
        $this->userValidation = $userValidation;
    }

    /**
     * 显示新增用户窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddUserForm()
    {
        return view('views.user.addUser');
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
        return view('views.user.updateUser',$param);
    }

    /**
     * 显示重置密码窗口
     *
     * @param $userID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetPasswordForm($userID)
    {
        $param = ['userID' => $userID];
        return view('views.user.resetPassword',$param);
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
        return view('views.user.userProfile',$param);
    }

    /**
     * 显示用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userList()
    {
        $input = [];
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $userPaginate = $this->userLogic->getUsers($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['users' => $userPaginate->toJson()];
        return view('views.user.list',$param);
    }

    /**
     * 创建新用户
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewUser()
    {
        $input = null;
        return $this->userLogic->createUser($input);
    }

    /**
     * 修改用户信息
     *
     * @param $userID
     * @return bool
     */
    public function updateUser($userID)
    {
        $input = null;
        return $this->userLogic->updateUser($userID,$input);
    }

    /**
     * 删除用户
     *
     * @param $userID
     * @return bool
     */
    public function deleteUser($userID)
    {
        return $this->userLogic->deleteUser($userID);
    }

    /**
     * 重置用户密码
     *
     * @param $userID
     * @return bool
     */
    public function resetUserPassword($userID)
    {
        $newPassword = null;
        return $this->userLogic->resetPassword($userID,$newPassword);
    }
}
