<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Logic\Employee\EmployeeLogic;
use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Logic\Maintenance\MaintenanceLogic;
use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Maintenance\FailureValidation;
use App\Http\Validations\Maintenance\MaintenanceValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * @var MaintenanceLogic
     */
    protected $maintenanceLogic;

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
     * @var MaintenanceValidation
     */
    protected $maintenanceValidation;

    /**
     * @var FailureValidation
     */
    protected $failureValidation;

    /**
     * MaintenanceController constructor.
     * @param MaintenanceLogic $maintenanceLogic
     * @param MaintenanceValidation $maintenanceValidation
     * @param FailureValidation $failureValidation
     * @param FailureLogic $failureLogic
     * @param EquipmentLogic $equipmentLogic
     * @param StationLogic $stationLogic
     * @param UserLogic $userLogic
     */
    public function __construct(MaintenanceLogic $maintenanceLogic,MaintenanceValidation $maintenanceValidation,FailureValidation $failureValidation,
                                FailureLogic $failureLogic,EquipmentLogic $equipmentLogic,StationLogic $stationLogic,UserLogic $userLogic,EmployeeLogic $employeeLogic)
    {
        $this->middleware('auth');

        $this->maintenanceLogic = $maintenanceLogic;
        $this->maintenanceValidation = $maintenanceValidation;
        $this->failureValidation = $failureValidation;
        $this->failureLogic = $failureLogic;
        $this->equipmentLogic = $equipmentLogic;
        $this->stationLogic = $stationLogic;
        $this->userLogic = $userLogic;
        $this->employeeLogic = $employeeLogic;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddMaintenanceForm($failureID)
    {
        $failure = $this->failureInfo($failureID);
        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
        $employees = $this->employeeLogic->getAllEmployees();

        $param = ['failure' => $failure,'equipments' => $equipments,'stations' => $stations,'employees' => $employees];
        return view('maintenance.addMaintenance',$param);
    }

    /**
     * @param $maintenanceID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateMaintenanceForm($maintenanceID)
    {
        $maintenance = $this->maintenanceLogic->findMaintenance($maintenanceID);

//        $equipment = $this->equipmentInfo($maintenance['equipment_id']);
        $station = $this->stationInfo($maintenance['station_id']);
//        $repairer = $this->employeeInfo($maintenance['repairer_id']);

//        $maintenance['equipment_name'] = $equipment['name'];
        $maintenance['station_name'] = $station['name'];
//        $maintenance['repairer_name'] = $repairer['name'];

//        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
//        $employees = $this->employeeLogic->getAllEmployees();

        $param = ['maintenance' => $maintenance, 'stations' => $stations];

        return view('maintenance.updateMaintenance',$param);
    }

    /**
     * @param $maintenanceID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function maintenanceInfo($maintenanceID)
    {
        $maintenance = $this->maintenanceLogic->findMaintenance($maintenanceID);
        return $maintenance;
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
     * @return mixed
     */
    public function failureList()
    {
        $input = $this->failureValidation->failurePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'desc');
        $pageSize        = array_get($input, 'page_size', 20);
        $failurePaginate = $this->failureLogic->getFailures($pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($failurePaginate as $failure)
        {
//            $equipment = $this->equipmentInfo($failure['equipment_id']);
            $station = $this->stationInfo($failure['station_id']);
//            $reporter = $this->employeeInfo($failure['reporter_id']);
//            $repairer = $this->employeeInfo($failure['repairer_id']);

//            $failure['equipment_name'] = $equipment['name'];
            $failure['station_name'] = $station['name'];
//            $failure['reporter_name'] = $reporter['name'];
//            $failure['repairer_name'] = $repairer['name'];
        }

        return $failurePaginate;
    }

    public function failureListAll()
    {
        $input = $this->failureValidation->failurePaginate();

        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'desc');
        $failureList = $this->failureLogic->getAllFailureOrderBy($orderColumn,$orderDirection);

        foreach($failureList as $failure)
        {
//            $equipment = $this->equipmentInfo($failure['equipment_id']);
            $station = $this->stationInfo($failure['station_id']);
//            $reporter = $this->employeeInfo($failure['reporter_id']);
//            $repairer = $this->employeeInfo($failure['repairer_id']);

//            $failure['equipment_name'] = $equipment['name'];
            $failure['station_name'] = $station['name'];
//            $failure['reporter_name'] = $reporter['name'];
//            $failure['repairer_name'] = $repairer['name'];
        }

        return $failureList;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintenanceList()
    {
        $input = $this->maintenanceValidation->maintenancePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'desc');
        $pageSize        = array_get($input, 'page_size', 20);
        $maintenancePaginate = $this->maintenanceLogic->getMaintenances($pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($maintenancePaginate as $maintenance)
        {
//            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
//            $repairer = $this->employeeInfo($maintenance['repairer_id']);

//            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
//            $maintenance['repairer_name'] = $repairer['name'];
        }

        $failureList = $this->failureListAll();

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了维修记录']);

        $param = ['maintenances' => $maintenancePaginate,'failures' => $failureList];
        return view('maintenance.list',$param);
    }

    /**
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintenanceListOfStation($stationID)
    {
        $input = $this->maintenanceValidation->maintenancePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'desc');
        $pageSize        = array_get($input, 'page_size', 20);
        $maintenancePaginate = $this->maintenanceLogic->getMaintenancesByStation($stationID,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($maintenancePaginate as $maintenance)
        {
//            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
//            $repairer = $this->employeeInfo($maintenance['repairer_id']);

//            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
//            $maintenance['repairer_name'] = $repairer['name'];
        }

        $param = ['maintenances' => $maintenancePaginate];
        return view('maintenance.listOfStation',$param);
    }

    /**
     * @param $failureID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintenanceListOfFailure($failureID)
    {
        $input = $this->maintenanceValidation->maintenancePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'desc');
        $pageSize        = array_get($input, 'page_size', 20);
        $maintenancePaginate = $this->maintenanceLogic->getMaintenancesByFailure($failureID,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($maintenancePaginate as $maintenance)
        {
//            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
//            $repairer = $this->employeeInfo($maintenance['repairer_id']);

//            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
//            $maintenance['repairer_name'] = $repairer['name'];
        }

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了维修记录']);

        $param = ['maintenances' => $maintenancePaginate,'failureID' => $failureID];
        return view('maintenance.listOfFailure',$param);
    }

    /**
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewMaintenance()
    {
        $input = $this->maintenanceValidation->storeNewMaintenance();
        $result = $this->maintenanceLogic->createMaintenance($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了维修记录']);

            return redirect('/maintenance/lists');
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
     * @param $maintenanceID
     * @return bool
     */
    public function updateMaintenance($maintenanceID)
    {
        $input = $this->maintenanceValidation->updateMaintenance($maintenanceID);
        $result = $this->maintenanceLogic->updateMaintenance($maintenanceID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了维修记录']);

            return redirect('/maintenance/lists');
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
    public function deleteMaintenance($maintenanceID)
    {
//        $maintenanceID = $this->maintenanceValidation->deleteMaintenance();
        $result = $this->maintenanceLogic->deleteMaintenance($maintenanceID);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '删除成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了维修记录']);
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '删除失败!',
                'message'   => '数据未删除成功,请稍后重试!',
                'level'     => 'error'
            ]);
        }

        return redirect('/maintenance/lists');
    }

    public function getAllMaintenance()
    {
        $maintenanceList = $this->maintenanceLogic->getAllMaintenance();

        foreach($maintenanceList as $maintenance)
        {
//            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
//            $repairer = $this->employeeInfo($maintenance['repairer_id']);

//            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
//            $maintenance['repairer_name'] = $repairer['name'];
        }

        return $maintenanceList;
    }

    public function exportToExcel()
    {
        $title = '设备维修记录';
        $excelData = $this->getAllMaintenance();

        Excel::create($title, function ($excel) use ($excelData, $title) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('维修记录', function ($sheet) use ($excelData) {

                $sheet->row(1, ['所属泵站', '故障设备', '故障原因', '解决办法','维修结果',  '维修人	', '维修时间','备注']);

                if (empty($excelData)) {

                    $sheet->row(2, ['空']);
                    return;
                }

                $i = 2;
                // 循环写入订单数据
                foreach ($excelData as $rowData) {

                    $row = [
                        $rowData['station_name'],
                        $rowData['equipment_name'],
                        $rowData['failure_reason'],
                        $rowData['repair_solution'],
                        $rowData['result'],
                        $rowData['repairer_name'],
                        $rowData['repair_at'],
                        $rowData['remark'],
                    ];

                    $sheet->row($i, $row);
                    $i++;
                }

                $sheet->setAllBorders('thin');
                $sheet->setAutoSize(true);

            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了维修记录']);
    }
}
