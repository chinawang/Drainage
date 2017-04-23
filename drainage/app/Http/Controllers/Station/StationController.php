<?php

namespace App\Http\Controllers\Station;

use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Station\StationValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class StationController extends Controller
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
     * StationController constructor.
     * @param StationLogic $stationLogic
     * @param StationValidation $stationValidation
     */
    public function __construct(StationLogic $stationLogic,StationValidation $stationValidation)
    {
        $this->stationLogic = $stationLogic;
        $this->stationValidation = $stationValidation;
    }

    /**
     * 显示添加泵站窗口
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddStationForm()
    {
        return view('station.addStation');
    }

    /**
     * 显示编辑泵站窗口
     *
     * @param $stationID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUpdateStationForm($stationID)
    {
        $station = $this->stationLogic->findStation($stationID);
        $param = ['station' => $station];
        return view('station.updateStation',$param);
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
     * 分页查询泵站列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stationList()
    {
        $input = $this->stationValidation->stationPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $stationPaginate = $this->stationLogic->getStations($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['stations' => $stationPaginate];
        return view('station.list',$param);
    }

    /**
     * 新增泵站
     *
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function storeNewStation()
    {
        $input = $this->stationValidation->storeNewStation();
        $result = $this->stationLogic->createStation($input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
            return redirect('/station/lists');
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
     * 编辑泵站
     *
     * @param $stationID
     * @return bool
     */
    public function updateStation($stationID)
    {
        $input = $this->stationValidation->updateStation($stationID);
        $result = $this->stationLogic->updateStation($stationID,$input);

        if($result)
        {
            session()->flash('flash_message', [
                'title'     => '保存成功!',
                'message'   => '',
                'level'     => 'success'
            ]);
            return redirect('/station/lists');
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
     * 删除泵站
     *
     * @return bool
     */
    public function deleteStation($stationID)
    {
//        $stationID = $this->stationValidation->deleteStation();
        $result = $this->stationLogic->deleteStation($stationID);

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

        return redirect('/station/lists');
    }

    public function runList()
    {
        $input = $this->stationValidation->stationPaginate();

        $cursorPage      = array_get($input, 'cursor_page', null);
        $orderColumn     = array_get($input, 'order_column', 'created_at');
        $orderDirection  = array_get($input, 'order_direction', 'asc');
        $pageSize        = array_get($input, 'page_size', 10);
        $stationPaginate = $this->stationLogic->getStations($pageSize,$orderColumn,$orderDirection,$cursorPage);
        foreach ($stationPaginate as $station)
        {
            $station['status'] = 'yellow';
            $station['runPump'] = 2;
            $station['stopPump'] = 0;
            $station['culvertWater'] = 1.5;
            $station['tankWater'] = 3;
        }
        $param = ['stations' => $stationPaginate];
        return view('station.runList',$param);
    }

    public function runDetail($stationID)
    {
        $station = $this->stationInfo($stationID);
        $stationNum = $station['station_number'];
        $stationRT = $this->findStationRT($stationNum);
        $param = ['station' => $station,'stationRT' => $stationRT[0]];
        return $param;
        return view('station.runDetail',$param);
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
}
