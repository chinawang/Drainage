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
}
