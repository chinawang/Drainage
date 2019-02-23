<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2019/2/23
 * Time: 23:14
 */

namespace App\Jobs;

use App\Job as JobModel;
use App\Models\Station\StationRecordModel;
use Illuminate\Support\Facades\DB;

class StationRecord extends Job
{
    protected $type = JobModel::TYPE_STATION_RECORD;

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
    }

    /**
     * 获取运行记录
     *
     * @return array
     */
    protected function getRecords()
    {
//        return Idol::orderBy('is_quit')->orderByDesc('popularity')->orderBy('idol_id')
//            ->get(['id as idol_id', 'name', 'avatar', 'popularity', 'is_quit'])
//            ->toArray();
    }

    /**
     * 保存运行记录
     *
     * @param array $records
     * @return void
     */
    protected function saveRecords(array $records)
    {
//        Rank::truncate();
//
//        Rank::insert($ranks);
//
//        Rank::forgetCache();
    }
}
