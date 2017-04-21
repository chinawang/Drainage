<?php

namespace App\Http\Controllers\Map;

use App\Http\Logic\Station\StationLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $stationList = $this->stationLogic->getAllStations();
        $param = ['stations' => $stationList];
        return view('map.map',$param);
    }

}
