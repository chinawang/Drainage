<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:16
 */

namespace App\Http\Logic\Maintenance;


use App\Repositories\Maintenance\MaintenanceRepository;
use Support\Logic\Logic;

class MaintenanceLogic extends Logic
{
    /**
     * @var MaintenanceRepository
     */
    protected $maintenanceRepository;

    /**
     * MaintenanceLogic constructor.
     * @param MaintenanceRepository $maintenanceRepository
     */
    public function __construct(MaintenanceRepository $maintenanceRepository)
    {
        $this->maintenanceRepository = $maintenanceRepository;
    }
}