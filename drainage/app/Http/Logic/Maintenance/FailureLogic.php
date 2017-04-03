<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:16
 */

namespace App\Http\Logic\Maintenance;


use App\Repositories\Maintenance\FailureRepository;
use Support\Logic\Logic;

class FailureLogic extends Logic
{
    /**
     * @var FailureRepository
     */
    protected $failureRepository;

    /**
     * FailureLogic constructor.
     * @param FailureRepository $failureRepository
     */
    public function __construct(FailureRepository $failureRepository)
    {
        $this->failureRepository = $failureRepository;
    }

    /**
     * @param $failureId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findFailure($failureId)
    {
        $failure = $this->failureRepository->find($failureId);
        return $failure;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getFailures($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0];
        $failureList = $this->failureRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $failureList;
    }

    /**
     * @param $stationId
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getFailuresByStation($stationId,$pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0,'station_id' => $stationId];
        $failureList = $this->failureRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $failureList;
    }

    /**
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createFailure($attributes)
    {
        return $this->failureRepository->create($attributes);
    }

    public function updateFailure($failureId,$input)
    {
        return $this->failureRepository->update($failureId,$input);
    }

    /**
     * @param $failureId
     * @return mixed
     */
    public function deleteFailure($failureId)
    {
        return $this->failureRepository->deleteByFlag($failureId);
    }
}