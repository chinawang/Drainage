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
     * 显示泵站信息窗口
     *
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStationInfoForm($stationID)
    {
        $station = $this->stationLogic->findStation($stationID);
        $assignEmployeeIDs = $this->stationEmployeeLogic->getEmployeeIDsByStationID($station['id']);
        $assignEmployees = $this->employeeLogic->getEmployeesByIDs($assignEmployeeIDs);
        $station['assignEmployees'] = $assignEmployees;

        $param = ['station' => $station];
        return view('station.info',$param);
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

        $type = '全部';

        $param = ['stations' => $stationPaginate,'selectType' => $type];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站信息']);

        return view('station.list',$param);
    }

    /**
     * 分局泵站分类查询
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

        $param = ['stations' => $stationPaginate,'selectType' => $type,];

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

            $has2Pump = false;
            $has3Pump = false;
            $has4Pump = false;
            $has5Pump = false;

            $pump2List = ['18', '31', '34'];
            $pump3List = ['1', '2', '3', '4', '5', '6', '8', '9', '11', '12', '13', '16', '17', '20', '23', '24', '25', '26', '27', '28', '30', '32', '35', '37', '38'];
            $pump4List = ['7', '10', '14', '15', '19', '21', '22', '29', '36'];
            $pump5List = ['33'];

            if (in_array($stationNum, $pump2List)) {
                $has2Pump = true;
            } elseif (in_array($stationNum, $pump3List)) {
                $has3Pump = true;
            } elseif (in_array($stationNum, $pump4List)) {
                $has4Pump = true;
            } elseif (in_array($stationNum, $pump5List)) {
                $has5Pump = true;
            }

            $runCount = 0;
            $stopCount = 0;

            //1号泵
            if($stationRT[0]->yx_b1 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            //2号泵
            if($stationRT[0]->yx_b2 == '1')
            {
                $runCount ++;
            }
            else
            {
                $stopCount ++;
            }

            if ($has3Pump || $has4Pump || $has5Pump){
                //3号泵
                if($stationRT[0]->yx_b3 == '1')
                {
                    $runCount ++;
                }
                else
                {
                    $stopCount ++;
                }
            }


            if ($has4Pump || $has5Pump){
                //4号泵
                if($stationRT[0]->yx_b4 == '1')
                {
                    $runCount ++;
                }
                else
                {
                    $stopCount ++;
                }
            }


            //5号泵
            if($has5Pump)
            {
                if($stationRT[0]->yx_b5 == '1')
                {
                    $runCount ++;
                }
                else
                {
                    $stopCount ++;
                }
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
