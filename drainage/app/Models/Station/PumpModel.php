<?php

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
