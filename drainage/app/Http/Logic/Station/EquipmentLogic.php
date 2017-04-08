<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:11
 */

namespace App\Http\Logic\Station;


use App\Repositories\Station\EquipmentRepository;
use Support\Logic\Logic;

class EquipmentLogic extends Logic
{
    /**
     * @var EquipmentRepository
     */
    protected $equipmentRepository;

    /**
     * EquipmentLogic constructor.
     * @param EquipmentRepository $equipmentRepository
     */
    public function __construct(EquipmentRepository $equipmentRepository)
    {
        $this->equipmentRepository = $equipmentRepository;
    }

    /**
     * @param $equipmentId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findEquipment($equipmentId)
    {
        $equipment = $this->equipmentRepository->find($equipmentId);
        return $equipment;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getEquipments($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = [
            'delete_process' => 0
        ];
        $equipmentList = $this->equipmentRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        return $equipmentList;
    }

    /**
     * @param $stationId
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getEquipmentsByStation($stationId,$pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = [
            'delete_process' => 0,
            'station_id' => $stationId
        ];
        $equipmentList = $this->equipmentRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        return $equipmentList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllEquipments()
    {
        $equipmentList = $this->equipmentRepository->all();
        return $equipmentList;
    }

    /**
     * @param $stationId
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createEquipment($attributes)
    {
        //$attributes['station_id'] = $stationId;
        return $this->equipmentRepository->create($attributes);
    }

    /**
     * @param $equipmentId
     * @param $input
     * @return bool
     */
    public function updateEquipment($equipmentId,$input)
    {
        return $this->equipmentRepository->update($equipmentId,$input);
    }

    /**
     * @param $equipmentId
     * @return mixed
     */
    public function deleteEquipment($equipmentId)
    {
        return $this->equipmentRepository->deleteByFlag($equipmentId);
    }

}