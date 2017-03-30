<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 11:47
 */

namespace App\Repositories\Rbac;


use App\Models\Rbac\UserRoleModel;
use Support\Repository\Repository;

class UserRoleRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return UserRoleModel::class;
    }

    /**
     * 新增用户角色
     *
     * @param $userID
     * @param array $roleIDs
     * @return int
     */
    public function addRoles($userID, array $roleIDs)
    {
        $attributes = [];

        array_map(function($roleID) use ($userID, &$attributes) {
            array_push($attributes, [
                'user_id' => $userID,
                'role_id' => $roleID,
            ]);
        }, $roleIDs);

        return $this->insert($attributes);
    }

    /**
     * 删除用户角色
     *
     * @param $userID
     * @param array $roleIDs
     * @return int
     */
    public function deleteRoles($userID, array $roleIDs)
    {
        return $this->mode->where('user_id', $userID)->whereIn('role_id', $roleIDs)->delete();
    }
}