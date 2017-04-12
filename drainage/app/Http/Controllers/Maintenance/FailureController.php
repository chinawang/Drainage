<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Logic\User\UserLogic;
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
     * @var EquipmentLogic
     */
    protected $equipmentLogic;

    /**
     * @var StationLogic
     */
    protected $stationLogic;

    /**
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * @var FailureValidation
     */
    protected $failureValidation;

    /**
     * FailureController constructor.
     * @param FailureLogic $failureLogic
     * @param FailureValidation $failureValidation
     * @param EquipmentLogic $equipmentLogic
     * @param StationLogic $stationLogic
     * @param UserLogic $userLogic
     */
    public function __construct(FailureLogic $failureLogic,FailureValidation $failureValidation,
                                EquipmentLogic $equipmentLogic,StationLogic $stationLogic,UserLogic $userLogic)
    {
        $this->failureLogic = $failureLogic;
        $this->failureValidation =$failureValidation;

        $this->equipmentLogic = $equipmentLogic;
        $this->stationLogic = $stationLogic;
        $this->userLogic = $userLogic;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddFailureForm()
    {
        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
        $users = $this->userLogic->getAllUsers();

        $param = ['equipments' => $equipments,'stations' => $stations,'users' => $users];
        return view('failure.addFailure',$param);
    }

    /**
     * @param $failureID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateFailureForm($failureID)
    {
        $failure = $this->failureInfo($failureID);

        $equipment = $this->equipmentInfo($failure['equipment_id']);
        $station = $this->stationInfo($equipment['station_id']);
        $reporter = $this->userInfo($equipment['reporter_id']);
        $repairer = $this->userInfo($equipment['repairer_id']);

        $failure['equipment_name'] = $equipment['name'];
        $failure['station_name'] = $station['name'];
        $failure['reporter_name'] = $reporter['realname'];
        $failure['repairer_name'] = $repairer['realname'];

        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
        $users = $this->userLogic->getAllUsers();

        $param = ['failure' => $failure,'equipments' => $equipments,
            'stations' => $stations,'users' => $users];
        return view('failure.updateFailure',$param);
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
     * 查询设备信息
     *
     * @param $equipmentID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function equipmentInfo($equipmentID)
    {
        $equipment = $this->equipmentLogic->findEquipment($equipmentID);
        return $equipment;
    }

    /**
     * 查询泵站信息
     *
     * @param $stationID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function stationInfo($stationID)
    {
        $station = $this->stationLogic->findStation($stationID);
        return $station;
    }

    /**
     * 查询人员信息
     *
     * @param $userID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userInfo($userID)
    {
        $user = $this->userLogic->findUser($userID);
        return $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failureList()
    {
        $input = $this->failureValidation->failurePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $failurePaginate = $this->failureLogic->getFailures($pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($failurePaginate as $failure)
        {
            $equipment = $this->equipmentInfo($failure['equipment_id']);
            $station = $this->stationInfo($equipment['station_id']);
            $reporter = $this->userInfo($equipment['reporter_id']);
            $repairer = $this->userInfo($equipment['repairer_id']);

            $failure['equipment_name'] = $equipment['name'];
            $failure['station_name'] = $station['name'];
            $failure['reporter_name'] = $reporter['realname'];
            $failure['repairer_name'] = $repairer['realname'];
        }

        $param = ['failures' => $failurePaginate];
//        return view('failure.list',$param);
        return $param;
    }

    /**
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failureListOfStation($stationID)
    {
        $input = $this->failureValidation->failurePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $failurePaginate = $this->failureLogic->getFailuresByStation($stationID,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($failurePaginate as $failure)
        {
            $equipment = $this->equipmentInfo($failure['equipment_id']);
            $station = $this->stationInfo($equipment['station_id']);
            $reporter = $this->userInfo($equipment['reporter_id']);
            $repairer = $this->userInfo($equipment['repairer_id']);

            $failure['equipment_name'] = $equipment['name'];
            $failure['station_name'] = $station['name'];
            $failure['reporter_name'] = $reporter['realname'];
            $failure['repairer_name'] = $repairer['realname'];
        }

        $param = ['failures' => $failurePaginate];
        return view('failure.listOfStation',$param);
    }

    /**
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewFailure()
    {
        $input = $this->failureValidation->storeNewFailure();
        $result = $this->failureLogic->createFailure($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
            return redirect('/failure/lists');
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '保存失败!',
                'message'   => '数据未保存成功,请稍后重试!',
                'level'     => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * @param $failureID
     * @return bool
     */
    public function updateFailure($failureID)
    {
        $input = $this->failureValidation->updateFailure($failureID);
        $result = $this->failureLogic->updateFailure($failureID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
            return redirect('/failure/lists');
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '保存失败!',
                'message'   => '数据未保存成功,请稍后重试!',
                'level'     => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * @return mixed
     */
    public function deleteFailure($failureID)
    {
//        $failureID = $this->failureValidation->deleteFailure();
        $result = $this->failureLogic->deleteFailure($failureID);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '删除成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '删除失败!',
                'message'   => '数据未删除成功,请稍后重试!',
                'level'     => 'error'
            ]);
        }

        return redirect('/failure/lists');
    }
}
