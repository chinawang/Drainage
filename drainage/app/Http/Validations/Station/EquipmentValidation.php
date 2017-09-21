<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:25
 */

namespace App\Http\Validations\Station;


use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class EquipmentValidation extends Validation
{
    /**
     * @var EquipmentLogic
     */
    protected $equipmentLogic;

    /**
     * EquipmentValidation constructor.
     * @param Request $request
     * @param EquipmentLogic $equipmentLogic
     */
    public function __construct(Request $request,EquipmentLogic $equipmentLogic)
    {
        parent::__construct($request);
        $this->equipmentLogic = $equipmentLogic;
    }

    /**
     * @return array
     */
    public function storeNewEquipment()
    {
        $input = $this->filterRequest([
            'station_id','equipment_number','name','type','producer',
            'department','leader_id','custodian_id','quantity','alteration',
            'capacity','flux','range'
        ]);

        $rules = [
            'station_id' => ['integer', 'min:0'],
            'equipment_number' => ['string'],
            'name' => ['string'],
            'type' => ['string'],
            'producer' => ['string'],
            'department' => ['string'],
            'leader_id' => ['integer', 'min:0'],
            'custodian_id' => ['integer', 'min:0'],
            'quantity' => ['string'],
            'alteration' => ['string'],
            'capacity' => ['string'],
            'flux' => ['string'],
            'range' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    /**
     * @param $equipmentID
     * @return array
     */
    public function updateEquipment($equipmentID)
    {
        $input = $this->filterRequest([
            'station_id','equipment_number','name','type','producer',
            'department','leader_id','custodian_id','quantity','alteration',
            'capacity','flux','range'
        ]);

        $rules = [
            'station_id' => ['integer', 'min:0'],
            'equipment_number' => ['string'],
            'name' => ['string'],
            'type' => ['string'],
            'producer' => ['string'],
            'department' => ['string'],
            'leader_id' => ['integer', 'min:0'],
            'custodian_id' => ['integer', 'min:0'],
            'quantity' => ['string'],
            'alteration' => ['string'],
            'capacity' => ['string'],
            'flux' => ['string'],
            'range' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $equipment= $this->equipmentLogic->findEquipment($equipmentID);
        if($equipment->id != $equipmentID) {
            throw new ForbiddenException();
        }

        return $input;
    }

    /**
     * @return mixed
     */
    public function deleteEquipment()
    {
        $input = $this->filterRequest(['id']);
        $equipmentID = json_decode($input['id']);

        if (empty($equipmentID)) {
            throw new BadRequestException();
        }

        return $equipmentID;
    }

    /**
     * @return array
     */
    public function equipmentPaginate()
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