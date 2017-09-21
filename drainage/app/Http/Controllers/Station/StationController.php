<?php

namespace App\Http\Controllers\Station;

use App\Http\Logic\Employee\EmployeeLogic;
use App\Http\Logic\Log\LogLogic;
use App\Http\Logic\Station\StationEmployeeLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Station\StationEmployeeValidation;
use App\Http\Validations\Station\StationValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class StationController extends Controller
{
    /**
     * @var StationLogic
     */
    protected $stationLogic;

    protected $employeeLogic;

    protected $stationEmployeeLogic;

    /**
     * @var StationValidation
     */
    protected $stationValidation;

    protected $stationEmployeeValidation;

    /**
     * StationController constructor.
     * @param StationLogic $stationLogic
     * @param StationValidation $stationValidation
     */
    public function __construct(StationLogic $stationLogic,EmployeeLogic $employeeLogic,
                                StationEmployeeLogic $stationEmployeeLogic,StationEmployeeValidation $stationEmployeeValidation,
                                StationValidation $stationValidation)
    {
        $this->middleware('auth');

        $this->stationLogic = $stationLogic;
        $this->employeeLogic = $employeeLogic;
        $this->stationEmployeeLogic = $stationEmployeeLogic;
        $this->stationEmployeeValidation = $stationEmployeeValidation;
        $this->stationValidation = $stationValidation;
    }

    /**
     * 显示添加泵站窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddStationForm()
    {
        return view('station.addStation');
    }

    /**
     * 显示编辑泵站窗口
     *
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateStationForm($stationID)
    {
        $station = $this->stationLogic->findStation($stationID);
        $param = ['station' => $station];
        return view('station.updateStation',$param);
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
     * 分页查询泵站列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stationList()
    {
        $input = $this->stationValidation->stationPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $stationPaginate = $this->stationLogic->getStations($pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach ($stationPaginate as $station) {
            $assignEmployeeIDs = $this->stationEmployeeLogic->getEmployeeIDsByStationID($station['id']);
            $assignEmployees = $this->employeeLogic->getEmployeesByIDs($assignEmployeeIDs);
            $station['assignEmployees'] = $assignEmployees;
        }

        $param = ['stations' => $stationPaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站信息']);

        return view('station.list',$param);
    }

    public function getStationListByType()
    {
        $type = Input::get('type', 1);

        if($type == "全部")
        {
            $conditions = ['delete_process' => 0];
        }
        else
        {
            $conditions = ['delete_process' => 0,'type' => $type];
        }

        $stationPaginate = $this->stationLogic->getStationsBy($conditions,10,'created_at','asc',null);

        foreach ($stationPaginate as $station) {
            $assignEmployeeIDs = $this->stationEmployeeLogic->getEmployeeIDsByStationID($station['id']);
            $assignEmployees = $this->employeeLogic->getEmployeesByIDs($assignEmployeeIDs);
            $station['assignEmployees'] = $assignEmployees;
        }

        $param = ['stations' => $stationPaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站信息']);

        return view('station.list',$param);
    }

    /**
     * 新增泵站
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewStation()
    {
        $input = $this->stationValidation->storeNewStation();
        $result = $this->stationLogic->createStation($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了泵站信息']);

            return redirect('/station/lists');
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
     * 编辑泵站
     *
     * @param $stationID
     * @return bool
     */
    public function updateStation($stationID)
    {
        $input = $this->stationValidation->updateStation($stationID);
        $result = $this->stationLogic->updateStation($stationID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了泵站信息']);

            return redirect('/station/lists');
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
     * 删除泵站
     *
     * @return bool
     */
    public function deleteStation($stationID)
    {
//        $stationID = $this->stationValidation->deleteStation();
        $result = $this->stationLogic->deleteStation($stationID);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '删除成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了泵站信息']);
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '删除失败!',
                'message'   => '数据未删除成功,请稍后重试!',
                'level'     => 'error'
            ]);
        }

        return redirect('/station/lists');
    }

    /**
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSetStationEmployeeForm($stationID)
    {
        $employees = $this->employeeLogic->getAllEmployees();
        $assignEmployeeIDs = $this->stationEmployeeLogic->getEmployeeIDsByStationID($stationID);
        $station = $this->stationLogic->findStation($stationID);
        $param = ['station' => $station ,'employees' => $employees,'assignEmployeeIDs' => $assignEmployeeIDs];

        return view('station.setStationEmployee',$param);
    }

    /**
     * @return bool
     */
    public function setStationEmployee($stationID)
    {
        $input = $this->stationEmployeeValidation->setStationEmployee();
//        $userID = $input['user_id'];
        $employeeIDs = $input['employees'];

        $result = $this->stationEmployeeLogic->setStationEmployees($stationID,$employeeIDs);


        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '设置了用户角色']);

            return redirect('/station/lists');
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


    public function runList()
    {
        $input = $this->stationValidation->stationPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $stationPaginate = $this->stationLogic->getStations($pageSize,$orderColumn,$orderDirection,$cursorPage);
        foreach ($stationPaginate as $station)
        {
            $stationNum = $station['station_number'];
            $stationRT = $this->findStationRT($stationNum);

            $runCount = 0;
            $stopCount = 0;

            if($stationRT[0]->yx_b1 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($stationRT[0]->yx_b2 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($stationRT[0]->yx_b3 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($stationRT[0]->yx_b4 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if($runCount == 0)
            {
                $station['status'] = 'grey';
            }
            elseif ($stopCount == 0)
            {
                $station['status'] = 'red';
            }
            elseif ($stopCount == 1)
            {
                $station['status'] = 'yellow';
            }
            else
            {
                $station['status'] = 'green';
            }


            $station['runPump'] = $runCount;
            $station['stopPump'] = $stopCount;
            $station['culvertWater'] = $stationRT[0]->ywhandong;
            $station['tankWater'] = $stationRT[0]->ywjishui;
            $station['Time'] = $stationRT[0]->Time;
        }
        $param = ['stations' => $stationPaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站运行信息']);

        return view('station.runList',$param);
    }

    public function runDetail($stationID)
    {
        $station = $this->stationInfo($stationID);
        $stationNum = $station['station_number'];
        $stationRT = $this->findStationRT($stationNum);
        $param = ['station' => $station,'stationRT' => $stationRT];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站运行详细']);

        return view('station.runDetail',$param);
    }

    /**
     * @param $stationNum
     * @return mixed
     */
    public function findStationRT($stationNum)
    {
        $stationTable = "stationRT_".$stationNum;
        $stationRT = DB::select('select * from '.$stationTable.' order by Time desc limit 120');
        return $stationRT;
    }

    public function stationRT($stationNum)
    {
        $stationRT = $this->findStationRT($stationNum);
        return response()->json(array('stationRT'=> $stationRT[0]), 200);
    }

    public function stationRTHistory($stationNum)
    {
        $stationRT = $this->findStationRT($stationNum);

        return response()->json(array('stationRTHistory'=> $stationRT), 200);
    }
}
