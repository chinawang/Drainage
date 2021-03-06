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
use Maatwebsite\Excel\Facades\Excel;

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

    public function showWarningReportV2()
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

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

        $stationRTList = $this->getStationRTListByTime($stationNum,$searchStartTime,$searchEndTime);

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

            if($stationRTList[$i]->bj_b2 == 0 && $stationRTList[$i+1]->bj_b2 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_b3 == 0 && $stationRTList[$i+1]->bj_b3 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_b4 == 0 && $stationRTList[$i+1]->bj_b4 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵电机";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }


            if($stationRTList[$i]->rqbj_b1 == 0 && $stationRTList[$i+1]->rqbj_b1 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->rqbj_b2 == 0 && $stationRTList[$i+1]->rqbj_b2 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->rqbj_b3 == 0 && $stationRTList[$i+1]->rqbj_b3 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "3号泵软启动器";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->rqbj_b4 == 0 && $stationRTList[$i+1]->rqbj_b4 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "4号泵软启动器";
                $sWarning['alarmStatus'] = 1;

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

                if($stationRTList[$i]->rqbj_b5 == 0 && $stationRTList[$i+1]->rqbj_b5 == 1 )
                {
                    $sWarning['Time'] = $stationRTList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "5号泵软启动器";
                    $sWarning['alarmStatus'] = 1;

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

            if($stationRTList[$i]->bj_gs1 == 0 && $stationRTList[$i+1]->bj_gs1 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "1号格栅";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }

            if($stationRTList[$i]->bj_gs2 == 0 && $stationRTList[$i+1]->bj_gs2 == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "2号格栅";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }

            //部分泵站通讯中断,没有数据,不做市电的报警
            $stationNoWorking = ['11','12','20','34'];
            if(in_array($stationNum, $stationNoWorking))
            {

            }else{
                if($stationRTList[$i]->water_v == 1 && $stationRTList[$i+1]->water_v == 0 )
                {
                    $sWarning['Time'] = $stationRTList[$i+1]->Time;
                    $sWarning['alarmEquipment'] = "市电停电";
                    $sWarning['alarmStatus'] = 1;

                    array_push($stationWarningList,$sWarning);
                }
            }



            if($stationRTList[$i]->flow_v == 0 && $stationRTList[$i+1]->flow_v == 1 )
            {
                $sWarning['Time'] = $stationRTList[$i+1]->Time;
                $sWarning['alarmEquipment'] = "手动急停";
                $sWarning['alarmStatus'] = 1;

                array_push($stationWarningList,$sWarning);
            }
        }

        $param = ['stations' => $stations, 'warningList' => $stationWarningList,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站报警统计']);

        return view('report.stationWarning', $param);
    }

    public function showWarningAllReportV2()
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限


        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';
        $stations = $this->stationList();
        foreach ($stations as $station)
        {
            $stationNum = $station['station_number'];

            $stationRTList = $this->getStationRTListByTime($stationNum,$searchStartTime,$searchEndTime);

            $countB1 = 0;
            $countB2 = 0;
            $countB3 = 0;
            $countB4 = 0;
            $countB5 = 0;
            $countB1RQ = 0;
            $countB2RQ = 0;
            $countB3RQ = 0;
            $countB4RQ = 0;
            $countB5RQ = 0;
            $countGS1 = 0;
            $countGS2 = 0;
            $countJL = 0;
            $countSD = 0;
            $countJT = 0;

            for($i = 0 ; $i < count($stationRTList)-1;$i++)
            {
                if($stationRTList[$i]->bj_b1 == 0 && $stationRTList[$i+1]->bj_b1 == 1 )
                {
                    $countB1 ++;
                }

                if($stationRTList[$i]->bj_b2 == 0 && $stationRTList[$i+1]->bj_b2 == 1 )
                {
                    $countB2 ++;
                }

                if($stationRTList[$i]->bj_b3 == 0 && $stationRTList[$i+1]->bj_b3 == 1 )
                {
                    $countB3 ++;
                }

                if($stationRTList[$i]->bj_b4 == 0 && $stationRTList[$i+1]->bj_b4 == 1 )
                {
                    $countB4 ++;
                }


                if($stationRTList[$i]->rqbj_b1 == 0 && $stationRTList[$i+1]->rqbj_b1 == 1 )
                {
                    $countB1RQ ++;
                }

                if($stationRTList[$i]->rqbj_b2 == 0 && $stationRTList[$i+1]->rqbj_b2 == 1 )
                {
                    $countB2RQ ++;
                }

                if($stationRTList[$i]->rqbj_b3 == 0 && $stationRTList[$i+1]->rqbj_b3 == 1 )
                {
                    $countB3RQ ++;
                }

                if($stationRTList[$i]->rqbj_b4 == 0 && $stationRTList[$i+1]->rqbj_b4 == 1 )
                {
                    $countB4RQ ++;
                }

                if($stationNum == 33)
                {
                    if($stationRTList[$i]->bj_b5 == 0 && $stationRTList[$i+1]->bj_b5 == 1 )
                    {
                        $countB5 ++;
                    }

                    if($stationRTList[$i]->rqbj_b5 == 0 && $stationRTList[$i+1]->rqbj_b5 == 1 )
                    {
                        $countB5RQ ++;
                    }
                }

                if($stationRTList[$i]->bj_jl == 0 && $stationRTList[$i+1]->bj_jl == 1 )
                {
                    $countJL ++;
                }

                if($stationRTList[$i]->bj_gs1 == 0 && $stationRTList[$i+1]->bj_gs1 == 1 )
                {
                    $countGS1 ++;
                }

                if($stationRTList[$i]->bj_gs2 == 0 && $stationRTList[$i+1]->bj_gs2 == 1 )
                {
                    $countGS2 ++;
                }

                //部分泵站通讯中断,没有数据,不做市电的报警
                $stationNoWorking = ['11','12','20','34'];
                if(in_array($stationNum, $stationNoWorking))
                {

                }else{
                    if($stationRTList[$i]->water_v == 1 && $stationRTList[$i+1]->water_v == 0 )
                    {
                        $countSD ++;
                    }
                }


                if($stationRTList[$i]->flow_v == 0 && $stationRTList[$i+1]->flow_v == 1 )
                {
                    $countJT ++;
                }
            }

            $station['alarmPump1'] = $countB1;
            $station['alarmPump2'] = $countB2;
            $station['alarmPump3'] = $countB3;
            $station['alarmPump4'] = $countB4;

            $station['alarmPump1RQ'] = $countB1RQ;
            $station['alarmPump2RQ'] = $countB2RQ;
            $station['alarmPump3RQ'] = $countB3RQ;
            $station['alarmPump4RQ'] = $countB4RQ;

            if($stationNum == 33){
                $station['alarmPump5'] = $countB5;
                $station['alarmPump5RQ'] = $countB5RQ;
            }

            $station['alarmAuger'] = $countJL;
            $station['alarmCleaner1'] = $countGS1;
            $station['alarmCleaner2'] = $countGS2;

            //部分泵站通讯中断,没有数据,不做市电的报警
            $stationNoWorking = ['11','12','20','34'];
            if(in_array($stationNum, $stationNoWorking))
            {
                $station['alarmCity'] = 0;//市电停电报警
            }else{
                $station['alarmCity'] = $countSD;//市电停电报警
            }
            $station['alarmManual'] = $countJT;//手动急停报警

        }


        $param = ['stations' => $stations, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站报警统计']);

        return view('report.stationWarningAll', $param);
    }

    public function showWarningReport()
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

        $stationWarningList = [];

        $condition1 = ['bj_b1' => 1];
        $warningListPump1 = $this->getStationRTListByConditions($stationNum,$condition1,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump1);$i++)
        {
            $wainingPump1['Time'] = $warningListPump1[$i]->Time;
            $wainingPump1['alarmEquipment'] = '1号泵电机';
            array_push($stationWarningList,$wainingPump1);
        }

        $condition2 = ['bj_b2' => 1];
        $warningListPump2 = $this->getStationRTListByConditions($stationNum,$condition2,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump2);$i++)
        {
            $wainingPump2['Time'] = $warningListPump2[$i]->Time;
            $wainingPump2['alarmEquipment'] = '2号泵电机';
            array_push($stationWarningList,$wainingPump2);
        }

        $condition3 = ['bj_b3' => 1];
        $warningListPump3 = $this->getStationRTListByConditions($stationNum,$condition3,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump3);$i++)
        {
            $wainingPump3['Time'] = $warningListPump3[$i]->Time;
            $wainingPump3['alarmEquipment'] = '3号泵电机';
            array_push($stationWarningList,$wainingPump3);
        }

        $condition4 = ['bj_b4' => 1];
        $warningListPump4 = $this->getStationRTListByConditions($stationNum,$condition4,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump4);$i++)
        {
            $wainingPump4['Time'] = $warningListPump4[$i]->Time;
            $wainingPump4['alarmEquipment'] = '4号泵电机';
            array_push($stationWarningList,$wainingPump4);
        }


        $condition1RQ = ['rqbj_b1' => 1];
        $warningListPump1RQ = $this->getStationRTListByConditions($stationNum,$condition1RQ,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump1RQ);$i++)
        {
            $wainingPump1RQ['Time'] = $warningListPump1RQ[$i]->Time;
            $wainingPump1RQ['alarmEquipment'] = '1号泵软启动器';
            array_push($stationWarningList,$wainingPump1RQ);
        }

        $condition2RQ = ['rqbj_b2' => 1];
        $warningListPump2RQ = $this->getStationRTListByConditions($stationNum,$condition2RQ,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump2RQ);$i++)
        {
            $wainingPump2RQ['Time'] = $warningListPump2RQ[$i]->Time;
            $wainingPump2RQ['alarmEquipment'] = '2号泵软启动器';
            array_push($stationWarningList,$wainingPump2RQ);
        }

        $condition3RQ = ['rqbj_b3' => 1];
        $warningListPump3RQ = $this->getStationRTListByConditions($stationNum,$condition3RQ,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump3RQ);$i++)
        {
            $wainingPump3RQ['Time'] = $warningListPump3RQ[$i]->Time;
            $wainingPump3RQ['alarmEquipment'] = '3号泵软启动器';
            array_push($stationWarningList,$wainingPump3RQ);
        }

        $condition4RQ = ['rqbj_b4' => 1];
        $warningListPump4RQ = $this->getStationRTListByConditions($stationNum,$condition4RQ,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListPump4RQ);$i++)
        {
            $wainingPump4RQ['Time'] = $warningListPump4RQ[$i]->Time;
            $wainingPump4RQ['alarmEquipment'] = '4号泵软启动器';
            array_push($stationWarningList,$wainingPump4RQ);
        }

        if($stationNum == 33)
        {
            $conditionPlus = ['bj_b5' => 1];
            $warningListPump5 = $this->getStationRTListByConditions($stationNum,$conditionPlus,$searchStartTime,$searchEndTime);
            for($i = 0 ; $i < count($warningListPump5);$i++)
            {
                $wainingPump5['Time'] = $warningListPump5[$i]->Time;
                $wainingPump5['alarmEquipment'] = '5号泵电机';
                array_push($stationWarningList,$wainingPump5);
            }

            $conditionPlusRQ = ['rqbj_b5' => 1];
            $warningListPump5RQ = $this->getStationRTListByConditions($stationNum,$conditionPlusRQ,$searchStartTime,$searchEndTime);
            for($i = 0 ; $i < count($warningListPump5RQ);$i++)
            {
                $wainingPump5RQ['Time'] = $warningListPump5RQ[$i]->Time;
                $wainingPump5RQ['alarmEquipment'] = '5号泵软启动器';
                array_push($stationWarningList,$wainingPump5RQ);
            }
        }

        $condition5 = ['bj_gs1' => 1];
        $warningListGS1 = $this->getStationRTListByConditions($stationNum,$condition5,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListGS1);$i++)
        {
            $wainingGS1['Time'] = $warningListGS1[$i]->Time;
            $wainingGS1['alarmEquipment'] = '1号格栅';
            array_push($stationWarningList,$wainingGS1);
        }

        $condition6 = ['bj_gs2' => 1];
        $warningListGS2 = $this->getStationRTListByConditions($stationNum,$condition6,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListGS2);$i++)
        {
            $wainingGS2['Time'] = $warningListGS2[$i]->Time;
            $wainingGS2['alarmEquipment'] = '2号格栅';
            array_push($stationWarningList,$wainingGS2);
        }

        $condition7 = ['bj_jl' => 1];
        $warningListJL = $this->getStationRTListByConditions($stationNum,$condition7,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListJL);$i++)
        {
            $wainingJL['Time'] = $warningListJL[$i]->Time;
            $wainingJL['alarmEquipment'] = '绞笼';
            array_push($stationWarningList,$wainingJL);
        }

        $condition8 = ['water_v' => 0];
        $warningListSD = $this->getStationRTListByConditions($stationNum,$condition8,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListSD);$i++)
        {
            $wainingSD['Time'] = $warningListSD[$i]->Time;
            $wainingSD['alarmEquipment'] = '市电停电';
            array_push($stationWarningList,$wainingSD);
        }

        $condition9 = ['flow_v' => 1];
        $warningListJT = $this->getStationRTListByConditions($stationNum,$condition9,$searchStartTime,$searchEndTime);
        for($i = 0 ; $i < count($warningListJT);$i++)
        {
            $wainingJT['Time'] = $warningListJT[$i]->Time;
            $wainingJT['alarmEquipment'] = '手动急停';
            array_push($stationWarningList,$wainingJT);
        }

        $param = ['stations' => $stations, 'warningList' => $stationWarningList,
            'stationSelect' => $stationTemp, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵站报警统计']);

        return view('report.stationWarning', $param);
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
        $stationID = Input::get('station_id', 0);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');
        $repairProcess = Input::get('repairProcess', '99');//全部

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

        $failurePaginate = $this->getFailureListByStationID($stationID,$repairProcess,$pageSize,$cursorPage,$searchStartTime,$searchEndTime);

        foreach ($failurePaginate as $failure) {
//            $equipment = $this->equipmentInfo($failure->equipment_id);
            $station = $this->stationInfo($failure->station_id);
//            $reporter = $this->employeeInfo($failure->reporter_id);
//            $repairer = $this->employeeInfo($failure->repairer_id);

//            $failure->equipment_name = $equipment['name'];
            $failure->station_name = $station['name'];
//            $failure->reporter_name = $reporter['name'];
//            $failure->repairer_name = $repairer['name'];
        }

        $param = ['stations' => $stations, 'failures' => $failurePaginate,
            'stationSelect' => $stationTemp,'selectProcess' => $repairProcess, 'startTime' => $startTime, 'endTime' => $endTime];
//        return $param;

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了设备故障统计']);

        return view('report.stationFailure', $param);
    }

    public function showMaintenanceReport()
    {
        $stationID = Input::get('station_id', 0);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');
        $repairProcess = Input::get('repairProcess', '99');//全部

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

        $maintenancePaginate = $this->getMaintenanceListByStationID($stationID,$repairProcess,$pageSize,$cursorPage,$searchStartTime,$searchEndTime);

        foreach ($maintenancePaginate as $maintenance) {
//            $equipment = $this->equipmentInfo($maintenance->equipment_id);
            $station = $this->stationInfo($maintenance->station_id);
//            $repairer = $this->employeeInfo($maintenance->repairer_id);

//            $maintenance->equipment_name = $equipment['name'];
            $maintenance->station_name = $station['name'];
//            $maintenance->repairer_name = $repairer['name'];
        }

        $param = ['stations' => $stations, 'maintenances' => $maintenancePaginate,
            'stationSelect' => $stationTemp, 'selectProcess' => $repairProcess,'startTime' => $startTime, 'endTime' => $endTime];
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

    public function getStationRTListByConditions($stationNum,$conditions, $searchStartTime,$searchEndTime)
    {
        $stationTable = "stationRT_" . $stationNum;
//        $stationRTList = DB::select('select * from '.$stationTable.' order by Time asc')->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $stationRTList = DB::table($stationTable)->where($conditions)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')->get();;

        }
        else
        {
            $stationRTList = DB::table($stationTable)->where($conditions)->orderBy('Time', 'asc')->get();;
        }

        return $stationRTList;
    }

    public function getStationRTListByTime($stationNum, $searchStartTime,$searchEndTime)
    {
        $stationTable = "stationRT_" . $stationNum;
//        $stationRTList = DB::select('select * from '.$stationTable.' order by Time asc')->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);

        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            $stationRTList = DB::table($stationTable)->whereBetween('Time',[$searchStartTime,$searchEndTime])->orderBy('Time', 'asc')->get();;

        }
        else
        {
            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')->get();;
        }

        return $stationRTList;
    }

    public function getFailureListByStationID($stationID,$repairProcess, $size, $cursorPage,$searchStartTime,$searchEndTime)
    {
        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            // 全部
            if($repairProcess == 99)
            {
                if($stationID == 0)
                {
                    $failureList = DB::table('failures')->where(['delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $failureList = DB::table('failures')->where(['station_id'=>$stationID,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }

            }
            else
            {
                if($stationID == 0)
                {
                    $failureList = DB::table('failures')->where(['repair_process'=>$repairProcess,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $failureList = DB::table('failures')->where(['station_id'=>$stationID,'repair_process'=>$repairProcess,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }

            }

        }
        else
        {
            if($repairProcess == 99)
            {
                if($stationID == 0)
                {
                    $failureList = DB::table('failures')->where(['delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $failureList = DB::table('failures')->where(['station_id'=>$stationID,'delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }

            }
            else
            {
                if($stationID == 0)
                {
                    $failureList = DB::table('failures')->where(['repair_process'=>$repairProcess,'delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $failureList = DB::table('failures')->where(['station_id'=>$stationID,'repair_process'=>$repairProcess,'delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }

            }

        }

        return $failureList;
    }

    public function getMaintenanceListByStationID($stationID,$repairProcess, $size, $cursorPage,$searchStartTime,$searchEndTime)
    {
        if(!empty($searchStartTime) && !empty($searchEndTime))
        {
            // 全部
            if($repairProcess == 99)
            {
                if($stationID == 0)
                {
                    $MaintenanceList = DB::table('maintenances')->where(['delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $MaintenanceList = DB::table('maintenances')->where(['station_id'=>$stationID,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }

            }
            else
            {
                if($stationID == 0)
                {
                    $MaintenanceList = DB::table('maintenances')->where(['repair_process'=>$repairProcess,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $MaintenanceList = DB::table('maintenances')->where(['station_id'=>$stationID,'repair_process'=>$repairProcess,'delete_process'=>0])->whereBetween('created_at',[$searchStartTime,$searchEndTime])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }

            }


        }
        else
        {
            if($repairProcess == 99)
            {
                if($stationID == 0)
                {
                    $MaintenanceList = DB::table('maintenances')->where(['delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $MaintenanceList = DB::table('maintenances')->where(['station_id'=>$stationID,'delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
            }
            else
            {
                if($stationID == 0)
                {
                    $MaintenanceList = DB::table('maintenances')->where(['repair_process'=>$repairProcess,'delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }
                else
                {
                    $MaintenanceList = DB::table('maintenances')->where(['station_id'=>$stationID,'repair_process'=>$repairProcess,'delete_process'=>0])->orderBy('created_at', 'desc')
                        ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
                }

            }
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

            $stationRTList = DB::select('SELECT * from (Select *,(@rowNum:=@rowNum+1) as rowNo From '.$stationTable.', (Select (@rowNum :=0) ) b where Time > ? and Time < ?) as a where mod(a.rowNo, 20) = 1',[$searchStartTime,$searchEndTime])
                ;

        }
        else
        {
//            $stationRTList = DB::table($stationTable)->orderBy('Time', 'asc')
//                ->get();

            $stationRTList = DB::select('SELECT * from (Select *,(@rowNum:=@rowNum+1) as rowNo From '.$stationTable.', (Select (@rowNum :=0) ) b ) as a where mod(a.rowNo, 20) = 1')
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

    public function getWaningCountAjax($stationID,$startTime,$endTime)
    {
        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationWarningCountList = [];

        $condition1 = ['bj_b1' => 1];
        $warningListPump1 = $this->getStationRTListByConditions($stationNum,$condition1,$searchStartTime,$searchEndTime);
        $warningCount1 = ['alarmEquipment' => '1号泵电机','alarmCount' => count($warningListPump1)];
        array_push($stationWarningCountList,$warningCount1);


        $condition2 = ['bj_b2' => 1];
        $warningListPump2 = $this->getStationRTListByConditions($stationNum,$condition2,$searchStartTime,$searchEndTime);
        $warningCount2 = ['alarmEquipment' => '2号泵电机','alarmCount' => count($warningListPump2)];
        array_push($stationWarningCountList,$warningCount2);

        $condition3 = ['bj_b3' => 1];
        $warningListPump3 = $this->getStationRTListByConditions($stationNum,$condition3,$searchStartTime,$searchEndTime);
        $warningCount3 = ['alarmEquipment' => '3号泵电机','alarmCount' => count($warningListPump3)];
        array_push($stationWarningCountList,$warningCount3);

        $condition4 = ['bj_b4' => 1];
        $warningListPump4 = $this->getStationRTListByConditions($stationNum,$condition4,$searchStartTime,$searchEndTime);
        $warningCount4 = ['alarmEquipment' => '4号泵电机','alarmCount' => count($warningListPump4)];
        array_push($stationWarningCountList,$warningCount4);



        $condition1RQ = ['rqbj_b1' => 1];
        $warningListPump1RQ = $this->getStationRTListByConditions($stationNum,$condition1RQ,$searchStartTime,$searchEndTime);
        $warningCount1RQ = ['alarmEquipment' => '1号泵软启动器','alarmCount' => count($warningListPump1RQ)];
        array_push($stationWarningCountList,$warningCount1RQ);


        $condition2RQ = ['rqbj_b2' => 1];
        $warningListPump2RQ = $this->getStationRTListByConditions($stationNum,$condition2RQ,$searchStartTime,$searchEndTime);
        $warningCount2RQ = ['alarmEquipment' => '2号泵软启动器','alarmCount' => count($warningListPump2RQ)];
        array_push($stationWarningCountList,$warningCount2RQ);

        $condition3RQ = ['rqbj_b3' => 1];
        $warningListPump3RQ = $this->getStationRTListByConditions($stationNum,$condition3RQ,$searchStartTime,$searchEndTime);
        $warningCount3RQ = ['alarmEquipment' => '3号泵软启动器','alarmCount' => count($warningListPump3RQ)];
        array_push($stationWarningCountList,$warningCount3RQ);

        $condition4RQ = ['rqbj_b4' => 1];
        $warningListPump4RQ = $this->getStationRTListByConditions($stationNum,$condition4RQ,$searchStartTime,$searchEndTime);
        $warningCount4RQ = ['alarmEquipment' => '4号泵软启动器','alarmCount' => count($warningListPump4RQ)];
        array_push($stationWarningCountList,$warningCount4RQ);


        if($stationNum == 33)
        {
            $conditionPlus = ['bj_b5' => 1];
            $warningListPump5 = $this->getStationRTListByConditions($stationNum,$conditionPlus,$searchStartTime,$searchEndTime);
            $warningCountPlus = ['alarmEquipment' => '5号泵电机','alarmCount' => count($warningListPump5)];
            array_push($stationWarningCountList,$warningCountPlus);

            $conditionPlusRQ = ['rqbj_b5' => 1];
            $warningListPump5RQ = $this->getStationRTListByConditions($stationNum,$conditionPlusRQ,$searchStartTime,$searchEndTime);
            $warningCountPlusRQ = ['alarmEquipment' => '5号泵软启动器','alarmCount' => count($warningListPump5RQ)];
            array_push($stationWarningCountList,$warningCountPlusRQ);
        }
        $condition5 = ['bj_gs1' => 1];
        $warningListGS1 = $this->getStationRTListByConditions($stationNum,$condition5,$searchStartTime,$searchEndTime);
        $warningCount5 = ['alarmEquipment' => '1号格栅','alarmCount' => count($warningListGS1)];
        array_push($stationWarningCountList,$warningCount5);

        $condition6 = ['bj_gs2' => 1];
        $warningListGS2 = $this->getStationRTListByConditions($stationNum,$condition6,$searchStartTime,$searchEndTime);
        $warningCount6 = ['alarmEquipment' => '2号格栅','alarmCount' => count($warningListGS2)];
        array_push($stationWarningCountList,$warningCount6);

        $condition7 = ['bj_jl' => 1];
        $warningListJL = $this->getStationRTListByConditions($stationNum,$condition7,$searchStartTime,$searchEndTime);
        $warningCount7 = ['alarmEquipment' => '绞笼','alarmCount' => count($warningListJL)];
        array_push($stationWarningCountList,$warningCount7);

        $condition8 = ['water_v' => 0];
        $warningListSD = $this->getStationRTListByConditions($stationNum,$condition8,$searchStartTime,$searchEndTime);
        $warningCount8 = ['alarmEquipment' => '市电停电','alarmCount' => count($warningListSD)];
        array_push($stationWarningCountList,$warningCount8);

        $condition9 = ['flow_v' => 1];
        $warningListJT = $this->getStationRTListByConditions($stationNum,$condition9,$searchStartTime,$searchEndTime);
        $warningCount9 = ['alarmEquipment' => '手动急停','alarmCount' => count($warningListJT)];
        array_push($stationWarningCountList,$warningCount9);

        return response()->json(array('stationWarningCountList'=> $stationWarningCountList,'startTime' => $startTime, 'endTime' => $endTime), 200);
    }

    public function getWaningCountAjaxV2($stationID,$startTime,$endTime)
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stationTemp = $this->stationInfo($stationID);
        $stationNum = $stationTemp['station_number'];

        $stationRTList = $this->getStationRTListByTime($stationNum,$searchStartTime,$searchEndTime);

        $stationWarningCountList = [];

        $countB1 = 0;
        $countB2 = 0;
        $countB3 = 0;
        $countB4 = 0;
        $countB5 = 0;
        $countB1RQ = 0;
        $countB2RQ = 0;
        $countB3RQ = 0;
        $countB4RQ = 0;
        $countB5RQ = 0;
        $countGS1 = 0;
        $countGS2 = 0;
        $countJL = 0;
        $countSD = 0;
        $countJT = 0;

        for($i = 0 ; $i < count($stationRTList)-1;$i++)
        {
            if($stationRTList[$i]->bj_b1 == 0 && $stationRTList[$i+1]->bj_b1 == 1 )
            {
                $countB1 ++;
            }

            if($stationRTList[$i]->bj_b2 == 0 && $stationRTList[$i+1]->bj_b2 == 1 )
            {
                $countB2 ++;
            }

            if($stationRTList[$i]->bj_b3 == 0 && $stationRTList[$i+1]->bj_b3 == 1 )
            {
                $countB3 ++;
            }

            if($stationRTList[$i]->bj_b4 == 0 && $stationRTList[$i+1]->bj_b4 == 1 )
            {
                $countB4 ++;
            }


            if($stationRTList[$i]->rqbj_b1 == 0 && $stationRTList[$i+1]->rqbj_b1 == 1 )
            {
                $countB1RQ ++;
            }

            if($stationRTList[$i]->rqbj_b2 == 0 && $stationRTList[$i+1]->rqbj_b2 == 1 )
            {
                $countB2RQ ++;
            }

            if($stationRTList[$i]->rqbj_b3 == 0 && $stationRTList[$i+1]->rqbj_b3 == 1 )
            {
                $countB3RQ ++;
            }

            if($stationRTList[$i]->rqbj_b4 == 0 && $stationRTList[$i+1]->rqbj_b4 == 1 )
            {
                $countB4RQ ++;
            }

            if($stationNum == 33)
            {
                if($stationRTList[$i]->bj_b5 == 0 && $stationRTList[$i+1]->bj_b5 == 1 )
                {
                    $countB5 ++;
                }

                if($stationRTList[$i]->rqbj_b5 == 0 && $stationRTList[$i+1]->rqbj_b5 == 1 )
                {
                    $countB5RQ ++;
                }
            }

            if($stationRTList[$i]->bj_jl == 0 && $stationRTList[$i+1]->bj_jl == 1 )
            {
                $countJL ++;
            }

            if($stationRTList[$i]->bj_gs1 == 0 && $stationRTList[$i+1]->bj_gs1 == 1 )
            {
                $countGS1 ++;
            }

            if($stationRTList[$i]->bj_gs2 == 0 && $stationRTList[$i+1]->bj_gs2 == 1 )
            {
                $countGS2 ++;
            }

            //部分泵站通讯中断,没有数据,不做市电的报警
            $stationNoWorking = ['11','12','20','34'];
            if(in_array($stationNum, $stationNoWorking))
            {

            }else{
                if($stationRTList[$i]->water_v == 1 && $stationRTList[$i+1]->water_v == 0 )
                {
                    $countSD ++;
                }
            }


            if($stationRTList[$i]->flow_v == 0 && $stationRTList[$i+1]->flow_v == 1 )
            {
                $countJT ++;
            }
        }

        $warningCount1 = ['alarmEquipment' => '1号泵电机','alarmCount' => $countB1];
        array_push($stationWarningCountList,$warningCount1);


        $warningCount2 = ['alarmEquipment' => '2号泵电机','alarmCount' => $countB2];
        array_push($stationWarningCountList,$warningCount2);

        $warningCount3 = ['alarmEquipment' => '3号泵电机','alarmCount' => $countB3];
        array_push($stationWarningCountList,$warningCount3);

        $warningCount4 = ['alarmEquipment' => '4号泵电机','alarmCount' => $countB4];
        array_push($stationWarningCountList,$warningCount4);

        $warningCount1RQ = ['alarmEquipment' => '1号泵软启动器','alarmCount' => $countB1RQ];
        array_push($stationWarningCountList,$warningCount1RQ);

        $warningCount2RQ = ['alarmEquipment' => '2号泵软启动器','alarmCount' => $countB2RQ];
        array_push($stationWarningCountList,$warningCount2RQ);

        $warningCount3RQ = ['alarmEquipment' => '3号泵软启动器','alarmCount' => $countB3RQ];
        array_push($stationWarningCountList,$warningCount3RQ);

        $warningCount4RQ = ['alarmEquipment' => '4号泵软启动器','alarmCount' => $countB4RQ];
        array_push($stationWarningCountList,$warningCount4RQ);


        if($stationNum == 33)
        {
            $warningCountPlus = ['alarmEquipment' => '5号泵电机','alarmCount' => $countB5];
            array_push($stationWarningCountList,$warningCountPlus);

            $warningCountPlusRQ = ['alarmEquipment' => '5号泵软启动器','alarmCount' => $countB5RQ];
            array_push($stationWarningCountList,$warningCountPlusRQ);
        }

        $warningCount5 = ['alarmEquipment' => '1号格栅','alarmCount' => $countGS1];
        array_push($stationWarningCountList,$warningCount5);

        $warningCount6 = ['alarmEquipment' => '2号格栅','alarmCount' => $countGS2];
        array_push($stationWarningCountList,$warningCount6);

        $warningCount7 = ['alarmEquipment' => '绞笼','alarmCount' => $countJL];
        array_push($stationWarningCountList,$warningCount7);

        $warningCount8 = ['alarmEquipment' => '市电停电','alarmCount' => $countSD];
        array_push($stationWarningCountList,$warningCount8);

        $warningCount9 = ['alarmEquipment' => '手动急停','alarmCount' => $countJT];
        array_push($stationWarningCountList,$warningCount9);

        return response()->json(array('stationWarningCountList'=> $stationWarningCountList,'startTime' => $startTime, 'endTime' => $endTime), 200);
    }

    public function getWaningCountAllAjaxV2($startTime,$endTime)
    {
        set_time_limit(0);      //执行时间无限
        ini_set('memory_limit', '-1');    //内存无限

        if ($startTime == '' || $endTime == '') {
            $startTime = date("Y-m-d");
            $endTime = date("Y-m-d");
        }

        $searchStartTime = !empty($startTime) ? date('Y-m-d 00:00:00', strtotime($startTime)) : '';
        $searchEndTime = !empty($endTime) ? date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($endTime))) : '';

        $stations = $this->stationList();
        foreach ($stations as $station)
        {
            $stationNum = $station['station_number'];

            $stationRTList = $this->getStationRTListByTime($stationNum,$searchStartTime,$searchEndTime);

            $countB1 = 0;
            $countB2 = 0;
            $countB3 = 0;
            $countB4 = 0;
            $countB5 = 0;
            $countB1RQ = 0;
            $countB2RQ = 0;
            $countB3RQ = 0;
            $countB4RQ = 0;
            $countB5RQ = 0;
            $countGS1 = 0;
            $countGS2 = 0;
            $countJL = 0;
            $countSD = 0;
            $countJT = 0;

            for($i = 0 ; $i < count($stationRTList)-1;$i++)
            {
                if($stationRTList[$i]->bj_b1 == 0 && $stationRTList[$i+1]->bj_b1 == 1 )
                {
                    $countB1 ++;
                }

                if($stationRTList[$i]->bj_b2 == 0 && $stationRTList[$i+1]->bj_b2 == 1 )
                {
                    $countB2 ++;
                }

                if($stationRTList[$i]->bj_b3 == 0 && $stationRTList[$i+1]->bj_b3 == 1 )
                {
                    $countB3 ++;
                }

                if($stationRTList[$i]->bj_b4 == 0 && $stationRTList[$i+1]->bj_b4 == 1 )
                {
                    $countB4 ++;
                }


                if($stationRTList[$i]->rqbj_b1 == 0 && $stationRTList[$i+1]->rqbj_b1 == 1 )
                {
                    $countB1RQ ++;
                }

                if($stationRTList[$i]->rqbj_b2 == 0 && $stationRTList[$i+1]->rqbj_b2 == 1 )
                {
                    $countB2RQ ++;
                }

                if($stationRTList[$i]->rqbj_b3 == 0 && $stationRTList[$i+1]->rqbj_b3 == 1 )
                {
                    $countB3RQ ++;
                }

                if($stationRTList[$i]->rqbj_b4 == 0 && $stationRTList[$i+1]->rqbj_b4 == 1 )
                {
                    $countB4RQ ++;
                }

                if($stationNum == 33)
                {
                    if($stationRTList[$i]->bj_b5 == 0 && $stationRTList[$i+1]->bj_b5 == 1 )
                    {
                        $countB5 ++;
                    }

                    if($stationRTList[$i]->rqbj_b5 == 0 && $stationRTList[$i+1]->rqbj_b5 == 1 )
                    {
                        $countB5RQ ++;
                    }
                }

                if($stationRTList[$i]->bj_jl == 0 && $stationRTList[$i+1]->bj_jl == 1 )
                {
                    $countJL ++;
                }

                if($stationRTList[$i]->bj_gs1 == 0 && $stationRTList[$i+1]->bj_gs1 == 1 )
                {
                    $countGS1 ++;
                }

                if($stationRTList[$i]->bj_gs2 == 0 && $stationRTList[$i+1]->bj_gs2 == 1 )
                {
                    $countGS2 ++;
                }

                //部分泵站通讯中断,没有数据,不做市电的报警
                $stationNoWorking = ['11','12','20','34'];
                if(in_array($stationNum, $stationNoWorking))
                {

                }else{
                    if($stationRTList[$i]->water_v == 1 && $stationRTList[$i+1]->water_v == 0 )
                    {
                        $countSD ++;
                    }
                }


                if($stationRTList[$i]->flow_v == 0 && $stationRTList[$i+1]->flow_v == 1 )
                {
                    $countJT ++;
                }
            }

            $station['alarmPump1'] = $countB1;
            $station['alarmPump2'] = $countB2;
            $station['alarmPump3'] = $countB3;
            $station['alarmPump4'] = $countB4;

            $station['alarmPump1RQ'] = $countB1RQ;
            $station['alarmPump2RQ'] = $countB2RQ;
            $station['alarmPump3RQ'] = $countB3RQ;
            $station['alarmPump4RQ'] = $countB4RQ;

            if($stationNum == 33){
                $station['alarmPump5'] = $countB5;
                $station['alarmPump5RQ'] = $countB5RQ;
            }

            $station['alarmAuger'] = $countJL;
            $station['alarmCleaner1'] = $countGS1;
            $station['alarmCleaner2'] = $countGS2;

            //部分泵站通讯中断,没有数据,不做市电的报警
            $stationNoWorking = ['11','12','20','34'];
            if(in_array($stationNum, $stationNoWorking))
            {
                $station['alarmCity'] = 0;//市电停电报警
            }else{
                $station['alarmCity'] = $countSD;//市电停电报警
            }
            $station['alarmManual'] = $countJT;//手动急停报警

            $station['alarmSum'] = $countB1 + $countB2 + $countB3 + $countB4 + $countB5
                + $countB1RQ + $countB2RQ + $countB3RQ + $countB4RQ + $countB5RQ
                + $countJL + $countGS1 + $countGS2 + $countSD + $countJT;

        }

        return response()->json(array('stations'=> $stations,'startTime' => $startTime, 'endTime' => $endTime), 200);
    }

    public function exportToExcelFailure()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');
        $repairProcess = Input::get('repairProcess', '99');//全部

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
        $pageSize = 10000;
//        $orderColumn = 'created_at';
//        $orderDirection = 'asc';

        // 故障统计

//        $failurePaginate = $this->failureLogic->getFailures($pageSize, $orderColumn, $orderDirection, $cursorPage);

        $failurePaginate = $this->getFailureListByStationID($stationID,$repairProcess,$pageSize,$cursorPage,$searchStartTime,$searchEndTime);

        foreach ($failurePaginate as $failure) {
//            $equipment = $this->equipmentInfo($failure->equipment_id);
            $station = $this->stationInfo($failure->station_id);
//            $reporter = $this->employeeInfo($failure->reporter_id);
//            $repairer = $this->employeeInfo($failure->repairer_id);

//            $failure->equipment_name = $equipment['name'];
            $failure->station_name = $station['name'];
//            $failure->reporter_name = $reporter['name'];
//            $failure->repairer_name = $repairer['name'];
        }

        $titleTime = "(".$startTime. " 至 " .$endTime.")";

        $title = '泵站故障统计报表-' . $titleTime;

        $excelData = $failurePaginate;

        Excel::create($title, function ($excel) use ($excelData, $title) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('故障统计记录', function ($sheet) use ($excelData) {

                $sheet->row(1, ['报修时间','所属泵站', '故障设备及其他', '故障类型','故障描述', '设备状态','报修人',  '维修进度', '维修人','维修时间']);

                if (empty($excelData)) {

                    $sheet->row(2, ['空']);
                    return;
                }

                $i = 2;
                // 循环写入数据
                foreach ($excelData as $rowData) {

                    $repairProcess = '未知';
                    if($rowData->repair_process == 0)
                    {
                        $repairProcess = '报修';
                    }
                    elseif ($rowData->repair_process == 1)
                    {
                        $repairProcess = '维修中';
                    }
                    elseif ($rowData->repair_process == 2)
                    {
                        $repairProcess = '维修完成';
                    }
                    $row = [
                        $rowData->report_at,
                        $rowData->station_name,
                        $rowData->equipment,
                        $rowData->failure_type,
                        $rowData->failure_description,
                        $rowData->equipment_status,
                        $rowData->reporter,
                        $repairProcess,
                        $rowData->repairer,
                        $rowData->repair_at,
                    ];

                    $sheet->row($i, $row);
                    $i++;
                }

                $sheet->setAllBorders('thin');
                $sheet->setAutoSize(true);

            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了故障统计记录']);
    }

    public function exportToExcelMaintenance()
    {
        $stationID = Input::get('station_id', 1);
        $startTime = Input::get('timeStart', '');
        $endTime = Input::get('timeEnd', '');
        $repairProcess = Input::get('repairProcess', '99');//全部

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
        $pageSize = 10000;
//        $orderColumn = 'created_at';
//        $orderDirection = 'asc';

        // 维修统计

//        $maintenancePaginate = $this->maintenanceLogic->getMaintenances($pageSize, $orderColumn, $orderDirection, $cursorPage);

        $maintenancePaginate = $this->getMaintenanceListByStationID($stationID,$repairProcess,$pageSize,$cursorPage,$searchStartTime,$searchEndTime);

        foreach ($maintenancePaginate as $maintenance) {
//            $equipment = $this->equipmentInfo($maintenance->equipment_id);
            $station = $this->stationInfo($maintenance->station_id);
//            $repairer = $this->employeeInfo($maintenance->repairer_id);

//            $maintenance->equipment_name = $equipment['name'];
            $maintenance->station_name = $station['name'];
//            $maintenance->repairer_name = $repairer['name'];
        }

        $titleTime = "(".$startTime. " 至 " .$endTime.")";

        $title = '泵站维修统计报表-' . $titleTime;

        $excelData = $maintenancePaginate;

        Excel::create($title, function ($excel) use ($excelData, $title) {

            $excel->setTitle($title);

            $excel->setCreator('Eason')->setCompany('LegendX');

            $excel->sheet('维修统计记录', function ($sheet) use ($excelData) {

                $sheet->row(1, ['维修时间','所属泵站', '故障设备及其他', '故障原因','解决办法','维修进度', '维修结果', '维修人','备注']);

                if (empty($excelData)) {

                    $sheet->row(2, ['空']);
                    return;
                }

                $i = 2;
                // 循环写入数据
                foreach ($excelData as $rowData) {

                    $repairProcess = '未知';
                    if($rowData->repair_process == 0)
                    {
                        $repairProcess = '报修';
                    }
                    elseif ($rowData->repair_process == 1)
                    {
                        $repairProcess = '维修中';
                    }
                    elseif ($rowData->repair_process == 2)
                    {
                        $repairProcess = '维修完成';
                    }
                    $row = [
                        $rowData->repair_at,
                        $rowData->station_name,
                        $rowData->equipment,
                        $rowData->failure_reason,
                        $rowData->repair_solution,
                        $repairProcess,
                        $rowData->result,
                        $rowData->repairer,
                        $rowData->remark,
                    ];

                    $sheet->row($i, $row);
                    $i++;
                }

                $sheet->setAllBorders('thin');
                $sheet->setAutoSize(true);

            });

        })->export('xls');

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '导出了维修统计记录']);
    }

}
