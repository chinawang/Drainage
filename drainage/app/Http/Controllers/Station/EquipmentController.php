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

    /**
     * 显示添加设备窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddEquipmentForm()
    {
        return view('views.equipment.addEquipment');
    }

    /**
     * 显示编辑设备窗口
     *
     * @param $equipmentID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateEquipmentForm($equipmentID)
    {
        $equipment = $this->equipmentLogic->findEquipment($equipmentID);
        $param = ['equipment' => $equipment];
        return view('views.equipment.updateEquipment',$param);
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
     * 分页查询设备列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function equipmentList()
    {
        $input = [];
        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $equipmentPaginate = $this->equipmentLogic->getEquipments($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['equipments' => $equipmentPaginate->toJson()];
        return view('views.equipment.list',$param);
    }

    /**
     * 添加新设备
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewEquipment()
    {
        $input = null;
        $stationID = null;
        return $this->equipmentLogic->createEquipment($stationID,$input);
    }

    /**
     * 编辑设备
     *
     * @param $equipmentID
     * @return bool
     */
    public function updateEquipment($equipmentID)
    {
        $input = null;
        return $this->equipmentLogic->updateEquipment($equipmentID,$input);
    }

    /**
     * 删除设备
     *
     * @param $equipmentID
     * @return mixed
     */
    public function deleteEquipment($equipmentID)
    {
        return $this->equipmentLogic->deleteEquipment($equipmentID);
    }
}
