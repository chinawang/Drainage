<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Logic\Employee\EmployeeLogic;
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
use Illuminate\Support\Facades\Auth;

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

    protected $employeeLogic;

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
                                FailureLogic $failureLogic, MaintenanceLogic $maintenanceLogic,EmployeeLogic $employeeLogic)
    {
        $this->middleware('auth');

        $this->stationLogic = $stationLogic;
        $this->stationValidation = $stationValidation;
        $this->equipmentLogic = $equipmentLogic;
        $this->userLogic = $userLogic;
        $this->failureLogic = $failureLogic;
        $this->maintenanceLogic = $maintenanceLogic;
        $this->employeeLogic = $employeeLogic;
    }

    public function showWaterReport()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';


        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];
        $stations = $this->stationList();

//        $input = $this->stationValidation->stationPaginate();

        $cursorPage = null;
        $pageSize = 20;

        $stationRTPaginate = $this->getStationRTList($stationNum, $pageSize, $cursorPage,$searchStartTime,$searchEndTime);


        $param = ['stations' => $stations, 'waterList' => $stationRTPaginate,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站水位统计']);

        return view('report.stationWater', $param);
    }

    public function showRunningReport()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];
        $stations = $this->stationList();

        //        $input = $this->stationValidation->stationPaginate();

        $cursorPage = null;
        $pageSize = 20;

        $stationRTPaginate = $this->getStationRTList($stationNum, $pageSize, $cursorPage,$searchStartTime,$searchEndTime);

        $param = ['stations' => $stations, 'runList' => $stationRTPaginate,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站运行统计']);

        return view('report.stationRunning', $param);
    }

    public function showStatusReport()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];
        $stations = $this->stationList();

        //        $input = $this->stationValidation->stationPaginate();

        $cursorPage = null;
        $pageSize = 20;

        $stationRTPaginate = $this->getStationRTList($stationNum, $pageSize, $cursorPage,$searchStartTime,$searchEndTime);

        $param = ['stations' => $stations, 'statusList' => $stationRTPaginate,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站启动状态统计']);

        return view('report.stationStatus', $param);
    }

    public function showFailureReport()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();

        //        $input = $this->stationValidation->stationPaginate();

        $cursorPage = null;
        $pageSize = 20;
//        $orderColumn = 'created_at';
//        $orderDirection = 'asc';

        // 故障统计

//        $failurePaginate = $this->failureLogic->getFailures($pageSize, $orderColumn, $orderDirection, $cursorPage);

        $failurePaginate = $this->getFailureListByStationID($stationID,$pageSize,$cursorPage,$searchStartTime,$searchEndTime);

        foreach ($failurePaginate as $failure) {
            $equipment = $this->equipmentInfo($failure->equipment_id);
            $station = $this->stationInfo($failure->station_id);
            $reporter = $this->employeeInfo($failure->reporter_id);
            $repairer = $this->employeeInfo($failure->repairer_id);

            $failure->equipment_name = $equipment['name'];
            $failure->station_name = $station['name'];
            $failure->reporter_name = $reporter['name'];
            $failure->repairer_name = $repairer['name'];
        }

        $param = ['stations' => $stations, 'failures' => $failurePaginate,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了设备故障统计']);

        return view('report.stationFailure', $param);
    }

    public function showMaintenanceReport()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationTemp = $this->stationInfo($stationID);
        $stations = $this->stationList();

        //        $input = $this->stationValidation->stationPaginate();

        $cursorPage = null;
        $pageSize = 20;
//        $orderColumn = 'created_at';
//        $orderDirection = 'asc';

        // 维修统计

