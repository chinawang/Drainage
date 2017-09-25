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
                $sheet->row(2, ['泵站: '.$station['name'],'','','','','','','','','','',$startTime]);
                $sheet->row(3, ['总电流(A)','电压(V)','进水池位(M)','1号泵','','','','2号泵','','','','3号泵','','','','4号泵','','',''
                    ,'变压器','','总电度表度数(度)','','']);
                $sheet->row(4, ['开泵时分','停泵时分','运行(分)','电流(A)','开泵时分','停泵时分','运行(分)','电流(A)',
                    '开泵时分','停泵时分','运行(分)','电流(A)','开泵时分','停泵时分','运行(分)','电流(A)',
                    '环境温度(℃)','油温(℃)','有功读数','无功读数','功率读数']);

                if (empty($excelData)) {

                    $sheet->row(5, ['']);
                    return;
                }

                $i = 5;
                $j = 5;
                $k = 5;
                $h = 5;
                $rowTmp1 = [];
                $rowTmp2 = [];
                $rowTmp3 = [];
                $rowTmp4 = [];

                // 循环写入数据(1号泵)
                foreach ($excelData['stationStatusList1'] as $rowData1){
                    $sheet->cell('D'.$i, function($cell) use ($rowData1) {
                        $cell->setValue(substr($rowData1['timeStart'],10));

                    });
                    $sheet->cell('E'.$i, function($cell) use ($rowData1) {
                        $cell->setValue(substr($rowData1['timeEnd'],10));

                    });
                    $sheet->cell('F'.$i, function($cell) use ($rowData1) {
                        $cell->setValue($rowData1['timeGap']);

                    });
                    $sheet->cell('G'.$i, function($cell) use ($rowData1) {
                        $cell->setValue($rowData1['current']);

                    });

                    //行高
                    $sheet->setHeight($i, 25);

                    $i++;
                }

                // 循环写入数据(2号泵)
                foreach ($excelData['stationStatusList2'] as $rowData2){
                    $sheet->cell('H'.$j, function($cell) use ($rowData2) {
                        $cell->setValue(substr($rowData2['timeStart'],10));

                    });
                    $sheet->cell('I'.$j, function($cell) use ($rowData2) {
                        $cell->setValue(substr($rowData2['timeEnd'],10));

                    });
                    $sheet->cell('J'.$j, function($cell) use ($rowData2) {
                        $cell->setValue($rowData2['timeGap']);

                    });
                    $sheet->cell('K'.$j, function($cell) use ($rowData2) {
                        $cell->setValue($rowData2['current']);

                    });

                    //行高
                    $sheet->setHeight($j, 25);

                    $j++;
                }

                // 循环写入数据(3号泵)
                foreach ($excelData['stationStatusList3'] as $rowData3){
                    $sheet->cell('L'.$k, function($cell) use ($rowData3) {
                        $cell->setValue(substr($rowData3['timeStart'],10));

                    });
                    $sheet->cell('M'.$k, function($cell) use ($rowData3) {
                        $cell->setValue(substr($rowData3['timeEnd'],10));

                    });
                    $sheet->cell('N'.$k, function($cell) use ($rowData3) {
                        $cell->setValue($rowData3['timeGap']);

                    });
                    $sheet->cell('O'.$k, function($cell) use ($rowData3) {
                        $cell->setValue($rowData3['current']);

                    });

                    //行高
                    $sheet->setHeight($k, 25);

                    $k++;
                }

                // 循环写入数据(4号泵)
                foreach ($excelData['stationStatusList4'] as $rowData4){
                    $sheet->cell('P'.$h, function($cell) use ($rowData4) {
                        $cell->setValue(substr($rowData4['timeStart'],10));

                    });
                    $sheet->cell('Q'.$h, function($cell) use ($rowData4) {
                        $cell->setValue(substr($rowData4['timeEnd'],10));

                    });
                    $sheet->cell('R'.$h, function($cell) use ($rowData4) {
                        $cell->setValue($rowData4['timeGap']));

                    });
                    $sheet->cell('S'.$h, function($cell) use ($rowData4) {
                        $cell->setValue($rowData4['current']);

                    });

                    //行高
                    $sheet->setHeight($h, 25);

                    $h++;
                }

