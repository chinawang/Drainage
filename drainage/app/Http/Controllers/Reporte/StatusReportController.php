<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Logic\Station\PumpLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Station\StationValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

/**
 * 泵站运行状态(启停状态)
 * Class StatusReportController
 * @package App\Http\Controllers\Reporte
 */
class StatusReportController extends Controller
{

    /**
     * @var StationLogic
     */
    protected $stationLogic;

    /**
     * @var StationValidation
     */
    protected $stationValidation;

    protected $pumpLogic;

    /**
     * StatusReportController constructor.
     * @param StationLogic $stationLogic
     * @param StationValidation $stationValidation
     * @param PumpLogic $pumpLogic
     */
    public function __construct(StationLogic $stationLogic, StationValidation $stationValidation,PumpLogic $pumpLogic)
    {
        $this->middleware('auth');

        $this->stationLogic = $stationLogic;
        $this->stationValidation = $stationValidation;
        $this->pumpLogic = $pumpLogic;
    }

    /**
     * 泵站泵组运行日志
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStatusReportDay()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = $startTime;

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $param = $this->getStatusReport($stationID,$startTime,$endTime);

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站启动状态统计']);

        return view('report.stationStatusDay', $param);
    }

    /**
     * 获取当月单机运行统计
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStatusReportMonth()
    {
//        $stationID = Input::get('station_id', 1);
        $type = Input::get('type', '全部');
        $selectDay = Input::get('timeStart', '');

        if ($selectDay == '')
        {
            $selectDay = date("Y-m-d");
        }
        $days = $this->getTheMonthDay($selectDay);
        $startTime = $days[0];
        $endTime = $days[1];

        $stations = $this->stationListByType($type);

        foreach ($stations as $station)
        {
            $param = $this->getStatusReport($station['id'],$startTime,$endTime);
            //单位小时
            $station['totalTimeDay1'] = round(($param['totalTimeDay1'])/60,2);
            $station['totalTimeDay2'] = round(($param['totalTimeDay2'])/60,2);
            $station['totalTimeDay3'] = round(($param['totalTimeDay3'])/60,2);
            $station['totalTimeDay4'] = round(($param['totalTimeDay4'])/60,2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];

            $station['totalTimeBefore1'] = $param['totalTimeBefore1'];
            $station['totalTimeBefore2'] = $param['totalTimeBefore2'];
            $station['totalTimeBefore3'] = $param['totalTimeBefore3'];
            $station['totalTimeBefore4'] = $param['totalTimeBefore4'];

            $station['totalFluxBefore1'] = $param['totalFluxBefore1'];
            $station['totalFluxBefore2'] = $param['totalFluxBefore2'];
            $station['totalFluxBefore3'] = $param['totalFluxBefore3'];
            $station['totalFluxBefore4'] = $param['totalFluxBefore4'];
        }
        $paramMonth = ['stations' => $stations, 'selectType' => $type, 'startTime' => $startTime];
        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站启动状态统计']);

        return view('report.stationStatusMonth', $paramMonth);
    }

    /**
     * 获取当前月泵站所有泵组运行总计
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStatusReportMonthAll()
    {
        $type = Input::get('type', '全部');
        $selectDay = Input::get('timeStart', '');

        if ($selectDay == '')
        {
            $selectDay = date("Y-m-d");
        }
        $days = $this->getTheMonthDay($selectDay);
        $startTime = $days[0];
        $endTime = $days[1];

        $stations = $this->stationListByType($type);

        $index = 1;

        //所有泵站之和
        $totalTimeDayAll = 0;
        $totalTimeBeforeAll = 0;
        $totalFluxDayAll = 0;
        $totalFluxBeforeAll = 0;

        foreach ($stations as $station)
        {
            $param = $this->getStatusReport($station['id'],$startTime,$endTime);
            //单位小时
            $station['totalTimeDay'] = round(($param['totalTimeDay'])/60,2);
            $station['totalFluxDay'] = $param['totalFluxDay'];
            $station['totalTimeBefore'] = $param['totalTimeBefore'];
            $station['totalFluxBefore'] = $param['totalFluxBefore'];
            $totalTimeDayAll += $station['totalTimeDay'];
            $totalTimeBeforeAll += $station['totalTimeBefore'];
            $totalFluxDayAll += $station['totalFluxDay'];
            $totalFluxBeforeAll += $station['totalFluxBefore'];

            $station['index'] = $index;
            $index ++;
        }
        $paramMonthAll = ['stations' => $stations,'totalTimeDayAll'=>$totalTimeDayAll,
            'totalTimeBeforeAll'=>$totalTimeBeforeAll,'totalFluxDayAll'=>$totalFluxDayAll,
            'totalFluxBeforeAll'=>$totalFluxBeforeAll, '$selectType' => $type, 'startTime' => $startTime];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站启动状态统计']);

        return view('report.stationStatusMonthAll', $paramMonthAll);
    }



    /**
     * 获取当前月的第一天与最后一天
     * @param $date
     * @return array
     */
    function getTheMonthDay($date)
    {
        $firstDay = date('Y-m-01', strtotime($date));
        $lastDay = date('Y-m-d', strtotime("$firstDay +1 month -1 day"));
        return array($firstDay,$lastDay);
    }

