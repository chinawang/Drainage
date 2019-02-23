<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2019/2/23
 * Time: 23:11
 */

namespace App\Jobs;

use Exception;
use App\Job as JobModel;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class Job
{
    use Dispatchable;

    /**
     * 任务类型
     *
     * @var string
     */
    protected $type = 'default';

    /**
     * 运行任务
     *
     * @return mixed
     */
    abstract protected function run();

    /**
     * 处理任务
     *
     * @return void
     */
    public function handle()
    {
        $job = new JobModel([
            'type' => $this->type,
        ]);

        try {
            $this->run();

            $job->status = JobModel::STATUS_SUCCESS;
        } catch (Exception $exception) {
            $job->status = JobModel::STATUS_FAILURE;
            $job->exception = (string) $exception;
        }

        $job->save();
    }
}
