<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:13
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\RoleRepository;
use Support\Logic\Logic;

class RoleLogic extends Logic
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * RoleLogic constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
}