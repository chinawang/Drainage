<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employees';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
