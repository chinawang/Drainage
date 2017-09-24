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

        //当日运行时间合计
        $totalTimeDay1 = $this->sumTime($statusRT['pump1']);
        $totalTimeDay2 = $this->sumTime($statusRT['pump2']);
        $totalTimeDay3 = $this->sumTime($statusRT['pump3']);
        $totalTimeDay4 = $this->sumTime($statusRT['pump4']);

        //当日泵站运行合计
        $totalTimeDay = $totalTimeDay1 + $totalTimeDay2 + $totalTimeDay3 + $totalTimeDay4;

        //当日抽升量合计
        $totalFluxDay1 = $this->sumFlux($statusRT['pump1']);
        $totalFluxDay2 = $this->sumFlux($statusRT['pump2']);
        $totalFluxDay3 = $this->sumFlux($statusRT['pump3']);
        $totalFluxDay4 = $this->sumFlux($statusRT['pump4']);

        //当日泵站总抽升量
        $totalFluxDay = $totalFluxDay1 + $totalFluxDay2 + $totalFluxDay3 + $totalFluxDay4;

        //连前累计
        $beforeTime = date("2017-1-1");
        $beforeStatusRT = $this->statusRTHistory($stationID,$beforeTime,$endTime);

        //连前累计运行
        $totalTimeBefore1 = $this->sumTime($beforeStatusRT['pump1']);
        $totalTimeBefore2 = $this->sumTime($beforeStatusRT['pump2']);
        $totalTimeBefore3 = $this->sumTime($beforeStatusRT['pump3']);
        $totalTimeBefore4 = $this->sumTime($beforeStatusRT['pump4']);

        //连前累计泵站总运行时间
        $totalTimeBefore = $totalTimeBefore1 + $totalTimeBefore2 + $totalTimeBefore3 + $totalTimeBefore4;

        //连前累计抽水量
        $totalFluxBefore1 = $this->sumFlux($beforeStatusRT['pump1']);
        $totalFluxBefore2 = $this->sumFlux($beforeStatusRT['pump2']);
        $totalFluxBefore3 = $this->sumFlux($beforeStatusRT['pump3']);
        $totalFluxBefore4 = $this->sumFlux($beforeStatusRT['pump4']);

        //连前累计泵站总抽升量
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
                $sRunning['current'] = $stationRTList[$i+1]->$currentCode;
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
                    $stationStatusList[$index -2]['timeEnd'] = $sRunning['timeEnd'];
                    $stationStatusList[$index -2]['timeGap'] = $sRunning['timeGap'];
                    $stationStatusList[$index -2]['flux'] = $sRunning['timeGap'] * $pumpFlux;
                    $stationStatusList[$index -2]['current'] = $sRunning['current'];
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
    public function statusRTHistoryAjax($stationID,$startTime,$endTime)
    {
        $statusRT = $this->statusRTHistory($stationID,$startTime,$endTime);

        $param = array('stationStatusList1'=> $statusRT['pump1'],'stationStatusList2'=> $statusRT['pump2'],
            'stationStatusList3'=> $statusRT['pump3'],'stationStatusList4'=> $statusRT['pump4']);

        return response()->json($param, 200);
    }

}
