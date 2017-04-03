<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Logic\Maintenance\MaintenanceLogic;
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
     * @var MaintenanceValidation
     */
    protected $maintenanceValidation;

    /**
     * MaintenanceController constructor.
     * @param MaintenanceLogic $maintenanceLogic
     * @param MaintenanceValidation $maintenanceValidation
     */
    public function __construct(MaintenanceLogic $maintenanceLogic,MaintenanceValidation $maintenanceValidation)
    {
        $this->maintenanceLogic = $maintenanceLogic;
        $this->maintenanceValidation = $maintenanceValidation;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddMaintenanceForm()
    {
        return view('views.maintenance.addMaintenance');
    }

    /**
     * @param $maintenanceID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateMaintenanceForm($maintenanceID)
    {
        $maintenance = $this->maintenanceLogic->findMaintenance($maintenanceID);
        $param = ['maintenance' => $maintenance];
        return view('views.maintenance.updateMaintenance',$param);
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function maintenanceList()
    {
        $input = [];
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $maintenancePaginate = $this->maintenanceLogic->getMaintenances($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['maintenances' => $maintenancePaginate->toJson()];
        return view('views.maintenance.list',$param);
    }

    /**
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewMaintenance()
    {
        $input = null;
        return $this->maintenanceLogic->createMaintenance($input);
    }

    /**
     * @param $maintenanceID
     * @return bool
     */
    public function updateMaintenance($maintenanceID)
    {
        $input = null;
        return $this->maintenanceLogic->updateMaintenance($maintenanceID,$input);
    }

    /**
     * @param $maintenanceID
     * @return mixed
     */
    public function deleteMaintenance($maintenanceID)
    {
        return $this->maintenanceLogic->deleteMaintenance($maintenanceID);
    }
}
