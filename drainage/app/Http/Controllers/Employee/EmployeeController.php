<?php

namespace App\Http\Controllers\Employee;

use App\Http\Logic\Employee\EmployeeLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Employee\EmployeeValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EmployeeController extends Controller
{
    protected $employeeLogic;

    protected $employeeValidation;

    protected $stationLogic;

    public function __construct(EmployeeLogic $employeeLogic,EmployeeValidation $employeeValidation,StationLogic $stationLogic)
    {
        $this->middleware('auth');

        $this->employeeLogic = $employeeLogic;
        $this->employeeValidation = $employeeValidation;
        $this->stationLogic = $stationLogic;
    }

    /**
     * 显示添加窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddEmployeeForm()
    {
        return view('employee.addEmployee');
    }

    /**
     * 显示编辑窗口
     *
     * @param $employeeID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateEmployeeForm($employeeID)
    {
        $employee = $this->employeeLogic->findEmployee($employeeID);
        $param = ['employee' => $employee];
        return view('employee.updateEmployee',$param);
    }

    /**
     * 显示信息窗口
     *
     * @param $employeeID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEmployeeForm($employeeID)
    {
        $employee = $this->employeeLogic->findEmployee($employeeID);
        $param = ['employee' => $employee];
        return view('employee.info',$param);
    }

    /**
     * 查询信息
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
     * 分页查询列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function employeeList()
    {
        $input = $this->employeeValidation->employeePaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $employeePaginate = $this->employeeLogic->getEmployees($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['employees' => $employeePaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了工作人员信息']);

        return view('employee.list',$param);
    }

    public function employeeListByStation()
    {
        $stationID = Input::get('station_id', 1);
        $stationTemp = $this->stationLogic->findStation($stationID);
        $stations = $this->stationLogic->getAllStations();

        $conditions = ['delete_process' => 0,'station_id' => $stationID];
        $employeePaginate = $this->employeeLogic->getEmployeesBy($conditions,10,'created_at','asc',null);
        $param = ['employees' => $employeePaginate,'stations' => $stations,'stationSelect' => $stationTemp];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了工作人员信息']);

        return view('employee.list',$param);
    }


    /**
     * 新增
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewEmployee()
    {
        $input = $this->employeeValidation->storeNewEmployee();
        $result = $this->employeeLogic->createEmployee($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了工作人员信息']);

            return redirect('/employee/lists');
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
     * 编辑
     *
     * @param $employeeID
     * @return bool
     */
    public function updateEmployee($employeeID)
    {
        $input = $this->employeeValidation->updateEmployee($employeeID);
        $result = $this->employeeLogic->updateEmployee($employeeID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了工作人员信息']);

            return redirect('/employee/lists');
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
     * 删除
     *
     * @return bool
     */
    public function deleteEmployee($employeeID)
    {
        $result = $this->employeeLogic->deleteEmployee($employeeID);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '删除成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了工作人员信息']);
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '删除失败!',
                'message'   => '数据未删除成功,请稍后重试!',
                'level'     => 'error'
            ]);
        }

        return redirect('/employee/lists');
    }

    public function getAllEmployees()
    {
        $employeeList = $this->employeeLogic->getAllEmployees();

        return $employeeList;
    }

    /**
     * 导出Excel
     */
    public function exportToExcel()
    {
        $title = '工作人员信息';
        $excelData = $this->getAllEmployees();

        Excel::create($title, function ($excel) use ($excelData, $title) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('工作人员信息', function ($sheet) use ($excelData) {

                $sheet->row(1, ['姓名', '编号', '职务', '部门', '手机', 'IP电话	', '语音电话']);

                if (empty($excelData)) {

                    $sheet->row(2, ['空']);
                    return;
                }

                $i = 2;
                // 循环写入数据
                foreach ($excelData as $rowData) {

                    $row = [
                        $rowData['name'],
                        $rowData['number'],
                        $rowData['job'],
                        $rowData['department'],
                        $rowData['cellphone'],
                        $rowData['voip'],
                        $rowData['call'],
                    ];

                    $sheet->row($i, $row);
                    $i++;
                }

                $sheet->setAllBorders('thin');
                $sheet->setAutoSize(true);
            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了工作人员信息']);
    }
}
