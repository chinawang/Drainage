<?php

namespace App\Http\Controllers\Station;

use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Validations\Station\EquipmentValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EquipmentController extends Controller
{
    /**
     * @var EquipmentLogic
     */
    protected $equipmentLogic;

    /**
     * @var EquipmentValidation
     */
    protected $equipmentValidation;


    /**
     * EquipmentController constructor.
     * @param EquipmentLogic $equipmentLogic
     * @param EquipmentValidation $equipmentValidation
     */
    public function __construct(EquipmentLogic $equipmentLogic,EquipmentValidation $equipmentValidation)
    {
        $this->equipmentLogic = $equipmentLogic;
        $this->equipmentValidation = $equipmentValidation;
    }
}
