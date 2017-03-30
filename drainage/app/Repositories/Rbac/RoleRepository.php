<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 11:37
 */

namespace App\Repositories\Rbac;


use App\Models\Rbac\RoleModel;
use Support\Repository\Repository;

class RoleRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return RoleModel::class;
    }
}