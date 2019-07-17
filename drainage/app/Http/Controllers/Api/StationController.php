<?php

namespace App\Http\Controllers\Api;

use App\Http\Logic\Station\StationLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Reporte\StatusReportController;

class StationController extends Controller
{

    /**
     * @var StationLogic
     */
    protected $stationLogic;

    protected $reportController;

    /**
     * StationController constructor.
     * @param StationLogic $stationLogic
     * @param StatusReportController $reportController
     */
    public function __construct(StationLogic $stationLogic,StatusReportController $reportController)
    {
        $this->stationLogic = $stationLogic;
        $this->reportController = $reportController;
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

                'message' => 'invalid station resources'

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
    public function stationInfo()
    {
        $stationID = Route::input('stationID',1);

        if (!$stationID) {
            return response()->json([

                'code' => 1002,

                'message' => 'missing parameters'

            ], 400);
        }

        $station = $this->stationLogic->findStation($stationID);

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid station resources'

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
    public function getRealTimeWorking()
    {
        $stationID = Route::input('stationID',1);

        if (!$stationID) {
            return response()->json([

                'code' => 1002,

                'message' => 'missing parameters'

            ], 400);
        }

        $station = $this->stationLogic->findStation($stationID);

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid station resources'

            ], 404);
        }

        $stationNum = $station['station_number'];

        $realTimeData['id'] = $station['id'];
        $realTimeData['name'] = $station['name'];
        $realTimeData['type'] = $station['type'];

        $stationRT = $this->getStationRTs($stationNum);

