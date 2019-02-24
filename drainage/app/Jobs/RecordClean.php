<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2019/2/23
 * Time: 23:29
 */

namespace App\Jobs;

use App\Job as JobModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RecordClean extends Job
{
    protected $type = JobModel::TYPE_RECORD_CLEAN;

    /**
     * 处理任务
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function run()
    {

        $firstDay = Carbon::now()->subMonth()->firstOfMonth();//上个月第1天
        $lastDay = Carbon::now()->subMonth()->lastOfMonth();//上个月最后1天
        $cleanStartTime = date('Y-m-d 00:00:00', strtotime($firstDay));
        $cleanEndTime = date('Y-m-d 23:59:59', strtotime($lastDay));

//        $cleanStartTime = date("2017-10-10 00:00:00");
//        $cleanStartTime = date("2017-10-10 23:59:59");
        for($i=1;$i<=38;$i++)
        {
            $tableName = 'stationRTY_'.$i;
            DB::table($tableName)->whereBetween('Time', array($cleanStartTime, $cleanEndTime))->delete();
        }
    }
}
