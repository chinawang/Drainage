<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/24
 * Time: 01:49
 */

namespace App\Models\Station;

use Illuminate\Database\Eloquent\Model;

class PumpModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pumps';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

}