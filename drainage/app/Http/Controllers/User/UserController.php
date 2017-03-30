<?php

namespace App\Http\Controllers\User;

use App\Http\Logic\User\UserLogic;
use App\Http\Validations\User\UserValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * @var UserValidation
     */
    protected $userValidation;


    /**
     * UserController constructor.
     * @param UserLogic $userLogic
     * @param UserValidation $userValidation
     */
    public function __construct(UserLogic $userLogic,UserValidation $userValidation)
    {
        $this->userLogic = $userLogic;
        $this->userValidation = $userValidation;
    }
}
