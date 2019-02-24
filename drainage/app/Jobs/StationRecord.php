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

    protected $reportController;
    protected $recordRepository;

    public function __construct(StatusReportController $reportController,StationRecordRepository $recordRepository)
    {
        $this->reportController = $reportController;
        $this->recordRepository = $recordRepository;
    }

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

        $recordList = $this->reportController->getStationRecords($stationNum, $startTime, $endTime);

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
        $this->recordRepository->insert($attributes);
    }
}