//                // 循环写入数据
//                foreach ($excelData['stationStatusList1'] as $rowData1) {
//
//                    $row1 = [
//                        substr($rowData1['timeStart'],10),
//                        substr($rowData1['timeEnd'],10),
//                        $rowData1['timeGap'],
//                        $rowData1['current'],
//                    ];
//
//                    $sheet->row($i, $row1);
//
//                    //行高
//                    $sheet->setHeight($i, 25);
//
//                    $rowTmp1[$i] = $row1;
//                    $rowTmp2[$i] = $row1;
//                    $rowTmp3[$i] = $row1;
//                    $rowTmp4[$i] = $row1;
//
//                    $i++;
//                }
//
//                // 循环写入数据
//                foreach ($excelData['stationStatusList2'] as $rowData2) {
//
//                    $dataColumn1 = '';
//                    $dataColumn2 = '';
//                    $dataColumn3 = '';
//                    $dataColumn4 = '';
//
//                    if(!($i < $j)){
//                        $dataColumn1 = $rowTmp1[$j];
//                        $dataColumn2 = $rowTmp1[$j];
//                        $dataColumn3 = $rowTmp1[$j];
//                        $dataColumn4 = $rowTmp1[$j];
//                    }
//
//                    $row2 = [
//                        $dataColumn1,
//                        $dataColumn2,
//                        $dataColumn3,
//                        $dataColumn4,
//                        substr($rowData2['timeStart'],10),
//                        substr($rowData2['timeEnd'],10),
//                        $rowData2['timeGap'],
//                        $rowData2['current'],
//                    ];
//
//                    $sheet->row($j, $row2);
//
//                    //行高
//                    $sheet->setHeight($j, 25);
//
//                    $rowTmp1[$j] = $row2;
//                    $rowTmp2[$j] = $row2;
//                    $rowTmp3[$j] = $row2;
//                    $rowTmp4[$j] = $row2;
//
//                    $j++;
//                }
//
//                // 循环写入数据
//                foreach ($excelData['stationStatusList3'] as $rowData3) {
//
//                    $dataColumn1 = '';
//                    $dataColumn2 = '';
//                    $dataColumn3 = '';
//                    $dataColumn4 = '';
//
//                    if(!($i < $k)){
//                        $dataColumn1 = $rowTmp1[$k];
//                        $dataColumn2 = $rowTmp1[$k];
//                        $dataColumn3 = $rowTmp1[$k];
//                        $dataColumn4 = $rowTmp1[$k];
//                    }
//
//                    $dataColumn5 = '';
//                    $dataColumn6 = '';
//                    $dataColumn7 = '';
//                    $dataColumn8 = '';
//
//                    if(!($j < $k)){
//                        $dataColumn5 = $rowTmp2[$k];
//                        $dataColumn6 = $rowTmp2[$k];
//                        $dataColumn7 = $rowTmp2[$k];
//                        $dataColumn8 = $rowTmp2[$k];
//                    }
//
//                    $row3 = [
//                        $dataColumn1,
//                        $dataColumn2,
//                        $dataColumn3,
//                        $dataColumn4,
//                        $dataColumn5,
//                        $dataColumn6,
//                        $dataColumn7,
//                        $dataColumn8,
//                        substr($rowData3['timeStart'],10),
//                        substr($rowData3['timeEnd'],10),
//                        $rowData3['timeGap'],
//                        $rowData3['current'],
//                    ];
//
//                    $sheet->row($k, $row3);
//
//                    //行高
//                    $sheet->setHeight($k, 25);
//                    $rowTmp1[$k] = $row3;
//                    $rowTmp2[$k] = $row3;
//                    $rowTmp3[$k] = $row3;
//                    $rowTmp4[$k] = $row3;
//
//                    $k++;
//                }
//
//                // 循环写入数据
//                foreach ($excelData['stationStatusList4'] as $rowData4) {
//
//                    $dataColumn1 = '';
//                    $dataColumn2 = '';
//                    $dataColumn3 = '';
//                    $dataColumn4 = '';
//
//                    if(!($i < $h)){
//                        $dataColumn1 = $rowTmp1[$h];
//                        $dataColumn2 = $rowTmp1[$h];
//                        $dataColumn3 = $rowTmp1[$h];
//                        $dataColumn4 = $rowTmp1[$h];
//                    }
//
//                    $dataColumn5 = '';
//                    $dataColumn6 = '';
//                    $dataColumn7 = '';
//                    $dataColumn8 = '';
//
//                    if(!($j < $h)){
//                        $dataColumn5 = $rowTmp2[$h];
//                        $dataColumn6 = $rowTmp2[$h];
//                        $dataColumn7 = $rowTmp2[$h];
//                        $dataColumn8 = $rowTmp2[$h];
//                    }
//
//                    $dataColumn9 = '';
//                    $dataColumn10 = '';
//                    $dataColumn11 = '';
//                    $dataColumn12 = '';
//
//                    if(!($k < $h)){
//                        $dataColumn9 = $rowTmp3[$h];
//                        $dataColumn10 = $rowTmp3[$h];
//                        $dataColumn11 = $rowTmp3[$h];
//                        $dataColumn12 = $rowTmp3[$h];
//                    }
//
//                    $row4 = [
//                        $dataColumn1,
//                        $dataColumn2,
//                        $dataColumn3,
//                        $dataColumn4,
//                        $dataColumn5,
//                        $dataColumn6,
//                        $dataColumn7,
//                        $dataColumn8,
//                        $dataColumn9,
//                        $dataColumn10,
//                        $dataColumn11,
//                        $dataColumn12,
//                        substr($rowData4['timeStart'],10),
//                        substr($rowData4['timeEnd'],10),
//                        $rowData4['timeGap'],
//                        $rowData4['current'],
//                    ];
//
//                    $sheet->row($h, $row4);
//
//                    //行高
//                    $sheet->setHeight($h, 25);
//                    $rowTmp1[$h] = $row4;
//                    $rowTmp2[$h] = $row4;
//                    $rowTmp3[$h] = $row4;
//                    $rowTmp4[$h] = $row4;
//
//                    $h++;
//                }

                $rowMax = $this->get_max($i,$j,$k,$h);

                //运行合计
                $sheet->row($rowMax, ['运行合计','','',$excelData['totalTimeDay1'],'','','分',$excelData['totalTimeDay2'],'','','分',
                    $excelData['totalTimeDay3'],'','','分',$excelData['totalTimeDay4'],'','','分','总耗电量(度)','','','','']);

                $sheet->row($rowMax+1, ['连前累计运行','','',$excelData['totalTimeBefore1'],'','','小时',$excelData['totalTimeBefore2'],'','','小时',
                    $excelData['totalTimeBefore3'],'','','小时',$excelData['totalTimeBefore4'],'','','小时','电表指数差','','','','']);

                $sheet->row($rowMax+2, ['抽升量','','',$excelData['totalFluxDay1'],'','','万吨',$excelData['totalFluxDay2'],'','','万吨',
                    $excelData['totalFluxDay3'],'','','万吨',$excelData['totalFluxDay4'],'','','万吨','倍率','','','','']);

                $sheet->row($rowMax+3, ['连前累计抽升量','','',$excelData['totalFluxBefore1'],'','','万吨',$excelData['totalFluxBefore2'],'','','万吨',
                    $excelData['totalFluxBefore3'],'','','万吨',$excelData['totalFluxBefore4'],'','','万吨','今日电量','','','','']);

                $sheet->row($rowMax+4, ['电度表读数','','','','','','度','','','','度','','','','度','','','','度','连前累计','','','','']);

                $sheet->row($rowMax+5, ['今日总抽升量','','',$excelData['totalFluxDay'],'','','万吨','值班人员','','白','','','','','','','','','','','','','']);

                $sheet->row($rowMax+6, ['连前累计总抽升量','','',$excelData['totalFluxBefore'],'','','万吨','签名','','黑','','','','','','','','','','','','','','']);

                $sheet->row($rowMax+7, [$rowMax,'','','','','','','校核:']);



                //表体样式
                $sheet->setHeight($rowMax, 25);
                $sheet->setHeight($rowMax+1, 25);
                $sheet->setHeight($rowMax+2, 25);
                $sheet->setHeight($rowMax+3, 25);
                $sheet->setHeight($rowMax+4, 25);
                $sheet->setHeight($rowMax+5, 25);
                $sheet->setHeight($rowMax+6, 25);
                $sheet->setHeight($rowMax+7, 25);

                $sheet->mergeCells('A'.$rowMax.':C'.$rowMax);
                $sheet->mergeCells('D'.$rowMax.':F'.$rowMax);
                $sheet->mergeCells('H'.$rowMax.':J'.$rowMax);
                $sheet->mergeCells('L'.$rowMax.':N'.$rowMax);
                $sheet->mergeCells('P'.$rowMax.':R'.$rowMax);
                $sheet->mergeCells('T'.$rowMax.':X'.$rowMax);

                $sheet->mergeCells('A'.($rowMax+1).':C'.($rowMax+1));
                $sheet->mergeCells('D'.($rowMax+1).':F'.($rowMax+1));
                $sheet->mergeCells('H'.($rowMax+1).':J'.($rowMax+1));
                $sheet->mergeCells('L'.($rowMax+1).':N'.($rowMax+1));
                $sheet->mergeCells('P'.($rowMax+1).':R'.($rowMax+1));
                $sheet->mergeCells('T'.($rowMax+1).':U'.($rowMax+1));
                $sheet->mergeCells('V'.($rowMax+1).':X'.($rowMax+1));

                $sheet->mergeCells('A'.($rowMax+2).':C'.($rowMax+2));
                $sheet->mergeCells('D'.($rowMax+2).':F'.($rowMax+2));
                $sheet->mergeCells('H'.($rowMax+2).':J'.($rowMax+2));
                $sheet->mergeCells('L'.($rowMax+2).':N'.($rowMax+2));
                $sheet->mergeCells('P'.($rowMax+2).':R'.($rowMax+2));
                $sheet->mergeCells('T'.($rowMax+2).':U'.($rowMax+2));
                $sheet->mergeCells('V'.($rowMax+2).':X'.($rowMax+2));

                $sheet->mergeCells('A'.($rowMax+3).':C'.($rowMax+3));
                $sheet->mergeCells('D'.($rowMax+3).':F'.($rowMax+3));
                $sheet->mergeCells('H'.($rowMax+3).':J'.($rowMax+3));
                $sheet->mergeCells('L'.($rowMax+3).':N'.($rowMax+3));
                $sheet->mergeCells('P'.($rowMax+3).':R'.($rowMax+3));
                $sheet->mergeCells('T'.($rowMax+3).':U'.($rowMax+3));
                $sheet->mergeCells('V'.($rowMax+3).':X'.($rowMax+3));

                $sheet->mergeCells('A'.($rowMax+4).':C'.($rowMax+4));
                $sheet->mergeCells('D'.($rowMax+4).':F'.($rowMax+4));
                $sheet->mergeCells('H'.($rowMax+4).':J'.($rowMax+4));
                $sheet->mergeCells('L'.($rowMax+4).':N'.($rowMax+4));
                $sheet->mergeCells('P'.($rowMax+4).':R'.($rowMax+4));
                $sheet->mergeCells('T'.($rowMax+4).':U'.($rowMax+4));
                $sheet->mergeCells('V'.($rowMax+4).':X'.($rowMax+4));

                $sheet->mergeCells('A'.($rowMax+5).':C'.($rowMax+5));
                $sheet->mergeCells('D'.($rowMax+5).':F'.($rowMax+5));
                $sheet->mergeCells('H'.($rowMax+5).':I'.($rowMax+5));
                $sheet->mergeCells('L'.($rowMax+5).':N'.($rowMax+5));
                $sheet->mergeCells('P'.($rowMax+5).':R'.($rowMax+5));
                $sheet->mergeCells('T'.($rowMax+5).':X'.($rowMax+5));

                $sheet->mergeCells('A'.($rowMax+6).':C'.($rowMax+6));
                $sheet->mergeCells('D'.($rowMax+6).':F'.($rowMax+6));
                $sheet->mergeCells('H'.($rowMax+6).':I'.($rowMax+6));
                $sheet->mergeCells('L'.($rowMax+6).':N'.($rowMax+6));
                $sheet->mergeCells('P'.($rowMax+6).':R'.($rowMax+6));
                $sheet->mergeCells('T'.($rowMax+6).':X'.($rowMax+6));

                $sheet->mergeCells('A'.($rowMax+7).':G'.($rowMax+7));
                $sheet->mergeCells('H'.($rowMax+7).':O'.($rowMax+7));

                $sheet->setBorder('A3:X'.($rowMax+6), 'thin');
                $sheet->setAutoSize(true);
                $sheet->setWidth(array(
                    'A'     =>  15,
                    'B'     =>  15,
                    'C'     =>  15,
                    'D'     =>  10,
                    'E'     =>  10,
                    'F'     =>  10,
                    'G'     =>  10,
                    'H'     =>  10,
                    'I'     =>  10,
                    'J'     =>  10,
                    'K'     =>  10,
                    'L'     =>  10,
                    'M'     =>  10,
                    'N'     =>  10,
                    'O'     =>  10,
                    'P'     =>  10,
                    'Q'     =>  10,
                    'R'     =>  10,
                    'S'     =>  10,
                    'T'     =>  10,
                    'U'     =>  10,
                    'V'     =>  10,
                    'W'     =>  10,
                    'X'     =>  10
                ));
                $sheet->cells('A3:X'.($rowMax+7), function($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //表头样式
                $sheet->mergeCells('A3:A4');
                $sheet->mergeCells('B3:B4');
                $sheet->mergeCells('C3:C4');

                $sheet->mergeCells('D3:G3');
                $sheet->mergeCells('H3:K3');
                $sheet->mergeCells('L3:O3');
                $sheet->mergeCells('P3:S3');

                $sheet->mergeCells('T3:U3');
                $sheet->mergeCells('V3:X3');

                $sheet->setHeight(3, 30);
                $sheet->setHeight(4, 30);
                $sheet->cells('A3:X4', function($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //标题样式
                $sheet->mergeCells('A1:X1');
                $sheet->setHeight(1, 60);
                $sheet->cells('A1', function($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(22);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //日期样式
                $sheet->mergeCells('A2:K2');
                $sheet->mergeCells('L2:X2');
                $sheet->setHeight(2, 20);
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
            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了统计信息']);
    }

}
