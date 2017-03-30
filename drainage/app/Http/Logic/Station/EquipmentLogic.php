<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:11
 */

namespace App\Http\Logic\Station;


use App\Repositories\Station\EquipmentRepository;
use Support\Logic\Logic;

class EquipmentLogic extends Logic
{
    /**
     * @var EquipmentRepository
     */
    protected $equipmentRepository;

    /**
     * EquipmentLogic constructor.
     * @param EquipmentRepository $equipmentRepository
     */
    public function __construct(EquipmentRepository $equipmentRepository)
    {
        $this->equipmentRepository = $equipmentRepository;
    }
}