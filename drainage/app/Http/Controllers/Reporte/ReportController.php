<?php

namespace App\Http\Controllers\Reporte;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function showReport()
    {
        return view('report.reportList');
    }
}
