<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:26
 */

namespace App\Http\Validations\Maintenance;


use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class FailureValidation extends Validation
{
    /**
     * @var FailureLogic
     */
    protected $failureLogic;

    /**
     * FailureValidation constructor.
     * @param Request $request
     * @param FailureLogic $failureLogic
     */
    public function __construct(Request $request,FailureLogic $failureLogic)
    {
        parent::__construct($request);
        $this->failureLogic = $failureLogic;
    }

    /**
     * @return array
     */
    public function storeNewFailure()
    {
        $input = $this->filterRequest([
            'station_id','equipment_id','failure_type','failure_description','equipment_status',
            'reporter_id','report_at','repair_process','repair_at','repairer_id'
        ]);

        $rules = [
            'station_id' => ['integer', 'min:0'],
            'equipment_id' => ['integer', 'min:0'],
            'failure_type' => ['string'],
            'failure_description' => ['string'],
            'equipment_status' => ['string'],
            'reporter_id' => ['integer', 'min:0'],
            'report_at' => ['string'],
            'repair_process' => ['integer'],
            'repair_at' => ['string'],
            'repairer_id' => ['integer', 'min:0'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    /**
     * @param $failureID
     * @return array
     */
    public function updateFailure($failureID)
    {
        $input = $this->filterRequest([
            'station_id','equipment_id','failure_type','failure_description','equipment_status',
            'reporter_id','report_at','repair_process','repair_at','repairer_id'
        ]);

        $rules = [
            'station_id' => ['integer', 'min:0'],
            'equipment_id' => ['integer', 'min:0'],
            'failure_type' => ['string'],
            'failure_description' => ['string'],
            'equipment_status' => ['string'],
            'reporter_id' => ['integer', 'min:0'],
            'report_at' => ['string'],
            'repair_process' => ['integer'],
            'repair_at' => ['string'],
            'repairer_id' => ['integer', 'min:0'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $failure= $this->failureLogic->findFailure($failureID);
        if($failure->id != $failureID) {
            throw new ForbiddenException();
        }

        return $input;
    }

    /**
     * @return mixed
     */
    public function deleteFailure()
    {
        $input = $this->filterRequest(['id']);
        $failureID = json_decode($input['id']);

        if (empty($failureID)) {
            throw new BadRequestException();
        }

        return $failureID;
    }

    /**
     * @return array
     */
    public function failurePaginate()
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