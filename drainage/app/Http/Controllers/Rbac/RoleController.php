<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Validations\Rbac\RoleValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * @var RoleValidation
     */
    protected $roleValidation;

    /**
     * RoleController constructor.
     * @param RoleLogic $roleLogic
     * @param RoleValidation $roleValidation
     */
    public function __construct(RoleLogic $roleLogic,RoleValidation $roleValidation)
    {
        $this->roleLogic = $roleLogic;
        $this->roleValidation = $roleValidation;
    }
}
