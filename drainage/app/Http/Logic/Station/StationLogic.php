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
}