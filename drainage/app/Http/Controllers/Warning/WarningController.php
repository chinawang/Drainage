<?php

namespace App\Http\Controllers\Warning;

use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Station\StationValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WarningController extends Controller
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
     * WarningController constructor.
     * @param StationLogic $stationLogic
     * @param StationValidation $stationValidation
     */
    public function __construct(StationLogic $stationLogic, StationValidation $stationValidation)
    {
        $this->middleware('auth');

        $this->stationLogic = $stationLogic;
        $this->stationValidation = $stationValidation;
    }

    public function warningList()
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

            if(count($stationRT)>0)
            {
                $station['alarmPump1'] = $stationRT[0]->bj_b1;
                $station['alarmPump2'] = $stationRT[0]->bj_b2;
                $station['alarmPump3'] = $stationRT[0]->bj_b3;
                $station['alarmPump4'] = $stationRT[0]->bj_b4;

                $station['alarmPump1RQ'] = $stationRT[0]->rqbj_b1;
                $station['alarmPump2RQ'] = $stationRT[0]->rqbj_b2;
                $station['alarmPump3RQ'] = $stationRT[0]->rqbj_b3;
                $station['alarmPump4RQ'] = $stationRT[0]->rqbj_b4;

                if($stationNum == 33){
                    $station['alarmPump5'] = $stationRT[0]->bj_b5;
                    $station['alarmPump5RQ'] = $stationRT[0]->rqbj_b5;
                }

                $station['alarmAuger'] = $stationRT[0]->bj_jl;
                $station['alarmCleaner1'] = $stationRT[0]->bj_gs1;
                $station['alarmCleaner2'] = $stationRT[0]->bj_gs2;

                //部分泵站通讯中断,没有数据,不做市电的报警
                $stationNoWorking = ['11','12','20','34'];
                if(in_array($stationNum, $stationNoWorking))
                {
                    $station['alarmCity'] = 1;//市电停电报警
                }else{
                    $station['alarmCity'] = $stationRT[0]->water_v;//市电停电报警
                }
                $station['alarmManual'] = $stationRT[0]->flow_v;//手动急停报警

                $station['Time'] = $stationRT[0]->Time;
            }

        }
        $param = ['stations' => $stationPaginate];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了实时报警信息']);

        return view('warning.warningList',$param);
    }

    public function warningDetail($stationID)
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        $station = $this->stationInfo($stationID);
        $stationNum = $station['station_number'];
        $stationRT = $this->findStationRT($stationNum);

        if(count($stationRT)>0){
            $station['alarmPump1'] = $stationRT[0]->bj_b1;
            $station['alarmPump2'] = $stationRT[0]->bj_b2;
            $station['alarmPump3'] = $stationRT[0]->bj_b3;
            $station['alarmPump4'] = $stationRT[0]->bj_b4;

            $station['alarmPump1RQ'] = $stationRT[0]->rqbj_b1;
            $station['alarmPump2RQ'] = $stationRT[0]->rqbj_b2;
            $station['alarmPump3RQ'] = $stationRT[0]->rqbj_b3;
            $station['alarmPump4RQ'] = $stationRT[0]->rqbj_b4;

            $station['alarmPump5'] = 0;
            $station['alarmPump5RQ'] = 0;

            if($stationNum == 33){
                $station['alarmPump5'] = $stationRT[0]->bj_b5;
                $station['alarmPump5RQ'] = $stationRT[0]->rqbj_b5;
            }

            $station['alarmAuger'] = $stationRT[0]->bj_jl;
            $station['alarmCleaner1'] = $stationRT[0]->bj_gs1;
            $station['alarmCleaner2'] = $stationRT[0]->bj_gs2;

            //部分泵站通讯中断,没有数据,不做市电的报警
            $stationNoWorking = ['11','12','20','21','31','33','34','36'];
            if(in_array($stationNum, $stationNoWorking))
            {
                $station['alarmCity'] = 1;//市电停电报警
            }else{
                $station['alarmCity'] = $stationRT[0]->water_v;//市电停电报警
            }

            $station['alarmManual'] = $stationRT[0]->flow_v;//手动急停报警
        }


        $stationWarningList = $this->getStationRTList($stationNum);

        $param = ['station' => $station,'stationRT' => $stationRT,'stationWarningList' => $stationWarningList];

        //return $stationWarningList;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了实时报警详细']);

        return view('warning.warningDetail',$param);
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
     * @param $stationNum
     * @return mixed
     */
    public function findStationRT($stationNum)
    {
        $stationTable = "stationRT_".$stationNum;
        $stationRT = DB::select('select * from '.$stationTable.' order by Time desc limit 1');
        return $stationRT;
    }

    public function getStationRTList($stationNum)
    {
        $stationTable = "stationRT_".$stationNum;
        $stationRTList = DB::select('select * from '.$stationTable.' order by Time asc');
        $stationWarningList = [];

        for($i = 0 ; $i < count($stationRTList)-1;$i++)
        {
            if($stationRTList[$i]->bj_b1 == 0 && $stationRTList[$i+1]->bj_b1 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->bj_b1 == 1 && $stationRTList[$i+1]->bj_b1 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号泵电机";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_b2 == 0 && $stationRTList[$i+1]->bj_b2 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->bj_b2 == 1 && $stationRTList[$i+1]->bj_b2 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵电机";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_b3 == 0 && $stationRTList[$i+1]->bj_b3 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->bj_b3 == 1 && $stationRTList[$i+1]->bj_b3 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵电机";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_b4 == 0 && $stationRTList[$i+1]->bj_b4 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->bj_b4 == 1 && $stationRTList[$i+1]->bj_b4 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵电机";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }



            if($stationRTList[$i]->rqbj_b1 == 0 && $stationRTList[$i+1]->rqbj_b1 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->rqbj_b1 == 1 && $stationRTList[$i+1]->rqbj_b1 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号泵软启动器";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->rqbj_b2 == 0 && $stationRTList[$i+1]->rqbj_b2 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->rqbj_b2 == 1 && $stationRTList[$i+1]->rqbj_b2 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵软启动器";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->rqbj_b3 == 0 && $stationRTList[$i+1]->rqbj_b3 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->rqbj_b3 == 1 && $stationRTList[$i+1]->rqbj_b3 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵软启动器";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->rqbj_b4 == 0 && $stationRTList[$i+1]->rqbj_b4 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->rqbj_b4 == 1 && $stationRTList[$i+1]->rqbj_b4 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵软启动器";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationNum == 33)
            {
                if($stationRTList[$i]->bj_b5 == 0 && $stationRTList[$i+1]->bj_b5 == 1 )
                {
                    $sWarning['Time'] = $stationRTList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "5号泵电机";
                    $sWarning['alarmStatus'] = 1;

                    array_push($stationWarningList,$sWarning);
                }
                if($stationRTList[$i]->bj_b5 == 1 && $stationRTList[$i+1]->bj_b5 == 0 )
                {
                    $sWarning['Time'] = $stationRTList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "5号泵电机";
                    $sWarning['alarmStatus'] = 0;

                    array_push($stationWarningList,$sWarning);
                }

                if($stationRTList[$i]->rqbj_b5 == 0 && $stationRTList[$i+1]->rqbj_b5 == 1 )
                {
                    $sWarning['Time'] = $stationRTList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "5号泵软启动器";
                    $sWarning['alarmStatus'] = 1;

                    array_push($stationWarningList,$sWarning);
                }
                if($stationRTList[$i]->rqbj_b5 == 1 && $stationRTList[$i+1]->rqbj_b5 == 0 )
                {
                    $sWarning['Time'] = $stationRTList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "5号泵软启动器";
                    $sWarning['alarmStatus'] = 0;

                    array_push($stationWarningList,$sWarning);
                }
            }

            if($stationRTList[$i]->bj_jl == 0 && $stationRTList[$i+1]->bj_jl == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "绞笼";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->bj_jl == 1 && $stationRTList[$i+1]->bj_jl == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "绞笼";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_gs1 == 0 && $stationRTList[$i+1]->bj_gs1 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号格栅";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->bj_gs1 == 1 && $stationRTList[$i+1]->bj_gs1 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号格栅";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_gs2 == 0 && $stationRTList[$i+1]->bj_gs2 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号格栅";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->bj_gs2 == 1 && $stationRTList[$i+1]->bj_gs2 == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号格栅";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->water_v == 1 && $stationRTList[$i+1]->water_v == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "市电停电";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->water_v == 0 && $stationRTList[$i+1]->water_v == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "市电停电";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->flow_v == 0 && $stationRTList[$i+1]->flow_v == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "手动急停";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
            if($stationRTList[$i]->flow_v == 1 && $stationRTList[$i+1]->flow_v == 0 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "手动急停";
                $sWarning['alarmStatus'] = 0;

                array_push($stationWarningList,$sWarning);
            }
        }

        return $stationWarningList;
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
