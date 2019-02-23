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
        $yesterday = Carbon::yesterday();
    }
}
