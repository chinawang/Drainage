<?php

namespace App\Http\Controllers\Log;

use App\Http\Logic\Log\LogLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * @var LogLogic
     */
    protected $logLogic;

    /**
     * LogController constructor.
     * @param LogLogic $logLogic
     */
    public function __construct(LogLogic $logLogic)
    {
        $this->middleware('auth');
        $this->logLogic = $logLogic;
    }

    public function lgoList()
    {
        $cursorPage      =  null;
        $orderColumn     = 'created_at';
        $orderDirection  = 'desc';
        $pageSize        = 20;
        $logPaginate = $this->logLogic->getLogs($pageSize,$orderColumn,$orderDirection,$cursorPage);
        $param = ['logs' => $logPaginate];
        //记录Log
        app('App\Http\Logic\Log\LogLogic')->createLog(['name' => Auth::user()->name,'log' => '查看了日志信息']);
        return view('log.list',$param);
    }
}
