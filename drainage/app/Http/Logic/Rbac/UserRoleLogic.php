<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:15
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\RoleRepository;
use App\Repositories\Rbac\UserRoleRepository;
use Support\Logic\Logic;

class UserRoleLogic extends Logic
{
    /**
     * @var UserRoleRepository
     */
    protected $userRoleRepository;

    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * UserRoleLogic constructor.
     * @param UserRoleRepository $userRoleRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(UserRoleRepository $userRoleRepository,RoleRepository $roleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findUserRole($id)
    {
        $userRole = $this->userRoleRepository->find($id);
        return $userRole;
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserRoles($userId)
    {
        $conditions['user_id'] = $userId;
        $userRoleList = $this->userRoleRepository->getBy($conditions);
        return $userRoleList;
    }

    /**
     * 根据用户ID查找角色ID列表
     *
     * @param $userID
     * @return array
     */
    public function getRoleIDsByUserID($userID)
    {
        $userRoles = $this->getUserRoles($userID);
        $roleIDs = array_column($userRoles,'role_id');
        return $roleIDs;
    }

    /**
     * 根据用户ID查找角色信息
     *
     * @param $userID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesByUser($userID)
    {
        $roleIDs = $this->getRoleIDsByUserID($userID);
        $roles = $this->roleRepository->get($roleIDs);
        return $roles;
    }

    /**
     * @param $userId
     * @param $roleIDs
     * @return int
     */
    public function addUserRoles($userId,$roleIDs)
    {
        return $this->userRoleRepository->addRoles($userId,$roleIDs);
    }

    /**
     * @param $userId
     * @param $roleIDs
     * @return int
     */
    public function deleteUserRoles($userId,$roleIDs)
    {
        return $this->userRoleRepository->deleteRoles(userId,$roleIDs);
    }

    public function setUserRoles($userID,$roleIDs)
    {
        /**
         * 已经分配用户的角色
         */
        $assignRoleIDs =$this->getRoleIDsByUserID($userID);

        $deleteResult = true;
        $addResult = true;

        /**
         * 找出删除的角色
         * 假如已有的角色集合是A，界面传递过得角色集合是B
         * 角色集合A当中的某个角色不在角色集合B当中，就应该删除
         * array_diff();计算补集
         */
        $deleteRoleIDs = array_diff($assignRoleIDs,$roleIDs);
        if($deleteRoleIDs)
        {
            $deleteResult = $this->userRoleRepository->deleteRoles($userID,$deleteRoleIDs);
        }

        /**
         * 找出添加的角色
         * 假如已有的角色集合是A，界面传递过得角色集合是B
         * 角色集合B当中的某个角色不在角色集合A当中，就应该添加
         */
        $newRoleIDs = array_diff($roleIDs,$assignRoleIDs);
        if($newRoleIDs)
        {
            $addResult = $this->userRoleRepository->addRoles($userID,$newRoleIDs);
        }

        return ($deleteResult && $addResult);
    }
}