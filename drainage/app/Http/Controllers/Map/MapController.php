<?php

namespace App\Http\Controllers\Map;

use App\Http\Logic\Station\StationLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
        $this->stationLogic = $stationLogic;
    }

    public function showMap()
    {
        return $this->getStationRTs();
        $stationList = $this->stationLogic->getAllStations();
        $param = ['stations' => $stationList];
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

    public function getStationRTs()
    {
        $stationRTs = DB::select('select * from stationRT_1 order by Time desc limit 1');
        return $stationRTs;
    }

}
