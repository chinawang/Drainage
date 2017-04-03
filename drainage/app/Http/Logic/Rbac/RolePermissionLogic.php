<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:14
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\RolePermissionRepository;
use Support\Logic\Logic;

class RolePermissionLogic extends Logic
{
    /**
     * @var RolePermissionRepository
     */
    protected $rolePermissionRepository;

    /**
     * RolePermissionLogic constructor.
     * @param RolePermissionRepository $rolePermissionRepository
     */
    public function __construct(RolePermissionRepository $rolePermissionRepository)
    {
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findRolePermission ($id)
    {
        $rolePermission = $this->rolePermissionRepository->find($id);
        return $rolePermission;
    }

    /**
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolePermissions($roleId)
    {
        $conditions['role_id'] = $roleId;
        $rolePermissionList = $this->rolePermissionRepository->getBy($conditions);
        return $rolePermissionList;
    }

    /**
     * @param $roleId
     * @param $permissionIDs
     * @return int
     */
    public function addRolePermission($roleId,$permissionIDs)
    {
        return $this->rolePermissionRepository->addPermissions($roleId,$permissionIDs);
    }

    /**
     * @param $roleId
     * @param $permissionIDs
     * @return int
     */
    public function deleteRolePermission($roleId,$permissionIDs)
    {
        return $this->rolePermissionRepository->deletePermissions($roleId,$permissionIDs);
    }
}