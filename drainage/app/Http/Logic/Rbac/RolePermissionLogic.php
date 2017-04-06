<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:14
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\PermissionRepository;
use App\Repositories\Rbac\RolePermissionRepository;
use Support\Logic\Logic;

class RolePermissionLogic extends Logic
{
    /**
     * @var RolePermissionRepository
     */
    protected $rolePermissionRepository;

    /**
     * @var
     */
    protected $permissionRepository;

    /**
     * RolePermissionLogic constructor.
     * @param RolePermissionRepository $rolePermissionRepository
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(RolePermissionRepository $rolePermissionRepository,PermissionRepository $permissionRepository)
    {
        $this->rolePermissionRepository = $rolePermissionRepository;
        $this->permissionRepository = $permissionRepository;
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
     * 根据角色ID查找已分配的权限ID列表
     *
     * @param $roleID
     * @return array
     */
    public function getPermissionIDsByRoleID($roleID)
    {
        $rolePermissions = $this->getRolePermissions($roleID);
        $permissionIDs = array_column($rolePermissions,'permission_id');
        return $permissionIDs;
    }

    /**
     * 根据角色ID查找已分配的权限信息列表
     *
     * @param $roleID
     * @return \Illuminate\Database\Eloquent\Collection
     *
     */
    public function getPermissionsByRole($roleID)
    {
        $permissionIDs = $this->getPermissionIDsByRoleID($roleID);
        $permissions = $this->permissionRepository->get($permissionIDs);
        return $permissions;
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

    /**
     * 设置角色的权限
     *
     * @param $roleID
     * @param $permissionIDs
     * @return bool
     */
    public function setRolePermissions($roleID,$permissionIDs)
    {
        /**
         * 已分配给角色的权限
         */
        $assignPermissionIDs = $this->getPermissionIDsByRoleID($roleID);

        /**
         * 找出删除的权限
         * 假如已有的权限集合是A，界面传递过得权限集合是B
         * 权限集合A当中的某个权限不在权限集合B当中，就应该删除
         * 使用 array_diff() 计算补集
         */
        $deletePermissionsIDs = array_diff($assignPermissionIDs,$permissionIDs);
        if($deletePermissionsIDs)
        {
            $this->deleteRolePermission($roleID,$deletePermissionsIDs);
        }

        /**
         * 找出添加的权限
         * 假如已有的权限集合是A，界面传递过得权限集合是B
         * 权限集合B当中的某个权限不在权限集合A当中，就应该添加
         * 使用 array_diff() 计算补集
         */
        $newPermissionIDs = array_diff($permissionIDs,$assignPermissionIDs);
        if($newPermissionIDs)
        {
            $this->addRolePermission($roleID,$newPermissionIDs);
        }

        return true;
    }
}