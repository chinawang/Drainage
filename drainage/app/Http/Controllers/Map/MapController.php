<?php

namespace App\Http\Controllers\Map;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    /**
     * MapController constructor.
     */
    public function __construct()
    {
    }

    public function showMap()
    {
        return view('map.map');
    }


}
