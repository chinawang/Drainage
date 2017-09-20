<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/20
 * Time: 22:42
 */

namespace App\Http\Validations\Employee;

use App\Http\Logic\Employee\EmployeeLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class EmployeeValidation extends Validation
{
    protected $employeeLogic;

    public function __construct(Request $request,EmployeeLogic $employeeLogic)
    {
        parent::__construct($request);
        $this->employeeLogic = $employeeLogic;
    }

    /**
     * @return array
     */
    public function storeNewEmployee()
    {
        $input = $this->filterRequest([
            'name','number','job','department','cellphone','voip','call']);

        $rules = [
            'name' => ['string'],
            'number' => ['string'],
            'job' => ['string'],
            'department' => ['string'],
            'cellphone' => ['string'],
            'voip' => ['string'],
            'call' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    /**
     * @param $stationID
     * @return array
     */
    public function updateEmployee($employeeId)
    {
        $input = $this->filterRequest([
            'name','number','job','department','cellphone','voip','call']);

        $rules = [
            'name' => ['string'],
            'number' => ['string'],
            'job' => ['string'],
            'department' => ['string'],
            'cellphone' => ['string'],
            'voip' => ['string'],
            'call' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $employee= $this->employeeLogic->findEmployee($employeeId);
        if($employee->id != $employeeId) {
            throw new ForbiddenException();
        }

        return $input;
    }

    /**
     * @return mixed
     */
    public function deleteEmployee()
    {
        $employeeId = $this->filterRequest(['id']);
//        $employeeId = json_decode($input['id']);

        if (empty($employeeId)) {
            throw new BadRequestException();
        }

        return $employeeId;
    }

    /**
     * @return array
     */
    public function employeePaginate()
    {
        $input = $this->filterRequest(['page', 'cursor_page', 'order_column', 'order_direction']);

        $rules = [
            'page'            => ['integer'],
            'page_size'       => ['integer'],
            'cursor_page'     => ['integer'],
            'order_column'    => ['string', 'in:created_at,updated_at'],
            'order_direction' => ['string', 'in:asc,desc'],
        ];

        $validator  = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

}