<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Logic\Employee\EmployeeLogic;
use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Maintenance\FailureValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

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

    protected $employeeLogic;

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
                                EquipmentLogic $equipmentLogic,StationLogic $stationLogic,UserLogic $userLogic,EmployeeLogic $employeeLogic)
    {
        $this->middleware('auth');

        $this->failureLogic = $failureLogic;
        $this->failureValidation =$failureValidation;

        $this->equipmentLogic = $equipmentLogic;
        $this->stationLogic = $stationLogic;
        $this->userLogic = $userLogic;
        $this->employeeLogic = $employeeLogic;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddFailureForm()
    {
        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
        $employees = $this->employeeLogic->getAllEmployees();

        $param = ['equipments' => $equipments,'stations' => $stations,'employees' => $employees];
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
        $station = $this->stationInfo($failure['station_id']);
        $reporter = $this->employeeInfo($failure['reporter_id']);
        $repairer = $this->employeeInfo($failure['repairer_id']);

        $failure['equipment_name'] = $equipment['name'];
        $failure['station_name'] = $station['name'];
        $failure['reporter_name'] = $reporter['name'];
        $failure['repairer_name'] = $repairer['name'];

        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
        $employees = $this->employeeLogic->getAllEmployees();

        $param = ['failure' => $failure,'equipments' => $equipments,
            'stations' => $stations,'employees' => $employees];
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
     * @param $employeeID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function employeeInfo($employeeID)
    {
        $employee = $this->employeeLogic->findEmployee($employeeID);
        return $employee;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failureList()
    {
        $stationID = Input::get('station_id', 0);
        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();

        $input = $this->failureValidation->failurePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);

        if($stationID == 0)
        {
            $failurePaginate = $this->failureLogic->getFailures($pageSize,$orderColumn,$orderDirection,$cursorPage);

            foreach($failurePaginate as $failure)
            {
                $equipment = $this->equipmentInfo($failure['equipment_id']);
                $station = $this->stationInfo($failure['station_id']);
                $reporter = $this->employeeInfo($failure['reporter_id']);
                $repairer = $this->employeeInfo($failure['repairer_id']);

                $failure['equipment_name'] = $equipment['name'];
                $failure['station_name'] = $station['name'];
                $failure['reporter_name'] = $reporter['name'];
                $failure['repairer_name'] = $repairer['name'];
            }
        }
        else
        {
            $failurePaginate = $this->getFailureListByStationID($stationID,$pageSize,$cursorPage);

            foreach($failurePaginate as $failure)
            {
                $equipment = $this->equipmentInfo($failure->equipment_id);
                $station = $this->stationInfo($failure->station_id);
                $reporter = $this->employeeInfo($failure->reporter_id);
                $repairer = $this->employeeInfo($failure->repairer_id);

                $failure->equipment_name = $equipment['name'];
                $failure->station_name = $station['name'];
                $failure->reporter_name = $reporter['name'];
                $failure->repairer_name = $repairer['name'];
            }
        }



        $param = ['stations' => $stations,'stationSelect' => $stationTemp,'failures' => $failurePaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了故障信息']);

        return view('failure.list',$param);
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
            $station = $this->stationInfo($failure['station_id']);
            $reporter = $this->employeeInfo($failure['reporter_id']);
            $repairer = $this->employeeInfo($failure['repairer_id']);

            $failure['equipment_name'] = $equipment['name'];
            $failure['station_name'] = $station['name'];
            $failure['reporter_name'] = $reporter['name'];
            $failure['repairer_name'] = $repairer['name'];
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

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了故障信息']);

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

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了故障信息']);

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

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了故障信息']);
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

    public function stationList()
    {
        $stations = $this->stationLogic->getAllStations();

        return $stations;
    }

    public function getFailureListByStationID($stationID, $size, $cursorPage)
    {
        $failureList = DB::table('failures')->where(['station_id'=>$stationID,'delete_process'=>0])->orderBy('created_at', 'asc')
            ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        return $failureList;
    }
}