    /**
     * 运行状态统计
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function getStatusReport($stationID,$startTime,$endTime)
    {
        $stationTemp = $this->stationInfo($stationID);

        $stations = $this->stationList();

        $statusRT = $this->statusRTHistory($stationID,$startTime,$endTime);

        //当日运行时间合计(分钟)
        $totalTimeDay1 = $this->sumTime($statusRT['pump1']);
        $totalTimeDay2 = $this->sumTime($statusRT['pump2']);
        $totalTimeDay3 = $this->sumTime($statusRT['pump3']);
        $totalTimeDay4 = $this->sumTime($statusRT['pump4']);

        //当日泵站运行合计(分钟)
        $totalTimeDay = $totalTimeDay1 + $totalTimeDay2 + $totalTimeDay3 + $totalTimeDay4;

        //当日抽升量合计(万吨)
        $totalFluxDay1 = ($this->sumFlux($statusRT['pump1']))/10000;
        $totalFluxDay2 = ($this->sumFlux($statusRT['pump2']))/10000;
        $totalFluxDay3 = ($this->sumFlux($statusRT['pump3']))/10000;
        $totalFluxDay4 = ($this->sumFlux($statusRT['pump4']))/10000;

        //当日泵站总抽升量(万吨)
        $totalFluxDay = $totalFluxDay1 + $totalFluxDay2 + $totalFluxDay3 + $totalFluxDay4;

        //连前累计
        $beforeTime = date("2017-1-1");
        $beforeStatusRT = $this->statusRTHistory($stationID,$beforeTime,$endTime);

        //连前累计运行(小时)
        $totalTimeBefore1 = round(($this->sumTime($beforeStatusRT['pump1']))/60,2);
        $totalTimeBefore2 = round(($this->sumTime($beforeStatusRT['pump2']))/60,2);
        $totalTimeBefore3 = round(($this->sumTime($beforeStatusRT['pump3']))/60,2);
        $totalTimeBefore4 = round(($this->sumTime($beforeStatusRT['pump4']))/60,2);

        //连前累计泵站总运行时间(小时)
        $totalTimeBefore = $totalTimeBefore1 + $totalTimeBefore2 + $totalTimeBefore3 + $totalTimeBefore4;

        //连前累计抽水量(万吨)
        $totalFluxBefore1 = ($this->sumFlux($beforeStatusRT['pump1']))/10000;
        $totalFluxBefore2 = ($this->sumFlux($beforeStatusRT['pump2']))/10000;
        $totalFluxBefore3 = ($this->sumFlux($beforeStatusRT['pump3']))/10000;
        $totalFluxBefore4 = ($this->sumFlux($beforeStatusRT['pump4']))/10000;

        //连前累计泵站总抽升量(万吨)
        $totalFluxBefore = $totalFluxBefore1 + $totalFluxBefore2 + $totalFluxBefore3 + $totalFluxBefore4;

        $param = ['stations' => $stations, 'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime,
            'stationStatusList1'=> $statusRT['pump1'],'stationStatusList2'=> $statusRT['pump2'],
            'stationStatusList3'=> $statusRT['pump3'],'stationStatusList4'=> $statusRT['pump4'],
            'totalTimeDay1' => $totalTimeDay1,'totalTimeDay2' => $totalTimeDay2,'totalTimeDay3' => $totalTimeDay3,'totalTimeDay4' => $totalTimeDay4,
            'totalFluxDay1' => $totalFluxDay1,'totalFluxDay2' => $totalFluxDay2,'totalFluxDay3' => $totalFluxDay3,'totalFluxDay4' => $totalFluxDay4,
            'totalTimeBefore1' => $totalTimeBefore1,'totalTimeBefore2' => $totalTimeBefore2,'totalTimeBefore3' => $totalTimeBefore3,'totalTimeBefore4' => $totalTimeBefore4,
            'totalFluxBefore1' => $totalFluxBefore1,'totalFluxBefore2' => $totalFluxBefore2,'totalFluxBefore3' => $totalFluxBefore3,'totalFluxBefore4' => $totalFluxBefore4,
            'totalTimeDay' => $totalTimeDay,'totalFluxDay' => $totalFluxDay,'totalTimeBefore' => $totalTimeBefore,'totalFluxBefore' => $totalFluxBefore,
        ];

        return $param;
    }

    /**
     * 运行时间求和
     *
     * @param $statusList
     * @return int
     */
    public function sumTime($statusList)
    {
        $sTime = 0;
        for($i = 0 ; $i < count($statusList)-1;$i++)
        {
            $sTime = $sTime + $statusList[$i]['timeGap'];
        }

        return $sTime;
    }

