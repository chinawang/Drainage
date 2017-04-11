<?php

namespace App\Http\Controllers\Station;

use App\Http\Logic\Station\StationLogic;
use App\Http\Validations\Station\StationValidation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

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
        $pageSize        = array_get($input, 'page_size', 20);
        $stationPaginate = $this->stationLogic->getStations($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['stations' => $stationPaginate->toJson()];
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
        return $this->stationLogic->updateStation($stationID,$input);
    }

    /**
     * 删除泵站
     *
     * @return bool
     */
    public function deleteStation()
    {
//        $stationID = $this->stationValidation->deleteStation();
//        return $this->stationLogic->deleteStation($stationID);

//        session()->flash('flash_message', [
//            'title'     => '删除成功!',
//            'message'   => '',
//            'level'     => 'success'
//        ]);
//        return redirect('/station/lists');

        return response()->json(array(
            'status' => -1,
        ));

    }
}
