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
    public function __construct(StationLogic $stationLogic, StationValidation $stationValidation, PumpLogic $pumpLogic)
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

        //连前累计
        $beforeTime = date("2017-10-01");

        $statusReportDay = $this->getStatusReportV3($stationID, $startTime, $endTime);

        $statusReportBefore = $this->getStatusReportV3($stationID, $beforeTime, $endTime);

//        $days = $this->getTheMonthDay($startTime);
//        $startTime = $days[0];

        $param = ['stations' => $statusReportDay['stations'], 'stationSelect' => $statusReportDay['stationSelect'], 'startTime' => $startTime, 'endTime' => $endTime,
            'stationStatusList1' => $statusReportDay['stationStatusList1'], 'stationStatusList2' => $statusReportDay['stationStatusList2'], 'stationStatusList3' => $statusReportDay['stationStatusList3'], 'stationStatusList4' => $statusReportDay['stationStatusList4'], 'stationStatusList5' => $statusReportDay['stationStatusList5'],
            'totalTimeDay1' => $statusReportDay['totalTimeDay1'], 'totalTimeDay2' => $statusReportDay['totalTimeDay2'], 'totalTimeDay3' => $statusReportDay['totalTimeDay3'], 'totalTimeDay4' => $statusReportDay['totalTimeDay4'], 'totalTimeDay5' => $statusReportDay['totalTimeDay5'],
            'totalFluxDay1' => $statusReportDay['totalFluxDay1'], 'totalFluxDay2' => $statusReportDay['totalFluxDay2'], 'totalFluxDay3' => $statusReportDay['totalFluxDay3'], 'totalFluxDay4' => $statusReportDay['totalFluxDay4'], 'totalFluxDay5' => $statusReportDay['totalFluxDay5'],
            'totalTimeDay' => $statusReportDay['totalTimeDay'], 'totalFluxDay' => $statusReportDay['totalFluxDay'],
            'totalTimeBefore1' => round(($statusReportBefore['totalTimeDay1']) / 60, 2), 'totalTimeBefore2' => round(($statusReportBefore['totalTimeDay2']) / 60, 2), 'totalTimeBefore3' => round(($statusReportBefore['totalTimeDay3']) / 60, 2), 'totalTimeBefore4' => round(($statusReportBefore['totalTimeDay4']) / 60, 2), 'totalTimeBefore5' => round(($statusReportBefore['totalTimeDay5']) / 60, 2),
            'totalFluxBefore1' => $statusReportBefore['totalFluxDay1'], 'totalFluxBefore2' => $statusReportBefore['totalFluxDay2'], 'totalFluxBefore3' => $statusReportBefore['totalFluxDay3'], 'totalFluxBefore4' => $statusReportBefore['totalFluxDay4'], 'totalFluxBefore5' => $statusReportBefore['totalFluxDay5'],
            'totalTimeBefore' => round(($statusReportBefore['totalTimeDay']) / 60, 2), 'totalFluxBefore' => $statusReportBefore['totalFluxDay'],
        ];

//        return $param;
        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name, 'log' => '查看了泵站启动状态统计']);

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
//        $selectDay = Input::get('timeStart', '');
//
//        if ($selectDay == '')
//        {
//            $selectDay = date("Y-m-d");
//        }
//        $days = $this->getTheMonthDay($selectDay);
//        $startTime = $days[0];
//        $endTime = $days[1];

        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationListByType($type);

        foreach ($stations as $station) {
            $param = $this->getStatusReportV3($station['id'], $startTime, $endTime);

            //连前累计
            $beforeTime = date("2017-10-01");
            $paramBefore = $this->getStatusReportV3($station['id'], $beforeTime, $endTime);

            //单位小时
            $station['totalTimeDay1'] = round(($param['totalTimeDay1']) / 60, 2);
            $station['totalTimeDay2'] = round(($param['totalTimeDay2']) / 60, 2);
            $station['totalTimeDay3'] = round(($param['totalTimeDay3']) / 60, 2);
            $station['totalTimeDay4'] = round(($param['totalTimeDay4']) / 60, 2);
            $station['totalTimeDay5'] = round(($param['totalTimeDay5']) / 60, 2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];
            $station['totalFluxDay5'] = $param['totalFluxDay5'];

            $station['totalTimeBefore1'] = round(($paramBefore['totalTimeDay1']) / 60, 2);
            $station['totalTimeBefore2'] = round(($paramBefore['totalTimeDay2']) / 60, 2);
            $station['totalTimeBefore3'] = round(($paramBefore['totalTimeDay3']) / 60, 2);
            $station['totalTimeBefore4'] = round(($paramBefore['totalTimeDay4']) / 60, 2);
            $station['totalTimeBefore5'] = round(($paramBefore['totalTimeDay5']) / 60, 2);

            $station['totalFluxBefore1'] = $paramBefore['totalFluxDay1'];
            $station['totalFluxBefore2'] = $paramBefore['totalFluxDay2'];
            $station['totalFluxBefore3'] = $paramBefore['totalFluxDay3'];
            $station['totalFluxBefore4'] = $paramBefore['totalFluxDay4'];
            $station['totalFluxBefore5'] = $paramBefore['totalFluxDay5'];
        }

