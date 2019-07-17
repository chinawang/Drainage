<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// API Test
Route::get('/v1/test/{station_num}', 'Api\TestController@stationRTHistory');

Route::prefix('v1')->group(function () {
    Route::get('/stations', 'Api\StationController@getAllStations');

    Route::get('/stations/{stationID}', 'Api\StationController@stationInfo');
});
