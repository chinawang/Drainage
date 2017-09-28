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
use Maatwebsite\Excel\Facades\Excel;

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
            $station['totalTimeDay5'] = round(($param['totalTimeDay5'])/60,2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];
            $station['totalFluxDay5'] = $param['totalFluxDay5'];

            $station['totalTimeBefore1'] = $param['totalTimeBefore1'];
            $station['totalTimeBefore2'] = $param['totalTimeBefore2'];
            $station['totalTimeBefore3'] = $param['totalTimeBefore3'];
            $station['totalTimeBefore4'] = $param['totalTimeBefore4'];
            $station['totalTimeBefore5'] = $param['totalTimeBefore5'];

            $station['totalFluxBefore1'] = $param['totalFluxBefore1'];
            $station['totalFluxBefore2'] = $param['totalFluxBefore2'];
            $station['totalFluxBefore3'] = $param['totalFluxBefore3'];
            $station['totalFluxBefore4'] = $param['totalFluxBefore4'];
            $station['totalFluxBefore5'] = $param['totalFluxBefore5'];
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
            'totalFluxBeforeAll'=>$totalFluxBeforeAll, 'selectType' => $type, 'startTime' => $startTime];

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
        if($stationTemp['station_number'] == 33){
            $totalTimeDay5 = $this->sumTime($statusRT['pump5']);
        }
        else{
            $totalTimeDay5 = 0;
        }


        //当日泵站运行合计(分钟)
        $totalTimeDay = $totalTimeDay1 + $totalTimeDay2 + $totalTimeDay3 + $totalTimeDay4 + $totalTimeDay5;

        //当日抽升量合计(万吨)
        $totalFluxDay1 = ($this->sumFlux($statusRT['pump1']))/10000;
        $totalFluxDay2 = ($this->sumFlux($statusRT['pump2']))/10000;
        $totalFluxDay3 = ($this->sumFlux($statusRT['pump3']))/10000;
        $totalFluxDay4 = ($this->sumFlux($statusRT['pump4']))/10000;
        if($stationTemp['station_number'] == 33){
            $totalFluxDay5 = ($this->sumFlux($statusRT['pump5']))/10000;
        }
        else{
            $totalFluxDay5 = 0;
        }

        //当日泵站总抽升量(万吨)
        $totalFluxDay = $totalFluxDay1 + $totalFluxDay2 + $totalFluxDay3 + $totalFluxDay4 + $totalFluxDay5;

        //连前累计
        $beforeTime = date("2017-1-1");
        $beforeStatusRT = $this->statusRTHistory($stationID,$beforeTime,$endTime);

        //连前累计运行(小时)
        $totalTimeBefore1 = round(($this->sumTime($beforeStatusRT['pump1']))/60,2);
        $totalTimeBefore2 = round(($this->sumTime($beforeStatusRT['pump2']))/60,2);
        $totalTimeBefore3 = round(($this->sumTime($beforeStatusRT['pump3']))/60,2);
        $totalTimeBefore4 = round(($this->sumTime($beforeStatusRT['pump4']))/60,2);
        if($stationTemp['station_number'] == 33){
            $totalTimeBefore5 = round(($this->sumTime($beforeStatusRT['pump5']))/60,2);
        }
        else{
            $totalTimeBefore5 = 0;
        }

        //连前累计泵站总运行时间(小时)
        $totalTimeBefore = $totalTimeBefore1 + $totalTimeBefore2 + $totalTimeBefore3 + $totalTimeBefore4 + $totalTimeBefore5;

        //连前累计抽水量(万吨)
        $totalFluxBefore1 = ($this->sumFlux($beforeStatusRT['pump1']))/10000;
        $totalFluxBefore2 = ($this->sumFlux($beforeStatusRT['pump2']))/10000;
        $totalFluxBefore3 = ($this->sumFlux($beforeStatusRT['pump3']))/10000;
        $totalFluxBefore4 = ($this->sumFlux($beforeStatusRT['pump4']))/10000;
        if($stationTemp['station_number'] == 33){
            $totalFluxBefore5 = ($this->sumFlux($beforeStatusRT['pump5']))/10000;
        }
        else{
            $totalFluxBefore5 = 0;
        }

        //连前累计泵站总抽升量(万吨)
        $totalFluxBefore = $totalFluxBefore1 + $totalFluxBefore2 + $totalFluxBefore3 + $totalFluxBefore4 + $totalFluxBefore5;

        $param = ['stations' => $stations, 'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime,
            'stationStatusList1'=> $statusRT['pump1'],'stationStatusList2'=> $statusRT['pump2'],
            'stationStatusList3'=> $statusRT['pump3'],'stationStatusList4'=> $statusRT['pump4'],'stationStatusList5'=> $statusRT['pump5'],
            'totalTimeDay1' => $totalTimeDay1,'totalTimeDay2' => $totalTimeDay2,'totalTimeDay3' => $totalTimeDay3,'totalTimeDay4' => $totalTimeDay4,'totalTimeDay5' => $totalTimeDay5,
            'totalFluxDay1' => $totalFluxDay1,'totalFluxDay2' => $totalFluxDay2,'totalFluxDay3' => $totalFluxDay3,'totalFluxDay4' => $totalFluxDay4,'totalFluxDay5' => $totalFluxDay5,
            'totalTimeBefore1' => $totalTimeBefore1,'totalTimeBefore2' => $totalTimeBefore2,'totalTimeBefore3' => $totalTimeBefore3,'totalTimeBefore4' => $totalTimeBefore4,'totalTimeBefore5' => $totalTimeBefore5,
            'totalFluxBefore1' => $totalFluxBefore1,'totalFluxBefore2' => $totalFluxBefore2,'totalFluxBefore3' => $totalFluxBefore3,'totalFluxBefore4' => $totalFluxBefore4,'totalFluxBefore5' => $totalFluxBefore5,
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
        $station = $this->stationInfo($stationID);

        $statusRT['pump1'] = $this->getStationStatusList($stationRTList,'yx_b1','ib1',$pump['flux1']);
        $statusRT['pump2'] = $this->getStationStatusList($stationRTList,'yx_b2','ib2',$pump['flux2']);
        $statusRT['pump3'] = $this->getStationStatusList($stationRTList,'yx_b3','ib3',$pump['flux3']);
        $statusRT['pump4'] = $this->getStationStatusList($stationRTList,'yx_b4','ib4',$pump['flux4']);
        if($station['station_number'] == 33)
        {
            $statusRT['pump5'] = $this->getStationStatusList($stationRTList,'yx_b5','ib5',$pump['flux5']);
        }
        else
        {
            $statusRT['pump5'] = null;
        }

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
            'stationStatusList3'=> $statusRT['pump3'],'stationStatusList4'=> $statusRT['pump4'],'stationStatusList5'=> $statusRT['pump5']);

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
            $station['totalTimeDay5'] = round(($param['totalTimeDay5'])/60,2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];
            $station['totalFluxDay5'] = $param['totalFluxDay5'];

            $station['totalTimeBefore1'] = $param['totalTimeBefore1'];
            $station['totalTimeBefore2'] = $param['totalTimeBefore2'];
            $station['totalTimeBefore3'] = $param['totalTimeBefore3'];
            $station['totalTimeBefore4'] = $param['totalTimeBefore4'];
            $station['totalTimeBefore5'] = $param['totalTimeBefore5'];

            $station['totalFluxBefore1'] = $param['totalFluxBefore1'];
            $station['totalFluxBefore2'] = $param['totalFluxBefore2'];
            $station['totalFluxBefore3'] = $param['totalFluxBefore3'];
            $station['totalFluxBefore4'] = $param['totalFluxBefore4'];
            $station['totalFluxBefore5'] = $param['totalFluxBefore5'];
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

    function get_max($a,$b,$c,$d)
    {
        return ( $a > $b ? $a : $b ) > $c ? ( $a > $b ? $a : $b ) : $c > $d ? (( $a > $b ? $a : $b ) > $c ? ( $a > $b ? $a : $b ) : $c) :$d;
    }

    /**
     * 导出单机运行日志
     */
    public function exportToExcelStatusDay()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = $startTime;

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $title = '泵站运行日志-'.$startTime;

        $excelData = $this->getStatusReport($stationID,$startTime,$endTime);

        Excel::create($title, function ($excel) use ($excelData, $title,$startTime) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('泵站运行日志', function ($sheet) use ($excelData,$startTime) {

                $station = $excelData['stationSelect'];

                $sheet->row(1, ['郑州市市政工程管理处泵站所泵站运行日志']);
                if($station['station_number'] == 33)
                {
                    $sheet->row(2, ['泵站: '.$station['name'],'','','','','','','','','','','','','','',$startTime]);
                    $sheet->row(3, ['序号','总电流(A)','电压(V)','进水池位(M)','1号泵','','','','2号泵','','','','3号泵','','','','4号泵','','','','5号泵','','',''
                        ,'变压器','','总电度表度数(度)','','']);
                    $sheet->row(4, ['','','','','开泵时分','停泵时分','运行(分)','电流(A)','开泵时分','停泵时分','运行(分)','电流(A)',
                        '开泵时分','停泵时分','运行(分)','电流(A)','开泵时分','停泵时分','运行(分)','电流(A)','开泵时分','停泵时分','运行(分)','电流(A)',
                        '环境温度(℃)','油温(℃)','有功读数','无功读数','功率读数']);

                }
                else
                {
                    $sheet->row(2, ['泵站: '.$station['name'],'','','','','','','','','','',$startTime]);
                    $sheet->row(3, ['序号','总电流(A)','电压(V)','进水池位(M)','1号泵','','','','2号泵','','','','3号泵','','','','4号泵','','',''
                        ,'变压器','','总电度表度数(度)','','']);
                    $sheet->row(4, ['','','','','开泵时分','停泵时分','运行(分)','电流(A)','开泵时分','停泵时分','运行(分)','电流(A)',
                        '开泵时分','停泵时分','运行(分)','电流(A)','开泵时分','停泵时分','运行(分)','电流(A)',
                        '环境温度(℃)','油温(℃)','有功读数','无功读数','功率读数']);
                }

                if (empty($excelData)) {

                    $sheet->row(5, ['']);
                    return;
                }

                $i = 5;
                $j = 5;
                $k = 5;
                $h = 5;

                // 循环写入数据(1号泵)
                foreach ($excelData['stationStatusList1'] as $rowData1){
                    $sheet->cell('E'.$i, function($cell) use ($rowData1) {
                        $cell->setValue(substr($rowData1['timeStart'],10));

                    });
                    $sheet->cell('F'.$i, function($cell) use ($rowData1) {
                        $cell->setValue(substr($rowData1['timeEnd'],10));

                    });
                    $sheet->cell('G'.$i, function($cell) use ($rowData1) {
                        $cell->setValue($rowData1['timeGap']);

                    });
                    $sheet->cell('H'.$i, function($cell) use ($rowData1) {
                        $cell->setValue($rowData1['current']);

                    });

                    //行高
                    $sheet->setHeight($i, 25);

                    $i++;
                }

                // 循环写入数据(2号泵)
                foreach ($excelData['stationStatusList2'] as $rowData2){
                    $sheet->cell('I'.$j, function($cell) use ($rowData2) {
                        $cell->setValue(substr($rowData2['timeStart'],10));

                    });
                    $sheet->cell('J'.$j, function($cell) use ($rowData2) {
                        $cell->setValue(substr($rowData2['timeEnd'],10));

                    });
                    $sheet->cell('K'.$j, function($cell) use ($rowData2) {
                        $cell->setValue($rowData2['timeGap']);

                    });
                    $sheet->cell('L'.$j, function($cell) use ($rowData2) {
                        $cell->setValue($rowData2['current']);

                    });

                    //行高
                    $sheet->setHeight($j, 25);

                    $j++;
                }

                // 循环写入数据(3号泵)
                foreach ($excelData['stationStatusList3'] as $rowData3){
                    $sheet->cell('M'.$k, function($cell) use ($rowData3) {
                        $cell->setValue(substr($rowData3['timeStart'],10));

                    });
                    $sheet->cell('N'.$k, function($cell) use ($rowData3) {
                        $cell->setValue(substr($rowData3['timeEnd'],10));

                    });
                    $sheet->cell('O'.$k, function($cell) use ($rowData3) {
                        $cell->setValue($rowData3['timeGap']);

                    });
                    $sheet->cell('P'.$k, function($cell) use ($rowData3) {
                        $cell->setValue($rowData3['current']);

                    });

                    //行高
                    $sheet->setHeight($k, 25);

                    $k++;
                }

                // 循环写入数据(4号泵)
                foreach ($excelData['stationStatusList4'] as $rowData4){
                    $sheet->cell('Q'.$h, function($cell) use ($rowData4) {
                        $cell->setValue(substr($rowData4['timeStart'],10));

                    });
                    $sheet->cell('R'.$h, function($cell) use ($rowData4) {
                        $cell->setValue(substr($rowData4['timeEnd'],10));

                    });
                    $sheet->cell('S'.$h, function($cell) use ($rowData4) {
                        $cell->setValue($rowData4['timeGap']);

                    });
                    $sheet->cell('T'.$h, function($cell) use ($rowData4) {
                        $cell->setValue($rowData4['current']);

                    });

                    //行高
                    $sheet->setHeight($h, 25);

                    $h++;
                }

                if($station['station_number'] == 33)
                {
                    // 循环写入数据(5号泵)
                    foreach ($excelData['stationStatusList5'] as $rowData5){
                        $sheet->cell('U'.$h, function($cell) use ($rowData5) {
                            $cell->setValue(substr($rowData5['timeStart'],10));

                        });
                        $sheet->cell('V'.$h, function($cell) use ($rowData5) {
                            $cell->setValue(substr($rowData5['timeEnd'],10));

                        });
                        $sheet->cell('W'.$h, function($cell) use ($rowData5) {
                            $cell->setValue($rowData5['timeGap']);

                        });
                        $sheet->cell('X'.$h, function($cell) use ($rowData5) {
                            $cell->setValue($rowData5['current']);

                        });

                        //行高
                        $sheet->setHeight($h, 25);

                        $h++;
                    }
                }

                $rowMax = $this->get_max($i,$j,$k,$h);

                for($index = 5 ; $index <= $rowMax; $index ++)
                {
                    $sheet->cell('A'.$index, function($cell) use ($index) {
                        $cell->setValue($index-4);

                    });
                }

                //运行合计
                if($station['station_number'] == 33)
                {
                    $sheet->row($rowMax, ['运行合计','','','',$excelData['totalTimeDay1'],'','','分',$excelData['totalTimeDay2'],'','','分',
                        $excelData['totalTimeDay3'],'','','分',$excelData['totalTimeDay4'],'','','分',$excelData['totalTimeDay5'],'','','分','总耗电量(度)','','','','']);

                    $sheet->row($rowMax+1, ['连前累计运行','','','',$excelData['totalTimeBefore1'],'','','小时',$excelData['totalTimeBefore2'],'','','小时',
                        $excelData['totalTimeBefore3'],'','','小时',$excelData['totalTimeBefore4'],'','','小时',$excelData['totalTimeBefore5'],'','','小时','电表指数差','','','','']);

                    $sheet->row($rowMax+2, ['抽升量','','','',$excelData['totalFluxDay1'],'','','万吨',$excelData['totalFluxDay2'],'','','万吨',
                        $excelData['totalFluxDay3'],'','','万吨',$excelData['totalFluxDay4'],'','','万吨',$excelData['totalFluxDay5'],'','','万吨','倍率','','','','']);

                    $sheet->row($rowMax+3, ['连前累计抽升量','','','',$excelData['totalFluxBefore1'],'','','万吨',$excelData['totalFluxBefore2'],'','','万吨',
                        $excelData['totalFluxBefore3'],'','','万吨',$excelData['totalFluxBefore4'],'','','万吨',$excelData['totalFluxBefore5'],'','','万吨','今日电量','','','','']);

                    $sheet->row($rowMax+4, ['电度表读数','','','','','','','度','','','','度','','','','度','','','','度','','','','度','连前累计','','','','']);

                    $sheet->row($rowMax+5, ['今日总抽升量','','','',$excelData['totalFluxDay'],'','','万吨','值班人员','','白','','','','','','','','','','','','','','','','','']);

                    $sheet->row($rowMax+6, ['连前累计总抽升量','','','',$excelData['totalFluxBefore'],'','','万吨','签名','','黑','','','','','','','','','','','','','','','','','','']);

                    $sheet->row($rowMax+7, ['记录:','','','','','','','','校核:']);
                }
                else
                {
                    $sheet->row($rowMax, ['运行合计','','','',$excelData['totalTimeDay1'],'','','分',$excelData['totalTimeDay2'],'','','分',
                        $excelData['totalTimeDay3'],'','','分',$excelData['totalTimeDay4'],'','','分','总耗电量(度)','','','','']);

                    $sheet->row($rowMax+1, ['连前累计运行','','','',$excelData['totalTimeBefore1'],'','','小时',$excelData['totalTimeBefore2'],'','','小时',
                        $excelData['totalTimeBefore3'],'','','小时',$excelData['totalTimeBefore4'],'','','小时','电表指数差','','','','']);

                    $sheet->row($rowMax+2, ['抽升量','','','',$excelData['totalFluxDay1'],'','','万吨',$excelData['totalFluxDay2'],'','','万吨',
                        $excelData['totalFluxDay3'],'','','万吨',$excelData['totalFluxDay4'],'','','万吨','倍率','','','','']);

                    $sheet->row($rowMax+3, ['连前累计抽升量','','','',$excelData['totalFluxBefore1'],'','','万吨',$excelData['totalFluxBefore2'],'','','万吨',
                        $excelData['totalFluxBefore3'],'','','万吨',$excelData['totalFluxBefore4'],'','','万吨','今日电量','','','','']);

                    $sheet->row($rowMax+4, ['电度表读数','','','','','','','度','','','','度','','','','度','','','','度','连前累计','','','','']);

                    $sheet->row($rowMax+5, ['今日总抽升量','','','',$excelData['totalFluxDay'],'','','万吨','值班人员','','白','','','','','','','','','','','','','']);

                    $sheet->row($rowMax+6, ['连前累计总抽升量','','','',$excelData['totalFluxBefore'],'','','万吨','签名','','黑','','','','','','','','','','','','','','']);

                    $sheet->row($rowMax+7, ['记录:','','','','','','','','校核:']);
                }


                if($station['station_number'] == 33)
                {
                    //表体样式
                    $sheet->setHeight($rowMax, 25);
                    $sheet->setHeight($rowMax+1, 25);
                    $sheet->setHeight($rowMax+2, 25);
                    $sheet->setHeight($rowMax+3, 25);
                    $sheet->setHeight($rowMax+4, 25);
                    $sheet->setHeight($rowMax+5, 25);
                    $sheet->setHeight($rowMax+6, 25);
                    $sheet->setHeight($rowMax+7, 25);

                    $sheet->mergeCells('A'.$rowMax.':D'.$rowMax);
                    $sheet->mergeCells('E'.$rowMax.':G'.$rowMax);
                    $sheet->mergeCells('I'.$rowMax.':K'.$rowMax);
                    $sheet->mergeCells('M'.$rowMax.':O'.$rowMax);
                    $sheet->mergeCells('Q'.$rowMax.':S'.$rowMax);
                    $sheet->mergeCells('U'.$rowMax.':W'.$rowMax);
                    $sheet->mergeCells('Y'.$rowMax.':AC'.$rowMax);

                    $sheet->mergeCells('A'.($rowMax+1).':D'.($rowMax+1));
                    $sheet->mergeCells('E'.($rowMax+1).':G'.($rowMax+1));
                    $sheet->mergeCells('I'.($rowMax+1).':K'.($rowMax+1));
                    $sheet->mergeCells('M'.($rowMax+1).':O'.($rowMax+1));
                    $sheet->mergeCells('Q'.($rowMax+1).':S'.($rowMax+1));
                    $sheet->mergeCells('U'.($rowMax+1).':W'.($rowMax+1));
                    $sheet->mergeCells('Y'.($rowMax+1).':Z'.($rowMax+1));
                    $sheet->mergeCells('AA'.($rowMax+1).':AC'.($rowMax+1));

                    $sheet->mergeCells('A'.($rowMax+2).':D'.($rowMax+2));
                    $sheet->mergeCells('E'.($rowMax+2).':G'.($rowMax+2));
                    $sheet->mergeCells('I'.($rowMax+2).':K'.($rowMax+2));
                    $sheet->mergeCells('M'.($rowMax+2).':O'.($rowMax+2));
                    $sheet->mergeCells('Q'.($rowMax+2).':S'.($rowMax+2));
                    $sheet->mergeCells('U'.($rowMax+2).':W'.($rowMax+2));
                    $sheet->mergeCells('Y'.($rowMax+2).':Z'.($rowMax+2));
                    $sheet->mergeCells('AA'.($rowMax+2).':AC'.($rowMax+2));

                    $sheet->mergeCells('A'.($rowMax+3).':D'.($rowMax+3));
                    $sheet->mergeCells('E'.($rowMax+3).':G'.($rowMax+3));
                    $sheet->mergeCells('I'.($rowMax+3).':K'.($rowMax+3));
                    $sheet->mergeCells('M'.($rowMax+3).':O'.($rowMax+3));
                    $sheet->mergeCells('Q'.($rowMax+3).':S'.($rowMax+3));
                    $sheet->mergeCells('U'.($rowMax+3).':W'.($rowMax+3));
                    $sheet->mergeCells('Y'.($rowMax+3).':Z'.($rowMax+3));
                    $sheet->mergeCells('AA'.($rowMax+3).':AC'.($rowMax+3));

                    $sheet->mergeCells('A'.($rowMax+4).':D'.($rowMax+4));
                    $sheet->mergeCells('E'.($rowMax+4).':G'.($rowMax+4));
                    $sheet->mergeCells('I'.($rowMax+4).':K'.($rowMax+4));
                    $sheet->mergeCells('M'.($rowMax+4).':O'.($rowMax+4));
                    $sheet->mergeCells('Q'.($rowMax+4).':S'.($rowMax+4));
                    $sheet->mergeCells('U'.($rowMax+4).':W'.($rowMax+4));
                    $sheet->mergeCells('Y'.($rowMax+4).':Z'.($rowMax+4));
                    $sheet->mergeCells('AA'.($rowMax+4).':AC'.($rowMax+4));

                    $sheet->mergeCells('A'.($rowMax+5).':D'.($rowMax+5));
                    $sheet->mergeCells('E'.($rowMax+5).':G'.($rowMax+5));
                    $sheet->mergeCells('I'.($rowMax+5).':J'.($rowMax+5));
                    $sheet->mergeCells('L'.($rowMax+5).':AC'.($rowMax+5));

                    $sheet->mergeCells('A'.($rowMax+6).':D'.($rowMax+6));
                    $sheet->mergeCells('E'.($rowMax+6).':G'.($rowMax+6));
                    $sheet->mergeCells('I'.($rowMax+6).':J'.($rowMax+6));
                    $sheet->mergeCells('L'.($rowMax+6).':AC'.($rowMax+6));

                    $sheet->mergeCells('A'.($rowMax+7).':H'.($rowMax+7));
                    $sheet->mergeCells('I'.($rowMax+7).':P'.($rowMax+7));

                    $sheet->setBorder('A3:AC'.($rowMax+6), 'thin');
                    $sheet->setAutoSize(true);
                    $sheet->setWidth(array(
                        'A'     =>  8,
                        'B'     =>  15,
                        'C'     =>  15,
                        'D'     =>  15,
                        'E'     =>  12,
                        'F'     =>  12,
                        'G'     =>  12,
                        'H'     =>  12,
                        'I'     =>  12,
                        'J'     =>  12,
                        'K'     =>  12,
                        'L'     =>  12,
                        'M'     =>  12,
                        'N'     =>  12,
                        'O'     =>  12,
                        'P'     =>  12,
                        'Q'     =>  12,
                        'R'     =>  12,
                        'S'     =>  12,
                        'T'     =>  12,
                        'U'     =>  15,
                        'V'     =>  12,
                        'W'     =>  12,
                        'X'     =>  12,
                        'Y'     =>  12,
                        'Z'     =>  12,
                        'AA'     =>  12,
                        'AB'     =>  12,
                        'AC'     =>  12
                    ));
                    $sheet->cells('A3:AC'.($rowMax+7), function($cells) {
                        $cells->setFontSize(14);
                        $cells->setFontWeight('normal');
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //表头样式
                    $sheet->mergeCells('A3:A4');
                    $sheet->mergeCells('B3:B4');
                    $sheet->mergeCells('C3:C4');
                    $sheet->mergeCells('D3:D4');

                    $sheet->mergeCells('E3:H3');
                    $sheet->mergeCells('I3:L3');
                    $sheet->mergeCells('M3:P3');
                    $sheet->mergeCells('Q3:T3');
                    $sheet->mergeCells('U3:X3');

                    $sheet->mergeCells('Y3:Z3');
                    $sheet->mergeCells('AA3:AC3');

                    $sheet->setHeight(3, 30);
                    $sheet->setHeight(4, 30);
                    $sheet->cells('A3:AC4', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //标题样式
                    $sheet->mergeCells('A1:AC1');
                    $sheet->setHeight(1, 60);
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A1', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(22);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //日期样式
                    $sheet->mergeCells('A2:K2');
                    $sheet->mergeCells('L2:AC2');
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A2', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('left');
                        $cells->setValignment('center');

                    });

                    $sheet->cells('L2', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('right');
                        $cells->setValignment('center');

                    });
                }
                else
                {
                    //表体样式
                    $sheet->setHeight($rowMax, 25);
                    $sheet->setHeight($rowMax+1, 25);
                    $sheet->setHeight($rowMax+2, 25);
                    $sheet->setHeight($rowMax+3, 25);
                    $sheet->setHeight($rowMax+4, 25);
                    $sheet->setHeight($rowMax+5, 25);
                    $sheet->setHeight($rowMax+6, 25);
                    $sheet->setHeight($rowMax+7, 25);

                    $sheet->mergeCells('A'.$rowMax.':D'.$rowMax);
                    $sheet->mergeCells('E'.$rowMax.':G'.$rowMax);
                    $sheet->mergeCells('I'.$rowMax.':K'.$rowMax);
                    $sheet->mergeCells('M'.$rowMax.':O'.$rowMax);
                    $sheet->mergeCells('Q'.$rowMax.':S'.$rowMax);
                    $sheet->mergeCells('U'.$rowMax.':Y'.$rowMax);

                    $sheet->mergeCells('A'.($rowMax+1).':D'.($rowMax+1));
                    $sheet->mergeCells('E'.($rowMax+1).':G'.($rowMax+1));
                    $sheet->mergeCells('I'.($rowMax+1).':K'.($rowMax+1));
                    $sheet->mergeCells('M'.($rowMax+1).':O'.($rowMax+1));
                    $sheet->mergeCells('Q'.($rowMax+1).':S'.($rowMax+1));
                    $sheet->mergeCells('U'.($rowMax+1).':V'.($rowMax+1));
                    $sheet->mergeCells('W'.($rowMax+1).':Y'.($rowMax+1));

                    $sheet->mergeCells('A'.($rowMax+2).':D'.($rowMax+2));
                    $sheet->mergeCells('E'.($rowMax+2).':G'.($rowMax+2));
                    $sheet->mergeCells('I'.($rowMax+2).':K'.($rowMax+2));
                    $sheet->mergeCells('M'.($rowMax+2).':O'.($rowMax+2));
                    $sheet->mergeCells('Q'.($rowMax+2).':S'.($rowMax+2));
                    $sheet->mergeCells('U'.($rowMax+2).':V'.($rowMax+2));
                    $sheet->mergeCells('W'.($rowMax+2).':Y'.($rowMax+2));

                    $sheet->mergeCells('A'.($rowMax+3).':D'.($rowMax+3));
                    $sheet->mergeCells('E'.($rowMax+3).':G'.($rowMax+3));
                    $sheet->mergeCells('I'.($rowMax+3).':K'.($rowMax+3));
                    $sheet->mergeCells('M'.($rowMax+3).':O'.($rowMax+3));
                    $sheet->mergeCells('Q'.($rowMax+3).':S'.($rowMax+3));
                    $sheet->mergeCells('U'.($rowMax+3).':V'.($rowMax+3));
                    $sheet->mergeCells('W'.($rowMax+3).':Y'.($rowMax+3));

                    $sheet->mergeCells('A'.($rowMax+4).':D'.($rowMax+4));
                    $sheet->mergeCells('E'.($rowMax+4).':G'.($rowMax+4));
                    $sheet->mergeCells('I'.($rowMax+4).':K'.($rowMax+4));
                    $sheet->mergeCells('M'.($rowMax+4).':O'.($rowMax+4));
                    $sheet->mergeCells('Q'.($rowMax+4).':S'.($rowMax+4));
                    $sheet->mergeCells('U'.($rowMax+4).':V'.($rowMax+4));
                    $sheet->mergeCells('W'.($rowMax+4).':Y'.($rowMax+4));

                    $sheet->mergeCells('A'.($rowMax+5).':D'.($rowMax+5));
                    $sheet->mergeCells('E'.($rowMax+5).':G'.($rowMax+5));
                    $sheet->mergeCells('I'.($rowMax+5).':J'.($rowMax+5));
                    $sheet->mergeCells('L'.($rowMax+5).':Y'.($rowMax+5));

                    $sheet->mergeCells('A'.($rowMax+6).':D'.($rowMax+6));
                    $sheet->mergeCells('E'.($rowMax+6).':G'.($rowMax+6));
                    $sheet->mergeCells('I'.($rowMax+6).':J'.($rowMax+6));
                    $sheet->mergeCells('L'.($rowMax+6).':Y'.($rowMax+6));

                    $sheet->mergeCells('A'.($rowMax+7).':H'.($rowMax+7));
                    $sheet->mergeCells('I'.($rowMax+7).':P'.($rowMax+7));

                    $sheet->setBorder('A3:Y'.($rowMax+6), 'thin');
                    $sheet->setAutoSize(true);
                    $sheet->setWidth(array(
                        'A'     =>  8,
                        'B'     =>  15,
                        'C'     =>  15,
                        'D'     =>  15,
                        'E'     =>  12,
                        'F'     =>  12,
                        'G'     =>  12,
                        'H'     =>  12,
                        'I'     =>  12,
                        'J'     =>  12,
                        'K'     =>  12,
                        'L'     =>  12,
                        'M'     =>  12,
                        'N'     =>  12,
                        'O'     =>  12,
                        'P'     =>  12,
                        'Q'     =>  12,
                        'R'     =>  12,
                        'S'     =>  12,
                        'T'     =>  12,
                        'U'     =>  15,
                        'V'     =>  12,
                        'W'     =>  12,
                        'X'     =>  12,
                        'Y'     =>  12
                    ));
                    $sheet->cells('A3:Y'.($rowMax+7), function($cells) {
                        $cells->setFontSize(14);
                        $cells->setFontWeight('normal');
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //表头样式
                    $sheet->mergeCells('A3:A4');
                    $sheet->mergeCells('B3:B4');
                    $sheet->mergeCells('C3:C4');
                    $sheet->mergeCells('D3:D4');

                    $sheet->mergeCells('E3:H3');
                    $sheet->mergeCells('I3:L3');
                    $sheet->mergeCells('M3:P3');
                    $sheet->mergeCells('Q3:T3');

                    $sheet->mergeCells('U3:V3');
                    $sheet->mergeCells('W3:Y3');

                    $sheet->setHeight(3, 30);
                    $sheet->setHeight(4, 30);
                    $sheet->cells('A3:Y4', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //标题样式
                    $sheet->mergeCells('A1:Y1');
                    $sheet->setHeight(1, 60);
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A1', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(22);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //日期样式
                    $sheet->mergeCells('A2:K2');
                    $sheet->mergeCells('L2:Y2');
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A2', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('left');
                        $cells->setValignment('center');

                    });

                    $sheet->cells('L2', function($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('right');
                        $cells->setValignment('center');

                    });
                }

            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了统计信息']);
    }

    /**
     * 导出单机当月运行报表
     */
    public function exportToExcelStatusMonth()
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

        foreach ($stations as $station)
        {
            $param = $this->getStatusReport($station['id'],$startTime,$endTime);
            //单位小时
            $station['totalTimeDay1'] = round(($param['totalTimeDay1'])/60,2);
            $station['totalTimeDay2'] = round(($param['totalTimeDay2'])/60,2);
            $station['totalTimeDay3'] = round(($param['totalTimeDay3'])/60,2);
            $station['totalTimeDay4'] = round(($param['totalTimeDay4'])/60,2);
            $station['totalTimeDay5'] = round(($param['totalTimeDay5'])/60,2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];
            $station['totalFluxDay5'] = $param['totalFluxDay5'];

            $station['totalTimeBefore1'] = $param['totalTimeBefore1'];
            $station['totalTimeBefore2'] = $param['totalTimeBefore2'];
            $station['totalTimeBefore3'] = $param['totalTimeBefore3'];
            $station['totalTimeBefore4'] = $param['totalTimeBefore4'];
            $station['totalTimeBefore5'] = $param['totalTimeBefore5'];

            $station['totalFluxBefore1'] = $param['totalFluxBefore1'];
            $station['totalFluxBefore2'] = $param['totalFluxBefore2'];
            $station['totalFluxBefore3'] = $param['totalFluxBefore3'];
            $station['totalFluxBefore4'] = $param['totalFluxBefore4'];
            $station['totalFluxBefore5'] = $param['totalFluxBefore5'];
        }

        $title = '单机运行情况月报表-'.$startTime;

        $excelData = $stations;

        Excel::create($title, function ($excel) use ($excelData, $title,$startTime) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('单机运行情况月报表', function ($sheet) use ($excelData,$startTime) {

                $strMonth = substr($startTime,0,4).'年'.substr($startTime,5,2).'月';
                $today = date('Y-m-d');

                $sheet->row(1, [$strMonth.'单机运行抽升情况报表']);
                $sheet->row(2, [$today]);
                $sheet->row(3, ['泵站名称','1号泵','','2号泵','','3号泵','','4号泵','','5号泵']);
                $sheet->row(4, ['','运行(小时)','抽升量(万吨)','运行(小时)','抽升量(万吨)','运行(小时)','抽升量(万吨)','运行(小时)','抽升量(万吨)','运行(小时)','抽升量(万吨)']);

                if (empty($excelData)) {

                    $sheet->row(5, ['']);
                    return;
                }

                $i = 5;

                // 循环写入数据
                foreach ($excelData as $rowData){
                    $row1 = [
                        $rowData['name'],
                        $rowData['totalTimeDay1'],
                        $rowData['totalFluxDay1'],
                        $rowData['totalTimeDay2'],
                        $rowData['totalFluxDay2'],
                        $rowData['totalTimeDay3'],
                        $rowData['totalFluxDay3'],
                        $rowData['totalTimeDay4'],
                        $rowData['totalFluxDay4'],
                        $rowData['totalTimeDay5'],
                        $rowData['totalFluxDay5'],
                    ];

                    $row2 = [
                        '累计',
                        $rowData['totalTimeBefore1'],
                        $rowData['totalFluxBefore1'],
                        $rowData['totalTimeBefore2'],
                        $rowData['totalFluxBefore2'],
                        $rowData['totalTimeBefore3'],
                        $rowData['totalFluxBefore3'],
                        $rowData['totalTimeBefore4'],
                        $rowData['totalFluxBefore4'],
                        $rowData['totalTimeBefore5'],
                        $rowData['totalFluxBefore5'],
                    ];

                    $sheet->row($i, $row1);
                    $sheet->row($i+1, $row2);

                    //行高
                    $sheet->setHeight($i, 25);
                    $sheet->setHeight($i+1, 25);

                    $i++;
                    $i++;
                }

                $sheet->row($i, ['主管:','','','','','','','制表:']);


                //表体样式

                $sheet->setBorder('A3:K'.($i-1), 'thin');
                $sheet->setAutoSize(true);
                $sheet->setWidth(array(
                    'A'     =>  30,
                    'B'     =>  20,
                    'C'     =>  20,
                    'D'     =>  20,
                    'E'     =>  20,
                    'F'     =>  20,
                    'G'     =>  20,
                    'H'     =>  20,
                    'I'     =>  20,
                    'J'     =>  20,
                    'K'     =>  20,
                ));
                $sheet->cells('A4:K'.($i-1), function($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                $sheet->mergeCells('A'.($i).':G'.($i));
                $sheet->mergeCells('H'.($i).':K'.($i));
                $sheet->cells('A'.($i).':K'.($i), function($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('left');
                    $cells->setValignment('center');

                });

                //表头样式
                $sheet->mergeCells('A3:A4');
                $sheet->mergeCells('B3:C3');
                $sheet->mergeCells('D3:E3');
                $sheet->mergeCells('F3:G3');
                $sheet->mergeCells('H3:I3');
                $sheet->mergeCells('J3:K3');

                $sheet->setHeight(3, 30);
                $sheet->setHeight(4, 30);
                $sheet->cells('A3:K4', function($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //标题样式
                $sheet->mergeCells('A1:K1');
                $sheet->setHeight(1, 60);
                $sheet->setHeight(2, 25);
                $sheet->cells('A1', function($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(22);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //日期样式
                $sheet->mergeCells('A2:K2');
                $sheet->setHeight(2, 25);
                $sheet->cells('A2', function($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(14);
                    $cells->setAlignment('right');
                    $cells->setValignment('center');
                });
            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了统计信息']);
    }

    /**
     * 导出泵站所有泵组当月运行报表
     */
    public function exportToExcelStatusMonthAll()
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
            'totalFluxBeforeAll'=>$totalFluxBeforeAll, 'selectType' => $type, 'startTime' => $startTime];

        $title = '泵站月生产报表-'.$startTime;

        $excelData = $paramMonthAll;

        Excel::create($title, function ($excel) use ($excelData, $title,$startTime) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('泵站月生产报表', function ($sheet) use ($excelData,$startTime) {

                $strMonth = substr($startTime,0,4).'年'.substr($startTime,5,2).'月';
                $today = date('Y-m-d');

                $sheet->row(1, [$strMonth.'泵站月生产报表']);
                $sheet->row(2, ['单位名称: 市政工程管理处泵站管理所','','','','',$today]);
                $sheet->row(3, ['序号','泵站名称','泵组运行时间(小时)','累计(小时)','泵组抽升量(万吨)','累计(万吨)','备注']);

                if (empty($excelData)) {

                    $sheet->row(4, ['']);
                    return;
                }

                $i = 4;

                // 循环写入数据
                foreach ($excelData['stations'] as $rowData){
                    $row = [
                        $rowData['index'],
                        $rowData['name'],
                        $rowData['totalTimeDay'],
                        $rowData['totalTimeBefore'],
                        $rowData['totalFluxDay'],
                        $rowData['totalFluxBefore'],
                    ];

                    $sheet->row($i, $row);
                    //行高
                    $sheet->setHeight($i, 25);

                    $i++;
                }

                $sheet->row($i, ['合计:','',$excelData['totalTimeDayAll'],$excelData['totalTimeBeforeAll'],$excelData['totalFluxDayAll'],$excelData['totalFluxBeforeAll']]);
                //行高
                $sheet->setHeight($i, 25);

                $sheet->row($i+1, ['主管:','','','','制表:']);


                //表体样式

                $sheet->mergeCells('A'.($i).':B'.($i));

                $sheet->setBorder('A3:G'.($i), 'thin');
                $sheet->setAutoSize(true);
                $sheet->setWidth(array(
                    'A'     =>  10,
                    'B'     =>  30,
                    'C'     =>  30,
                    'D'     =>  30,
                    'E'     =>  30,
                    'F'     =>  30,
                    'G'     =>  20,
                ));
                $sheet->cells('A4:G'.($i), function($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                $sheet->mergeCells('A'.($i+1).':D'.($i+1));
                $sheet->mergeCells('E'.($i+1).':G'.($i+1));
                $sheet->cells('A'.($i+1).':G'.($i+1), function($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('left');
                    $cells->setValignment('center');

                });

                //表头样式

                $sheet->setHeight(3, 40);
                $sheet->cells('A3:G3', function($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //标题样式
                $sheet->mergeCells('A1:G1');
                $sheet->setHeight(1, 60);
                $sheet->setHeight(2, 25);
                $sheet->cells('A1', function($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(22);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //日期样式
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('F2:G2');
                $sheet->setHeight(2, 25);
                $sheet->cells('A2', function($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });
                $sheet->cells('F2', function($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('right');
                    $cells->setValignment('center');
                });
            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了统计信息']);
    }

}
