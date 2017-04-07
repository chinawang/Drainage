<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Logic\Maintenance\FailureLogic;
use App\Http\Logic\Maintenance\MaintenanceLogic;
use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Maintenance\MaintenanceValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    /**
     * @var MaintenanceValidation
     */
    protected $maintenanceValidation;

    /**
     * MaintenanceController constructor.
     * @param MaintenanceLogic $maintenanceLogic
     * @param MaintenanceValidation $maintenanceValidation
     * @param FailureLogic $failureLogic
     * @param EquipmentLogic $equipmentLogic
     * @param StationLogic $stationLogic
     * @param UserLogic $userLogic
     */
    public function __construct(MaintenanceLogic $maintenanceLogic,MaintenanceValidation $maintenanceValidation,
                                FailureLogic $failureLogic,EquipmentLogic $equipmentLogic,StationLogic $stationLogic,UserLogic $userLogic)
    {
        $this->maintenanceLogic = $maintenanceLogic;
        $this->maintenanceValidation = $maintenanceValidation;
        $this->failureLogic = $failureLogic;
        $this->equipmentLogic = $equipmentLogic;
        $this->stationLogic = $stationLogic;
        $this->userLogic = $userLogic;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddMaintenanceForm()
    {
        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
        $users = $this->userLogic->getAllUsers();

        $param = ['equipments' => $equipments->toJson(),'stations' => $stations->toJson(),'users' => $users->toJson()];
        return view('maintenance.addMaintenance',$param);
    }

    /**
     * @param $maintenanceID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateMaintenanceForm($maintenanceID)
    {
        $maintenance = $this->maintenanceLogic->findMaintenance($maintenanceID);

        $equipment = $this->equipmentInfo($maintenance['equipment_id']);
        $station = $this->stationInfo($maintenance['station_id']);
        $repairer = $this->userInfo($maintenance['repairer_id']);

        $maintenance['equipment_name'] = $equipment['name'];
        $maintenance['station_name'] = $station['name'];
        $maintenance['repairer_name'] = $repairer['real_name'];

        $equipments = $this->equipmentLogic->getAllEquipments();
        $stations = $this->stationLogic->getAllStations();
        $users = $this->userLogic->getAllUsers();

        $param = ['maintenance' => $maintenance,'equipments' => $equipments->toJson(),
            'stations' => $stations->toJson(),'users' => $users->toJson()];
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
     * @param $userID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function userInfo($userID)
    {
        $user = $this->userLogic->findUser($userID);
        return $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintenanceList()
    {
        $input = $this->maintenanceValidation->maintenancePaginate();
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $maintenancePaginate = $this->maintenanceLogic->getMaintenances($pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($maintenancePaginate as $maintenance)
        {
            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
            $repairer = $this->userInfo($maintenance['repairer_id']);

            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
            $maintenance['repairer_name'] = $repairer['real_name'];
        }

        $param = ['maintenances' => $maintenancePaginate->toJson()];
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
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $maintenancePaginate = $this->maintenanceLogic->getMaintenancesByStation($stationID,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($maintenancePaginate as $maintenance)
        {
            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
            $repairer = $this->userInfo($maintenance['repairer_id']);

            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
            $maintenance['repairer_name'] = $repairer['real_name'];
        }

        $param = ['maintenances' => $maintenancePaginate->toJson()];
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
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $maintenancePaginate = $this->maintenanceLogic->getMaintenancesByFailure($failureID,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($maintenancePaginate as $maintenance)
        {
            $equipment = $this->equipmentInfo($maintenance['equipment_id']);
            $station = $this->stationInfo($maintenance['station_id']);
            $repairer = $this->userInfo($maintenance['repairer_id']);

            $maintenance['equipment_name'] = $equipment['name'];
            $maintenance['station_name'] = $station['name'];
            $maintenance['repairer_name'] = $repairer['real_name'];
        }

        $param = ['maintenances' => $maintenancePaginate->toJson()];
        return view('maintenance.listOfFailure',$param);
    }

    /**
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewMaintenance()
    {
        $input = $this->maintenanceValidation->storeNewMaintenance();
        return $this->maintenanceLogic->createMaintenance($input);
    }

    /**
     * @param $maintenanceID
     * @return bool
     */
    public function updateMaintenance($maintenanceID)
    {
        $input = $this->maintenanceValidation->updateMaintenance($maintenanceID);
        return $this->maintenanceLogic->updateMaintenance($maintenanceID,$input);
    }

    /**
     * @return mixed
     */
    public function deleteMaintenance()
    {
        $maintenanceID = $this->maintenanceValidation->deleteMaintenance();
        return $this->maintenanceLogic->deleteMaintenance($maintenanceID);
    }
}
