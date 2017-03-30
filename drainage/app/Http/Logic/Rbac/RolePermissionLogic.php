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
}