    /**
     * 抽升量求和
     *
     * @param $statusList
     * @return int
     */
    public function sumFlux($statusList)
    {
        $sFlux = 0;

        for($i = 0 ; $i < count($statusList)-1;$i++)
        {
            $sFlux = $sFlux + $statusList[$i]['flux'];
        }

        return $sFlux;
    }

    /**
     * 查询所有泵站
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function stationList()
    {
        $stations = $this->stationLogic->getAllStations();

        return $stations;
    }

    /**
     * 根据泵站类型查看泵站
     *
     * @param $type
     * @return mixed
     */
    public function stationListByType($type)
    {
        if($type == "全部")
        {
            $conditions = ['delete_process' => 0];
        }
        else
        {
            $conditions = ['delete_process' => 0,'type' => $type];
        }

        $stations = $this->stationLogic->getAllStationsBy($conditions);

        return $stations;
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
        return $station;
    }

    /**
     * 查询泵组抽水量信息
     * @param $stationID
     * @return mixed
     */
    public function pumpInfo($stationID)
    {
        $pumps = $this->pumpLogic->getPumpsByStation($stationID,1,'updated_at','desc',null);
        return $pumps[0];
    }

    /**
     * 查询所有泵站实时信息列表
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getStationRTAll($stationID,$startTime,$endTime)
    {
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationTable = "stationRT_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';


        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $stationRTList = DB::table($stationTable)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')
                ->get();

        }
        else
        {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
                ->get();
        }

        return $stationRTList;
    }


    /**
     * 计算泵组运行时间与抽水量
     *
     * @param $stationRTList
     * @param $equipmentCode
     * @param $currentCode
     * @param $pumpFlux
     * @return array
     */
    public function getStationStatusList($stationRTList,$equipmentCode,$currentCode,$pumpFlux)
    {
        $stationStatusList = [];
        $index = 1;

        for($i = 0 ; $i < count($stationRTList)-1;$i++)
        {
            $sRunning = [];

            if($stationRTList[$i]->$equipmentCode == 0 && $stationRTList[$i+1]->$equipmentCode == 1 )
            {
                $sRunning['timeStart'] = $stationRTList[$i+1]->Time;
//                $sRunning['current'] = $stationRTList[$i+1]->$currentCode;
//                $sRunning['timeEnd'] = '';
//                $sRunning['timeGap'] = '';
//                $sRunning['index'] = $index;
                $index ++;
                array_push($stationStatusList,$sRunning);
            }
            if($stationRTList[$i]->$equipmentCode == 1 && $stationRTList[$i+1]->$equipmentCode == 0 )
            {
//                $sRunning['timeStart'] = '';
                $sRunning['timeEnd'] = $stationRTList[$i+1]->Time;
                if($index > 1)
                {
                    $sRunning['timeGap'] = abs(strtotime($sRunning['timeEnd']) - strtotime($stationStatusList[$index -2]['timeStart']))/60;
                    $sRunning['timeGap'] = round($sRunning['timeGap']);
                    $sRunning['current'] = $stationRTList[$i+1]->$currentCode;
                    $stationStatusList[$index -2]['timeEnd'] = $sRunning['timeEnd'];
                    $stationStatusList[$index -2]['timeGap'] = $sRunning['timeGap'];
                    $stationStatusList[$index -2]['flux'] = $sRunning['timeGap'] * $pumpFlux;
                    $stationStatusList[$index -2]['current'] = $sRunning['current'];
                    $stationStatusList[$index -2]['index'] = $index -1;
                }

            }

        }

        return $stationStatusList;
    }

