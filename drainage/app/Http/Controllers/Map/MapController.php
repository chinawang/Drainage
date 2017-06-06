<?php

namespace App\Http\Controllers\Map;

use App\Http\Logic\Station\StationLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    /**
     * @var StationLogic
     */
    protected $stationLogic;

    /**
     * MapController constructor.
     * @param StationLogic $stationLogic
     */
    public function __construct(StationLogic $stationLogic)
    {
        $this->middleware('auth');

        $this->stationLogic = $stationLogic;
    }

    public function showMap()
    {
        $stationList = $this->stationLogic->getAllStations();

        foreach ($stationList as $station)
        {
            $stationNum = $station['station_number'];
            $stationRT = $this->getStationRTs($stationNum);

            $runCount = 0;
            $stopCount = 0;

            if($stationRT[0]->yx_b1 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($stationRT[0]->yx_b2 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($stationRT[0]->yx_b3 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($stationRT[0]->yx_b4 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($runCount == 0)
            {
                $station['status'] = 'grey';
            }
            elseif ($stopCount == 0)
            {
                $station['status'] = 'red';
            }
            elseif ($stopCount == 1)
            {
                $station['status'] = 'yellow';
            }
            else
            {
                $station['status'] = 'green';
            }


            $station['runPump'] = $runCount;
            $station['stopPump'] = $stopCount;
            $station['culvertWater'] = $stationRT[0]->ywhandong;
            $station['tankWater'] = $stationRT[0]->ywjishui;
            $station['Time'] = $stationRT[0]->Time;
        }

        $param = ['stations' => $stationList];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站地图']);

        return view('map.map',$param);
    }

    public function showMapEmpty()
    {
        return view('map.map');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllStations()
    {
        $stationList = $this->stationLogic->getAllStations();
        return response()->json(array('stations'=> $stationList), 200);
    }

    public function getStationRTs($stationNum)
    {
        $stationTable = "stationRT_".$stationNum;
        $stationRTs = DB::select('select * from '.$stationTable.' order by Time desc limit 1');
        return $stationRTs;
    }

}
