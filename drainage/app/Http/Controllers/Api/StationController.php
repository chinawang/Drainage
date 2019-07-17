<?php

namespace App\Http\Controllers\Api;

use App\Http\Logic\Station\StationLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StationController extends Controller
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

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllStations()
    {
        $stationList = $this->stationLogic->getAllStations();

        foreach ($stationList as $station)
        {
            $stationNum = $station['station_number'];
            $stationRT = $this->getStationRTs($stationNum);

            $has2Pump = false;
            $has3Pump = false;
            $has4Pump = false;
            $has5Pump = false;

            $pump2List = ['18', '31', '34'];
            $pump3List = ['1', '2', '3', '4', '5', '6', '8', '9', '11', '12', '13', '16', '17', '20', '23', '24', '25', '26', '27', '28', '30', '32', '35', '37', '38'];
            $pump4List = ['7', '10', '14', '15', '19', '21', '22', '29', '36'];
            $pump5List = ['33'];

            if (in_array($stationNum, $pump2List)) {
                $has2Pump = true;
            } elseif (in_array($stationNum, $pump3List)) {
                $has3Pump = true;
            } elseif (in_array($stationNum, $pump4List)) {
                $has4Pump = true;
            } elseif (in_array($stationNum, $pump5List)) {
                $has5Pump = true;
            }

            $runCount = 0;
            $stopCount = 0;

            $station['culvertWater'] = 0;
            $station['tankWater'] = 0;

            if(count($stationRT)>0)
            {
                //1号泵
                if($stationRT[0]->yx_b1 == '1')
                {
                    $runCount ++;
                }
                else
                {
                    $stopCount ++;
                }

                //2号泵
                if($stationRT[0]->yx_b2 == '1')
                {
                    $runCount ++;
                }
                else
                {
                    $stopCount ++;
                }

                if ($has3Pump || $has4Pump || $has5Pump){
                    //3号泵
                    if($stationRT[0]->yx_b3 == '1')
                    {
                        $runCount ++;
                    }
                    else
                    {
                        $stopCount ++;
                    }
                }


                if ($has4Pump || $has5Pump){
                    //4号泵
                    if($stationRT[0]->yx_b4 == '1')
                    {
                        $runCount ++;
                    }
                    else
                    {
                        $stopCount ++;
                    }
                }


                //5号泵
                if($has5Pump)
                {
                    if($stationRT[0]->yx_b5 == '1')
                    {
                        $runCount ++;
                    }
                    else
                    {
                        $stopCount ++;
                    }
                }

                $station['culvertWater'] = $stationRT[0]->ywhandong;
                $station['tankWater'] = $stationRT[0]->ywjishui;
//                $station['Time'] = $stationRT[0]->Time;

            }


            $station['runPump'] = $runCount;
            $station['stopPump'] = $stopCount;
        }

        return response()->json([

            'code'=>0,

            'message'=> 'success',

            'data'  =>$stationList

        ],200);
    }

    /**
     * 查询泵站信息
     *
     * @param $stationID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function stationInfo($stationID)
    {
        $station = $this->stationLogic->findStation($stationID);

        if(!$station){
            return response()->json([

                'code'=>1001,

                'message'=> 'invalid resources'

            ],404);
        }

        $stationNum = $station['station_number'];
        $stationRT = $this->getStationRTs($stationNum);

        $has2Pump = false;
        $has3Pump = false;
        $has4Pump = false;
        $has5Pump = false;

        $pump2List = ['18', '31', '34'];
        $pump3List = ['1', '2', '3', '4', '5', '6', '8', '9', '11', '12', '13', '16', '17', '20', '23', '24', '25', '26', '27', '28', '30', '32', '35', '37', '38'];
        $pump4List = ['7', '10', '14', '15', '19', '21', '22', '29', '36'];
        $pump5List = ['33'];

        if (in_array($stationNum, $pump2List)) {
            $has2Pump = true;
        } elseif (in_array($stationNum, $pump3List)) {
            $has3Pump = true;
        } elseif (in_array($stationNum, $pump4List)) {
            $has4Pump = true;
        } elseif (in_array($stationNum, $pump5List)) {
            $has5Pump = true;
        }

        $runCount = 0;
        $stopCount = 0;

        $station['culvertWater'] = 0;
        $station['tankWater'] = 0;

        if(count($stationRT)>0) {
            //1号泵
            if ($stationRT[0]->yx_b1 == '1') {
                $runCount++;
            } else {
                $stopCount++;
            }

            //2号泵
            if ($stationRT[0]->yx_b2 == '1') {
                $runCount++;
            } else {
                $stopCount++;
            }

            if ($has3Pump || $has4Pump || $has5Pump) {
                //3号泵
                if ($stationRT[0]->yx_b3 == '1') {
                    $runCount++;
                } else {
                    $stopCount++;
                }
            }


            if ($has4Pump || $has5Pump) {
                //4号泵
                if ($stationRT[0]->yx_b4 == '1') {
                    $runCount++;
                } else {
                    $stopCount++;
                }
            }


            //5号泵
            if ($has5Pump) {
                if ($stationRT[0]->yx_b5 == '1') {
                    $runCount++;
                } else {
                    $stopCount++;
                }
            }

            $station['culvertWater'] = $stationRT[0]->ywhandong;
            $station['tankWater'] = $stationRT[0]->ywjishui;
//                $station['Time'] = $stationRT[0]->Time;
        }
        $station['runPump'] = $runCount;
        $station['stopPump'] = $stopCount;

        return response()->json([

            'code'=>0,

            'message'=> 'success',

            'data'  =>$station

        ],200);
    }


    public function getStationRTs($stationNum)
    {
        $nowTime = date("Y-m-d H:i:s",strtotime("-2 Minute"));

        $stationTable = "stationRT_".$stationNum;
        $stationRTs = DB::select('select * from '.$stationTable.' WHERE Time > ? order by Time desc limit 1', [$nowTime]);
//        $stationRTs = DB::select('SELECT * FROM  '.$stationTable.' WHERE Time = (select max(Time) AS maxTime from  '.$stationTable.' WHERE Time > ?)',[$nowTime]);
        return $stationRTs;
    }
}
