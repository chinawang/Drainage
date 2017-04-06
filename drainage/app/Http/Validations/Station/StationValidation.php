<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:24
 */

namespace App\Http\Validations\Station;


use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Validation;

class StationValidation extends Validation
{
    protected $stationLogic;

    public function __construct(Request $request,StationLogic $stationLogic)
    {
        parent::__construct($request);
        $this->stationLogic = $stationLogic;
    }

    public function storeNewStation()
    {
        $input = $this->filterRequest([
            'station_number','name','address','lat','lng'
        ]);

        $rules = [
            'station_number' => ['string'],
            'name' => ['string'],
            'address' => ['string'],
            'lat' => ['string'],
            'lng' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    public function updateStation($stationID)
    {
        $input = $this->filterRequest([
            'station_number','name','address','lat','lng'
        ]);

        $rules = [
            'station_number' => ['string'],
            'name' => ['string'],
            'address' => ['string'],
            'lat' => ['string'],
            'lng' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $station= $this->stationLogic->findStation($stationID);
        if($station->id != $stationID) {
            throw new ForbiddenException();
        }

        return $input;
    }

    public function deleteStation()
    {
        $input = $this->filterRequest(['id']);
        $stationID = json_decode($input['id']);

        if (empty($stationID)) {
            throw new BadRequestException();
        }

        return $stationID;
    }
}