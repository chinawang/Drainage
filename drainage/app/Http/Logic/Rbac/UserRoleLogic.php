<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:15
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\UserRoleRepository;
use Support\Logic\Logic;

class UserRoleLogic extends Logic
{
    /**
     * @var UserRoleRepository
     */
    protected $userRoleRepository;

    /**
     * UserRoleLogic constructor.
     * @param UserRoleRepository $userRoleRepository
     */
    public function __construct(UserRoleRepository $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
    }
}