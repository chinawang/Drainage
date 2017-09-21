<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/21
 * Time: 13:36
 */

namespace App\Http\Validations\Station;


use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;

class StationEmployeeValidation extends Validation
{
    /**
     * UserRoleValidation constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @return array
     */
    public function setStationEmployee()
    {
        $input = $this->filterRequest(['station_id','employees']);

        $rules = [
            'station_id' => ['required','integer', 'min:0'],
            'employees' => ['required','array'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }
}