//        $maintenancePaginate = $this->maintenanceLogic->getMaintenances($pageSize, $orderColumn, $orderDirection, $cursorPage);

        $maintenancePaginate = $this->getMaintenanceListByStationID($stationID,$pageSize,$cursorPage,$searchStartTime,$searchEndTime);

        foreach ($maintenancePaginate as $maintenance) {
            $equipment = $this->equipmentInfo($maintenance->equipment_id);
            $station = $this->stationInfo($maintenance->station_id);
            $repairer = $this->employeeInfo($maintenance->repairer_id);

            $maintenance->equipment_name = $equipment['name'];
            $maintenance->station_name = $station['name'];
            $maintenance->repairer_name = $repairer['name'];
        }

        $param = ['stations' => $stations, 'maintenances' => $maintenancePaginate,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了设备维修统计']);

        return view('report.stationMaintenance', $param);
    }


    public function getStationRTList($stationNum, $size, $cursorPage,$searchStartTime,$searchEndTime)
    {
        $stationTable = "stationRT_" . $stationNum;
//        $stationRTList = DB::select('select * from '.$stationTable.' order by Time asc')->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $stationRTList = DB::table($stationTable)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')
                ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        }
        else
        {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
                ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
        }

        return $stationRTList;
    }

    public function getFailureListByStationID($stationID, $size, $cursorPage,$searchStartTime,$searchEndTime)
    {
        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $failureList = DB::table('failures')->where(['station_id'=>$stationID,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'asc')
                ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        }
        else
        {
            $failureList = DB::table('failures')->where(['station_id'=>$stationID,'delete_process'=>0])->orderBy('created_at', 'asc')
                ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
        }

        return $failureList;
    }

    public function getMaintenanceListByStationID($stationID, $size, $cursorPage,$searchStartTime,$searchEndTime)
    {
        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $MaintenanceList = DB::table('maintenances')->where(['station_id'=>$stationID,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'asc')
                ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        }
        else
        {
            $MaintenanceList = DB::table('maintenances')->where(['station_id'=>$stationID,'delete_process'=>0])->orderBy('created_at', 'asc')
                ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
        }

        return $MaintenanceList;
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
     * @param $employeeID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function employeeInfo($employeeID)
    {
        $employee = $this->employeeLogic->findEmployee($employeeID);
        return $employee;
    }

    public function getStationRTAll($stationID,$startTime,$endTime)
    {
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationTable = "stationRT_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';


        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $stationRTList = DB::table($stationTable)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')
                ->get();

        }
        else
        {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
                ->get();
        }

        return $stationRTList;
    }

    public function stationRTHistory($stationID,$startTime,$endTime)
    {
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationTable = "stationRT_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';


        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
//            $stationRTList = DB::table($stationTable)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')
//                ->get();
            //            SELECT * from (Select *,(@rowNum:=@rowNum+1) as rowNo From `stationRT_1`, (Select (@rowNum :=0) ) b order by `Time` asc) as a where mod(a.rowNo, 60) = 1

            $stationRTList = DB::select('SELECT * from (Select *,(@rowNum:=@rowNum+1) as rowNo From '.$stationTable.', (Select (@rowNum :=0) ) b where Time > ? and Time < ?) as a where mod(a.rowNo, 60) = 1',[$searchStartTime,$searchEndTime])
                ;

        }
        else
        {
//            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
//                ->get();

            $stationRTList = DB::select('SELECT * from (Select *,(@rowNum:=@rowNum+1) as rowNo From '.$stationTable.', (Select (@rowNum :=0) ) b ) as a where mod(a.rowNo, 60) = 1')
            ;
        }

        return response()->json(array('stationRTHistory'=> $stationRTList), 200);
    }

    public function statusRTHistory($stationID,$startTime,$endTime)
    {
        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationTable = "stationRT_" . $stationNum;

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';


        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $stationRTList = DB::table($stationTable)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')
                ->get();

        }
        else
        {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
                ->get();
        }

        $stationStatusList1 = $this->getStationStatusList($stationRTList,'yx_b1');
        $stationStatusList2 = $this->getStationStatusList($stationRTList,'yx_b2');
        $stationStatusList3 = $this->getStationStatusList($stationRTList,'yx_b3');
        $stationStatusList4 = $this->getStationStatusList($stationRTList,'yx_b4');
        $stationStatusList5 = $this->getStationStatusList($stationRTList,'yx_gs1');
        $stationStatusList6 = $this->getStationStatusList($stationRTList,'yx_gs2');
        $stationStatusList7 = $this->getStationStatusList($stationRTList,'yx_jl');

        $param = array('stationStatusList1'=> $stationStatusList1,'stationStatusList2'=> $stationStatusList2,
            'stationStatusList3'=> $stationStatusList3,'stationStatusList4'=> $stationStatusList4,
            'stationStatusList5'=> $stationStatusList5,'stationStatusList6'=> $stationStatusList6,
            'stationStatusList7'=> $stationStatusList7);

        return response()->json($param, 200);
    }

    public function getStationStatusList($stationRTList,$equipmentCode)
    {
        $stationStatusList = [];
        $index = 1;

        for($i = 0 ; $i < count($stationRTList)-1;$i++)
        {
            $sRunning = [];

            if($stationRTList[$i]->$equipmentCode == 0 && $stationRTList[$i+1]->$equipmentCode == 1 )
            {
                $sRunning['timeStart'] = $stationRTList[$i+1]->Time;
                $sRunning['timeEnd'] = '';
                $sRunning['timeGap'] = '';
                $sRunning['index'] = $index;
                $index ++;
                array_push($stationStatusList,$sRunning);
            }
            if($stationRTList[$i]->$equipmentCode == 1 && $stationRTList[$i+1]->$equipmentCode == 0 )
            {
                $sRunning['timeStart'] = '';
                $sRunning['timeEnd'] = $stationRTList[$i+1]->Time;
                if($index > 1)
                {
                    $sRunning['timeGap'] = abs(strtotime($sRunning['timeEnd']) - strtotime($stationStatusList[$index -2]['timeStart']))/60;
                    $sRunning['timeGap'] = round($sRunning['timeGap']);
                    $stationStatusList[$index -2]['timeEnd'] = $sRunning['timeEnd'];
                    $stationStatusList[$index -2]['timeGap'] = $sRunning['timeGap'];
                }

            }

        }

        return $stationStatusList;
    }

}
