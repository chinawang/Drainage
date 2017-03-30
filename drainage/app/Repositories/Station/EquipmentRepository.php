<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 15:46
 */

namespace App\Repositories\Station;


use App\Models\Station\EquipmentModel;
use Support\Repository\Repository;

class EquipmentRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return EquipmentModel::class;
    }
}