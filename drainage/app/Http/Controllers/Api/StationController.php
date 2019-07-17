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

        if (!$stationList) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid resources'

            ], 404);
        }

        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $stationList

        ], 200);
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

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid resources'

            ], 404);
        }

        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $station

        ], 200);
    }

    /**
     * 实时运行信息
     */
    public function getRealTimeWorking($stationID)
    {
        $station = $this->stationLogic->findStation($stationID);

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid resources'

            ], 404);
        }

        $stationNum = $station['station_number'];

        $realTimeData['id'] = $station['id'];
        $realTimeData['name'] = $station['name'];
        $realTimeData['type'] = $station['type'];

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
        $culvertWater = 0;
        $tankWater = 0;
        $uab = 0;
        $ubc = 0;
        $uca = 0;

        //1号泵
        if (count($stationRT) > 0 && $stationRT[0]->yx_b1 == '1') {
            $realTimeData['pump1Status'] = 1;
            $realTimeData['iPump1'] = $stationRT[0]->ib1;
            $runCount++;
        } else {
            $realTimeData['pump1Status'] = 0;
            $realTimeData['iPump1'] = 0;
            $stopCount++;
        }

        //2号泵
        if (count($stationRT) > 0 && $stationRT[0]->yx_b2 == '1') {
            $realTimeData['pump2Status'] = 1;
            $realTimeData['iPump2'] = $stationRT[0]->ib2;
            $runCount++;
        } else {
            $realTimeData['pump2Status'] = 0;
            $realTimeData['iPump2'] = 0;
            $stopCount++;
        }

        if ($has3Pump || $has4Pump || $has5Pump) {
            //3号泵
            if (count($stationRT) > 0 && $stationRT[0]->yx_b3 == '1') {
                $realTimeData['pump3Status'] = 1;
                $realTimeData['iPump3'] = $stationRT[0]->ib3;
                $runCount++;
            } else {
                $realTimeData['pump3Status'] = 0;
                $realTimeData['iPump3'] = 0;
                $stopCount++;
            }
        }


        if ($has4Pump || $has5Pump) {
            //4号泵
            if (count($stationRT) > 0 && $stationRT[0]->yx_b4 == '1') {
                $realTimeData['pump4Status'] = 1;
                $realTimeData['iPump4'] = $stationRT[0]->ib4;
                $runCount++;
            } else {
                $realTimeData['pump4Status'] = 0;
                $realTimeData['iPump4'] = 0;
                $stopCount++;
            }
        }


        //5号泵
        if ($has5Pump) {
            if (count($stationRT) > 0 && $stationRT[0]->yx_b5 == '1') {
                $realTimeData['pump5Status'] = 1;
                $realTimeData['iPump5'] = $stationRT[0]->ib5;
                $runCount++;
            } else {
                $realTimeData['pump5Status'] = 0;
                $realTimeData['iPump5'] = 0;
                $stopCount++;
            }
        }

        if (count($stationRT) > 0) {
            $culvertWater = $stationRT[0]->ywhandong;
            $tankWater = $stationRT[0]->ywjishui;
            $uab = $stationRT[0]->uab;
            $ubc = $stationRT[0]->ubc;
            $uca = $stationRT[0]->uca;
        }

        $realTimeData['runPumps'] = $runCount;
        $realTimeData['stopPumps'] = $stopCount;
        $realTimeData['uab'] = $uab;
        $realTimeData['ubc'] = $ubc;
        $realTimeData['uca'] = $uca;

        if($station['type'] == '雨水')
        {
            $realTimeData['culvertWater'] = $culvertWater;
        }
        if($station['type'] == '污水')
        {
            $realTimeData['tankWater'] = $tankWater;
        }


        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $realTimeData

        ], 200);
    }

    /**
     * 实时报警信息
     */
    public function getRealTimeAlarm($stationID)
    {
        $station = $this->stationLogic->findStation($stationID);

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid resources'

            ], 404);
        }

        $stationNum = $station['station_number'];

        $realTimeData['id'] = $station['id'];
        $realTimeData['name'] = $station['name'];
        $realTimeData['type'] = $station['type'];

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

        if(count($stationRT)>0)
        {
            //1号泵
            $realTimeData['pump1Alarm'] = $stationRT[0]->bj_b1;
            $realTimeData['pump1RQAlarm'] = $stationRT[0]->rqbj_b1;
            //2号泵
            $realTimeData['pump2Alarm'] = $stationRT[0]->bj_b2;
            $realTimeData['pump2RQAlarm'] = $stationRT[0]->rqbj_b2;

            if ($has3Pump || $has4Pump || $has5Pump) {
                //3号泵
                $realTimeData['pump3Alarm'] = $stationRT[0]->bj_b3;
                $realTimeData['pump3RQAlarm'] = $stationRT[0]->rqbj_b3;
            }

            if ($has4Pump || $has5Pump) {
                //4号泵
                $realTimeData['pump4Alarm'] = $stationRT[0]->bj_b4;
                $realTimeData['pump4RQAlarm'] = $stationRT[0]->rqbj_b4;
            }

            //5号泵
            if ($has5Pump) {
                $realTimeData['pump5Alarm'] = $stationRT[0]->bj_b5;
                $realTimeData['pump5RQAlarm'] = $stationRT[0]->rqbj_b5;
            }

            $realTimeData['augerAlarm'] = $stationRT[0]->bj_jl;
            $realTimeData['cleaner1Alarm'] = $stationRT[0]->bj_gs1;
            $realTimeData['cleaner2Alarm'] = $stationRT[0]->bj_gs2;

            //部分泵站通讯中断,没有数据,不做市电的报警
            $stationNoWorking = ['11','12','20','21','31','33','34','36'];
            if(in_array($stationNum, $stationNoWorking))
            {
                $realTimeData['powerAlarm'] = 0;//市电停电报警
            }else{
                $realTimeData['powerAlarm'] = $stationRT[0]->water_v ^ 1;//市电停电报警
            }
            $realTimeData['emergencyAlarm'] = $stationRT[0]->flow_v;//手动急停报警

        }


        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $realTimeData

        ], 200);
    }


    public function getStationRTs($stationNum)
    {
        $nowTime = date("Y-m-d H:i:s", strtotime("-2 Minute"));

        $stationTable = "stationRT_" . $stationNum;
        $stationRTs = DB::select('select * from ' . $stationTable . ' WHERE Time > ? order by Time desc limit 1', [$nowTime]);
//        $stationRTs = DB::select('SELECT * FROM  '.$stationTable.' WHERE Time = (select max(Time) AS maxTime from  '.$stationTable.' WHERE Time > ?)',[$nowTime]);
        return $stationRTs;
    }


}
