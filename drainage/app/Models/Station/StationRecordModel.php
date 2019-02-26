<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2019/2/23
 * Time: 23:16
 */

namespace App\Models\Station;

use Illuminate\Database\Eloquent\Model;

class StationRecordModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'station_records';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $fillable = [
        'station_num','pump_num','run_at','start_at','stop_at','start_value','stop_value','run_time','run_current','created_at','updated_at'
    ];

    public $timestamps = true;
}
