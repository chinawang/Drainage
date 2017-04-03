<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Validations\Maintenance\FailureValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FailureController extends Controller
{
    /**
     * @var FailureLogic
     */
    protected $failureLogic;

    /**
     * @var FailureValidation
     */
    protected $failureValidation;

    /**
     * FailureController constructor.
     * @param FailureLogic $failureLogic
     * @param FailureValidation $failureValidation
     */
    public function __construct(FailureLogic $failureLogic,FailureValidation $failureValidation)
    {
        $this->failureLogic = $failureLogic;
        $this->failureValidation =$failureValidation;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddFailureForm()
    {
        return view('views.failure.addFailure');
    }

    /**
     * @param $failureID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateFailureForm($failureID)
    {
        $failure = $this->failureLogic->findFailure($failureID);
        $param = ['failure' => $failure];
        return view('views.failure.updateFailure',$param);
    }

    /**
     * @param $failureID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function failureInfo($failureID)
    {
        $failure = $this->failureLogic->findFailure($failureID);
        return $failure;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failureList()
    {
        $input = [];
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $failurePaginate = $this->failureLogic->getFailures($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['failures' => $failurePaginate->toJson()];
        return view('views.failure.list',$param);
    }

    /**
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failureListOfStation($stationID)
    {
        $input = [];
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $failurePaginate = $this->failureLogic->getFailures($stationID,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['failures' => $failurePaginate->toJson()];
        return view('views.failure.listOfStation',$param);
    }

    /**
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewFailure()
    {
        $input = null;
        return $this->failureLogic->createFailure($input);
    }

    /**
     * @param $failureID
     * @return bool
     */
    public function updateFailure($failureID)
    {
        $input = null;
        return $this->failureLogic->updateFailure($failureID,$input);
    }

    /**
     * @param $failureID
     * @return mixed
     */
    public function deleteFailure($failureID)
    {
        return $this->failureLogic->deleteFailure($failureID);
    }
}
