<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2019/2/23
 * Time: 23:14
 */

namespace App\Jobs;

use Carbon\Carbon;
use App\Job as JobModel;
use App\Models\Station\StationRecordModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Reporte\StatusReportController;
use App\Repositories\Station\StationRecordRepository;

class StationRecord extends Job
{
    protected $type = JobModel::TYPE_STATION_RECORD;

//    protected $reportController;
//    protected $recordRepository;
//
//    public function __construct(StatusReportController $reportController,StationRecordRepository $recordRepository)
//    {
//        $this->reportController = $reportController;
//        $this->recordRepository = $recordRepository;
//    }

    /**
     * 处理任务
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function run()
    {
//        DB::transaction(function () {
//            $records = $this->getRecords();
//
//            $this->saveRecords($records);
//        });

//        $today = Carbon::today()->toDateTimeString();// 2019-01-02 00:00:00
//        $yesterday = Carbon::yesterday()->toDateTimeString();// 2019-01-01 00:00:00

//        $startTime = $yesterday;
//        $endTime = date('Y-m-d 23:59:59', strtotime($yesterday);

        $startTime = date("2017-10-10 00:00:00");
        $endTime = date("2017-10-10 23:59:59");

        for($i=1;$i<=38;$i++)
        {
            $records = $this->getRecords($i,$startTime,$endTime);
            $this->saveRecords($i,$records);
        }

    }

    /**
     * 获取运行记录
     *
     * @return array
     */
    protected function getRecords($stationNum, $startTime, $endTime)
    {
//        return Idol::orderBy('is_quit')->orderByDesc('popularity')->orderBy('idol_id')
//            ->get(['id as idol_id', 'name', 'avatar', 'popularity', 'is_quit'])
//            ->toArray();

        $recordList = $this->getStationRecords($stationNum, $startTime, $endTime);

        return $recordList;
    }

    /**
     * 保存运行记录
     *
     * @param $stationNum
     * @param $records
     */
    protected function saveRecords($stationNum,$records)
    {
//        Rank::truncate();
//
//        Rank::insert($ranks);
//
//        Rank::forgetCache();
        $pumpRecordList1 = $records->stationStatusList1;
        $pumpRecordList2 = $records->stationStatusList2;
        $pumpRecordList3 = $records->stationStatusList3;
        $pumpRecordList4 = $records->stationStatusList4;
        $pumpRecordList5 = $records->stationStatusList5;

        $attributes = [];

        if(!empty($pumpRecordList1[0])){
            foreach ($pumpRecordList1 as $pumRecord)
            {
                $attributes[] = [
                    'station_num' => $stationNum,'pump_num' => 1,'run_at' => substr($pumRecord['timeStart'],0,10),
                    'start_at'=>substr($pumRecord['timeStart'],10),'stop_at'=>substr($pumRecord['timeEnd'],10),
                    'start_value'=>$pumRecord['waterStart'],'stop_value'=>$pumRecord['waterEnd'],
                    'run_time'=>$pumRecord['timeGap'],'run_current'=>$pumRecord['current']
                ];
            }
        }
        if(!empty($pumpRecordList2[0])){
            foreach ($pumpRecordList2 as $pumRecord)
            {
                $attributes[] = [
                    'station_num' => $stationNum,'pump_num' => 2,'run_at' => substr($pumRecord['timeStart'],0,10),
                    'start_at'=>substr($pumRecord['timeStart'],10),'stop_at'=>substr($pumRecord['timeEnd'],10),
                    'start_value'=>$pumRecord['waterStart'],'stop_value'=>$pumRecord['waterEnd'],
                    'run_time'=>$pumRecord['timeGap'],'run_current'=>$pumRecord['current']
                ];
            }
        }
        if(!empty($pumpRecordList3[0])){
            foreach ($pumpRecordList3 as $pumRecord)
            {
                $attributes[] = [
                    'station_num' => $stationNum,'pump_num' => 3,'run_at' => substr($pumRecord['timeStart'],0,10),
                    'start_at'=>substr($pumRecord['timeStart'],10),'stop_at'=>substr($pumRecord['timeEnd'],10),
                    'start_value'=>$pumRecord['waterStart'],'stop_value'=>$pumRecord['waterEnd'],
                    'run_time'=>$pumRecord['timeGap'],'run_current'=>$pumRecord['current']
                ];
            }
        }
        if(!empty($pumpRecordList4[0])){
            foreach ($pumpRecordList4 as $pumRecord)
            {
                $attributes[] = [
                    'station_num' => $stationNum,'pump_num' => 4,'run_at' => substr($pumRecord['timeStart'],0,10),
                    'start_at'=>substr($pumRecord['timeStart'],10),'stop_at'=>substr($pumRecord['timeEnd'],10),
                    'start_value'=>$pumRecord['waterStart'],'stop_value'=>$pumRecord['waterEnd'],
                    'run_time'=>$pumRecord['timeGap'],'run_current'=>$pumRecord['current']
                ];
            }
        }
        if(!empty($pumpRecordList5[0])){
            foreach ($pumpRecordList5 as $pumRecord)
            {
                $attributes[] = [
                    'station_num' => $stationNum,'pump_num' => 5,'run_at' => substr($pumRecord['timeStart'],0,10),
                    'start_at'=>substr($pumRecord['timeStart'],10),'stop_at'=>substr($pumRecord['timeEnd'],10),
                    'start_value'=>$pumRecord['waterStart'],'stop_value'=>$pumRecord['waterEnd'],
                    'run_time'=>$pumRecord['timeGap'],'run_current'=>$pumRecord['current']
                ];
            }
        }

        //批量插入数据
        DB::table('station_records')->insert($attributes);
//        $this->recordRepository->insert($attributes);
    }

