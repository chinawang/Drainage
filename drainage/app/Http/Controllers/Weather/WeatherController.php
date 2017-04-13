<?php

namespace App\Http\Controllers\Weather;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    /**
     * WeatherController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showWeather()
    {
        return view('weather.weather');
    }
}
