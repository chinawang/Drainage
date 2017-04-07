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
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class StationValidation extends Validation
{
    /**
     * @var StationLogic
     */
    protected $stationLogic;

    /**
     * StationValidation constructor.
     * @param Request $request
     * @param StationLogic $stationLogic
     */
    public function __construct(Request $request,StationLogic $stationLogic)
    {
        parent::__construct($request);
        $this->stationLogic = $stationLogic;
    }

    /**
     * @return array
     */
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

    /**
     * @param $stationID
     * @return array
     */
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

    /**
     * @return mixed
     */
    public function deleteStation()
    {
        $input = $this->filterRequest(['id']);
        $stationID = json_decode($input['id']);

        if (empty($stationID)) {
            throw new BadRequestException();
        }

        return $stationID;
    }

    /**
     * @return array
     */
    public function stationPaginate()
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