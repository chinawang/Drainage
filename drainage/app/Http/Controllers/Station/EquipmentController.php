<?php

namespace App\Http\Controllers\Station;

use App\Http\Logic\Station\EquipmentLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Logic\User\UserLogic;
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
     * @var StationLogic
     */
    protected $stationLogic;

    /**
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * @var EquipmentValidation
     */
    protected $equipmentValidation;

    /**
     * EquipmentController constructor.
     * @param EquipmentLogic $equipmentLogic
     * @param EquipmentValidation $equipmentValidation
     * @param StationLogic $stationLogic
     * @param UserLogic $userLogic
     */
    public function __construct(EquipmentLogic $equipmentLogic,EquipmentValidation $equipmentValidation,
                                StationLogic $stationLogic,UserLogic $userLogic)
    {
        $this->equipmentLogic = $equipmentLogic;
        $this->equipmentValidation = $equipmentValidation;
        $this->stationLogic = $stationLogic;
        $this->userLogic = $userLogic;
    }

    /**
     * 显示添加设备窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddEquipmentForm()
    {
        $stations = $this->stationLogic->getAllStations();
        $users = $this->userLogic->getAllUsers();

        $param = ['stations' => $stations,'users' => $users];
        return view('equipment.addEquipment',$param);
    }

    /**
     * 显示编辑设备窗口
     *
     * @param $equipmentID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateEquipmentForm($equipmentID)
    {
        $equipment = $this->equipmentInfo($equipmentID);
        $station = $this->stationInfo($equipment['station_id']);
        $leader = $this->userInfo($equipment['leader_id']);
        $custodian = $this->userInfo($equipment['custodian_id']);

        $equipment['station_name'] = $station['name'];
        $equipment['leader_name'] = $leader['real_name'];
        $equipment['custodian_name'] = $custodian['real_name'];

        $stations = $this->stationLogic->getAllStations();
        $users = $this->userLogic->getAllUsers();

        $param = ['equipment' => $equipment,'stations' => $stations,'users' => $users];
        return view('equipment.updateEquipment',$param);
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
     * 分页查询设备列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function equipmentList()
    {
        $input = $this->equipmentValidation->equipmentPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $equipmentPaginate = $this->equipmentLogic->getEquipments($pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($equipmentPaginate as $equipment)
        {
            $station = $this->stationInfo($equipment['station_id']);
            $leader = $this->userInfo($equipment['leader_id']);
            $custodian = $this->userInfo($equipment['custodian_id']);

            $equipment['station_name'] = $station['name'];
            $equipment['leader_name'] = $leader['real_name'];
            $equipment['custodian_name'] = $custodian['real_name'];
        }

        $param = ['equipments' => $equipmentPaginate];
        return view('equipment.list',$param);
    }

    /**
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function equipmentListOfStation($stationID)
    {
        $input = $this->equipmentValidation->equipmentPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 20);
        $equipmentPaginate = $this->equipmentLogic->getEquipmentsByStation($stationID,$pageSize,$orderColumn,$orderDirection,$cursorPage);

        foreach($equipmentPaginate as $equipment)
        {
            $station = $this->stationInfo($equipment['station_id']);
            $leader = $this->userInfo($equipment['leader_id']);
            $custodian = $this->userInfo($equipment['custodian_id']);

            $equipment['station_name'] = $station['name'];
            $equipment['leader_name'] = $leader['real_name'];
            $equipment['custodian_name'] = $custodian['real_name'];
        }

        $param = ['equipments' => $equipmentPaginate];
        return view('equipment.listOfStation',$param);
    }

    /**
     * 添加新设备
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewEquipment()
    {
        $input = $this->equipmentValidation->storeNewEquipment();
        $result = $this->equipmentLogic->createEquipment($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
            return redirect('/equipment/lists');
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
     * 编辑设备
     *
     * @param $equipmentID
     * @return bool
     */
    public function updateEquipment($equipmentID)
    {
        $input = $this->equipmentValidation->updateEquipment($equipmentID);
        $result = $this->equipmentLogic->updateEquipment($equipmentID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
            return redirect('/equipment/lists');
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
     * 删除设备
     *
     * @return mixed
     */
    public function deleteEquipment($equipmentID)
    {
//        $equipmentID = $this->equipmentValidation->deleteEquipment();
        $result = $this->equipmentLogic->deleteEquipment($equipmentID);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '删除成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
        }
        else
        {
            session()->flash('flash_message_overlay', [
                'title'     => '删除失败!',
                'message'   => '数据未删除成功,请稍后重试!',
                'level'     => 'error'
            ]);
        }

        return redirect('/equipment/lists');
    }
}
