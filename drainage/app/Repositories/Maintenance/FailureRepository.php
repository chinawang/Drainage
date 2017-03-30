<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 15:49
 */

namespace App\Repositories\Maintenance;


use App\Models\Maintenance\FailureModel;
use Support\Repository\Repository;

class FailureRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return FailureModel::class;
    }
}