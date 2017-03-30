<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 15:51
 */

namespace App\Repositories\Maintenance;


use App\Models\Maintenance\MaintenanceModel;
use Support\Repository\Repository;

class MaintenanceRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return MaintenanceModel::class;
    }
}