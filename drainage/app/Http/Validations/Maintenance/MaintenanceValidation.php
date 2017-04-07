<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:27
 */

namespace App\Http\Validations\Maintenance;


use App\Http\Logic\Maintenance\MaintenanceLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class MaintenanceValidation extends Validation
{
    /**
     * @var MaintenanceLogic
     */
    protected $maintenanceLogic;

    /**
     * MaintenanceValidation constructor.
     * @param Request $request
     * @param MaintenanceLogic $maintenanceLogic
     */
    public function __construct(Request $request,MaintenanceLogic $maintenanceLogic)
    {
        parent::__construct($request);
        $this->maintenanceLogic = $maintenanceLogic;
    }

    /**
     * @return array
     */
    public function storeNewMaintenance()
    {
        $input = $this->filterRequest([
            'failure_id','station_id','equipment_id','repairer_id','failure_reason',
            'repair_solution','repair_process','repair_at'
        ]);

        $rules = [
            'failure_id' => ['integer', 'min:0'],
            'station_id' => ['integer', 'min:0'],
            'equipment_id' => ['integer', 'min:0'],
            'repairer_id' => ['integer', 'min:0'],
            'failure_reason' => ['string'],
            'repair_solution' => ['string'],
            'repair_process' => ['integer'],
            'repair_at' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    /**
     * @param $maintenanceID
     * @return array
     */
    public function updateMaintenance($maintenanceID)
    {
        $input = $this->filterRequest([
            'failure_id','station_id','equipment_id','repairer_id','failure_reason',
            'repair_solution','repair_process','repair_at'
        ]);

        $rules = [
            'failure_id' => ['integer', 'min:0'],
            'station_id' => ['integer', 'min:0'],
            'equipment_id' => ['integer', 'min:0'],
            'repairer_id' => ['integer', 'min:0'],
            'failure_reason' => ['string'],
            'repair_solution' => ['string'],
            'repair_process' => ['integer'],
            'repair_at' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $maintenance= $this->maintenanceLogic->findMaintenance($maintenanceID);
        if($maintenance->id != $maintenanceID) {
            throw new ForbiddenException();
        }

        return $input;
    }

    /**
     * @return mixed
     */
    public function deleteMaintenance()
    {
        $input = $this->filterRequest(['id']);
        $maintenanceID = json_decode($input['id']);

        if (empty($maintenanceID)) {
            throw new BadRequestException();
        }

        return $maintenanceID;
    }

    /**
     * @return array
     */
    public function maintenancePaginate()
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