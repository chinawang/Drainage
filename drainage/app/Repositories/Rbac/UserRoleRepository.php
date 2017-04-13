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
        return $this->model->where('user_id', $userID)->whereIn('role_id', $roleIDs)->delete();
    }

    /**
     * 分页获取列表
     *
     * @param mixed $conditions
     * @param string $orderColumn
     * @param string $orderDirection
     * @param int $cursor
     * @param int $size
     * @param array $with
     * @param array $fiel
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByPage($conditions, $orderColumn, $orderDirection, $cursor, $size, array $with = [], array $fields = ['*'])
    {
        return $this->model->where($conditions)
            ->orderBy($orderColumn, $orderDirection)
            ->skip($cursor)->take($size)->with($with)
            ->get($fields);
    }

    /**
     * 按条件获取分页数据
     *
     * @param $conditions
     * @param $size
     * @param $orderColumn
     * @param $orderDirection
     * @param int $cursorPage
     * @return mixed
     */
    public function getPaginate($conditions, $size, $orderColumn, $orderDirection, $cursorPage = null)
    {
        return $this->model->where($conditions)
            ->orderBy($orderColumn, $orderDirection)
            ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
    }
}