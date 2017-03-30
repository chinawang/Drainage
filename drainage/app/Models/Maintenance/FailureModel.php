<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class FailureModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'failures';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
