<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 11:24
 */

namespace App\Repositories\Rbac;

use Support\Repository\Repository;
use App\Models\Rbac\PermissionModel;

class PermissionRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return PermissionModel::class;
    }
}