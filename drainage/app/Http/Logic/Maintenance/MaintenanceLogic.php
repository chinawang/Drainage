<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:16
 */

namespace App\Http\Logic\Maintenance;


use App\Repositories\Maintenance\FailureRepository;
use App\Repositories\Maintenance\MaintenanceRepository;
use Support\Logic\Logic;

class MaintenanceLogic extends Logic
{
    /**
     * @var MaintenanceRepository
     */
    protected $maintenanceRepository;

    /**
     * @var FailureRepository
     */
    protected $failureRepository;

    /**
     * MaintenanceLogic constructor.
     * @param MaintenanceRepository $maintenanceRepository
     * @param FailureRepository $failureRepository
     */
    public function __construct(MaintenanceRepository $maintenanceRepository,FailureRepository $failureRepository)
    {
        $this->maintenanceRepository = $maintenanceRepository;
        $this->failureRepository = $failureRepository;
    }

    /**
     * @param $maintenanceId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findMaintenance($maintenanceId)
    {
        $conditions = [
            'delete_process' => 0,
            'id' => $maintenanceId
        ];
        $maintenance = $this->maintenanceRepository->findBy($conditions);
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMaintenance()
    {
        $conditions = [
            'delete_process' => 0
        ];
        $maintenanceList = $this->maintenanceRepository->getBy($conditions);
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
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createMaintenance($attributes)
    {
        $failure = ['id' =>$attributes['failure_id'],'repair_process' => $attributes['repair_process'],
            'repair_at' => $attributes['repair_at'],'repairer_id' => $attributes['repairer_id'],
            'repairer' => $attributes['repairer']];
        $maintenanceResult = $this->maintenanceRepository->create($attributes);
        $failureResult = $this->failureRepository->update($failure['id'],$failure);

        return $maintenanceResult && $failureResult;
    }

    /**
     * @param $maintenanceId
     * @param $input
     * @return bool
     */
    public function updateMaintenance($maintenanceId,$input)
    {
        $failure = ['id' =>$input['failure_id'],'repair_process' => $input['repair_process'],
            'repair_at' => $input['repair_at'],'repairer_id' => $input['repairer_id'],
            'repairer' => $input['repairer']];
        $maintenanceResult = $this->maintenanceRepository->update($maintenanceId,$input);
        $failureResult = $this->failureRepository->update($failure['id'],$failure);
        return $maintenanceResult && $failureResult;
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