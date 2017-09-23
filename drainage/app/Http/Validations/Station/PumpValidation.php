<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/24
 * Time: 02:01
 */

namespace App\Http\Validations\Station;

use App\Http\Logic\Station\PumpLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class PumpValidation extends Validation
{
    /**
     * @var PumpLogic
     */
    protected $pumpLogic;

    /**
     * PumpValidation constructor.
     * @param Request $request
     * @param PumpLogic $pumpLogic
     */
    public function __construct(Request $request,PumpLogic $pumpLogic)
    {
        parent::__construct($request);
        $this->pumpLogic = $pumpLogic;
    }

    /**
     * @return array
     */
    public function storeNewPump()
    {
        $input = $this->filterRequest([
            'station_id','flux1','flux2','flux3','flux4','flux5'
            ]);

        $rules = [
            'station_id' => ['integer', 'min:0'],
            'flux1' => ['numeric'],
            'flux2' => ['numeric'],
            'flux3' => ['numeric'],
            'flux4' => ['numeric'],
            'flux5' => ['numeric'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    /**
     * @param $pumpID
     * @return array
     */
    public function updatePump($pumpID)
    {
        $input = $this->filterRequest([
            'station_id','flux1','flux2','flux3','flux4','flux5'
        ]);

        $rules = [
            'station_id' => ['integer', 'min:0'],
            'flux1' => ['numeric'],
            'flux2' => ['numeric'],
            'flux3' => ['numeric'],
            'flux4' => ['numeric'],
            'flux5' => ['numeric'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $pump= $this->pumpLogic->findPump($pumpID);
        if($pump->id != $pumpID) {
            throw new ForbiddenException();
        }

        return $input;
    }

    /**
     * @return mixed
     */
    public function deletePump()
    {
        $input = $this->filterRequest(['id']);
        $pumpID = json_decode($input['id']);

        if (empty($pumpID)) {
            throw new BadRequestException();
        }

        return $pumpID;
    }

    /**
     * @return array
     */
    public function pumpPaginate()
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