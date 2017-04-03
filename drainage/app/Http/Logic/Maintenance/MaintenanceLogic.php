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

    /**
     * @param $maintenanceId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findMaintenance($maintenanceId)
    {
        $maintenance = $this->maintenanceRepository->find($maintenanceId);
        return $maintenance;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getMaintenances($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0];
        $maintenanceList = $this->maintenanceRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $maintenanceList;
    }

    /**
     * @param $stationId
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getMaintenancesByStation($stationId,$pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0,'station_id' => $stationId];
        $maintenanceList = $this->maintenanceRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $maintenanceList;
    }

    /**
     * @param $failureId
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getMaintenancesByFailure($failureId,$pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0,'failure_id' => $failureId];
        $maintenanceList = $this->maintenanceRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $maintenanceList;
    }

    /**
     * @param $maintenanceId
     * @param $input
     * @return bool
     */
    public function updateMaintenance($maintenanceId,$input)
    {
        return $this->maintenanceRepository->update($maintenanceId,$input);
    }

    /**
     * @param $maintenanceId
     * @return mixed
     */
    public function deleteMaintenance($maintenanceId)
    {
        return $this->maintenanceRepository->deleteByFlag($maintenanceId);
    }

}