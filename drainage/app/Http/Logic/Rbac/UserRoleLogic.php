<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:15
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\UserRoleRepository;
use Support\Logic\Logic;

class UserRoleLogic extends Logic
{
    /**
     * @var UserRoleRepository
     */
    protected $userRoleRepository;

    /**
     * UserRoleLogic constructor.
     * @param UserRoleRepository $userRoleRepository
     */
    public function __construct(UserRoleRepository $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
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
}