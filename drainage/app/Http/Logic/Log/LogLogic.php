<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/6/4
 * Time: 13:43
 */

namespace App\Http\Logic\Log;


use App\Repositories\Log\LogRepository;
use Support\Logic\Logic;

class LogLogic extends Logic
{
    /**
     * @var $logRepository
     */
    protected $logRepository;

    /**
     * LogLogic constructor.
     * @param LogRepository $logRepository
     */
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getLogs($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = [];
        $logList = $this->logRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $logList;
    }

    /**
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createLog($attributes)
    {
        return $this->logRepository->create($attributes);
    }

}