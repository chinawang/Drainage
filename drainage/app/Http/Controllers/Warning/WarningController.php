<?php

namespace App\Http\Controllers\Warning;

use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Station\StationValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WarningController extends Controller
{
    /**
     * @var StationLogic
     */
    protected $stationLogic;

    /**
     * @var StationValidation
     */
    protected $stationValidation;


    /**
     * WarningController constructor.
     * @param StationLogic $stationLogic
     * @param StationValidation $stationValidation
     */
    public function __construct(StationLogic $stationLogic, StationValidation $stationValidation)
    {
        $this->middleware('auth');

        $this->stationLogic = $stationLogic;
        $this->stationValidation = $stationValidation;
    }

    public function warningList()
    {
        $input = $this->stationValidation->stationPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $stationPaginate = $this->stationLogic->getStations($pageSize,$orderColumn,$orderDirection,$cursorPage);
        foreach ($stationPaginate as $station)
        {
            $stationNum = $station['station_number'];
            $stationRT = $this->findStationRT($stationNum);

            $station['alarmPump1'] = $stationRT[0]->bj_b1;
            $station['alarmPump2'] = $stationRT[0]->bj_b2;
            $station['alarmPump3'] = $stationRT[0]->bj_b3;
            $station['alarmPump4'] = $stationRT[0]->bj_b4;

            $station['alarmAuger'] = $stationRT[0]->bj_jl;
            $station['alarmCleaner1'] = $stationRT[0]->bj_gs1;
            $station['alarmCleaner2'] = $stationRT[0]->bj_gs2;
            $station['Time'] = $stationRT[0]->Time;
        }
        $param = ['stations' => $stationPaginate];
        return view('warning.warningList',$param);
    }

    /**
     * @param $stationNum
     * @return mixed
     */
    public function findStationRT($stationNum)
    {
        $stationTable = "stationRT_".$stationNum;
        $stationRT = DB::select('select * from '.$stationTable.' order by Time desc limit 120');
        return $stationRT;
    }

    public function stationRT($stationNum)
    {
        $stationRT = $this->findStationRT($stationNum);
        return response()->json(array('stationRT'=> $stationRT[0]), 200);
    }

    public function stationRTHistory($stationNum)
    {
        $stationRT = $this->findStationRT($stationNum);

        return response()->json(array('stationRTHistory'=> $stationRT), 200);
    }
}