    /**
     *
     * 从staion_RTY表中计算运行信息
     * @param $stationNum
     * @param $startTime
     * @param $endTime
     *
     * @return array
     */
    public function getStationRecords($stationNum, $startTime, $endTime)
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        $todayTime = date("Y-m-d H:i:s");

        $statusYList = $this->getStatusYList($stationNum, $startTime, $endTime);

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

        $currentCode1 = 'ib1';
        $currentCode2 = 'ib2';
        $currentCode3 = 'ib3';
        $currentCode4 = 'ib4';
        $currentCode5 = 'ib5';

        $initialCurrent = 10;

        // 遍历实时运行数据表,找出起泵时刻与停泵时刻
        for ($i = 0; $i < count($statusYList); $i++) {
            $sRunning1 = [];
            $sRunning2 = [];
            $sRunning3 = [];
            $sRunning4 = [];
            $sRunning5 = [];

            //1号泵
            if ($i == 0) {
                if ($statusYList[$i]->$currentCode1 > $initialCurrent) {
                    $sRunning1['timeStart'] = $statusYList[$i]->Time;
                    $sRunning1['waterStart'] = $statusYList[$i]->ywjishui;
                    $index1++;
                    array_push($stationStatusList1, $sRunning1);
                }
            } else {
                if ($statusYList[$i]->$currentCode1 > $initialCurrent && $statusYList[$i - 1]->$currentCode1 <= $initialCurrent) {
                    $sRunning1['timeStart'] = $statusYList[$i]->Time;
                    $sRunning1['waterStart'] = $statusYList[$i]->ywjishui;
                    $index1++;
                    array_push($stationStatusList1, $sRunning1);

                } elseif ($i == (count($statusYList) - 1) && $statusYList[$i]->$currentCode1 > $initialCurrent) {
//                    array_pop($stationStatusList1);
                } elseif ($statusYList[$i]->$currentCode1 <= $initialCurrent && $statusYList[$i - 1]->$currentCode1 > $initialCurrent) {
                    $sRunning1['timeEnd'] = $statusYList[$i]->Time;
                    $sRunning1['waterEnd'] = $statusYList[$i]->ywjishui;
                    $sRunning1['current'] = $statusYList[$i - 1]->$currentCode1;

                    if ($index1 > 0) {
                        $sRunning1['timeGap'] = abs(strtotime($sRunning1['timeEnd']) - strtotime($stationStatusList1[$index1 - 1]['timeStart'])) / 60;
                        $sRunning1['timeGap'] = round($sRunning1['timeGap'],2);
                        $stationStatusList1[$index1 - 1]['timeEnd'] = $sRunning1['timeEnd'];
                        $stationStatusList1[$index1 - 1]['timeGap'] = $sRunning1['timeGap'];
                        $stationStatusList1[$index1 - 1]['waterEnd'] = $sRunning1['waterEnd'];
                        if($sRunning1['current'] > 1000)
                        {
                            $sRunning1['current'] = 30;
                        }
                        $stationStatusList1[$index1 - 1]['current'] = $sRunning1['current'];
//                        $stationStatusList1[$index1 - 1]['flux'] = $sRunning1['timeGap'] * $pump['flux1'] / 600000;
                        $stationStatusList1[$index1 - 1]['index'] = $index1;

                        //运行时间求和
//                        $totalTimeDay1 += $sRunning1['timeGap'];
                        //抽升量求和
//                        $totalFluxDay1 += ($sRunning1['timeGap'] * $pump['flux1']) / 10000;
                    }

                }
            }

            //2号泵
            if ($i == 0) {
                if ($statusYList[$i]->$currentCode2 > $initialCurrent) {
                    $sRunning2['timeStart'] = $statusYList[$i]->Time;
                    $sRunning2['waterStart'] = $statusYList[$i]->ywjishui;
                    $index2++;
                    array_push($stationStatusList2, $sRunning2);
                }
            } else {
                if ($statusYList[$i]->$currentCode2 > $initialCurrent && $statusYList[$i - 1]->$currentCode2 <= $initialCurrent) {

                    $sRunning2['timeStart'] = $statusYList[$i]->Time;
                    $sRunning2['waterStart'] = $statusYList[$i]->ywjishui;
                    $index2++;
                    array_push($stationStatusList2, $sRunning2);

                } elseif ($i == (count($statusYList) - 1) && $statusYList[$i]->$currentCode2 > $initialCurrent) {
//                    array_pop($stationStatusList2);
                } elseif ($statusYList[$i]->$currentCode2 <= $initialCurrent && $statusYList[$i - 1]->$currentCode2 > $initialCurrent) {
                    $sRunning2['timeEnd'] = $statusYList[$i]->Time;
                    $sRunning2['waterEnd'] = $statusYList[$i]->ywjishui;
                    $sRunning2['current'] = $statusYList[$i - 1]->$currentCode2;

                    if ($index2 > 0) {
                        $sRunning2['timeGap'] = abs(strtotime($sRunning2['timeEnd']) - strtotime($stationStatusList2[$index2 - 1]['timeStart'])) / 60;
                        $sRunning2['timeGap'] = round($sRunning2['timeGap'],2);
                        $stationStatusList2[$index2 - 1]['timeEnd'] = $sRunning2['timeEnd'];
                        $stationStatusList2[$index2 - 1]['waterEnd'] = $sRunning2['waterEnd'];
                        $stationStatusList2[$index2 - 1]['timeGap'] = $sRunning2['timeGap'];
                        if($sRunning2['current'] > 1000)
                        {
                            $sRunning2['current'] = 30;
                        }
                        $stationStatusList2[$index2 - 1]['current'] = $sRunning2['current'];
//                        $stationStatusList2[$index2 - 1]['flux'] = $sRunning2['timeGap'] * $pump['flux2'] / 600000;
                        $stationStatusList2[$index2 - 1]['index'] = $index2;


                        if($index2 == 1)
                        {
                            //运行时间求和
                            //$totalTimeDay2 = $sRunning2['timeGap'];

                        }

                        //运行时间求和
//                        $totalTimeDay2 += $sRunning2['timeGap'];
                        //抽升量求和
//                        $totalFluxDay2 += ($sRunning2['timeGap'] * $pump['flux2']) / 10000;
                    }

                }
            }

            if ($has3Pump || $has4Pump || $has5Pump) {
                //3号泵
                if ($i == 0) {
                    if ($statusYList[$i]->$currentCode3 > $initialCurrent) {
                        $sRunning3['timeStart'] = $statusYList[$i]->Time;
                        $sRunning3['waterStart'] = $statusYList[$i]->ywjishui;
                        $index3++;
                        array_push($stationStatusList3, $sRunning3);
                    }
                } else {
                    if ($statusYList[$i]->$currentCode3 > $initialCurrent && $statusYList[$i - 1]->$currentCode3 <= $initialCurrent) {
                        $sRunning3['timeStart'] = $statusYList[$i]->Time;
                        $sRunning3['waterStart'] = $statusYList[$i]->ywjishui;
                        $index3++;
                        array_push($stationStatusList3, $sRunning3);

                    } elseif ($i == (count($statusYList) - 1) && $statusYList[$i]->$currentCode3 > $initialCurrent) {
//                        array_pop($stationStatusList3);
                    } elseif ($statusYList[$i]->$currentCode3 <= $initialCurrent && $statusYList[$i - 1]->$currentCode3 > $initialCurrent) {
                        $sRunning3['timeEnd'] = $statusYList[$i]->Time;
                        $sRunning3['waterEnd'] = $statusYList[$i]->ywjishui;
                        $sRunning3['current'] = $statusYList[$i - 1]->$currentCode3;

                        if ($index3 > 0) {
                            $sRunning3['timeGap'] = abs(strtotime($sRunning3['timeEnd']) - strtotime($stationStatusList3[$index3 - 1]['timeStart'])) / 60;
                            $sRunning3['timeGap'] = round($sRunning3['timeGap'],2);
                            $stationStatusList3[$index3 - 1]['timeEnd'] = $sRunning3['timeEnd'];
                            $stationStatusList3[$index3 - 1]['timeGap'] = $sRunning3['timeGap'];
                            $stationStatusList3[$index3 - 1]['waterEnd'] = $sRunning3['waterEnd'];
                            if($sRunning3['current'] > 1000)
                            {
                                $sRunning3['current'] = 30;
                            }
                            $stationStatusList3[$index3 - 1]['current'] = $sRunning3['current'];
//                            $stationStatusList3[$index3 - 1]['flux'] = $sRunning3['timeGap'] * $pump['flux3'] / 600000;
                            $stationStatusList3[$index3 - 1]['index'] = $index3;

                            if($index3 == 1)
                            {
                                //运行时间求和
                                //$totalTimeDay3 = $sRunning3['timeGap'];

                            }

                            //运行时间求和
//                            $totalTimeDay3 += $sRunning3['timeGap'];
                            //抽升量求和
//                            $totalFluxDay3 += ($sRunning3['timeGap'] * $pump['flux3']) / 10000;
                        }

                    }
                }
            }

            if ($has4Pump || $has5Pump) {
                //4号泵
                if ($i == 0) {
                    if ($statusYList[$i]->$currentCode4 > $initialCurrent) {
                        $sRunning4['timeStart'] = $statusYList[$i]->Time;
                        $sRunning4['waterStart'] = $statusYList[$i]->ywjishui;
                        $index4++;
                        array_push($stationStatusList4, $sRunning4);
                    }
                } else {
                    if ($statusYList[$i]->$currentCode4 > $initialCurrent && $statusYList[$i - 1]->$currentCode4 <= $initialCurrent) {
                        $sRunning4['timeStart'] = $statusYList[$i]->Time;
                        $sRunning4['waterStart'] = $statusYList[$i]->ywjishui;
                        $index4++;
                        array_push($stationStatusList4, $sRunning4);

                    } elseif ($i == (count($statusYList) - 1) && $statusYList[$i]->$currentCode4 > 10) {
//                        array_pop($stationStatusList4);
                    } elseif ($statusYList[$i]->$currentCode4 <= $initialCurrent && $statusYList[$i - 1]->$currentCode4 > $initialCurrent) {
                        $sRunning4['timeEnd'] = $statusYList[$i]->Time;
                        $sRunning4['waterEnd'] = $statusYList[$i]->ywjishui;
                        $sRunning4['current'] = $statusYList[$i - 1]->$currentCode4;

                        if ($index4 > 0) {
                            $sRunning4['timeGap'] = abs(strtotime($sRunning4['timeEnd']) - strtotime($stationStatusList4[$index4 - 1]['timeStart'])) / 60;
                            $sRunning4['timeGap'] = round($sRunning4['timeGap'],2);
                            $stationStatusList4[$index4 - 1]['timeEnd'] = $sRunning4['timeEnd'];
                            $stationStatusList4[$index4 - 1]['timeGap'] = $sRunning4['timeGap'];
                            $stationStatusList4[$index4 - 1]['waterEnd'] = $sRunning4['waterEnd'];
                            if($sRunning4['current'] > 1000)
                            {
                                $sRunning4['current'] = 30;
                            }
                            $stationStatusList4[$index4 - 1]['current'] = $sRunning4['current'];
//                            $stationStatusList4[$index4 - 1]['flux'] = $sRunning4['timeGap'] * $pump['flux4'] / 600000;
                            $stationStatusList4[$index4 - 1]['index'] = $index4;


                            if($index4 == 1)
                            {
                                //运行时间求和
                                //$totalTimeDay4 = $sRunning4['timeGap'];

                            }


                            //运行时间求和
//                            $totalTimeDay4 = $totalTimeDay4 + $sRunning4['timeGap'];

                            //抽升量求和
//                            $totalFluxDay4 += ($sRunning4['timeGap'] * $pump['flux4']) / 10000;
                        }

                    }
                }
            }

            if ($has5Pump) {
                //5号泵
                if ($i == 0) {
                    if ($statusYList[$i]->$currentCode5 > $initialCurrent) {
                        $sRunning5['timeStart'] = $statusYList[$i]->Time;
                        $sRunning5['waterStart'] = $statusYList[$i]->ywjishui;
                        $index5++;
                        array_push($stationStatusList5, $sRunning5);
                    }
                } else {
                    if ($statusYList[$i]->$currentCode5 > $initialCurrent && $statusYList[$i - 1]->$currentCode5 <= $initialCurrent) {
                        $sRunning5['timeStart'] = $statusYList[$i]->Time;
                        $sRunning5['waterStart'] = $statusYList[$i]->ywjishui;
                        $index5++;
                        array_push($stationStatusList5, $sRunning5);
                    } elseif ($i == (count($statusYList) - 1) && $statusYList[$i]->$currentCode5 > $initialCurrent) {
//                        array_pop($stationStatusList5);
                    } elseif ($statusYList[$i]->$currentCode5 <= $initialCurrent && $statusYList[$i - 1]->$currentCode5 > $initialCurrent) {

                        $sRunning5['timeEnd'] = $statusYList[$i]->Time;
                        $sRunning5['waterEnd'] = $statusYList[$i]->ywjishui;
                        $sRunning5['current'] = $statusYList[$i - 1]->$currentCode5;

                        if ($index5 > 0) {
                            $sRunning5['timeGap'] = abs(strtotime($sRunning5['timeEnd']) - strtotime($stationStatusList5[$index5 - 1]['timeStart'])) / 60;
                            $sRunning5['timeGap'] = round($sRunning5['timeGap'],2);
                            $stationStatusList5[$index5 - 1]['timeEnd'] = $sRunning5['timeEnd'];
                            $stationStatusList5[$index5 - 1]['timeGap'] = $sRunning5['timeGap'];
                            $stationStatusList5[$index5 - 1]['waterEnd'] = $sRunning5['waterEnd'];
                            if($sRunning5['current'] > 1000)
                            {
                                $sRunning5['current'] = 30;
                            }
                            $stationStatusList5[$index5 - 1]['current'] = $sRunning5['current'];
//                            $stationStatusList5[$index5 - 1]['flux'] = $sRunning5['timeGap'] * $pump['flux5'] / 600000;
                            $stationStatusList5[$index5 - 1]['index'] = $index5;


                            if($index5 == 1)
                            {
                                //运行时间求和
                                //$totalTimeDay5 = $sRunning5['timeGap'];

                            }

                            //运行时间求和
//                            $totalTimeDay5 += $sRunning5['timeGap'];
                            //抽升量求和
//                            $totalFluxDay5 += ($sRunning5['timeGap'] * $pump['flux5']) / 10000;
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
                $stationStatusList1[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;

            }
            else
            {
                $stationStatusList1[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
                $stationStatusList1[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }

            $endTimeGap = abs(strtotime($stationStatusList1[$indexEnd]['timeEnd']) - strtotime($stationStatusList1[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap,2);
            $stationStatusList1[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList1[$indexEnd]['current'] = 57;
//            $stationStatusList1[$indexEnd]['flux'] = $endTimeGap * $pump['flux1'] / 600000;

            if($indexEnd == 0)
            {
                $stationStatusList1[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList1[$indexEnd]['index'] = $stationStatusList1[$indexEnd-1]['index'] + 1;
            }

//            $totalTimeDay1 += $stationStatusList1[$indexEnd]['timeGap'];
        }

        if(count($stationStatusList2) > 0 && !isset(end($stationStatusList2)['timeEnd']))
        {
            $indexEnd = count($stationStatusList2) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList2[$indexEnd]['timeEnd'] = $todayTime;

                $stationStatusList2[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }
            else
            {
                $stationStatusList2[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
                $stationStatusList2[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }

            $endTimeGap = abs(strtotime($stationStatusList2[$indexEnd]['timeEnd']) - strtotime($stationStatusList2[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap,2);
            $stationStatusList2[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList2[$indexEnd]['current'] = 57;
//            $stationStatusList2[$indexEnd]['flux'] = $endTimeGap * $pump['flux2'] / 600000;

            if($indexEnd == 0)
            {
                $stationStatusList2[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList2[$indexEnd]['index'] = $stationStatusList2[$indexEnd-1]['index'] + 1;
            }

//            $totalTimeDay2 += $stationStatusList2[$indexEnd]['timeGap'];
        }

        if(count($stationStatusList3) > 0 && !isset(end($stationStatusList3)['timeEnd']))
        {
            $indexEnd = count($stationStatusList3) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList3[$indexEnd]['timeEnd'] = $todayTime;
                $stationStatusList3[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }
            else
            {
                $stationStatusList3[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
                $stationStatusList3[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }

            $endTimeGap = abs(strtotime($stationStatusList3[$indexEnd]['timeEnd']) - strtotime($stationStatusList3[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap,2);
            $stationStatusList3[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList3[$indexEnd]['current'] = 57;
//            $stationStatusList3[$indexEnd]['flux'] = $endTimeGap * $pump['flux3'] / 600000;

            if($indexEnd == 0)
            {
                $stationStatusList3[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList3[$indexEnd]['index'] = $stationStatusList3[$indexEnd-1]['index'] + 1;
            }

//            $totalTimeDay3 += $stationStatusList3[$indexEnd]['timeGap'];
        }

        if(count($stationStatusList4) > 0 && !isset(end($stationStatusList4)['timeEnd']))
        {
            $indexEnd = count($stationStatusList4) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList4[$indexEnd]['timeEnd'] = $todayTime;
                $stationStatusList4[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }
            else
            {
                $stationStatusList4[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
                $stationStatusList4[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }

            $endTimeGap = abs(strtotime($stationStatusList4[$indexEnd]['timeEnd']) - strtotime($stationStatusList4[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap,2);
            $stationStatusList4[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList4[$indexEnd]['current'] = 57;
//            $stationStatusList4[$indexEnd]['flux'] = $endTimeGap * $pump['flux4'] / 600000;

            if($indexEnd == 0)
            {
                $stationStatusList4[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList4[$indexEnd]['index'] = $stationStatusList4[$indexEnd-1]['index'] + 1;
            }

//            $totalTimeDay4 += $stationStatusList4[$indexEnd]['timeGap'];
        }

        if(count($stationStatusList5) > 0 && !isset(end($stationStatusList5)['timeEnd']))
        {
            $indexEnd = count($stationStatusList5) -1;
            if($this->isSameDay($endTime,$todayTime))
            {
                $stationStatusList5[$indexEnd]['timeEnd'] = $todayTime;
                $stationStatusList5[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }
            else
            {
                $stationStatusList5[$indexEnd]['timeEnd'] = date('Y-m-d 23:59:59', strtotime($endTime));
                $stationStatusList5[$indexEnd]['waterEnd'] = $statusYList[count($statusYList) - 1]->ywjishui;;
            }

            $endTimeGap = abs(strtotime($stationStatusList5[$indexEnd]['timeEnd']) - strtotime($stationStatusList5[$indexEnd]['timeStart'])) / 60;
            $endTimeGap = round($endTimeGap,2);
            $stationStatusList5[$indexEnd]['timeGap'] = $endTimeGap;
            $stationStatusList5[$indexEnd]['current'] = 57;
//            $stationStatusList5[$indexEnd]['flux'] = $endTimeGap * $pump['flux5'] / 600000;

            if($indexEnd == 0)
            {
                $stationStatusList5[$indexEnd]['index'] = 1;
            }
            else
            {
                $stationStatusList5[$indexEnd]['index'] = $stationStatusList5[$indexEnd-1]['index'] + 1;
            }

//            $totalTimeDay5 += $stationStatusList5[$indexEnd]['timeGap'];
        }

        $param = [
            'stationStatusList1' => $stationStatusList1, 'stationStatusList2' => $stationStatusList2,
            'stationStatusList3' => $stationStatusList3, 'stationStatusList4' => $stationStatusList4, 'stationStatusList5' => $stationStatusList5,
        ];

        return $param;
    }

    //读取Status_RY表实时数据
    public function getStatusYList($stationNum, $startTime, $endTime)
    {
        $statusTable = "stationRTY_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationRTYList = DB::table($statusTable)->whereBetween('Time', [$searchStartTime, $searchEndTime])->orderBy('Time', 'asc')
            ->get();

        return $stationRTYList;
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
}