        if (!$stationRT) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid working resources'

            ], 404);
        }

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
    public function getRealTimeAlarm()
    {
        $stationID = Route::input('stationID',1);

        if (!$stationID) {
            return response()->json([

                'code' => 1002,

                'message' => 'missing parameters'

            ], 400);
        }

        $station = $this->stationLogic->findStation($stationID);

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid station resources'

            ], 404);
        }

        $stationNum = $station['station_number'];

        $realTimeData['id'] = $station['id'];
        $realTimeData['name'] = $station['name'];
        $realTimeData['type'] = $station['type'];

        $stationRT = $this->getStationRTs($stationNum);

        if (!$stationRT) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid alarm resources'

            ], 404);
        }

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
            $stationNoWorking = ['11','12','20','34'];
            if(in_array($stationNum, $stationNoWorking))
            {
                $realTimeData['powerAlarm'] = 0;//市电停电报警
            }else{
                $realTimeData['powerAlarm'] = $stationRT[0]->water_v ^ 1;//市电停电报警
            }
            $realTimeData['emergencyAlarm'] = $stationRT[0]->flow_v;//手动急停报警

        }else{
            //1号泵
            $realTimeData['pump1Alarm'] = 0;
            $realTimeData['pump1RQAlarm'] = 0;
            //2号泵
            $realTimeData['pump2Alarm'] = 0;
            $realTimeData['pump2RQAlarm'] = 0;

            if ($has3Pump || $has4Pump || $has5Pump) {
                //3号泵
                $realTimeData['pump3Alarm'] = 0;
                $realTimeData['pump3RQAlarm'] = 0;
            }

            if ($has4Pump || $has5Pump) {
                //4号泵
                $realTimeData['pump4Alarm'] = 0;
                $realTimeData['pump4RQAlarm'] = 0;
            }

            //5号泵
            if ($has5Pump) {
                $realTimeData['pump5Alarm'] = 0;
                $realTimeData['pump5RQAlarm'] = 0;
            }

            $realTimeData['augerAlarm'] = 0;
            $realTimeData['cleaner1Alarm'] = 0;
            $realTimeData['cleaner2Alarm'] = 0;

            $realTimeData['powerAlarm'] = 0;//市电停电报警
            $realTimeData['emergencyAlarm'] = 0;//手动急停报警
        }


        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $realTimeData

        ], 200);
    }

    /**
     * 运行统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportWorking()
    {
        $stationID = Route::input('stationID',1);
        $startTime = Input::get('startDate',date("Y-m-d"));
        $endTime = Input::get('endDate',date("Y-m-d"));

        $date=floor((strtotime($startTime)-strtotime($endTime))/86400);

        if($date > 30 || strtotime($startTime) > strtotime($endTime))
        {
            return response()->json([

                'code' => 1003,

                'message' => 'wrong parameters'

            ], 400);
        }

        if (!$stationID || !$startTime || !$endTime) {
            return response()->json([

                'code' => 1002,

                'message' => 'missing parameters'

            ], 400);
        }

        $station = $this->stationLogic->findStation($stationID);
        $stationNum = $station['station_number'];

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid station resources'

            ], 404);
        }

        $totalType = '本年';

        if($totalType == '本年')
        {
            $thisYear = date("Y",strtotime($startTime));
            $beforeTime = date($thisYear."-01-01");

        }else{
            //连前累计
            $beforeTime = date("2017-10-01");
        }

        if($startTime > date('2019-01-14 00:00:00'))
        {
            $endTime = date('Y-m-d 23:59:59', strtotime($endTime));

            $beforeTime = date("2019-01-14 00:00:00");

            $statusReportDay = $this->reportController->getStatusReportV4($stationID, $startTime, $endTime);

            $statusReportBefore = $this->reportController->getStatusReportV4($stationID, $beforeTime, $endTime);
        }
        else
        {
            $statusReportDay = $this->reportController->getStatusReportV3($stationID, $startTime, $endTime);

            $statusReportBefore = $this->reportController->getStatusReportV3($stationID, $beforeTime, $endTime);
        }

        if (!$statusReportDay || !$statusReportBefore) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid report resources'

            ], 404);
        }

        $reportData['id'] = $station['id'];
        $reportData['name'] = $station['name'];
        $reportData['type'] = $station['type'];
        $reportData['startDate'] = date('Y-m-d', strtotime($startTime));
        $reportData['endDate'] = date('Y-m-d', strtotime($endTime));

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

        //1号泵
        $reportData['pump1StatusList'] = $statusReportDay['stationStatusList1'];//运行记录
        $reportData['pump1SumTime'] = $statusReportDay['totalTimeDay1'];//运行时间
        $reportData['pump1SumFlux'] = $statusReportDay['totalFluxDay1'];//流量
        $reportData['pump1SumTimeBefore'] = round(($statusReportBefore['totalTimeDay1']) / 60, 2);//连前累计运行时间
        $reportData['pump1SumFluxBefore'] = $statusReportBefore['totalFluxDay1'];//连前累计流量
        //2号泵
        $reportData['pump2StatusList'] = $statusReportDay['stationStatusList2'];//运行记录
        $reportData['pump2SumTime'] = $statusReportDay['totalTimeDay2'];//运行时间
        $reportData['pump2SumFlux'] = $statusReportDay['totalFluxDay2'];//流量
        $reportData['pump2SumTimeBefore'] = round(($statusReportBefore['totalTimeDay2']) / 60, 2);//连前累计运行时间
        $reportData['pump2SumFluxBefore'] = $statusReportBefore['totalFluxDay2'];//连前累计流量

        if ($has3Pump || $has4Pump || $has5Pump) {
            //3号泵
            $reportData['pump3StatusList'] = $statusReportDay['stationStatusList3'];//运行记录
            $reportData['pump3SumTime'] = $statusReportDay['totalTimeDay3'];//运行时间
            $reportData['pump3SumFlux'] = $statusReportDay['totalFluxDay3'];//流量
            $reportData['pump3SumTimeBefore'] = round(($statusReportBefore['totalTimeDay3']) / 60, 2);//连前累计运行时间
            $reportData['pump3SumFluxBefore'] = $statusReportBefore['totalFluxDay3'];//连前累计流量
        }

        if ($has4Pump || $has5Pump) {
            //4号泵
            $reportData['pump4StatusList'] = $statusReportDay['stationStatusList4'];//运行记录
            $reportData['pump4SumTime'] = $statusReportDay['totalTimeDay4'];//运行时间
            $reportData['pump4SumFlux'] = $statusReportDay['totalFluxDay4'];//流量
            $reportData['pump4SumTimeBefore'] = round(($statusReportBefore['totalTimeDay4']) / 60, 2);//连前累计运行时间
            $reportData['pump4SumFluxBefore'] = $statusReportBefore['totalFluxDay4'];//连前累计流量
        }

        //5号泵
        if ($has5Pump) {
            $reportData['pump5StatusList'] = $statusReportDay['stationStatusList5'];//运行记录
            $reportData['pump5SumTime'] = $statusReportDay['totalTimeDay5'];//运行时间
            $reportData['pump5SumFlux'] = $statusReportDay['totalFluxDay5'];//流量
            $reportData['pump5SumTimeBefore'] = round(($statusReportBefore['totalTimeDay5']) / 60, 2);//连前累计运行时间
            $reportData['pump5SumFluxBefore'] = $statusReportBefore['totalFluxDay5'];//连前累计流量
        }

        $reportData['totalTime'] = $statusReportDay['totalTimeDay'];//总计运行时间
        $reportData['totalFlux'] = $statusReportDay['totalFluxDay'];//总计流量
        $reportData['totalTimeBefore'] = round(($statusReportBefore['totalTimeDay']) / 60, 2);//总计运行时间
        $reportData['totalFluxBefore'] = $statusReportBefore['totalFluxDay'];//总计流量

//        $param = [
//            'stationStatusList1' => $statusReportDay['stationStatusList1'],
//            'stationStatusList2' => $statusReportDay['stationStatusList2'],
//            'stationStatusList3' => $statusReportDay['stationStatusList3'],
//            'stationStatusList4' => $statusReportDay['stationStatusList4'],
//            'stationStatusList5' => $statusReportDay['stationStatusList5'],
//            'totalTimeDay1' => $statusReportDay['totalTimeDay1'],
//            'totalTimeDay2' => $statusReportDay['totalTimeDay2'],
//            'totalTimeDay3' => $statusReportDay['totalTimeDay3'],
//            'totalTimeDay4' => $statusReportDay['totalTimeDay4'],
//            'totalTimeDay5' => $statusReportDay['totalTimeDay5'],
//            'totalFluxDay1' => $statusReportDay['totalFluxDay1'],
//            'totalFluxDay2' => $statusReportDay['totalFluxDay2'],
//            'totalFluxDay3' => $statusReportDay['totalFluxDay3'],
//            'totalFluxDay4' => $statusReportDay['totalFluxDay4'],
//            'totalFluxDay5' => $statusReportDay['totalFluxDay5'],
//            'totalTimeDay' => $statusReportDay['totalTimeDay'],
//            'totalFluxDay' => $statusReportDay['totalFluxDay'],
//            'totalTimeBefore1' => round(($statusReportBefore['totalTimeDay1']) / 60, 2),
//            'totalTimeBefore2' => round(($statusReportBefore['totalTimeDay2']) / 60, 2),
//            'totalTimeBefore3' => round(($statusReportBefore['totalTimeDay3']) / 60, 2),
//            'totalTimeBefore4' => round(($statusReportBefore['totalTimeDay4']) / 60, 2),
//            'totalTimeBefore5' => round(($statusReportBefore['totalTimeDay5']) / 60, 2),
//            'totalFluxBefore1' => $statusReportBefore['totalFluxDay1'],
//            'totalFluxBefore2' => $statusReportBefore['totalFluxDay2'],
//            'totalFluxBefore3' => $statusReportBefore['totalFluxDay3'],
//            'totalFluxBefore4' => $statusReportBefore['totalFluxDay4'],
//            'totalFluxBefore5' => $statusReportBefore['totalFluxDay5'],
//            'totalTimeBefore' => round(($statusReportBefore['totalTimeDay']) / 60, 2),
//            'totalFluxBefore' => $statusReportBefore['totalFluxDay'],
//        ];



        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $reportData

        ], 200);

    }

    /**
     * 水位统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportWaterLevel()
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        $stationID = Route::input('stationID',1);
        $startTime = Input::get('startDate',date("Y-m-d"));
        $endTime = Input::get('endDate',date("Y-m-d"));

        $date=floor((strtotime($startTime)-strtotime($endTime))/86400);

        if($date > 30 || strtotime($startTime) > strtotime($endTime))
        {
            return response()->json([

                'code' => 1003,

                'message' => 'wrong parameters'

            ], 400);
        }

        if (!$stationID || !$startTime || !$endTime) {
            return response()->json([

                'code' => 1002,

                'message' => 'missing parameters'

            ], 400);
        }

        $station = $this->stationLogic->findStation($stationID);
        $stationNum = $station['station_number'];

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid station resources'

            ], 404);
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $statusYList = $this->reportController->getStatusYList($stationNum, $searchStartTime, $searchEndTime);

        if (!$statusYList) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid water resources'

            ], 404);
        }

        $reportData['id'] = $station['id'];
        $reportData['name'] = $station['name'];
        $reportData['type'] = $station['type'];
        $reportData['startDate'] = date('Y-m-d', strtotime($startTime));
        $reportData['endDate'] = date('Y-m-d', strtotime($endTime));


        if($station['type'] == '雨水')
        {
            $reportData['culvertWater'] = [];
            // 遍历实时运行数据表,找出起泵时刻与停泵时刻
            for ($i = 0; $i < count($statusYList); $i++)
            {
                $water['time'] = $statusYList[$i]->Time;
                $water['waterLevel'] = $statusYList[$i]->ywhandong;
                array_push($reportData['culvertWater'], $water);
            }
        }

        if($station['type'] == '污水')
        {
            $reportData['tankWater'] = [];
            // 遍历实时运行数据表,找出起泵时刻与停泵时刻
            for ($i = 0; $i < count($statusYList); $i++)
            {
                $water['time'] = $statusYList[$i]->Time;
                $water['waterLevel'] = $statusYList[$i]->ywjishui;
                array_push($reportData['tankWater'], $water);
            }
        }

        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $reportData

        ], 200);
    }

    /**
     * 报警统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportAlarm()
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        $stationID = Route::input('stationID',1);
        $startTime = Input::get('startDate',date("Y-m-d"));
        $endTime = Input::get('endDate',date("Y-m-d"));

        $date=floor((strtotime($startTime)-strtotime($endTime))/86400);

        if($date > 30 || strtotime($startTime) > strtotime($endTime))
        {
            return response()->json([

                'code' => 1003,

                'message' => 'wrong parameters'

            ], 400);
        }

        if (!$stationID || !$startTime || !$endTime) {
            return response()->json([

                'code' => 1002,

                'message' => 'missing parameters'

            ], 400);
        }

        $station = $this->stationLogic->findStation($stationID);
        $stationNum = $station['station_number'];

        if (!$station) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid station resources'

            ], 404);
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $statusYList = $this->reportController->getStatusYList($stationNum, $searchStartTime, $searchEndTime);

        if (!$statusYList) {
            return response()->json([

                'code' => 1001,

                'message' => 'invalid water resources'

            ], 404);
        }

        $reportData['id'] = $station['id'];
        $reportData['name'] = $station['name'];
        $reportData['type'] = $station['type'];
        $reportData['startDate'] = date('Y-m-d', strtotime($startTime));
        $reportData['endDate'] = date('Y-m-d', strtotime($endTime));
        $reportData['alarmList'] = [];

        for($i = 0 ; $i < count($statusYList)-1;$i++)
        {
            if($statusYList[$i]->bj_b1 == 0 && $statusYList[$i+1]->bj_b1 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->bj_b2 == 0 && $statusYList[$i+1]->bj_b2 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->bj_b3 == 0 && $statusYList[$i+1]->bj_b3 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->bj_b4 == 0 && $statusYList[$i+1]->bj_b4 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }


            if($statusYList[$i]->rqbj_b1 == 0 && $statusYList[$i+1]->rqbj_b1 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->rqbj_b2 == 0 && $statusYList[$i+1]->rqbj_b2 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->rqbj_b3 == 0 && $statusYList[$i+1]->rqbj_b3 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->rqbj_b4 == 0 && $statusYList[$i+1]->rqbj_b4 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($stationNum == 33)
            {
                if($statusYList[$i]->bj_b5 == 0 && $statusYList[$i+1]->bj_b5 == 1 )
                {
                    $sWarning['Time'] = $statusYList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "5号泵电机";
                    $sWarning['alarmStatus'] = 1;

                    array_push($reportData['alarmList'],$sWarning);
                }

                if($statusYList[$i]->rqbj_b5 == 0 && $statusYList[$i+1]->rqbj_b5 == 1 )
                {
                    $sWarning['Time'] = $statusYList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "5号泵软启动器";
                    $sWarning['alarmStatus'] = 1;

                    array_push($reportData['alarmList'],$sWarning);
                }
            }

            if($statusYList[$i]->bj_jl == 0 && $statusYList[$i+1]->bj_jl == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "绞笼";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->bj_gs1 == 0 && $statusYList[$i+1]->bj_gs1 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号格栅";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            if($statusYList[$i]->bj_gs2 == 0 && $statusYList[$i+1]->bj_gs2 == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号格栅";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }

            //部分泵站通讯中断,没有数据,不做市电的报警
            $stationNoWorking = ['11','12','20','34'];
            if(in_array($stationNum, $stationNoWorking))
            {

            }else{
                if($statusYList[$i]->water_v == 1 && $statusYList[$i+1]->water_v == 0 )
                {
                    $sWarning['Time'] = $statusYList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "市电停电";
                    $sWarning['alarmStatus'] = 1;

                    array_push($reportData['alarmList'],$sWarning);
                }
            }



            if($statusYList[$i]->flow_v == 0 && $statusYList[$i+1]->flow_v == 1 )
            {
                $sWarning['Time'] = $statusYList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "手动急停";
                $sWarning['alarmStatus'] = 1;

                array_push($reportData['alarmList'],$sWarning);
            }
        }

        return response()->json([

            'code' => 0,

            'message' => 'success',

            'data' => $reportData

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
