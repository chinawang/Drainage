<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 16:23
 */

namespace App\Repositories\User;

use App\User;
use Support\Repository\Repository;

class UserRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return User::class;
    }
}