    /**
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function statusRTHistory($stationID,$startTime,$endTime)
    {
        $stationRTList = $this->getStationRTAll($stationID,$startTime,$endTime);

        $pump = $this->pumpInfo($stationID);

        $statusRT['pump1'] = $this->getStationStatusList($stationRTList,'yx_b1','ib1',$pump['flux1']);
        $statusRT['pump2'] = $this->getStationStatusList($stationRTList,'yx_b2','ib2',$pump['flux2']);
        $statusRT['pump3'] = $this->getStationStatusList($stationRTList,'yx_b3','ib3',$pump['flux3']);
        $statusRT['pump4'] = $this->getStationStatusList($stationRTList,'yx_b4','ib4',$pump['flux4']);

        return $statusRT;
    }

    /**
     * Ajax查询泵站实时数据
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function stationRTHistoryAjax($stationID,$startTime,$endTime)
    {
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationTable = "stationRT_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';


        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $stationRTList = DB::table($stationTable)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')
                ->get();

        }
        else
        {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
                ->get();
        }

        return response()->json(array('stationRTHistory'=> $stationRTList), 200);
    }

    /**
     * Ajax查询泵站运行状态实时记录
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusRTHistoryAjax($stationID,$startTime)
    {
        $endTime = $startTime;

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $statusRT = $this->statusRTHistory($stationID,$startTime,$endTime);

        $param = array('stationStatusList1'=> $statusRT['pump1'],'stationStatusList2'=> $statusRT['pump2'],
            'stationStatusList3'=> $statusRT['pump3'],'stationStatusList4'=> $statusRT['pump4']);

        return response()->json($param, 200);
    }

    /**
     * 按月查询单机运行时间Ajax
     * @param $type
     * @param $selectDay
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusRTMonthAjax($type,$selectDay)
    {
        if ($selectDay == '')
        {
            $selectDay = date("Y-m-d");
        }
        $days = $this->getTheMonthDay($selectDay);
        $startTime = $days[0];
        $endTime = $days[1];

        $stations = $this->stationListByType($type);

        foreach ($stations as $station)
        {
            $param = $this->getStatusReport($station['id'],$startTime,$endTime);
            //单位小时
            $station['totalTimeDay1'] = round(($param['totalTimeDay1'])/60,2);
            $station['totalTimeDay2'] = round(($param['totalTimeDay2'])/60,2);
            $station['totalTimeDay3'] = round(($param['totalTimeDay3'])/60,2);
            $station['totalTimeDay4'] = round(($param['totalTimeDay4'])/60,2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];

            $station['totalTimeBefore1'] = $param['totalTimeBefore1'];
            $station['totalTimeBefore2'] = $param['totalTimeBefore2'];
            $station['totalTimeBefore3'] = $param['totalTimeBefore3'];
            $station['totalTimeBefore4'] = $param['totalTimeBefore4'];

            $station['totalFluxBefore1'] = $param['totalFluxBefore1'];
            $station['totalFluxBefore2'] = $param['totalFluxBefore2'];
            $station['totalFluxBefore3'] = $param['totalFluxBefore3'];
            $station['totalFluxBefore4'] = $param['totalFluxBefore4'];
        }
//        $paramMonth = ['stations' => $stations, 'selectType' => $type, 'startTime' => $startTime];
        return response()->json(array('stations'=> $stations), 200);
    }

    /**
     * 按月查询泵站所有泵组运行时间Ajax
     * @param $type
     * @param $selectDay
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusRTMonthAllAjax($type,$selectDay)
    {
        if ($selectDay == '')
        {
            $selectDay = date("Y-m-d");
        }
        $days = $this->getTheMonthDay($selectDay);
        $startTime = $days[0];
        $endTime = $days[1];

        $stations = $this->stationListByType($type);

        foreach ($stations as $station)
        {
            $param = $this->getStatusReport($station['id'],$startTime,$endTime);
            //单位小时
            $station['totalTimeDay'] = round(($param['totalTimeDay'])/60,2);
            $station['totalFluxDay'] = $param['totalFluxDay'];
            $station['totalTimeBefore'] = $param['totalTimeBefore'];
            $station['totalFluxBefore'] = $param['totalFluxBefore'];
        }
//        $paramMonth = ['stations' => $stations, 'selectType' => $type, 'startTime' => $startTime];
        return response()->json(array('stations'=> $stations), 200);
    }

}
