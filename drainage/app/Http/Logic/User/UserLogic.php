<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 19:57
 */

namespace App\Http\Logic\User;


use App\Repositories\User\UserRepository;
use Support\Logic\Logic;

class UserLogic extends Logic
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserLogic constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}