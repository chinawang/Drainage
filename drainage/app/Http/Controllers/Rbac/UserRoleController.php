<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\UserRoleLogic;
use App\Http\Validations\Rbac\UserRoleValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    /**
     * @var UserRoleLogic
     */
    protected $userRoleLogic;

    /**
     * @var UserRoleValidation
     */
    protected $userRoleValidation;

    /**
     * UserRoleController constructor.
     * @param UserRoleLogic $userRoleLogic
     * @param UserRoleValidation $userRoleValidation
     */
    public function __construct(UserRoleLogic $userRoleLogic,UserRoleValidation $userRoleValidation)
    {
        $this->userRoleLogic = $userRoleLogic;
        $this->userRoleValidation = $userRoleValidation;
    }

}
