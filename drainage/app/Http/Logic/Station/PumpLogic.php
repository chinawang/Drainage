<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/24
 * Time: 01:53
 */

namespace App\Http\Logic\Station;

use App\Repositories\Station\PumpRepository;
use Support\Logic\Logic;

class PumpLogic extends Logic
{
    /**
     * @var PumpRepository
     */
    protected $pumpRepository;

    /**
     * PumpLogic constructor.
     * @param PumpRepository $pumpRepository
     */
    public function __construct(PumpRepository $pumpRepository)
    {
        $this->$pumpRepository = $pumpRepository;
    }

    /**
     * @param $pumpId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findPump($pumpId)
    {
        $conditions = [
            'delete_process' => 0,
            'id' => $pumpId
        ];
        $pump = $this->pumpRepository->findBy($conditions);
        return $pump;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getPumps($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = [
            'delete_process' => 0
        ];
        $pumpList = $this->pumpRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        return $pumpList;
    }

    /**
     * @param $stationId
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getPumpsByStation($stationId,$pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = [
            'delete_process' => 0,
            'station_id' => $stationId
        ];
        $pumpList = $this->pumpRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        return $pumpList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPumps()
    {
        $conditions = [
            'delete_process' => 0
        ];
        $pumpList = $this->pumpRepository->getGroupBy($conditions);
        return $pumpList;
    }

    /**
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createPump($attributes)
    {
        //$attributes['station_id'] = $stationId;
        return $this->pumpRepository->create($attributes);
    }

    /**
     * @param $pumpId
     * @param $input
     * @return bool
     */
    public function updatePump($pumpId,$input)
    {
        return $this->pumpRepository->update($pumpId,$input);
    }

    /**
     * @param $pumpId
     * @return mixed
     */
    public function deleteEquipment($pumpId)
    {
        return $this->pumpRepository->deleteByFlag($pumpId);
    }
}