<?php

namespace App\Models\Station;

use Illuminate\Database\Eloquent\Model;

class StationEmployeeModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'station_employees';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
