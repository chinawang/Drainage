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
    // 泵站列表
    Route::get('/stations', 'Api\StationController@getAllStations');
    //泵站信息
    Route::get('/stations/detail', 'Api\StationController@stationInfo');
    //泵站实时运行信息
    Route::get('/realtime/working', 'Api\StationController@getRealTimeWorking');
    //泵站实时报警信息
    Route::get('/realtime/alarm', 'Api\StationController@getRealTimeAlarm');
    //泵站运行统计
    Route::get('/reports/working', 'Api\StationController@getReportWorking');
    //泵站水位统计
    Route::get('/reports/water', 'Api\StationController@getReportWaterLevel');
    //泵站报警统计
    Route::get('/reports/alarm', 'Api\StationController@getReportAlarm');
});

//Route::group(['prefix'=>'v1','middleware'=>CheckAPI::class],function () {
//    // 泵站列表
//    Route::get('/stations', 'Api\StationController@getAllStations');
//    //泵站信息
//    Route::get('/stations/detail', 'Api\StationController@stationInfo');
//    //泵站实时运行信息
//    Route::get('/realtime/working', 'Api\StationController@getRealTimeWorking');
//    //泵站实时报警信息
//    Route::get('/realtime/alarm', 'Api\StationController@getRealTimeAlarm');
//    //泵站运行统计
//    Route::get('/reports/working', 'Api\StationController@getReportWorking');
//    //泵站水位统计
//    Route::get('/reports/water', 'Api\StationController@getReportWaterLevel');
//    //泵站报警统计
//    Route::get('/reports/alarm', 'Api\StationController@getReportAlarm');
//});
