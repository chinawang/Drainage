<?php

namespace App\Http\Controllers\Station;

use App\Http\Logic\Employee\EmployeeLogic;
use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Station\EquipmentValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
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
     * @var EquipmentValidation
     */
    protected $equipmentValidation;

    /**
     * EquipmentController constructor.
     * @param EquipmentLogic $equipmentLogic
     * @param EquipmentValidation $equipmentValidation
     * @param StationLogic $stationLogic
     * @param UserLogic $userLogic
     */
    public function __construct(EquipmentLogic $equipmentLogic, EquipmentValidation $equipmentValidation,
                                StationLogic $stationLogic, UserLogic $userLogic,EmployeeLogic $employeeLogic)
    {
        $this->middleware('auth');

        $this->equipmentLogic = $equipmentLogic;
        $this->equipmentValidation = $equipmentValidation;
        $this->stationLogic = $stationLogic;
        $this->userLogic = $userLogic;
        $this->employeeLogic = $employeeLogic;
    }

    /**
     * 显示添加设备窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddEquipmentForm()
    {
        $stations = $this->stationLogic->getAllStations();
        $employees = $this->employeeLogic->getAllEmployees();

        $param = ['stations' => $stations, 'employees' => $employees];
        return view('equipment.addEquipment', $param);
    }

    /**
     * 显示编辑设备窗口
     *
     * @param $equipmentID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateEquipmentForm($equipmentID)
    {
        $equipment = $this->equipmentInfo($equipmentID);
        $station = $this->stationInfo($equipment['station_id']);
        $leader = $this->employeeInfo($equipment['leader_id']);
        $custodian = $this->employeeInfo($equipment['custodian_id']);

        $equipment['station_name'] = $station['name'];
        $equipment['leader_name'] = $leader['name'];
        $equipment['custodian_name'] = $custodian['name'];

        $stations = $this->stationLogic->getAllStations();
        $employees = $this->employeeLogic->getAllEmployees();

        $param = ['equipment' => $equipment, 'stations' => $stations, 'employees' => $employees];
        return view('equipment.updateEquipment', $param);
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
     * 分页查询设备列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function equipmentList()
    {
        $input = $this->equipmentValidation->equipmentPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $orderColumn = array_get($input, 'order_column', 'station_id');
        $orderDirection = array_get($input, 'order_direction', 'asc');
        $pageSize = array_get($input, 'page_size', 20);
        $equipmentPaginate = $this->equipmentLogic->getEquipments($pageSize, $orderColumn, $orderDirection, $cursorPage);

        foreach ($equipmentPaginate as $equipment) {
            $station = $this->stationInfo($equipment['station_id']);
            $leader = $this->employeeInfo($equipment['leader_id']);
            $custodian = $this->employeeInfo($equipment['custodian_id']);

            $equipment['station_name'] = $station['name'];
            $equipment['leader_name'] = $leader['name'];
            $equipment['custodian_name'] = $custodian['name'];
        }

        $param = ['equipments' => $equipmentPaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了设备信息']);

        return view('equipment.list', $param);
    }

    /**
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function equipmentListOfStation($stationID)
    {
        $input = $this->equipmentValidation->equipmentPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $orderColumn = array_get($input, 'order_column', 'created_at');
        $orderDirection = array_get($input, 'order_direction', 'asc');
        $pageSize = array_get($input, 'page_size', 20);
        $equipmentPaginate = $this->equipmentLogic->getEquipmentsByStation($stationID, $pageSize, $orderColumn, $orderDirection, $cursorPage);

        foreach ($equipmentPaginate as $equipment) {
            $station = $this->stationInfo($equipment['station_id']);
            $leader = $this->employeeInfo($equipment['leader_id']);
            $custodian = $this->employeeInfo($equipment['custodian_id']);

            $equipment['station_name'] = $station['name'];
            $equipment['leader_name'] = $leader['name'];
            $equipment['custodian_name'] = $custodian['name'];
        }

        $param = ['equipments' => $equipmentPaginate];
        return view('equipment.listOfStation', $param);
    }

    /**
     * 添加新设备
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewEquipment()
    {
        $input = $this->equipmentValidation->storeNewEquipment();
        $result = $this->equipmentLogic->createEquipment($input);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '保存成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了设备信息']);

            return redirect('/equipment/lists');
        } else {
            session()->flash('flash_message_overlay', [
                'title' => '保存失败!',
                'message' => '数据未保存成功,请稍后重试!',
                'level' => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * 编辑设备
     *
     * @param $equipmentID
     * @return bool
     */
    public function updateEquipment($equipmentID)
    {
        $input = $this->equipmentValidation->updateEquipment($equipmentID);
        $result = $this->equipmentLogic->updateEquipment($equipmentID, $input);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '保存成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了设备信息']);

            return redirect('/equipment/lists');
        } else {
            session()->flash('flash_message_overlay', [
                'title' => '保存失败!',
                'message' => '数据未保存成功,请稍后重试!',
                'level' => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * 删除设备
     *
     * @return mixed
     */
    public function deleteEquipment($equipmentID)
    {
//        $equipmentID = $this->equipmentValidation->deleteEquipment();
        $result = $this->equipmentLogic->deleteEquipment($equipmentID);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '删除成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了设备信息']);

        } else {
            session()->flash('flash_message_overlay', [
                'title' => '删除失败!',
                'message' => '数据未删除成功,请稍后重试!',
                'level' => 'error'
            ]);
        }

        return redirect('/equipment/lists');
    }

    public function getAllEquipments()
    {
        $equipmentList = $this->equipmentLogic->getAllEquipments();
        foreach ($equipmentList as $equipment) {
            $station = $this->stationInfo($equipment['station_id']);
            $leader = $this->employeeInfo($equipment['leader_id']);
            $custodian = $this->employeeInfo($equipment['custodian_id']);

            $equipment['station_name'] = $station['name'];
            $equipment['leader_name'] = $leader['name'];
            $equipment['custodian_name'] = $custodian['name'];
        }

        return $equipmentList;
    }

    public function exportToExcel()
    {
        $title = '泵站设备信息';
        $excelData = $this->getAllEquipments();

        Excel::create($title, function ($excel) use ($excelData, $title) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('设备信息', function ($sheet) use ($excelData) {

                $sheet->row(1, ['编号', '所属泵站', '设备名称', '型号', '制造单位', '使用部门	', '负责人', '设备管理员', '数量', '变动情况']);

                if (empty($excelData)) {

                    $sheet->row(2, ['空']);
                    return;
                }

                $i = 2;
                // 循环写入数据
                foreach ($excelData as $rowData) {

                    $row = [
                        $rowData['equipment_number'],
                        $rowData['station_name'],
                        $rowData['name'],
                        $rowData['type'],
                        $rowData['producer'],
                        $rowData['department'],
                        $rowData['leader_name'],
                        $rowData['custodian_name'],
                        $rowData['quantity'],
                        $rowData['alteration'],
                    ];

                    $sheet->row($i, $row);
                    $i++;
                }

                $sheet->setAllBorders('thin');
                $sheet->setAutoSize(true);
            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了设备信息']);
    }
}
