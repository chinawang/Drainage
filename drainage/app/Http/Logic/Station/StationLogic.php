<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:10
 */

namespace App\Http\Logic\Station;


use App\Repositories\Station\StationRepository;
use Support\Logic\Logic;

class StationLogic extends Logic
{
    /**
     * @var StationRepository
     */
    protected $stationRepository;

    /**
     * StationLogic constructor.
     * @param StationRepository $stationRepository
     */
    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }

    /**
     * @param $stationId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findStation($stationId)
    {
        $station = $this->stationRepository->find($stationId);
        return $station;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getStations($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0];
        $stationList = $this->stationRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $stationList;
    }

    /**
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createStation($attributes)
    {
        return $this->stationRepository->create($attributes);
    }

    /**
     * @param $stationId
     * @param $input
     * @return bool
     */
    public function updateStation($stationId,$input)
    {
        return $this->stationRepository->update($stationId,$input);
    }

    /**
     * @param $stationId
     * @return bool
     */
    public function deleteStation($stationId)
    {
        return $this->stationRepository->deleteByFlag($stationId);
    }
}