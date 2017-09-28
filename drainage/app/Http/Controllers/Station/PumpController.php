<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/24
 * Time: 02:10
 */

namespace App\Http\Controllers\Station;

use App\Http\Logic\Station\PumpLogic;
use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Station\PumpValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class PumpController extends Controller
{
    protected $pumpLogic;

    protected $stationLogic;

    protected $pumpValidation;

    /**
     * PumpController constructor.
     * @param PumpLogic $pumpLogic
     * @param PumpValidation $pumpValidation
     * @param StationLogic $stationLogic
     */
    public function __construct(PumpLogic $pumpLogic, PumpValidation $pumpValidation,
                                StationLogic $stationLogic)
    {
        $this->middleware('auth');

        $this->pumpLogic = $pumpLogic;
        $this->stationLogic = $stationLogic;
        $this->pumpValidation = $pumpValidation;
    }

    /**
     * 显示添加窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddPumpForm()
    {
        $stations = $this->stationLogic->getAllStations();

        $param = ['stations' => $stations];
        return view('pump.addPump', $param);
    }

    /**
     * 显示编辑窗口
     *
     * @param $equipmentID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdatePumpForm($pumpID)
    {
        $pump = $this->pumpInfo($pumpID);
        $station = $this->stationInfo($pump['station_id']);

        $pump['station_name'] = $station['name'];

        $stations = $this->stationLogic->getAllStations();

        $param = ['pump' => $pump, 'stations' => $stations];
        return view('pump.updatePump', $param);
    }

    /**
     * 查询信息
     *
     * @param $pumpID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pumpInfo($pumpID)
    {
        $pump = $this->pumpLogic->findPump($pumpID);
        return $pump;
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
     * 分页查询列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pumpList()
    {
        $input = $this->pumpValidation->pumpPaginate();

        $cursorPage = array_get($input, 'cursor_page', null);
        $orderColumn = array_get($input, 'order_column', 'station_id');
        $orderDirection = array_get($input, 'order_direction', 'asc');
        $pageSize = array_get($input, 'page_size', 40);
        $pumpPaginate = $this->pumpLogic->getPumps($pageSize, $orderColumn, $orderDirection, $cursorPage);

        foreach ($pumpPaginate as $pump) {
            $station = $this->stationInfo($pump['station_id']);
            $pump['station_name'] = $station['name'];
            $pump['station_number'] = $station['station_number'];
        }

        $stations = $this->stationLogic->getAllStations();

        $param = ['pumps' => $pumpPaginate ,'stations' => $stations];

        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了泵组抽水量信息']);

        return view('pump.list', $param);
    }

    /**
     * 添加
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewPump()
    {
        $input = $this->pumpValidation->storeNewPump();
        $result = $this->pumpLogic->createPump($input);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '保存成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '新增了泵组抽水量信息']);

            return redirect('/pump/lists');
        } else {
            session()->flash('flash_message_overlay', [
                'title' => '保存失败!',
                'message' => '数据未保存成功,请稍后重试!',
                'level' => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * 编辑
     *
     * @param $pumpID
     * @return bool
     */
    public function updatePump($pumpID)
    {
        $input = $this->pumpValidation->updatePump($pumpID);
        $result = $this->pumpLogic->updatePump($pumpID, $input);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '保存成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '编辑了泵组抽水量信息']);

            return redirect('/pump/lists');
        } else {
            session()->flash('flash_message_overlay', [
                'title' => '保存失败!',
                'message' => '数据未保存成功,请稍后重试!',
                'level' => 'error'
            ]);
            return redirect()->back();
        }
    }

    /**
     * 删除
     *
     * @return mixed
     */
    public function deletePump($pumpID)
    {
        $result = $this->pumpLogic->deleteEquipment($pumpID);

        if ($result) {
            session()->flash('flash_message', [
                'title' => '删除成功!',
                'message' => '',
                'level' => 'success'
            ]);

            //记录Log
            app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '删除了泵组抽水量信息']);

        } else {
            session()->flash('flash_message_overlay', [
                'title' => '删除失败!',
                'message' => '数据未删除成功,请稍后重试!',
                'level' => 'error'
            ]);
        }

        return redirect('/pump/lists');
    }

}