//        $days = $this->getTheMonthDay($startTime);
//        $startTime = $days[0];

        $paramMonth = ['stations' => $stations, 'selectType' => $type, 'startTime' => $startTime, 'endTime' => $endTime];
        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name, 'log' => '查看了泵站启动状态统计']);

        return view('report.stationStatusMonth', $paramMonth);
    }

    /**
     * 总计泵站总体运行统计
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStatusReportOverAll()
    {
        $type = Input::get('type', '全部');

//        $stationID = Input::get('station_id', 1);

//        $stationSelect = $this->stationInfo($stationID);
//        $stations = $this->stationList();

        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

//        if ($startTime == '' || $endTime == '') {
//            $startTime = date("Y-m-d");
//            $endTime = date("Y-m-d");
//        }

        if ($startTime == '') {
            $startTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';



        $paramOverAll = ['selectType' => $type, 'startTime' => $startTime, 'endTime' => $endTime];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name, 'log' => '查看了泵站启动状态统计']);

        return view('report.stationStatusOverAll', $paramOverAll);
    }

    /**
     * 获取当前月泵站所有泵组运行总计
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStatusReportMonthAll()
    {
        $type = Input::get('type', '全部');
//        $selectDay = Input::get('timeStart', '');
//
//        if ($selectDay == '')
//        {
//            $selectDay = date("Y-m-d");
//        }
//        $days = $this->getTheMonthDay($selectDay);
//        $startTime = $days[0];
//        $endTime = $days[1];

        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationListByType($type);

        $index = 1;

        //所有泵站之和
        $totalTimeDayAll = 0;
        $totalTimeBeforeAll = 0;
        $totalFluxDayAll = 0;
        $totalFluxBeforeAll = 0;

        foreach ($stations as $station) {
            $param = $this->getStatusReportV3($station['id'], $startTime, $endTime);

            //连前累计
            $beforeTime = date("2017-10-01");
            $paramBefore = $this->getStatusReportV3($station['id'], $beforeTime, $endTime);

            //单位小时
            $station['totalTimeDay'] = round(($param['totalTimeDay']) / 60, 2);
            $station['totalFluxDay'] = $param['totalFluxDay'];
            $station['totalTimeBefore'] = round(($paramBefore['totalTimeDay']) / 60, 2);
            $station['totalFluxBefore'] = $paramBefore['totalFluxDay'];
            $totalTimeDayAll += $station['totalTimeDay'];
            $totalTimeBeforeAll += $station['totalTimeBefore'];
            $totalFluxDayAll += $station['totalFluxDay'];
            $totalFluxBeforeAll += $station['totalFluxBefore'];

            $station['index'] = $index;
            $index++;
        }

//        $days = $this->getTheMonthDay($startTime);
//        $startTime = $days[0];

        $paramMonthAll = ['stations' => $stations, 'totalTimeDayAll' => $totalTimeDayAll,
            'totalTimeBeforeAll' => $totalTimeBeforeAll, 'totalFluxDayAll' => $totalFluxDayAll,
            'totalFluxBeforeAll' => $totalFluxBeforeAll, 'selectType' => $type, 'startTime' => $startTime, 'endTime' => $endTime];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name, 'log' => '查看了泵站启动状态统计']);

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
        return array($firstDay, $lastDay);
    }

    /**
     * 运行状态统计
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function getStatusReport($stationID, $startTime, $endTime)
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();
        $pump = $this->pumpInfo($stationID);    // 泵组抽水量信息

        $stationRTList = $this->getStationRTAll($stationID, $startTime, $endTime);

        //********

        $equipmentCode1 = 'yx_b1';
        $equipmentCode2 = 'yx_b2';
        $equipmentCode3 = 'yx_b3';
        $equipmentCode4 = 'yx_b4';
        $equipmentCode5 = 'yx_b5';
        $currentCode1 = 'ib1';
        $currentCode2 = 'ib2';
        $currentCode3 = 'ib3';
        $currentCode4 = 'ib4';
        $currentCode5 = 'ib5';

        //********

        $stationStatusList1 = [];
        $stationStatusList2 = [];
        $stationStatusList3 = [];
        $stationStatusList4 = [];
        $stationStatusList5 = [];
        $index1 = 1;
        $index2 = 1;
        $index3 = 1;
        $index4 = 1;
        $index5 = 1;

        //当日每个泵运行时间合计(分钟)
        $totalTimeDay1 = 0;
        $totalTimeDay2 = 0;
        $totalTimeDay3 = 0;
        $totalTimeDay4 = 0;
        $totalTimeDay5 = 0;
        //当日所有泵运行时间合计(分钟)
        $totalTimeDay = 0;

        //当日每个泵抽升量合计(万吨)
        $totalFluxDay1 = 0.00;
        $totalFluxDay2 = 0.00;
        $totalFluxDay3 = 0.00;
        $totalFluxDay4 = 0.00;
        $totalFluxDay5 = 0.00;
        //当日所有泵抽升量合计(万吨)
        $totalFluxDay = 0.00;

        // 遍历实时运行数据表,找出起泵时刻与停泵时刻
        for ($i = 0; $i < count($stationRTList) - 1; $i++) {
            $sRunning1 = [];
            $sRunning2 = [];
            $sRunning3 = [];
            $sRunning4 = [];
            $sRunning5 = [];

            //1号泵
            if ($stationRTList[$i]->$equipmentCode1 - $stationRTList[$i + 1]->$equipmentCode1 == -1) {
                $sRunning1['timeStart'] = $stationRTList[$i + 1]->Time;
                $sRunning1['current'] = $stationRTList[$i + 1]->$currentCode1;
                $index1++;
                array_push($stationStatusList1, $sRunning1);
            } elseif ($stationRTList[$i]->$equipmentCode1 - $stationRTList[$i + 1]->$equipmentCode1 == 1
                || (($i == (count($stationRTList) - 2) && $stationRTList[$i]->$equipmentCode1 == 1))
            ) {
                $sRunning1['timeEnd'] = $stationRTList[$i + 1]->Time;
                if ($index1 > 1) {
                    $sRunning1['timeGap'] = abs(strtotime($sRunning1['timeEnd']) - strtotime($stationStatusList1[$index1 - 2]['timeStart'])) / 60;
                    $sRunning1['timeGap'] = round($sRunning1['timeGap']);
                    $stationStatusList1[$index1 - 2]['timeEnd'] = $sRunning1['timeEnd'];
                    $stationStatusList1[$index1 - 2]['timeGap'] = $sRunning1['timeGap'];
                    $stationStatusList1[$index1 - 2]['flux'] = $sRunning1['timeGap'] * $pump['flux1'];
                    $stationStatusList1[$index1 - 2]['index'] = $index1 - 1;

                    //运行时间求和
                    $totalTimeDay1 += $sRunning1['timeGap'];
                    //抽升量求和
                    $totalFluxDay1 += ($sRunning1['timeGap'] * $pump['flux1']) / 10000;
                }

            }
//            elseif (($i == (count($stationRTList)-2) && $stationRTList[$i]->$equipmentCode1 == 1))
//            {
//                $sRunning1['timeEnd'] = $stationRTList[$i+1]->Time;
//                if($index1 > 1)
//                {
//                    $sRunning1['timeGap'] = abs(strtotime($sRunning1['timeEnd']) - strtotime($stationStatusList1[$index1 -2]['timeStart']))/60;
//                    $sRunning1['timeGap'] = round($sRunning1['timeGap']);
//                    $stationStatusList1[$index1 -2]['timeEnd'] = $sRunning1['timeEnd'];
//                    $stationStatusList1[$index1 -2]['timeGap'] = $sRunning1['timeGap'];
//                    $stationStatusList1[$index1 -2]['flux'] = $sRunning1['timeGap'] * $pump['flux1'];
//                    $stationStatusList1[$index1 -2]['index'] = $index1 -1;
//
//                    //运行时间求和
//                    $totalTimeDay1 += $sRunning1['timeGap'];
//                    //抽升量求和
//                    $totalFluxDay1 += ($sRunning1['timeGap'] * $pump['flux1'])/10000;
//                }
//            }


            //2号泵
            if ($stationRTList[$i]->$equipmentCode2 - $stationRTList[$i + 1]->$equipmentCode2 == -1) {
                $sRunning2['timeStart'] = $stationRTList[$i + 1]->Time;
                $sRunning2['current'] = $stationRTList[$i + 1]->$currentCode2;
                $index2++;
                array_push($stationStatusList2, $sRunning2);
            } elseif (($stationRTList[$i]->$equipmentCode2 - $stationRTList[$i + 1]->$equipmentCode2 == 1)
                || ($i == (count($stationRTList) - 2) && $stationRTList[$i]->$equipmentCode2 == 1)
            ) {
                $sRunning2['timeEnd'] = $stationRTList[$i + 1]->Time;
                if ($index2 > 1) {
                    $sRunning2['timeGap'] = abs(strtotime($sRunning2['timeEnd']) - strtotime($stationStatusList2[$index2 - 2]['timeStart'])) / 60;
                    $sRunning2['timeGap'] = round($sRunning2['timeGap']);
                    $stationStatusList2[$index2 - 2]['timeEnd'] = $sRunning2['timeEnd'];
                    $stationStatusList2[$index2 - 2]['timeGap'] = $sRunning2['timeGap'];
                    $stationStatusList2[$index2 - 2]['flux'] = $sRunning2['timeGap'] * $pump['flux2'];
                    $stationStatusList2[$index2 - 2]['index'] = $index2 - 1;

                    //运行时间求和
                    $totalTimeDay2 += $sRunning2['timeGap'];
                    //抽升量求和
                    $totalFluxDay2 += ($sRunning2['timeGap'] * $pump['flux2']) / 10000;
                }

            }

            //3号泵
            if ($stationRTList[$i]->$equipmentCode3 - $stationRTList[$i + 1]->$equipmentCode3 == -1) {
                $sRunning3['timeStart'] = $stationRTList[$i + 1]->Time;
                $sRunning3['current'] = $stationRTList[$i + 1]->$currentCode3;
                $index3++;
                array_push($stationStatusList3, $sRunning3);
            } elseif (($stationRTList[$i]->$equipmentCode3 - $stationRTList[$i + 1]->$equipmentCode3 == 1)
                || ($i == (count($stationRTList) - 2) && $stationRTList[$i]->$equipmentCode3 == 1)
            ) {
                $sRunning3['timeEnd'] = $stationRTList[$i + 1]->Time;
                if ($index3 > 1) {
                    $sRunning3['timeGap'] = abs(strtotime($sRunning3['timeEnd']) - strtotime($stationStatusList3[$index3 - 2]['timeStart'])) / 60;
                    $sRunning3['timeGap'] = round($sRunning3['timeGap']);
                    $stationStatusList3[$index3 - 2]['timeEnd'] = $sRunning3['timeEnd'];
                    $stationStatusList3[$index3 - 2]['timeGap'] = $sRunning3['timeGap'];
                    $stationStatusList3[$index3 - 2]['flux'] = $sRunning3['timeGap'] * $pump['flux3'];
                    $stationStatusList3[$index3 - 2]['index'] = $index3 - 1;

                    //运行时间求和
                    $totalTimeDay3 += $sRunning3['timeGap'];
                    //抽升量求和
                    $totalFluxDay3 += ($sRunning3['timeGap'] * $pump['flux3']) / 10000;
                }

            }

            //4号泵
            if ($stationRTList[$i]->$equipmentCode4 - $stationRTList[$i + 1]->$equipmentCode4 == -1) {
                $sRunning4['timeStart'] = $stationRTList[$i + 1]->Time;
                $sRunning4['current'] = $stationRTList[$i + 1]->$currentCode4;
                $index4++;
                array_push($stationStatusList4, $sRunning4);
            } elseif ($stationRTList[$i]->$equipmentCode4 - $stationRTList[$i + 1]->$equipmentCode4 == 1
                || ($i == (count($stationRTList) - 2) && $stationRTList[$i]->$equipmentCode4 == 1)
            ) {
                $sRunning4['timeEnd'] = $stationRTList[$i + 1]->Time;
                if ($index4 > 1) {
                    $sRunning4['timeGap'] = abs(strtotime($sRunning4['timeEnd']) - strtotime($stationStatusList4[$index4 - 2]['timeStart'])) / 60;
                    $sRunning4['timeGap'] = round($sRunning4['timeGap']);
                    $stationStatusList4[$index4 - 2]['timeEnd'] = $sRunning4['timeEnd'];
                    $stationStatusList4[$index4 - 2]['timeGap'] = $sRunning4['timeGap'];
                    $stationStatusList4[$index4 - 2]['flux'] = $sRunning4['timeGap'] * $pump['flux4'];
                    $stationStatusList4[$index4 - 2]['index'] = $index4 - 1;

                    //运行时间求和
                    $totalTimeDay4 += $sRunning4['timeGap'];
                    //抽升量求和
                    $totalFluxDay4 += ($sRunning4['timeGap'] * $pump['flux4']) / 10000;
                }

            }

            //5号泵
            if ($stationTemp['station_number'] == 33) {
                if ($stationRTList[$i]->$equipmentCode5 - $stationRTList[$i + 1]->$equipmentCode5 == -1) {
                    $sRunning5['timeStart'] = $stationRTList[$i + 1]->Time;
                    $sRunning5['current'] = $stationRTList[$i + 1]->$currentCode5;
                    $index5++;
                    array_push($stationStatusList5, $sRunning5);
                } elseif ($stationRTList[$i]->$equipmentCode5 - $stationRTList[$i + 1]->$equipmentCode5 == 1
                    || ($i == (count($stationRTList) - 2) && $stationRTList[$i]->$equipmentCode5 == 1)
                ) {
                    $sRunning5['timeEnd'] = $stationRTList[$i + 1]->Time;
                    if ($index5 > 1) {
                        $sRunning5['timeGap'] = abs(strtotime($sRunning5['timeEnd']) - strtotime($stationStatusList5[$index5 - 2]['timeStart'])) / 60;
                        $sRunning5['timeGap'] = round($sRunning5['timeGap']);
                        $stationStatusList5[$index5 - 2]['timeEnd'] = $sRunning5['timeEnd'];
                        $stationStatusList5[$index5 - 2]['timeGap'] = $sRunning5['timeGap'];
                        $stationStatusList5[$index5 - 2]['flux'] = $sRunning5['timeGap'] * $pump['flux5'];
                        $stationStatusList5[$index5 - 2]['index'] = $index5 - 1;

                        //运行时间求和
                        $totalTimeDay5 += $sRunning5['timeGap'];
                        //抽升量求和
                        $totalFluxDay5 += ($sRunning5['timeGap'] * $pump['flux5']) / 10000;
                    }

                }
            }

        }
        //********

        //当日泵站运行合计(分钟)
        $totalTimeDay = $totalTimeDay1 + $totalTimeDay2 + $totalTimeDay3 + $totalTimeDay4 + $totalTimeDay5;

        //当日泵站总抽升量(万吨)
        $totalFluxDay = $totalFluxDay1 + $totalFluxDay2 + $totalFluxDay3 + $totalFluxDay4 + $totalFluxDay5;


        $param = ['stations' => $stations, 'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime,
            'stationStatusList1' => $stationStatusList1, 'stationStatusList2' => $stationStatusList2,
            'stationStatusList3' => $stationStatusList3, 'stationStatusList4' => $stationStatusList4, 'stationStatusList5' => $stationStatusList5,
            'totalTimeDay1' => $totalTimeDay1, 'totalTimeDay2' => $totalTimeDay2, 'totalTimeDay3' => $totalTimeDay3, 'totalTimeDay4' => $totalTimeDay4, 'totalTimeDay5' => $totalTimeDay5,
            'totalFluxDay1' => $totalFluxDay1, 'totalFluxDay2' => $totalFluxDay2, 'totalFluxDay3' => $totalFluxDay3, 'totalFluxDay4' => $totalFluxDay4, 'totalFluxDay5' => $totalFluxDay5,
            'totalTimeDay' => $totalTimeDay, 'totalFluxDay' => $totalFluxDay,
        ];

        return $param;
    }

    /**
     * 运行状态统计(版本2:优化了数据源查询效率)
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function getStatusReportV2($stationID, $startTime, $endTime)
    {

        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();
        $pump = $this->pumpInfo($stationID);    // 泵组抽水量信息
        $stationNum = $stationTemp['station_number'];    // 泵站编号

        // 1号泵运行实时信息
        $statusListOfPump1 = $this->getStatusMergeList($stationNum, '1', $startTime, $endTime);
        // 2号泵运行实时信息
        $statusListOfPump2 = $this->getStatusMergeList($stationNum, '2', $startTime, $endTime);
        // 3号泵运行实时信息
        $statusListOfPump3 = $this->getStatusMergeList($stationNum, '3', $startTime, $endTime);
        // 4号泵运行实时信息
        $statusListOfPump4 = $this->getStatusMergeList($stationNum, '4', $startTime, $endTime);
        $statusListOfPump5 = [];
        if ($stationTemp['station_number'] == 33) {
            // 5号泵运行实时信息
            $statusListOfPump5 = $this->getStatusMergeList($stationNum, '5', $startTime, $endTime);
        }

        $stationStatusList1 = [];
        $stationStatusList2 = [];
        $stationStatusList3 = [];
        $stationStatusList4 = [];
        $stationStatusList5 = [];

        //当日每个泵运行时间合计(分钟)
        $totalTimeDay1 = 0;
        $totalTimeDay2 = 0;
        $totalTimeDay3 = 0;
        $totalTimeDay4 = 0;
        $totalTimeDay5 = 0;
        //当日所有泵运行时间合计(分钟)
        $totalTimeDay = 0;

        //当日每个泵抽升量合计(万吨)
        $totalFluxDay1 = 0.00;
        $totalFluxDay2 = 0.00;
        $totalFluxDay3 = 0.00;
        $totalFluxDay4 = 0.00;
        $totalFluxDay5 = 0.00;
        //当日所有泵抽升量合计(万吨)
        $totalFluxDay = 0.00;


        /** 遍历1号泵实时数据,并转化为启停数据 **/
        if (count($statusListOfPump1) % 2 == 0) {
            $countList1 = count($statusListOfPump1);
        } else {
            $countList1 = count($statusListOfPump1) - 1;
        }

        for ($i1 = 0; $i1 < $countList1; $i1++) {
            $sRunning1 = [];
            $sRunning1['timeStart'] = $statusListOfPump1[$i1]->Time_tmp;
            $sRunning1['timeEnd'] = $statusListOfPump1[$i1 + 1]->Time_tmp;
            $sRunning1['current'] = $statusListOfPump1[$i1 + 1]->ib1;
            $sRunning1['timeGap'] = abs(strtotime($sRunning1['timeEnd']) - strtotime($sRunning1['timeStart'])) / 60;
            $sRunning1['timeGap'] = round($sRunning1['timeGap']);
            $sRunning1['flux'] = $sRunning1['timeGap'] * $pump['flux1'];
            $sRunning1['index'] = ($i1 + 2) / 2;

            array_push($stationStatusList1, $sRunning1);

            //运行时间求和
            $totalTimeDay1 += $sRunning1['timeGap'];
            //抽升量求和
            $totalFluxDay1 += ($sRunning1['timeGap'] * $pump['flux1']) / 10000;

            $i1++;
        }


        /** 遍历2号泵实时数据,并转化为启停数据 **/
        if (count($statusListOfPump2) % 2 == 0) {
            $countList2 = count($statusListOfPump2);
        } else {
            $countList2 = count($statusListOfPump2) - 1;
        }

        for ($i2 = 0; $i2 < $countList2; $i2++) {
            $sRunning2 = [];
            $sRunning2['timeStart'] = $statusListOfPump2[$i2]->Time_tmp;
            $sRunning2['timeEnd'] = $statusListOfPump2[$i2 + 1]->Time_tmp;
            $sRunning2['current'] = $statusListOfPump2[$i2 + 1]->ib2;
            $sRunning2['timeGap'] = abs(strtotime($sRunning2['timeEnd']) - strtotime($sRunning2['timeStart'])) / 60;
            $sRunning2['timeGap'] = round($sRunning2['timeGap']);
            $sRunning2['flux'] = $sRunning2['timeGap'] * $pump['flux2'];
            $sRunning2['index'] = ($i2 + 2) / 2;

            array_push($stationStatusList2, $sRunning2);

            //运行时间求和
            $totalTimeDay2 += $sRunning2['timeGap'];
            //抽升量求和
            $totalFluxDay2 += ($sRunning2['timeGap'] * $pump['flux2']) / 10000;

            $i2++;
        }

        /** 遍历3号泵实时数据,并转化为启停数据 **/
        if (count($statusListOfPump3) % 2 == 0) {
            $countList3 = count($statusListOfPump3);
        } else {
            $countList3 = count($statusListOfPump3) - 1;
        }

        for ($i3 = 0; $i3 < $countList3; $i3++) {
            $sRunning3 = [];
            $sRunning3['timeStart'] = $statusListOfPump3[$i3]->Time_tmp;
            $sRunning3['timeEnd'] = $statusListOfPump3[$i3 + 1]->Time_tmp;
            $sRunning3['current'] = $statusListOfPump3[$i3 + 1]->ib3;
            $sRunning3['timeGap'] = abs(strtotime($sRunning3['timeEnd']) - strtotime($sRunning3['timeStart'])) / 60;
            $sRunning3['timeGap'] = round($sRunning3['timeGap']);
            $sRunning3['flux'] = $sRunning3['timeGap'] * $pump['flux3'];
            $sRunning3['index'] = ($i3 + 2) / 2;

            array_push($stationStatusList3, $sRunning3);

            //运行时间求和
            $totalTimeDay3 += $sRunning3['timeGap'];
            //抽升量求和
            $totalFluxDay3 += ($sRunning3['timeGap'] * $pump['flux3']) / 10000;

            $i3++;
        }

        /** 遍历4号泵实时数据,并转化为启停数据 **/
        if (count($statusListOfPump4) % 2 == 0) {
            $countList4 = count($statusListOfPump4);
        } else {
            $countList4 = count($statusListOfPump4) - 1;
        }

        for ($i4 = 0; $i4 < $countList4; $i4++) {
            $sRunning4 = [];
            $sRunning4['timeStart'] = $statusListOfPump4[$i4]->Time_tmp;
            $sRunning4['timeEnd'] = $statusListOfPump4[$i4 + 1]->Time_tmp;
            $sRunning4['current'] = $statusListOfPump4[$i4 + 1]->ib4;
            $sRunning4['timeGap'] = abs(strtotime($sRunning4['timeEnd']) - strtotime($sRunning4['timeStart'])) / 60;
            $sRunning4['timeGap'] = round($sRunning4['timeGap']);
            $sRunning4['flux'] = $sRunning4['timeGap'] * $pump['flux4'];
            $sRunning4['index'] = ($i4 + 2) / 2;

            array_push($stationStatusList4, $sRunning4);

            //运行时间求和
            $totalTimeDay4 += $sRunning4['timeGap'];
            //抽升量求和
            $totalFluxDay4 += ($sRunning4['timeGap'] * $pump['flux4']) / 10000;

            $i4++;
        }

        /** 遍历5号泵实时数据,并转化为启停数据 **/
        if ($stationTemp['station_number'] == 33) {
            /** 遍历5号泵实时数据,并转化为启停数据 **/
            if (count($statusListOfPump5) % 2 == 0) {
                $countList5 = count($statusListOfPump5);
            } else {
                $countList5 = count($statusListOfPump5) - 1;
            }

            for ($i5 = 0; $i5 < $countList5; $i5++) {
                $sRunning5 = [];
                $sRunning5['timeStart'] = $statusListOfPump5[$i5]->Time_tmp;
                $sRunning5['timeEnd'] = $statusListOfPump5[$i5 + 1]->Time_tmp;
                $sRunning5['current'] = $statusListOfPump5[$i5 + 1]->ib5;
                $sRunning5['timeGap'] = abs(strtotime($sRunning5['timeEnd']) - strtotime($sRunning5['timeStart'])) / 60;
                $sRunning5['timeGap'] = round($sRunning5['timeGap']);
                $sRunning5['flux'] = $sRunning5['timeGap'] * $pump['flux5'];
                $sRunning5['index'] = ($i5 + 2) / 2;

                array_push($stationStatusList5, $sRunning5);

                //运行时间求和
                $totalTimeDay5 += $sRunning5['timeGap'];
                //抽升量求和
                $totalFluxDay5 += ($sRunning5['timeGap'] * $pump['flux5']) / 10000;

                $i5++;
            }
        }

        //当日泵站运行合计(分钟)
        $totalTimeDay = $totalTimeDay1 + $totalTimeDay2 + $totalTimeDay3 + $totalTimeDay4 + $totalTimeDay5;

        //当日泵站总抽升量(万吨)
        $totalFluxDay = $totalFluxDay1 + $totalFluxDay2 + $totalFluxDay3 + $totalFluxDay4 + $totalFluxDay5;


        $param = ['stations' => $stations, 'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime,
            'stationStatusList1' => $stationStatusList1, 'stationStatusList2' => $stationStatusList2,
            'stationStatusList3' => $stationStatusList3, 'stationStatusList4' => $stationStatusList4, 'stationStatusList5' => $stationStatusList5,
            'totalTimeDay1' => $totalTimeDay1, 'totalTimeDay2' => $totalTimeDay2, 'totalTimeDay3' => $totalTimeDay3, 'totalTimeDay4' => $totalTimeDay4, 'totalTimeDay5' => $totalTimeDay5,
            'totalFluxDay1' => $totalFluxDay1, 'totalFluxDay2' => $totalFluxDay2, 'totalFluxDay3' => $totalFluxDay3, 'totalFluxDay4' => $totalFluxDay4, 'totalFluxDay5' => $totalFluxDay5,
            'totalTimeDay' => $totalTimeDay, 'totalFluxDay' => $totalFluxDay,
        ];

        return $param;
    }

    /**
     * 运行状态统计(版本3:读取拆分的运行表数据stationRTYX)
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function getStatusReportV3($stationID, $startTime, $endTime)
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();
        $pump = $this->pumpInfo($stationID);    // 泵组抽水量信息
        $stationNum = $stationTemp['station_number'];    // 泵站编号

        $todayTime = date("Y-m-d H:i:s");

        $statusYXList = $this->getStatusYXList($stationNum, $startTime, $endTime);

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

        $currentCode1 = 'ib1';
        $currentCode2 = 'ib2';
        $currentCode3 = 'ib3';
        $currentCode4 = 'ib4';
        $currentCode5 = 'ib5';

        $stationStatusList1 = [];
        $stationStatusList2 = [];
        $stationStatusList3 = [];
        $stationStatusList4 = [];
        $stationStatusList5 = [];
        $index1 = 0;
        $index2 = 0;
        $index3 = 0;
        $index4 = 0;
        $index5 = 0;

        //当日每个泵运行时间合计(分钟)
        $totalTimeDay1 = 0;
        $totalTimeDay2 = 0;
        $totalTimeDay3 = 0;
        $totalTimeDay4 = 0;
        $totalTimeDay5 = 0;
        //当日所有泵运行时间合计(分钟)
        $totalTimeDay = 0;

        //当日每个泵抽升量合计(万吨)
        $totalFluxDay1 = 0.00;
        $totalFluxDay2 = 0.00;
        $totalFluxDay3 = 0.00;
        $totalFluxDay4 = 0.00;
        $totalFluxDay5 = 0.00;
        //当日所有泵抽升量合计(万吨)
        $totalFluxDay = 0.00;

        $initialCurrent = 5;

        //8号泵(化工路立交)
        if($stationID == 8)
        {
            $initialCurrent = 10;
        }

        // 遍历实时运行数据表,找出起泵时刻与停泵时刻
        for ($i = 0; $i < count($statusYXList); $i++) {
            $sRunning1 = [];
            $sRunning2 = [];
            $sRunning3 = [];
            $sRunning4 = [];
            $sRunning5 = [];

            //1号泵
            if ($i == 0) {
                if ($statusYXList[$i]->$currentCode1 > $initialCurrent) {
                    $sRunning1['timeStart'] = $statusYXList[$i]->Time;
                    $index1++;
                    array_push($stationStatusList1, $sRunning1);
                }
            } else {
                if ($statusYXList[$i]->$currentCode1 > $initialCurrent && $statusYXList[$i - 1]->$currentCode1 <= $initialCurrent) {
                    $sRunning1['timeStart'] = $statusYXList[$i]->Time;
                    $index1++;
                    array_push($stationStatusList1, $sRunning1);

                } elseif ($i == (count($statusYXList) - 1) && $statusYXList[$i]->$currentCode1 > $initialCurrent) {
//                    array_pop($stationStatusList1);
                } elseif ($statusYXList[$i]->$currentCode1 <= $initialCurrent && $statusYXList[$i - 1]->$currentCode1 > $initialCurrent) {
                    $sRunning1['timeEnd'] = $statusYXList[$i]->Time;
                    $sRunning1['current'] = $statusYXList[$i - 1]->$currentCode1;

                    if ($index1 > 0) {
                        $sRunning1['timeGap'] = abs(strtotime($sRunning1['timeEnd']) - strtotime($stationStatusList1[$index1 - 1]['timeStart'])) / 60;
                        $sRunning1['timeGap'] = round($sRunning1['timeGap']);
                        $stationStatusList1[$index1 - 1]['timeEnd'] = $sRunning1['timeEnd'];
                        $stationStatusList1[$index1 - 1]['timeGap'] = $sRunning1['timeGap'];
                        $stationStatusList1[$index1 - 1]['current'] = $sRunning1['current'];
                        $stationStatusList1[$index1 - 1]['flux'] = $sRunning1['timeGap'] * $pump['flux1'];
                        $stationStatusList1[$index1 - 1]['index'] = $index1;

                        //运行时间求和
                        $totalTimeDay1 += $sRunning1['timeGap'];
                        //抽升量求和
                        $totalFluxDay1 += ($sRunning1['timeGap'] * $pump['flux1']) / 10000;
                    }

                }
            }

            //2号泵
            if ($i == 0) {
                if ($statusYXList[$i]->$currentCode2 > $initialCurrent) {
                    $sRunning2['timeStart'] = $statusYXList[$i]->Time;
                    $index2++;
                    array_push($stationStatusList2, $sRunning2);
                }
            } else {
                if ($statusYXList[$i]->$currentCode2 > $initialCurrent && $statusYXList[$i - 1]->$currentCode2 <= $initialCurrent) {

                    $sRunning2['timeStart'] = $statusYXList[$i]->Time;
                    $index2++;
                    array_push($stationStatusList2, $sRunning2);

                } elseif ($i == (count($statusYXList) - 1) && $statusYXList[$i]->$currentCode2 > $initialCurrent) {
//                    array_pop($stationStatusList2);
                } elseif ($statusYXList[$i]->$currentCode2 <= $initialCurrent && $statusYXList[$i - 1]->$currentCode2 > $initialCurrent) {
                    $sRunning2['timeEnd'] = $statusYXList[$i]->Time;
                    $sRunning2['current'] = $statusYXList[$i - 1]->$currentCode2;

                    if ($index2 > 0) {
                        $sRunning2['timeGap'] = abs(strtotime($sRunning2['timeEnd']) - strtotime($stationStatusList2[$index2 - 1]['timeStart'])) / 60;
                        $sRunning2['timeGap'] = round($sRunning2['timeGap']);
                        $stationStatusList2[$index2 - 1]['timeEnd'] = $sRunning2['timeEnd'];
                        $stationStatusList2[$index2 - 1]['timeGap'] = $sRunning2['timeGap'];
                        $stationStatusList2[$index2 - 1]['current'] = $sRunning2['current'];
                        $stationStatusList2[$index2 - 1]['flux'] = $sRunning2['timeGap'] * $pump['flux2'];
                        $stationStatusList2[$index2 - 1]['index'] = $index2;

                        //运行时间求和
                        $totalTimeDay2 += $sRunning2['timeGap'];
                        //抽升量求和
                        $totalFluxDay2 += ($sRunning2['timeGap'] * $pump['flux2']) / 10000;
                    }

                }
            }

            if ($has3Pump || $has4Pump || $has5Pump) {
                //3号泵
                if ($i == 0) {
                    if ($statusYXList[$i]->$currentCode3 > $initialCurrent) {
                        $sRunning3['timeStart'] = $statusYXList[$i]->Time;
                        $index3++;
                        array_push($stationStatusList3, $sRunning3);
                    }
                } else {
                    if ($statusYXList[$i]->$currentCode3 > $initialCurrent && $statusYXList[$i - 1]->$currentCode3 <= $initialCurrent) {
                        $sRunning3['timeStart'] = $statusYXList[$i]->Time;
                        $index3++;
                        array_push($stationStatusList3, $sRunning3);

                    } elseif ($i == (count($statusYXList) - 1) && $statusYXList[$i]->$currentCode3 > $initialCurrent) {
//                        array_pop($stationStatusList3);
                    } elseif ($statusYXList[$i]->$currentCode3 <= $initialCurrent && $statusYXList[$i - 1]->$currentCode3 > $initialCurrent) {
                        $sRunning3['timeEnd'] = $statusYXList[$i]->Time;
                        $sRunning3['current'] = $statusYXList[$i - 1]->$currentCode3;

                        if ($index3 > 0) {
                            $sRunning3['timeGap'] = abs(strtotime($sRunning3['timeEnd']) - strtotime($stationStatusList3[$index3 - 1]['timeStart'])) / 60;
                            $sRunning3['timeGap'] = round($sRunning3['timeGap']);
                            $stationStatusList3[$index3 - 1]['timeEnd'] = $sRunning3['timeEnd'];
                            $stationStatusList3[$index3 - 1]['timeGap'] = $sRunning3['timeGap'];
                            $stationStatusList3[$index3 - 1]['current'] = $sRunning3['current'];
                            $stationStatusList3[$index3 - 1]['flux'] = $sRunning3['timeGap'] * $pump['flux3'];
                            $stationStatusList3[$index3 - 1]['index'] = $index3;

                            //运行时间求和
                            $totalTimeDay3 += $sRunning3['timeGap'];
                            //抽升量求和
                            $totalFluxDay3 += ($sRunning3['timeGap'] * $pump['flux3']) / 10000;
                        }

                    }
                }
            }

            if ($has4Pump || $has5Pump) {
                //4号泵
                if ($i == 0) {
                    if ($statusYXList[$i]->$currentCode4 > $initialCurrent) {
                        $sRunning4['timeStart'] = $statusYXList[$i]->Time;
                        $index4++;
                        array_push($stationStatusList4, $sRunning4);
                    }
                } else {
                    if ($statusYXList[$i]->$currentCode4 > $initialCurrent && $statusYXList[$i - 1]->$currentCode4 <= $initialCurrent) {
                        $sRunning4['timeStart'] = $statusYXList[$i]->Time;
                        $index4++;
                        array_push($stationStatusList4, $sRunning4);

                    } elseif ($i == (count($statusYXList) - 1) && $statusYXList[$i]->$currentCode4 > 10) {
//                        array_pop($stationStatusList4);
                    } elseif ($statusYXList[$i]->$currentCode4 <= $initialCurrent && $statusYXList[$i - 1]->$currentCode4 > $initialCurrent) {
                        $sRunning4['timeEnd'] = $statusYXList[$i]->Time;
                        $sRunning4['current'] = $statusYXList[$i - 1]->$currentCode4;

                        if ($index4 > 0) {
                            $sRunning4['timeGap'] = abs(strtotime($sRunning4['timeEnd']) - strtotime($stationStatusList4[$index4 - 1]['timeStart'])) / 60;
                            $sRunning4['timeGap'] = round($sRunning4['timeGap']);
                            $stationStatusList4[$index4 - 1]['timeEnd'] = $sRunning4['timeEnd'];
                            $stationStatusList4[$index4 - 1]['timeGap'] = $sRunning4['timeGap'];
                            $stationStatusList4[$index4 - 1]['current'] = $sRunning4['current'];
                            $stationStatusList4[$index4 - 1]['flux'] = $sRunning4['timeGap'] * $pump['flux4'];
                            $stationStatusList4[$index4 - 1]['index'] = $index4;

                            //运行时间求和
                            $totalTimeDay4 += $sRunning4['timeGap'];
                            //抽升量求和
                            $totalFluxDay4 += ($sRunning4['timeGap'] * $pump['flux4']) / 10000;
                        }

                    }
                }
            }

            if ($has5Pump) {
                //5号泵
                if ($i == 0) {
                    if ($statusYXList[$i]->$currentCode5 > $initialCurrent) {
                        $sRunning5['timeStart'] = $statusYXList[$i]->Time;
                        $index5++;
                        array_push($stationStatusList5, $sRunning5);
                    }
                } else {
                    if ($statusYXList[$i]->$currentCode5 > $initialCurrent && $statusYXList[$i - 1]->$currentCode5 <= $initialCurrent) {
                        $sRunning5['timeStart'] = $statusYXList[$i]->Time;
                        $index5++;
                        array_push($stationStatusList5, $sRunning5);
                    } elseif ($i == (count($statusYXList) - 1) && $statusYXList[$i]->$currentCode5 > $initialCurrent) {
//                        array_pop($stationStatusList5);
                    } elseif ($statusYXList[$i]->$currentCode5 <= $initialCurrent && $statusYXList[$i - 1]->$currentCode5 > $initialCurrent) {

                        $sRunning5['timeEnd'] = $statusYXList[$i]->Time;
                        $sRunning5['current'] = $statusYXList[$i - 1]->$currentCode5;

                        if ($index5 > 0) {
                            $sRunning5['timeGap'] = abs(strtotime($sRunning5['timeEnd']) - strtotime($stationStatusList5[$index5 - 1]['timeStart'])) / 60;
                            $sRunning5['timeGap'] = round($sRunning5['timeGap']);
                            $stationStatusList5[$index5 - 1]['timeEnd'] = $sRunning5['timeEnd'];
                            $stationStatusList5[$index5 - 1]['timeGap'] = $sRunning5['timeGap'];
                            $stationStatusList5[$index5 - 1]['current'] = $sRunning5['current'];
                            $stationStatusList5[$index5 - 1]['flux'] = $sRunning5['timeGap'] * $pump['flux5'];
                            $stationStatusList5[$index5 - 1]['index'] = $index5;

                            //运行时间求和
                            $totalTimeDay5 += $sRunning5['timeGap'];
                            //抽升量求和
                            $totalFluxDay5 += ($sRunning5['timeGap'] * $pump['flux5']) / 10000;
                        }

                    }
                }
            }
        }

        //当泵组正在运行中或者跨天运行时的逻辑
        if(count($stationStatusList1) > 0 && !isset(end($stationStatusList1)['timeEnd']))
        {
            $indexEnd = count($stationStatusList1) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList1[$indexEnd]['timeEnd'] = $todayTime;
            }
            else
            {
                $stationStatusList1[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
            }

            $endTimeGap = abs(strtotime($stationStatusList1[$indexEnd]['timeEnd']) - strtotime($stationStatusList1[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap);
            $stationStatusList1[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList1[$indexEnd]['current'] = 57;
            $stationStatusList1[$indexEnd]['flux'] = $endTimeGap * $pump['flux1'];

            if($indexEnd == 0)
            {
                $stationStatusList1[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList1[$indexEnd]['index'] = $stationStatusList1[$indexEnd-1]['index'] + 1;
            }
        }

        if(count($stationStatusList2) > 0 && !isset(end($stationStatusList2)['timeEnd']))
        {
            $indexEnd = count($stationStatusList2) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList2[$indexEnd]['timeEnd'] = $todayTime;
            }
            else
            {
                $stationStatusList2[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
            }

            $endTimeGap = abs(strtotime($stationStatusList2[$indexEnd]['timeEnd']) - strtotime($stationStatusList2[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap);
            $stationStatusList2[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList2[$indexEnd]['current'] = 57;
            $stationStatusList2[$indexEnd]['flux'] = $endTimeGap * $pump['flux2'];

            if($indexEnd == 0)
            {
                $stationStatusList2[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList2[$indexEnd]['index'] = $stationStatusList2[$indexEnd-1]['index'] + 1;
            }
        }

        if(count($stationStatusList3) > 0 && !isset(end($stationStatusList3)['timeEnd']))
        {
            $indexEnd = count($stationStatusList3) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList3[$indexEnd]['timeEnd'] = $todayTime;
            }
            else
            {
                $stationStatusList3[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
            }

            $endTimeGap = abs(strtotime($stationStatusList3[$indexEnd]['timeEnd']) - strtotime($stationStatusList3[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap);
            $stationStatusList3[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList3[$indexEnd]['current'] = 57;
            $stationStatusList3[$indexEnd]['flux'] = $endTimeGap * $pump['flux3'];

            if($indexEnd == 0)
            {
                $stationStatusList3[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList3[$indexEnd]['index'] = $stationStatusList3[$indexEnd-1]['index'] + 1;
            }
        }

        if(count($stationStatusList4) > 0 && !isset(end($stationStatusList4)['timeEnd']))
        {
            $indexEnd = count($stationStatusList4) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList4[$indexEnd]['timeEnd'] = $todayTime;
            }
            else
            {
                $stationStatusList4[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
            }

            $endTimeGap = abs(strtotime($stationStatusList4[$indexEnd]['timeEnd']) - strtotime($stationStatusList4[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap);
            $stationStatusList4[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList4[$indexEnd]['current'] = 57;
            $stationStatusList4[$indexEnd]['flux'] = $endTimeGap * $pump['flux4'];

            if($indexEnd == 0)
            {
                $stationStatusList4[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList4[$indexEnd]['index'] = $stationStatusList4[$indexEnd-1]['index'] + 1;
            }
        }

        if(count($stationStatusList5) > 0 && !isset(end($stationStatusList5)['timeEnd']))
        {
            $indexEnd = count($stationStatusList5) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList5[$indexEnd]['timeEnd'] = $todayTime;
            }
            else
            {
                $stationStatusList5[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
            }

            $endTimeGap = abs(strtotime($stationStatusList5[$indexEnd]['timeEnd']) - strtotime($stationStatusList5[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap);
            $stationStatusList5[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList5[$indexEnd]['current'] = 57;
            $stationStatusList5[$indexEnd]['flux'] = $endTimeGap * $pump['flux5'];

            if($indexEnd == 0)
            {
                $stationStatusList5[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList5[$indexEnd]['index'] = $stationStatusList5[$indexEnd-1]['index'] + 1;
            }
        }

        //当日泵站运行合计(分钟)
        $totalTimeDay = $totalTimeDay1 + $totalTimeDay2 + $totalTimeDay3 + $totalTimeDay4 + $totalTimeDay5;

        //当日泵站总抽升量(万吨)
        $totalFluxDay = $totalFluxDay1 + $totalFluxDay2 + $totalFluxDay3 + $totalFluxDay4 + $totalFluxDay5;


        $param = ['stations' => $stations, 'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime,
            'stationStatusList1' => $stationStatusList1, 'stationStatusList2' => $stationStatusList2,
            'stationStatusList3' => $stationStatusList3, 'stationStatusList4' => $stationStatusList4, 'stationStatusList5' => $stationStatusList5,
            'totalTimeDay1' => $totalTimeDay1, 'totalTimeDay2' => $totalTimeDay2, 'totalTimeDay3' => $totalTimeDay3, 'totalTimeDay4' => $totalTimeDay4, 'totalTimeDay5' => $totalTimeDay5,
            'totalFluxDay1' => $totalFluxDay1, 'totalFluxDay2' => $totalFluxDay2, 'totalFluxDay3' => $totalFluxDay3, 'totalFluxDay4' => $totalFluxDay4, 'totalFluxDay5' => $totalFluxDay5,
            'totalTimeDay' => $totalTimeDay, 'totalFluxDay' => $totalFluxDay,
        ];

        return $param;
    }

    public function isSameDay($firstTime,$secondTime)
    {
        if(date('m', strtotime($firstTime)) == date('m', strtotime($secondTime)) && date('d', strtotime($firstTime)) == date('d', strtotime($secondTime)))
        {
            return true;
        }
        else
        {
            return false;
        }
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
        for ($i = 0; $i < count($statusList) - 1; $i++) {
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

        for ($i = 0; $i < count($statusList) - 1; $i++) {
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
        if ($type == "全部") {
            $conditions = ['delete_process' => 0];
        } else {
            $conditions = ['delete_process' => 0, 'type' => $type];
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
        $pumps = $this->pumpLogic->getPumpsByStation($stationID, 1, 'updated_at', 'desc', null);
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
    public function getStationRTAll($stationID, $startTime, $endTime)
    {
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationTable = "stationRT_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        if (!empty($searchStartTime) && !empty($searchEndTime)) {
            $stationRTList = DB::table($stationTable)->whereBetween('Time', [$searchStartTime, $searchEndTime])->orderBy('Time', 'asc')
                ->get();
//            $stationRTList = DB::select('SELECT * from (Select *,(@rowNum:=@rowNum+1) as rowNo From '.$stationTable.', (Select (@rowNum :=0) ) b where Time > ? and Time < ?) as a where mod(a.rowNo, 10) = 1',[$searchStartTime,$searchEndTime])
//            ;

//            select * from (select rank() over(order by HTAH01A060) as rank_sort,* from table) as a where a.rank_sort%4 = 0

        } else {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
                ->get();
//            $stationRTList = DB::select('SELECT * from (Select *,(@rowNum:=@rowNum+1) as rowNo From '.$stationTable.', (Select (@rowNum :=0) ) b ) as a where mod(a.rowNo, 10) = 1')
//            ;
        }

        return $stationRTList;
    }

    public function getStatusMergeList($stationNum, $pumpNum, $startTime, $endTime)
    {
        $stationTable = "stationRT_" . $stationNum;
        $statusCode = "yx_b" . $pumpNum;
        $currentCode = "ib" . $pumpNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationRTList = DB::select('SELECT a.Time,a.' . $statusCode . ',a.' . $currentCode . ',a.rowNo,a1.Time AS Time_tmp,a1.' . $statusCode . ' AS ' . $statusCode . '_tmp,
        a1.' . $currentCode . ' AS ' . $currentCode . '_tmp,a1.rowNo1 From (Select Time,' . $statusCode . ',' . $currentCode . ',(@rowNum:=@rowNum+1) as rowNo From ' . $stationTable . ' , (Select (@rowNum :=0) ) b WHERE Time > ? and Time < ?) as a 
         LEFT JOIN (SELECT a1.Time,a1.' . $statusCode . ',a1.' . $currentCode . ',a1.rowNo1 From (Select Time,' . $statusCode . ',' . $currentCode . ',(@rowNum1:=@rowNum1+1) as rowNo1 From ' . $stationTable . ' , 
         (Select (@rowNum1 :=0) ) b1 WHERE Time > ? and Time < ?)AS a1)AS a1  on a.rowNo +1 = a1.rowNo1 WHERE (a.' . $statusCode . ' - a1.' . $statusCode . ') = 1 || (a.' . $statusCode . ' - a1.' . $statusCode . ') = -1', [$searchStartTime, $searchEndTime, $searchStartTime, $searchEndTime]);

        return $stationRTList;
    }

    public function getStatusYXList($stationNum, $startTime, $endTime)
    {
        $statusTable = "stationRTYX_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationRTYXList = DB::table($statusTable)->whereBetween('Time', [$searchStartTime, $searchEndTime])->orderBy('Time', 'asc')
            ->get();

        return $stationRTYXList;
    }

    /**
     * Ajax查询泵站实时数据
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function stationRTHistoryAjax($stationID, $startTime, $endTime)
    {
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationTable = "stationRT_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';


        if (!empty($searchStartTime) && !empty($searchEndTime)) {
            $stationRTList = DB::table($stationTable)->whereBetween('Time', [$searchStartTime, $searchEndTime])->orderBy('Time', 'asc')
                ->get();

        } else {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
                ->get();
        }

        return response()->json(array('stationRTHistory' => $stationRTList), 200);
    }

    /**
     * Ajax查询泵站运行状态实时记录
     *
     * @param $stationID
     * @param $startTime
     * @param $endTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusRTHistoryAjax($stationID, $startTime)
    {
        $endTime = $startTime;

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $statusReportDay = $this->getStatusReportV3($stationID, $startTime, $endTime);

        $param = array('stationStatusList1' => $statusReportDay['stationStatusList1'], 'stationStatusList2' => $statusReportDay['stationStatusList2'],
            'stationStatusList3' => $statusReportDay['stationStatusList3'], 'stationStatusList4' => $statusReportDay['stationStatusList4'],
            'stationStatusList5' => $statusReportDay['stationStatusList5']);

        return response()->json($param, 200);
    }

    /**
     * 按月查询单机运行时间Ajax
     *
     * @param $type
     * @param $startTime
     * @param $endTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusRTMonthAjax($type, $startTime, $endTime)
    {
//        if ($selectDay == '')
//        {
//            $selectDay = date("Y-m-d");
//        }
//        $days = $this->getTheMonthDay($selectDay);
//        $startTime = $days[0];
//        $endTime = $days[1];

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationListByType($type);

        foreach ($stations as $station) {
            $param = $this->getStatusReportV3($station['id'], $startTime, $endTime);

            //连前累计
            $beforeTime = date("2017-10-01");
            $paramBefore = $this->getStatusReportV3($station['id'], $beforeTime, $endTime);

            //单位小时
            $station['totalTimeDay1'] = round(($param['totalTimeDay1']) / 60, 2);
            $station['totalTimeDay2'] = round(($param['totalTimeDay2']) / 60, 2);
            $station['totalTimeDay3'] = round(($param['totalTimeDay3']) / 60, 2);
            $station['totalTimeDay4'] = round(($param['totalTimeDay4']) / 60, 2);
            $station['totalTimeDay5'] = round(($param['totalTimeDay5']) / 60, 2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];
            $station['totalFluxDay5'] = $param['totalFluxDay5'];

            $station['totalTimeBefore1'] = round(($paramBefore['totalTimeDay1']) / 60, 2);
            $station['totalTimeBefore2'] = round(($paramBefore['totalTimeDay2']) / 60, 2);
            $station['totalTimeBefore3'] = round(($paramBefore['totalTimeDay3']) / 60, 2);
            $station['totalTimeBefore4'] = round(($paramBefore['totalTimeDay4']) / 60, 2);
            $station['totalTimeBefore5'] = round(($paramBefore['totalTimeDay5']) / 60, 2);

            $station['totalFluxBefore1'] = $paramBefore['totalFluxDay1'];
            $station['totalFluxBefore2'] = $paramBefore['totalFluxDay2'];
            $station['totalFluxBefore3'] = $paramBefore['totalFluxDay3'];
            $station['totalFluxBefore4'] = $paramBefore['totalFluxDay4'];
            $station['totalFluxBefore5'] = $paramBefore['totalFluxDay5'];
        }
//        $paramMonth = ['stations' => $stations, 'selectType' => $type, 'startTime' => $startTime];
        return response()->json(array('stations' => $stations), 200);
    }

    /**
     * 按月查询泵站所有泵组运行时间Ajax
     *
     * @param $type
     * @param $startTime
     * @param $endTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusRTMonthAllAjax($type, $startTime, $endTime)
    {
//        if ($selectDay == '')
//        {
//            $selectDay = date("Y-m-d");
//        }
//        $days = $this->getTheMonthDay($selectDay);
//        $startTime = $days[0];
//        $endTime = $days[1];

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationListByType($type);

        foreach ($stations as $station) {
            $param = $this->getStatusReportV3($station['id'], $startTime, $endTime);

            //连前累计
            $beforeTime = date("2017-10-01");
            $paramBefore = $this->getStatusReportV3($station['id'], $beforeTime, $endTime);

            //单位小时
            $station['totalTimeDay'] = round(($param['totalTimeDay']) / 60, 2);
            $station['totalFluxDay'] = $param['totalFluxDay'];
            $station['totalTimeBefore'] = round(($paramBefore['totalTimeDay']) / 60, 2);
            $station['totalFluxBefore'] = $paramBefore['totalFluxDay'];
        }
//        $paramMonth = ['stations' => $stations, 'selectType' => $type, 'startTime' => $startTime];
        return response()->json(array('stations' => $stations), 200);
    }

    /**
     * 查询泵站所有泵组整体运行时间Ajax
     *
     * @param $type
     * @param $startTime
     * @param $endTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusRTOverAllAjax($type, $startTime, $endTime)
    {
//        if ($selectDay == '')
//        {
//            $selectDay = date("Y-m-d");
//        }
//        $days = $this->getTheMonthDay($selectDay);
//        $startTime = $days[0];
//        $endTime = $days[1];

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationListByType($type);

        foreach ($stations as $station) {
            $statusReportDay = $this->getStatusReportV3($station['id'], $startTime, $endTime);

            $station['stationStatusList1'] = $statusReportDay['stationStatusList1'];
            $station['stationStatusList2'] = $statusReportDay['stationStatusList2'];
            $station['stationStatusList3'] = $statusReportDay['stationStatusList3'];
            $station['stationStatusList4'] = $statusReportDay['stationStatusList4'];
            $station['stationStatusList5'] = $statusReportDay['stationStatusList5'];
        }
        return response()->json(array('stations' => $stations,'selectDate' => $startTime), 200);
    }



    function get_max($a, $b, $c, $d)
    {
        return ($a > $b ? $a : $b) > $c ? ($a > $b ? $a : $b) : $c > $d ? (($a > $b ? $a : $b) > $c ? ($a > $b ? $a : $b) : $c) : $d;
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

        $title = '泵站运行日志-' . $startTime;

        //连前累计
        $beforeTime = date("2017-10-01");

        $excelData = $this->getStatusReportV3($stationID, $startTime, $endTime);

        $excelDataBefore = $this->getStatusReportV3($stationID, $beforeTime, $endTime);

        Excel::create($title, function ($excel) use ($excelData, $excelDataBefore, $title, $startTime) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('泵站运行日志', function ($sheet) use ($excelData, $excelDataBefore, $startTime) {

                $station = $excelData['stationSelect'];

                $sheet->row(1, ['郑州市市政工程管理处泵站所泵站运行日志']);
                if ($station['station_number'] == 33) {
                    $sheet->row(2, ['泵站: ' . $station['name'], '', '', '', '', '', '', '', '', '', '', '', '', '', '', $startTime]);
                    $sheet->row(3, ['序号', '总电流(A)', '电压(V)', '进水池位(M)', '1号泵', '', '', '', '2号泵', '', '', '', '3号泵', '', '', '', '4号泵', '', '', '', '5号泵', '', '', ''
                        , '变压器', '', '总电度表度数(度)', '', '']);
                    $sheet->row(4, ['', '', '', '', '开泵时分', '停泵时分', '运行(分)', '电流(A)', '开泵时分', '停泵时分', '运行(分)', '电流(A)',
                        '开泵时分', '停泵时分', '运行(分)', '电流(A)', '开泵时分', '停泵时分', '运行(分)', '电流(A)', '开泵时分', '停泵时分', '运行(分)', '电流(A)',
                        '环境温度(℃)', '油温(℃)', '有功读数', '无功读数', '功率读数']);

                } else {
                    $sheet->row(2, ['泵站: ' . $station['name'], '', '', '', '', '', '', '', '', '', '', $startTime]);
                    $sheet->row(3, ['序号', '总电流(A)', '电压(V)', '进水池位(M)', '1号泵', '', '', '', '2号泵', '', '', '', '3号泵', '', '', '', '4号泵', '', '', ''
                        , '变压器', '', '总电度表度数(度)', '', '']);
                    $sheet->row(4, ['', '', '', '', '开泵时分', '停泵时分', '运行(分)', '电流(A)', '开泵时分', '停泵时分', '运行(分)', '电流(A)',
                        '开泵时分', '停泵时分', '运行(分)', '电流(A)', '开泵时分', '停泵时分', '运行(分)', '电流(A)',
                        '环境温度(℃)', '油温(℃)', '有功读数', '无功读数', '功率读数']);
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
                foreach ($excelData['stationStatusList1'] as $rowData1) {
                    $sheet->cell('E' . $i, function ($cell) use ($rowData1) {
                        $cell->setValue(substr($rowData1['timeStart'], 10));

                    });
                    $sheet->cell('F' . $i, function ($cell) use ($rowData1) {
                        $cell->setValue(substr($rowData1['timeEnd'], 10));

                    });
                    $sheet->cell('G' . $i, function ($cell) use ($rowData1) {
                        $cell->setValue($rowData1['timeGap']);

                    });
                    $sheet->cell('H' . $i, function ($cell) use ($rowData1) {
                        $cell->setValue($rowData1['current']);

                    });

                    //行高
                    $sheet->setHeight($i, 25);

                    $i++;
                }

                // 循环写入数据(2号泵)
                foreach ($excelData['stationStatusList2'] as $rowData2) {
                    $sheet->cell('I' . $j, function ($cell) use ($rowData2) {
                        $cell->setValue(substr($rowData2['timeStart'], 10));

                    });
                    $sheet->cell('J' . $j, function ($cell) use ($rowData2) {
                        $cell->setValue(substr($rowData2['timeEnd'], 10));

                    });
                    $sheet->cell('K' . $j, function ($cell) use ($rowData2) {
                        $cell->setValue($rowData2['timeGap']);

                    });
                    $sheet->cell('L' . $j, function ($cell) use ($rowData2) {
                        $cell->setValue($rowData2['current']);

                    });

                    //行高
                    $sheet->setHeight($j, 25);

                    $j++;
                }

                // 循环写入数据(3号泵)
                foreach ($excelData['stationStatusList3'] as $rowData3) {
                    $sheet->cell('M' . $k, function ($cell) use ($rowData3) {
                        $cell->setValue(substr($rowData3['timeStart'], 10));

                    });
                    $sheet->cell('N' . $k, function ($cell) use ($rowData3) {
                        $cell->setValue(substr($rowData3['timeEnd'], 10));

                    });
                    $sheet->cell('O' . $k, function ($cell) use ($rowData3) {
                        $cell->setValue($rowData3['timeGap']);

                    });
                    $sheet->cell('P' . $k, function ($cell) use ($rowData3) {
                        $cell->setValue($rowData3['current']);

                    });

                    //行高
                    $sheet->setHeight($k, 25);

                    $k++;
                }

                // 循环写入数据(4号泵)
                foreach ($excelData['stationStatusList4'] as $rowData4) {
                    $sheet->cell('Q' . $h, function ($cell) use ($rowData4) {
                        $cell->setValue(substr($rowData4['timeStart'], 10));

                    });
                    $sheet->cell('R' . $h, function ($cell) use ($rowData4) {
                        $cell->setValue(substr($rowData4['timeEnd'], 10));

                    });
                    $sheet->cell('S' . $h, function ($cell) use ($rowData4) {
                        $cell->setValue($rowData4['timeGap']);

                    });
                    $sheet->cell('T' . $h, function ($cell) use ($rowData4) {
                        $cell->setValue($rowData4['current']);

                    });

                    //行高
                    $sheet->setHeight($h, 25);

                    $h++;
                }

                if ($station['station_number'] == 33) {
                    // 循环写入数据(5号泵)
                    foreach ($excelData['stationStatusList5'] as $rowData5) {
                        $sheet->cell('U' . $h, function ($cell) use ($rowData5) {
                            $cell->setValue(substr($rowData5['timeStart'], 10));

                        });
                        $sheet->cell('V' . $h, function ($cell) use ($rowData5) {
                            $cell->setValue(substr($rowData5['timeEnd'], 10));

                        });
                        $sheet->cell('W' . $h, function ($cell) use ($rowData5) {
                            $cell->setValue($rowData5['timeGap']);

                        });
                        $sheet->cell('X' . $h, function ($cell) use ($rowData5) {
                            $cell->setValue($rowData5['current']);

                        });

                        //行高
                        $sheet->setHeight($h, 25);

                        $h++;
                    }
                }

                $rowMax = $this->get_max($i, $j, $k, $h);

                for ($index = 5; $index <= $rowMax; $index++) {
                    $sheet->cell('A' . $index, function ($cell) use ($index) {
                        $cell->setValue($index - 4);

                    });
                }

                //运行合计
                if ($station['station_number'] == 33) {
                    $sheet->row($rowMax, ['运行合计', '', '', '', $excelData['totalTimeDay1'], '', '', '分', $excelData['totalTimeDay2'], '', '', '分',
                        $excelData['totalTimeDay3'], '', '', '分', $excelData['totalTimeDay4'], '', '', '分', $excelData['totalTimeDay5'], '', '', '分', '总耗电量(度)', '', '', '', '']);

                    $sheet->row($rowMax + 1, ['连前累计运行', '', '', '', round(($excelDataBefore['totalTimeDay1']) / 60, 2), '', '', '小时', round(($excelDataBefore['totalTimeDay2']) / 60, 2), '', '', '小时',
                        round(($excelDataBefore['totalTimeDay3']) / 60, 2), '', '', '小时', round(($excelDataBefore['totalTimeDay4']) / 60, 2), '', '', '小时', round(($excelDataBefore['totalTimeDay5']) / 60, 2), '', '', '小时', '电表指数差', '', '', '', '']);

                    $sheet->row($rowMax + 2, ['抽升量', '', '', '', $excelData['totalFluxDay1'], '', '', '万吨', $excelData['totalFluxDay2'], '', '', '万吨',
                        $excelData['totalFluxDay3'], '', '', '万吨', $excelData['totalFluxDay4'], '', '', '万吨', $excelData['totalFluxDay5'], '', '', '万吨', '倍率', '', '', '', '']);

                    $sheet->row($rowMax + 3, ['连前累计抽升量', '', '', '', $excelDataBefore['totalFluxDay1'], '', '', '万吨', $excelDataBefore['totalFluxDay2'], '', '', '万吨',
                        $excelDataBefore['totalFluxDay3'], '', '', '万吨', $excelDataBefore['totalFluxDay4'], '', '', '万吨', $excelDataBefore['totalFluxDay5'], '', '', '万吨', '今日电量', '', '', '', '']);

                    $sheet->row($rowMax + 4, ['电度表读数', '', '', '', '', '', '', '度', '', '', '', '度', '', '', '', '度', '', '', '', '度', '', '', '', '度', '连前累计', '', '', '', '']);

                    $sheet->row($rowMax + 5, ['今日总抽升量', '', '', '', $excelData['totalFluxDay'], '', '', '万吨', '值班人员', '', '白', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);

                    $sheet->row($rowMax + 6, ['连前累计总抽升量', '', '', '', $excelDataBefore['totalFluxDay'], '', '', '万吨', '签名', '', '黑', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);

                    $sheet->row($rowMax + 7, ['记录:', '', '', '', '', '', '', '', '校核:']);
                } else {
                    $sheet->row($rowMax, ['运行合计', '', '', '', $excelData['totalTimeDay1'], '', '', '分', $excelData['totalTimeDay2'], '', '', '分',
                        $excelData['totalTimeDay3'], '', '', '分', $excelData['totalTimeDay4'], '', '', '分', '总耗电量(度)', '', '', '', '']);

                    $sheet->row($rowMax + 1, ['连前累计运行', '', '', '', round(($excelDataBefore['totalTimeDay1']) / 60, 2), '', '', '小时', round(($excelDataBefore['totalTimeDay2']) / 60, 2), '', '', '小时',
                        round(($excelDataBefore['totalTimeDay3']) / 60, 2), '', '', '小时', round(($excelDataBefore['totalTimeDay4']) / 60, 2), '', '', '小时', '电表指数差', '', '', '', '']);

                    $sheet->row($rowMax + 2, ['抽升量', '', '', '', $excelData['totalFluxDay1'], '', '', '万吨', $excelData['totalFluxDay2'], '', '', '万吨',
                        $excelData['totalFluxDay3'], '', '', '万吨', $excelData['totalFluxDay4'], '', '', '万吨', '倍率', '', '', '', '']);

                    $sheet->row($rowMax + 3, ['连前累计抽升量', '', '', '', $excelDataBefore['totalFluxDay1'], '', '', '万吨', $excelDataBefore['totalFluxDay2'], '', '', '万吨',
                        $excelDataBefore['totalFluxDay3'], '', '', '万吨', $excelDataBefore['totalFluxDay4'], '', '', '万吨', '今日电量', '', '', '', '']);

                    $sheet->row($rowMax + 4, ['电度表读数', '', '', '', '', '', '', '度', '', '', '', '度', '', '', '', '度', '', '', '', '度', '连前累计', '', '', '', '']);

                    $sheet->row($rowMax + 5, ['今日总抽升量', '', '', '', $excelData['totalFluxDay'], '', '', '万吨', '值班人员', '', '白', '', '', '', '', '', '', '', '', '', '', '', '', '']);

                    $sheet->row($rowMax + 6, ['连前累计总抽升量', '', '', '', $excelDataBefore['totalFluxDay1'], '', '', '万吨', '签名', '', '黑', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);

                    $sheet->row($rowMax + 7, ['记录:', '', '', '', '', '', '', '', '校核:']);
                }


                if ($station['station_number'] == 33) {
                    //表体样式
                    $sheet->setHeight($rowMax, 25);
                    $sheet->setHeight($rowMax + 1, 25);
                    $sheet->setHeight($rowMax + 2, 25);
                    $sheet->setHeight($rowMax + 3, 25);
                    $sheet->setHeight($rowMax + 4, 25);
                    $sheet->setHeight($rowMax + 5, 25);
                    $sheet->setHeight($rowMax + 6, 25);
                    $sheet->setHeight($rowMax + 7, 25);

                    $sheet->mergeCells('A' . $rowMax . ':D' . $rowMax);
                    $sheet->mergeCells('E' . $rowMax . ':G' . $rowMax);
                    $sheet->mergeCells('I' . $rowMax . ':K' . $rowMax);
                    $sheet->mergeCells('M' . $rowMax . ':O' . $rowMax);
                    $sheet->mergeCells('Q' . $rowMax . ':S' . $rowMax);
                    $sheet->mergeCells('U' . $rowMax . ':W' . $rowMax);
                    $sheet->mergeCells('Y' . $rowMax . ':AC' . $rowMax);

                    $sheet->mergeCells('A' . ($rowMax + 1) . ':D' . ($rowMax + 1));
                    $sheet->mergeCells('E' . ($rowMax + 1) . ':G' . ($rowMax + 1));
                    $sheet->mergeCells('I' . ($rowMax + 1) . ':K' . ($rowMax + 1));
                    $sheet->mergeCells('M' . ($rowMax + 1) . ':O' . ($rowMax + 1));
                    $sheet->mergeCells('Q' . ($rowMax + 1) . ':S' . ($rowMax + 1));
                    $sheet->mergeCells('U' . ($rowMax + 1) . ':W' . ($rowMax + 1));
                    $sheet->mergeCells('Y' . ($rowMax + 1) . ':Z' . ($rowMax + 1));
                    $sheet->mergeCells('AA' . ($rowMax + 1) . ':AC' . ($rowMax + 1));

                    $sheet->mergeCells('A' . ($rowMax + 2) . ':D' . ($rowMax + 2));
                    $sheet->mergeCells('E' . ($rowMax + 2) . ':G' . ($rowMax + 2));
                    $sheet->mergeCells('I' . ($rowMax + 2) . ':K' . ($rowMax + 2));
                    $sheet->mergeCells('M' . ($rowMax + 2) . ':O' . ($rowMax + 2));
                    $sheet->mergeCells('Q' . ($rowMax + 2) . ':S' . ($rowMax + 2));
                    $sheet->mergeCells('U' . ($rowMax + 2) . ':W' . ($rowMax + 2));
                    $sheet->mergeCells('Y' . ($rowMax + 2) . ':Z' . ($rowMax + 2));
                    $sheet->mergeCells('AA' . ($rowMax + 2) . ':AC' . ($rowMax + 2));

                    $sheet->mergeCells('A' . ($rowMax + 3) . ':D' . ($rowMax + 3));
                    $sheet->mergeCells('E' . ($rowMax + 3) . ':G' . ($rowMax + 3));
                    $sheet->mergeCells('I' . ($rowMax + 3) . ':K' . ($rowMax + 3));
                    $sheet->mergeCells('M' . ($rowMax + 3) . ':O' . ($rowMax + 3));
                    $sheet->mergeCells('Q' . ($rowMax + 3) . ':S' . ($rowMax + 3));
                    $sheet->mergeCells('U' . ($rowMax + 3) . ':W' . ($rowMax + 3));
                    $sheet->mergeCells('Y' . ($rowMax + 3) . ':Z' . ($rowMax + 3));
                    $sheet->mergeCells('AA' . ($rowMax + 3) . ':AC' . ($rowMax + 3));

                    $sheet->mergeCells('A' . ($rowMax + 4) . ':D' . ($rowMax + 4));
                    $sheet->mergeCells('E' . ($rowMax + 4) . ':G' . ($rowMax + 4));
                    $sheet->mergeCells('I' . ($rowMax + 4) . ':K' . ($rowMax + 4));
                    $sheet->mergeCells('M' . ($rowMax + 4) . ':O' . ($rowMax + 4));
                    $sheet->mergeCells('Q' . ($rowMax + 4) . ':S' . ($rowMax + 4));
                    $sheet->mergeCells('U' . ($rowMax + 4) . ':W' . ($rowMax + 4));
                    $sheet->mergeCells('Y' . ($rowMax + 4) . ':Z' . ($rowMax + 4));
                    $sheet->mergeCells('AA' . ($rowMax + 4) . ':AC' . ($rowMax + 4));

                    $sheet->mergeCells('A' . ($rowMax + 5) . ':D' . ($rowMax + 5));
                    $sheet->mergeCells('E' . ($rowMax + 5) . ':G' . ($rowMax + 5));
                    $sheet->mergeCells('I' . ($rowMax + 5) . ':J' . ($rowMax + 5));
                    $sheet->mergeCells('L' . ($rowMax + 5) . ':AC' . ($rowMax + 5));

                    $sheet->mergeCells('A' . ($rowMax + 6) . ':D' . ($rowMax + 6));
                    $sheet->mergeCells('E' . ($rowMax + 6) . ':G' . ($rowMax + 6));
                    $sheet->mergeCells('I' . ($rowMax + 6) . ':J' . ($rowMax + 6));
                    $sheet->mergeCells('L' . ($rowMax + 6) . ':AC' . ($rowMax + 6));

                    $sheet->mergeCells('A' . ($rowMax + 7) . ':H' . ($rowMax + 7));
                    $sheet->mergeCells('I' . ($rowMax + 7) . ':P' . ($rowMax + 7));

                    $sheet->setBorder('A3:AC' . ($rowMax + 6), 'thin');
                    $sheet->setAutoSize(true);
                    $sheet->setWidth(array(
                        'A' => 8,
                        'B' => 15,
                        'C' => 15,
                        'D' => 15,
                        'E' => 12,
                        'F' => 12,
                        'G' => 12,
                        'H' => 12,
                        'I' => 12,
                        'J' => 12,
                        'K' => 12,
                        'L' => 12,
                        'M' => 12,
                        'N' => 12,
                        'O' => 12,
                        'P' => 12,
                        'Q' => 12,
                        'R' => 12,
                        'S' => 12,
                        'T' => 12,
                        'U' => 15,
                        'V' => 12,
                        'W' => 12,
                        'X' => 12,
                        'Y' => 12,
                        'Z' => 12,
                        'AA' => 12,
                        'AB' => 12,
                        'AC' => 12
                    ));
                    $sheet->cells('A3:AC' . ($rowMax + 7), function ($cells) {
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
                    $sheet->cells('A3:AC4', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //标题样式
                    $sheet->mergeCells('A1:AC1');
                    $sheet->setHeight(1, 60);
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A1', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(22);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //日期样式
                    $sheet->mergeCells('A2:K2');
                    $sheet->mergeCells('L2:AC2');
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A2', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('left');
                        $cells->setValignment('center');

                    });

                    $sheet->cells('L2', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('right');
                        $cells->setValignment('center');

                    });
                } else {
                    //表体样式
                    $sheet->setHeight($rowMax, 25);
                    $sheet->setHeight($rowMax + 1, 25);
                    $sheet->setHeight($rowMax + 2, 25);
                    $sheet->setHeight($rowMax + 3, 25);
                    $sheet->setHeight($rowMax + 4, 25);
                    $sheet->setHeight($rowMax + 5, 25);
                    $sheet->setHeight($rowMax + 6, 25);
                    $sheet->setHeight($rowMax + 7, 25);

                    $sheet->mergeCells('A' . $rowMax . ':D' . $rowMax);
                    $sheet->mergeCells('E' . $rowMax . ':G' . $rowMax);
                    $sheet->mergeCells('I' . $rowMax . ':K' . $rowMax);
                    $sheet->mergeCells('M' . $rowMax . ':O' . $rowMax);
                    $sheet->mergeCells('Q' . $rowMax . ':S' . $rowMax);
                    $sheet->mergeCells('U' . $rowMax . ':Y' . $rowMax);

                    $sheet->mergeCells('A' . ($rowMax + 1) . ':D' . ($rowMax + 1));
                    $sheet->mergeCells('E' . ($rowMax + 1) . ':G' . ($rowMax + 1));
                    $sheet->mergeCells('I' . ($rowMax + 1) . ':K' . ($rowMax + 1));
                    $sheet->mergeCells('M' . ($rowMax + 1) . ':O' . ($rowMax + 1));
                    $sheet->mergeCells('Q' . ($rowMax + 1) . ':S' . ($rowMax + 1));
                    $sheet->mergeCells('U' . ($rowMax + 1) . ':V' . ($rowMax + 1));
                    $sheet->mergeCells('W' . ($rowMax + 1) . ':Y' . ($rowMax + 1));

                    $sheet->mergeCells('A' . ($rowMax + 2) . ':D' . ($rowMax + 2));
                    $sheet->mergeCells('E' . ($rowMax + 2) . ':G' . ($rowMax + 2));
                    $sheet->mergeCells('I' . ($rowMax + 2) . ':K' . ($rowMax + 2));
                    $sheet->mergeCells('M' . ($rowMax + 2) . ':O' . ($rowMax + 2));
                    $sheet->mergeCells('Q' . ($rowMax + 2) . ':S' . ($rowMax + 2));
                    $sheet->mergeCells('U' . ($rowMax + 2) . ':V' . ($rowMax + 2));
                    $sheet->mergeCells('W' . ($rowMax + 2) . ':Y' . ($rowMax + 2));

                    $sheet->mergeCells('A' . ($rowMax + 3) . ':D' . ($rowMax + 3));
                    $sheet->mergeCells('E' . ($rowMax + 3) . ':G' . ($rowMax + 3));
                    $sheet->mergeCells('I' . ($rowMax + 3) . ':K' . ($rowMax + 3));
                    $sheet->mergeCells('M' . ($rowMax + 3) . ':O' . ($rowMax + 3));
                    $sheet->mergeCells('Q' . ($rowMax + 3) . ':S' . ($rowMax + 3));
                    $sheet->mergeCells('U' . ($rowMax + 3) . ':V' . ($rowMax + 3));
                    $sheet->mergeCells('W' . ($rowMax + 3) . ':Y' . ($rowMax + 3));

                    $sheet->mergeCells('A' . ($rowMax + 4) . ':D' . ($rowMax + 4));
                    $sheet->mergeCells('E' . ($rowMax + 4) . ':G' . ($rowMax + 4));
                    $sheet->mergeCells('I' . ($rowMax + 4) . ':K' . ($rowMax + 4));
                    $sheet->mergeCells('M' . ($rowMax + 4) . ':O' . ($rowMax + 4));
                    $sheet->mergeCells('Q' . ($rowMax + 4) . ':S' . ($rowMax + 4));
                    $sheet->mergeCells('U' . ($rowMax + 4) . ':V' . ($rowMax + 4));
                    $sheet->mergeCells('W' . ($rowMax + 4) . ':Y' . ($rowMax + 4));

                    $sheet->mergeCells('A' . ($rowMax + 5) . ':D' . ($rowMax + 5));
                    $sheet->mergeCells('E' . ($rowMax + 5) . ':G' . ($rowMax + 5));
                    $sheet->mergeCells('I' . ($rowMax + 5) . ':J' . ($rowMax + 5));
                    $sheet->mergeCells('L' . ($rowMax + 5) . ':Y' . ($rowMax + 5));

                    $sheet->mergeCells('A' . ($rowMax + 6) . ':D' . ($rowMax + 6));
                    $sheet->mergeCells('E' . ($rowMax + 6) . ':G' . ($rowMax + 6));
                    $sheet->mergeCells('I' . ($rowMax + 6) . ':J' . ($rowMax + 6));
                    $sheet->mergeCells('L' . ($rowMax + 6) . ':Y' . ($rowMax + 6));

                    $sheet->mergeCells('A' . ($rowMax + 7) . ':H' . ($rowMax + 7));
                    $sheet->mergeCells('I' . ($rowMax + 7) . ':P' . ($rowMax + 7));

                    $sheet->setBorder('A3:Y' . ($rowMax + 6), 'thin');
                    $sheet->setAutoSize(true);
                    $sheet->setWidth(array(
                        'A' => 8,
                        'B' => 15,
                        'C' => 15,
                        'D' => 15,
                        'E' => 12,
                        'F' => 12,
                        'G' => 12,
                        'H' => 12,
                        'I' => 12,
                        'J' => 12,
                        'K' => 12,
                        'L' => 12,
                        'M' => 12,
                        'N' => 12,
                        'O' => 12,
                        'P' => 12,
                        'Q' => 12,
                        'R' => 12,
                        'S' => 12,
                        'T' => 12,
                        'U' => 15,
                        'V' => 12,
                        'W' => 12,
                        'X' => 12,
                        'Y' => 12
                    ));
                    $sheet->cells('A3:Y' . ($rowMax + 7), function ($cells) {
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
                    $sheet->cells('A3:Y4', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //标题样式
                    $sheet->mergeCells('A1:Y1');
                    $sheet->setHeight(1, 60);
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A1', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(22);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');

                    });

                    //日期样式
                    $sheet->mergeCells('A2:K2');
                    $sheet->mergeCells('L2:Y2');
                    $sheet->setHeight(2, 25);
                    $sheet->cells('A2', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('left');
                        $cells->setValignment('center');

                    });

                    $sheet->cells('L2', function ($cells) {
                        $cells->setFontFamily('Hei');
                        $cells->setFontSize(14);
                        $cells->setAlignment('right');
                        $cells->setValignment('center');

                    });
                }

            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name, 'log' => '导出了统计信息']);
    }

    /**
     * 导出单机当月运行报表
     */
    public function exportToExcelStatusMonth()
    {
        $type = Input::get('type', '全部');
//        $selectDay = Input::get('timeStart', '');
//
//        if ($selectDay == '')
//        {
//            $selectDay = date("Y-m-d");
//        }
//        $days = $this->getTheMonthDay($selectDay);
//        $startTime = $days[0];
//        $endTime = $days[1];
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationListByType($type);

        foreach ($stations as $station) {
            $param = $this->getStatusReportV3($station['id'], $startTime, $endTime);

            //连前累计
            $beforeTime = date("2017-10-01");
            $paramBefore = $this->getStatusReportV3($station['id'], $beforeTime, $endTime);

            //单位小时
            $station['totalTimeDay1'] = round(($param['totalTimeDay1']) / 60, 2);
            $station['totalTimeDay2'] = round(($param['totalTimeDay2']) / 60, 2);
            $station['totalTimeDay3'] = round(($param['totalTimeDay3']) / 60, 2);
            $station['totalTimeDay4'] = round(($param['totalTimeDay4']) / 60, 2);
            $station['totalTimeDay5'] = round(($param['totalTimeDay5']) / 60, 2);

            $station['totalFluxDay1'] = $param['totalFluxDay1'];
            $station['totalFluxDay2'] = $param['totalFluxDay2'];
            $station['totalFluxDay3'] = $param['totalFluxDay3'];
            $station['totalFluxDay4'] = $param['totalFluxDay4'];
            $station['totalFluxDay5'] = $param['totalFluxDay5'];

            $station['totalTimeBefore1'] = round(($paramBefore['totalTimeDay1']) / 60, 2);
            $station['totalTimeBefore2'] = round(($paramBefore['totalTimeDay2']) / 60, 2);
            $station['totalTimeBefore3'] = round(($paramBefore['totalTimeDay3']) / 60, 2);
            $station['totalTimeBefore4'] = round(($paramBefore['totalTimeDay4']) / 60, 2);
            $station['totalTimeBefore5'] = round(($paramBefore['totalTimeDay5']) / 60, 2);

            $station['totalFluxBefore1'] = $paramBefore['totalFluxDay1'];
            $station['totalFluxBefore2'] = $paramBefore['totalFluxDay2'];
            $station['totalFluxBefore3'] = $paramBefore['totalFluxDay3'];
            $station['totalFluxBefore4'] = $paramBefore['totalFluxDay4'];
            $station['totalFluxBefore5'] = $paramBefore['totalFluxDay5'];
        }

        $titleTime = "(".$startTime. " 至 " .$endTime.")";

        $title = '单机运行情况月报表-' . $titleTime;

        $excelData = $stations;

        Excel::create($title, function ($excel) use ($excelData, $title, $startTime,$endTime) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('单机运行情况月报表', function ($sheet) use ($excelData, $startTime,$endTime) {

                $strMonth = substr($startTime, 0, 4) . '年' . substr($startTime, 5, 2) . '月';
                $today = date('Y-m-d');

                $displayTime = $startTime. " 至 " .$endTime;

                $sheet->row(1, [$strMonth . '单机运行抽升情况报表']);
                $sheet->row(2, [$displayTime]);
                $sheet->row(3, ['泵站名称', '1号泵', '', '2号泵', '', '3号泵', '', '4号泵', '', '5号泵']);
                $sheet->row(4, ['', '运行(小时)', '抽升量(万吨)', '运行(小时)', '抽升量(万吨)', '运行(小时)', '抽升量(万吨)', '运行(小时)', '抽升量(万吨)', '运行(小时)', '抽升量(万吨)']);

                if (empty($excelData)) {

                    $sheet->row(5, ['']);
                    return;
                }

                $i = 5;

                // 循环写入数据
                foreach ($excelData as $rowData) {
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
                    $sheet->row($i + 1, $row2);

                    //行高
                    $sheet->setHeight($i, 25);
                    $sheet->setHeight($i + 1, 25);

                    $i++;
                    $i++;
                }

                $sheet->row($i, ['主管:', '', '', '', '', '', '', '制表:']);


                //表体样式

                $sheet->setBorder('A3:K' . ($i - 1), 'thin');
                $sheet->setAutoSize(true);
                $sheet->setWidth(array(
                    'A' => 30,
                    'B' => 20,
                    'C' => 20,
                    'D' => 20,
                    'E' => 20,
                    'F' => 20,
                    'G' => 20,
                    'H' => 20,
                    'I' => 20,
                    'J' => 20,
                    'K' => 20,
                ));
                $sheet->cells('A4:K' . ($i - 1), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                $sheet->mergeCells('A' . ($i) . ':G' . ($i));
                $sheet->mergeCells('H' . ($i) . ':K' . ($i));
                $sheet->cells('A' . ($i) . ':K' . ($i), function ($cells) {
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
                $sheet->cells('A3:K4', function ($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //标题样式
                $sheet->mergeCells('A1:K1');
                $sheet->setHeight(1, 60);
                $sheet->setHeight(2, 25);
                $sheet->cells('A1', function ($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(22);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //日期样式
                $sheet->mergeCells('A2:K2');
                $sheet->setHeight(2, 25);
                $sheet->cells('A2', function ($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(14);
                    $cells->setAlignment('right');
                    $cells->setValignment('center');
                });
            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name, 'log' => '导出了统计信息']);
    }

    /**
     * 导出泵站所有泵组当月运行报表
     */
    public function exportToExcelStatusMonthAll()
    {
        $type = Input::get('type', '全部');
//        $selectDay = Input::get('timeStart', '');
//
//        if ($selectDay == '')
//        {
//            $selectDay = date("Y-m-d");
//        }
//        $days = $this->getTheMonthDay($selectDay);
//        $startTime = $days[0];
//        $endTime = $days[1];

        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

//        $startTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
//        $endTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationListByType($type);

        $index = 1;

        //所有泵站之和
        $totalTimeDayAll = 0;
        $totalTimeBeforeAll = 0;
        $totalFluxDayAll = 0;
        $totalFluxBeforeAll = 0;

        foreach ($stations as $station) {
            $param = $this->getStatusReportV3($station['id'], $startTime, $endTime);

            //连前累计
            $beforeTime = date("2017-10-01");
            $paramBefore = $this->getStatusReportV3($station['id'], $beforeTime, $endTime);

            //单位小时
            $station['totalTimeDay'] = round(($param['totalTimeDay']) / 60, 2);
            $station['totalFluxDay'] = $param['totalFluxDay'];
            $station['totalTimeBefore'] = round(($paramBefore['totalTimeDay']) / 60, 2);
            $station['totalFluxBefore'] = $paramBefore['totalFluxDay'];
            $totalTimeDayAll += $station['totalTimeDay'];
            $totalTimeBeforeAll += $station['totalTimeBefore'];
            $totalFluxDayAll += $station['totalFluxDay'];
            $totalFluxBeforeAll += $station['totalFluxBefore'];

            $station['index'] = $index;
            $index++;
        }
        $paramMonthAll = ['stations' => $stations, 'totalTimeDayAll' => $totalTimeDayAll,
            'totalTimeBeforeAll' => $totalTimeBeforeAll, 'totalFluxDayAll' => $totalFluxDayAll,
            'totalFluxBeforeAll' => $totalFluxBeforeAll, 'selectType' => $type, 'startTime' => $startTime];

        $titleTime = "(".$startTime. " 至 " .$endTime.")";
        $title = '泵站月生产报表-' . $titleTime;

        $excelData = $paramMonthAll;

        Excel::create($title, function ($excel) use ($excelData, $title, $startTime,$endTime) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('泵站月生产报表', function ($sheet) use ($excelData, $startTime,$endTime) {

                $strMonth = substr($startTime, 0, 4) . '年' . substr($startTime, 5, 2) . '月';
                $today = date('Y-m-d');
                $displayTime = $startTime. " 至 " .$endTime;

                $sheet->row(1, [$strMonth . '泵站月生产报表']);
                $sheet->row(2, ['单位名称: 市政工程管理处泵站管理所', '', '', '', '', $displayTime]);
                $sheet->row(3, ['序号', '泵站名称', '泵组运行时间(小时)', '累计(小时)', '泵组抽升量(万吨)', '累计(万吨)', '备注']);

                if (empty($excelData)) {

                    $sheet->row(4, ['']);
                    return;
                }

                $i = 4;

                // 循环写入数据
                foreach ($excelData['stations'] as $rowData) {
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

                $sheet->row($i, ['合计:', '', $excelData['totalTimeDayAll'], $excelData['totalTimeBeforeAll'], $excelData['totalFluxDayAll'], $excelData['totalFluxBeforeAll']]);
                //行高
                $sheet->setHeight($i, 25);

                $sheet->row($i + 1, ['主管:', '', '', '', '制表:']);


                //表体样式

                $sheet->mergeCells('A' . ($i) . ':B' . ($i));

                $sheet->setBorder('A3:G' . ($i), 'thin');
                $sheet->setAutoSize(true);
                $sheet->setWidth(array(
                    'A' => 10,
                    'B' => 30,
                    'C' => 30,
                    'D' => 30,
                    'E' => 30,
                    'F' => 30,
                    'G' => 20,
                ));
                $sheet->cells('A4:G' . ($i), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                $sheet->mergeCells('A' . ($i + 1) . ':D' . ($i + 1));
                $sheet->mergeCells('E' . ($i + 1) . ':G' . ($i + 1));
                $sheet->cells('A' . ($i + 1) . ':G' . ($i + 1), function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setFontWeight('normal');
                    $cells->setAlignment('left');
                    $cells->setValignment('center');

                });

                //表头样式

                $sheet->setHeight(3, 40);
                $sheet->cells('A3:G3', function ($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(14);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //标题样式
                $sheet->mergeCells('A1:G1');
                $sheet->setHeight(1, 60);
                $sheet->setHeight(2, 25);
                $sheet->cells('A1', function ($cells) {
                    $cells->setFontFamily('Hei');
                    $cells->setFontSize(22);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');

                });

                //日期样式
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('F2:G2');
                $sheet->setHeight(2, 25);
                $sheet->cells('A2', function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                });
                $sheet->cells('F2', function ($cells) {
                    $cells->setFontSize(14);
                    $cells->setAlignment('right');
                    $cells->setValignment('center');
                });
            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name, 'log' => '导出了统计信息']);
    }

}
