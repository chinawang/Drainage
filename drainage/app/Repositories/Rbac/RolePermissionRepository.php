<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 11:41
 */

namespace App\Repositories\Rbac;


use App\Models\Rbac\RolePermissionModel;
use Support\Repository\Repository;

class RolePermissionRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return RolePermissionModel::class;
    }

    /**
     * 新增角色权限
     *
     * @param $roleID
     * @param array $permissionIDs
     * @return int
     */
    public function addPermissions($roleID, array $permissionIDs)
    {
        $attributes = [];

        array_map(function($permissionID) use ($roleID, &$attributes) {
            array_push($attributes, [
                'role_id' => $roleID,
                'permission_id' => $permissionID,
            ]);
        }, $permissionIDs);

        return $this->insert($attributes);
    }

    /**
     * 删除角色权限
     *
     * @param $roleID
     * @param array $permissionIDs
     * @return int
     */
    public function deletePermissions($roleID, array $permissionIDs)
    {
        return $this->model->where('role_id', $roleID)->whereIn('permission_id', $permissionIDs)->delete();
    }
}