<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 19:57
 */

namespace App\Http\Logic\User;


use App\Repositories\User\UserRepository;
use Support\Logic\Logic;

class UserLogic extends Logic
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserLogic constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findUser($userId)
    {
        $conditions = ['delete_process' => 0,'id' => $userId];
        $user = $this->userRepository->findBy($conditions);
        return $user;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getUsers($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions =['delete_process' => 0];
        $userList = $this->userRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $userList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        $conditions = ['delete_process' => 0];
        $userList = $this->userRepository->getBy($conditions);
        return $userList;
    }

    /**
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createUser($attributes)
    {
        return $this->userRepository->create($attributes);
    }

    /**
     * @param $userId
     * @param $input
     * @return bool
     */
    public function updateUser($userId,$input)
    {
        $userInfo['employee_number'] = $input['employee_number'];
        $userInfo['realname'] = $input['realname'];
        $userInfo['office'] = $input['office'];
        $userInfo['contact'] = $input['contact'];
        return $this->userRepository->update($userId,$userInfo);
    }

    /**
     * @param $userId
     * @param $newPassword
     * @return bool
     */
    public function resetPassword($userId,$newPassword)
    {
        $attributes = ['password' => bcrypt($newPassword)];
        return $this->userRepository->update($userId,$attributes);
    }

    /**
     * @param $userId
     * @return bool
     */
    public function deleteUser($userId)
    {
        return $this->userRepository->deleteByFlag($userId);
    }
}