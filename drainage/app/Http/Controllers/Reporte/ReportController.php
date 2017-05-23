<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Logic\Maintenance\MaintenanceLogic;
use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Station\StationValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ReportController extends Controller
{

    /**
     * @var StationLogic
     */
    protected $stationLogic;

    /**
     * @var StationValidation
     */
    protected $stationValidation;

    /**
     * @var FailureLogic
     */
    protected $failureLogic;

    /**
     * @var EquipmentLogic
     */
    protected $equipmentLogic;

    /**
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * @var MaintenanceLogic
     */
    protected $maintenanceLogic;

    /**
     * ReportController constructor.
     * @param StationLogic $stationLogic
     * @param StationValidation $stationValidation
     * @param EquipmentLogic $equipmentLogic
     * @param UserLogic $userLogic
     * @param FailureLogic $failureLogic
     * @param MaintenanceLogic $maintenanceLogic
     */
    public function __construct(StationLogic $stationLogic, StationValidation $stationValidation,
                                EquipmentLogic $equipmentLogic, UserLogic $userLogic,
                                FailureLogic $failureLogic, MaintenanceLogic $maintenanceLogic)
    {
        $this->middleware('auth');

        $this->stationLogic = $stationLogic;
        $this->stationValidation = $stationValidation;
        $this->equipmentLogic = $equipmentLogic;
        $this->userLogic = $userLogic;
        $this->failureLogic = $failureLogic;
        $this->maintenanceLogic = $maintenanceLogic;
    }

    public function showWaterReport()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-01 H:i");
            $endTime = date("Y-m-d H:i");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

return $searchEndTime;

        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];
        $stations = $this->stationList();

        $input = $this->stationValidation->stationPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $pageSize = array_get($input, 'page_size', 20);
        $orderColumn = array_get($input, 'order_column', 'created_at');
        $orderDirection = array_get($input, 'order_direction', 'asc');

        $stationRTPaginate = $this->getStationRTList($stationNum, $pageSize, $cursorPage);


        $param = ['stations' => $stations, 'waterList' => $stationRTPaginate,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        return view('report.stationWater', $param);
    }

    public function showRunningReport()
    {
        $stationID = 1;
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];
        $stations = $this->stationList();

        $input = $this->stationValidation->stationPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $pageSize = array_get($input, 'page_size', 20);
        $orderColumn = array_get($input, 'order_column', 'created_at');
        $orderDirection = array_get($input, 'order_direction', 'asc');

        $stationRTPaginate = $this->getStationRTList($stationNum, $pageSize, $cursorPage);

        $param = ['stations' => $stations, 'runList' => $stationRTPaginate, 'stationSelect' => $stationTemp];
//        return $param;

        return view('report.stationRunning', $param);
    }

    public function showStatusReport()
    {
        $stationID = 1;
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];
        $stations = $this->stationList();

        $input = $this->stationValidation->stationPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $pageSize = array_get($input, 'page_size', 20);
        $orderColumn = array_get($input, 'order_column', 'created_at');
        $orderDirection = array_get($input, 'order_direction', 'asc');

        $stationRTPaginate = $this->getStationRTList($stationNum, $pageSize, $cursorPage);

        $param = ['stations' => $stations, 'statusList' => $stationRTPaginate, 'stationSelect' => $stationTemp];
//        return $param;

        return view('report.stationStatus', $param);
    }

    public function showFailureReport()
    {
        $stationID = 1;
        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();

        $input = $this->stationValidation->stationPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $pageSize = array_get($input, 'page_size', 20);
        $orderColumn = array_get($input, 'order_column', 'created_at');
        $orderDirection = array_get($input, 'order_direction', 'asc');

        // 故障统计

        $failurePaginate = $this->failureLogic->getFailures($pageSize, $orderColumn, $orderDirection, $cursorPage);

        foreach ($failurePaginate as $failure) {
            $equipment = $this->equipmentInfo($failure['equipment_id']);
            $station = $this->stationInfo($failure['station_id']);
            $reporter = $this->userInfo($failure['reporter_id']);
            $repairer = $this->userInfo($failure['repairer_id']);

            $failure['equipment_name'] = $equipment['name'];
            $failure['station_name'] = $station['name'];
            $failure['reporter_name'] = $reporter['realname'];
            $failure['repairer_name'] = $repairer['realname'];
        }

        $param = ['stations' => $stations, 'failures' => $failurePaginate, 'stationSelect' => $stationTemp];
//        return $param;

        return view('report.stationFailure', $param);
    }

    public function showMaintenanceReport()
    {
        $stationID = 1;
        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();

        $input = $this->stationValidation->stationPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $pageSize = array_get($input, 'page_size', 20);
        $orderColumn = array_get($input, 'order_column', 'created_at');
        $orderDirection = array_get($input, 'order_direction', 'asc');

        // 维修统计

        $maintenancePaginate = $this->maintenanceLogic->getMaintenances($pageSize, $orderColumn, $orderDirection, $cursorPage);

        foreach ($maintenancePaginate as $maintenance) {
            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
            $repairer = $this->userInfo($maintenance['repairer_id']);

            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
            $maintenance['repairer_name'] = $repairer['realname'];
        }

        $param = ['stations' => $stations, 'maintenances' => $maintenancePaginate, 'stationSelect' => $stationTemp];
//        return $param;

        return view('report.stationMaintenance', $param);
    }


    public function getStationRTList($stationNum, $size, $cursorPage)
    {
        $stationTable = "stationRT_" . $stationNum;
//        $stationRTList = DB::select('select * from '.$stationTable.' order by Time asc')->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
        $stationRTList = DB::table($stationTable)->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        return $stationRTList;
    }

    public function stationList()
    {
        $stations = $this->stationLogic->getAllStations();

        return $stations;
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


}
