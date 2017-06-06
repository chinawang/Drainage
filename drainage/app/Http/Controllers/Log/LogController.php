<?php

namespace App\Http\Controllers\Log;

use App\Http\Logic\Log\LogLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return view('log.list',$param);
    }
}
