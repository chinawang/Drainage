<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:13
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\PermissionRepository;
use Support\Logic\Logic;

class PermissionLogic extends Logic
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * PermissionLogic constructor.
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }
}