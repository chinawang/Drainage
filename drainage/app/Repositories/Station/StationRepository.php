<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 14:37
 */

namespace App\Repositories\Station;


use App\Models\Station\StationModel;
use Support\Repository\Repository;

class StationRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return StationModel::class;
    }
}