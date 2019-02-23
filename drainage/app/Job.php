<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2019/2/23
 * Time: 23:06
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    const TYPE_STATION_RECORD = 'station_record';//专长泵站运行记录
    const TYPE_RECORD_CLEAN = 'record_clean';//清理实时数据

    const STATUS_SUCCESS = 'success';
    const STATUS_FAILURE = 'failure';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'status', 'exception', 'run_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['run_at'];
}
