<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/laravel', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

/**
 *用户相关
 */

//用户列表
Route::get('/user/lists', 'User\UserController@userList');

//用户创建保存
Route::get('/user/add','User\UserController@showAddUserForm');
Route::post('/user/store','User\UserController@storeNewUser');

//用户编辑更新
Route::get('/user/edit/{user_id}','User\UserController@showUpdateUserForm');
Route::post('/user/update/{user_id}','User\UserController@updateUser');

//重设密码
Route::get('/user/password/reset/{user_id}','User\UserController@showResetPasswordForm');
Route::post('/user/password/store/{user_id}','User\UserController@resetUserPassword');

//删除用户
Route::post('/user/delete/{user_id}','User\UserController@deleteUser');


/**
 *泵站相关
 */

//泵站列表
Route::get('/station/lists', 'Station\StationController@stationList');

//泵站筛选
Route::get('/station/search', 'Station\StationController@getStationListByType');

//泵站创建保存
Route::get('/station/add','Station\StationController@showAddStationForm');
Route::post('/station/store','Station\StationController@storeNewStation');

//泵站编辑更新
Route::get('/station/edit/{station_id}','Station\StationController@showUpdateStationForm');
Route::post('/station/update/{station_id}','Station\StationController@updateStation');

//删除泵站
Route::post('/station/delete/{station_id}','Station\StationController@deleteStation');

//泵站运行列表
Route::get('/station/runList', 'Station\StationController@runList');

//泵站运行详细
Route::get('/station/runDetail/{station_id}', 'Station\StationController@runDetail');

//泵站最新实时信息Ajax
Route::get('/station/realTime/{station_number}', 'Station\StationController@stationRT');

//泵站历史实时信息Ajax
Route::get('/station/realTimeHistory/{station_number}', 'Station\StationController@stationRTHistory');


/**
 *工作人员相关
 */

//工作人员列表
Route::get('/employee/lists', 'Employee\EmployeeController@employeeList');


//工作人员详情
Route::get('/employee/info/{employee_id}','Employee\EmployeeController@showEmployeeForm');

//工作人员创建保存
Route::get('/employee/add','Employee\EmployeeController@showAddEmployeeForm');
Route::post('/employee/store','Employee\EmployeeController@storeNewEmployee');

//工作人员编辑更新
Route::get('/employee/edit/{employee_id}','Employee\EmployeeController@showUpdateEmployeeForm');
Route::post('/employee/update/{employee_id}','Employee\EmployeeController@updateEmployee');

//删除工作人员
Route::post('/employee/delete/{employee_id}','Employee\EmployeeController@deleteEmployee');

//工作人员导出
Route::get('/employee/export','Employee\EmployeeController@exportToExcel');

/**
 *泵站工作人员相关
 */

//泵站工作人员设置
Route::get('/station/employee/edit/{station_id}','Station\StationController@showSetStationEmployeeForm');
Route::post('/station/employee/store/{station_id}','Station\StationController@setStationEmployee');


/**
 *设备相关
 */

//设备列表
Route::get('/equipment/lists', 'Station\EquipmentController@equipmentList');
Route::get('/station/equipment/lists/{station_id}', 'Station\EquipmentController@equipmentListOfStation');

//设备筛选
Route::get('/equipment/search', 'Station\EquipmentController@equipmentListOfStation');

//设备创建保存
Route::get('/equipment/add','Station\EquipmentController@showAddEquipmentForm');
Route::post('/equipment/store','Station\EquipmentController@storeNewEquipment');

//设备编辑更新
Route::get('/equipment/edit/{equipment_id}','Station\EquipmentController@showUpdateEquipmentForm');
Route::post('/equipment/update/{equipment_id}','Station\EquipmentController@updateEquipment');

//删除设备
Route::post('/equipment/delete/{equipment_id}','Station\EquipmentController@deleteEquipment');

//设备导出
Route::get('/equipment/export','Station\EquipmentController@exportToExcel');


/**
 *泵组抽水量设置相关
 */

//泵组抽水量列表
Route::get('/pump/lists', 'Station\PumpController@pumpList');

//泵组抽水量创建保存
Route::get('/pump/add','Station\PumpController@showAddPumpForm');
Route::post('/pump/store','Station\PumpController@storeNewPump');

//泵组抽水量编辑更新
Route::get('/pump/edit/{pump_id}','Station\PumpController@showUpdatePumpForm');
Route::post('/pump/update/{pump_id}','Station\PumpController@updatePump');

//删除泵组抽水量
Route::post('/pump/delete/{pump_id}','Station\PumpController@deletePump');


/**
 *权限相关
 */

//权限列表
Route::get('/permission/lists', 'Rbac\PermissionController@permissionList');

//权限创建保存
Route::get('/permission/add','Rbac\PermissionController@showAddPermissionForm');
Route::post('/permission/store','Rbac\PermissionController@storeNewPermission');

//权限编辑更新
Route::get('/permission/edit/{permission_id}','Rbac\PermissionController@showUpdatePermissionForm');
Route::post('/permission/update/{permission_id}','Rbac\PermissionController@updatePermission');

//删除权限
Route::post('/permission/delete/{permission_id}','Rbac\PermissionController@deletePermission');

Route::get('/permission/test', 'Rbac\PermissionController@check');


/**
 *角色相关
 */

//角色列表
Route::get('/role/lists', 'Rbac\RoleController@roleList');

//角色创建保存
Route::get('/role/add','Rbac\RoleController@showAddRoleForm');
Route::post('/role/store','Rbac\RoleController@storeNewRole');

//角色编辑更新
Route::get('/role/edit/{role_id}','Rbac\RoleController@showUpdateRoleForm');
Route::post('/role/update/{role_id}','Rbac\RoleController@updateRole');

//删除角色
Route::post('/role/delete/{role_id}','Rbac\RoleController@deleteRole');


/**
 *角色权限相关
 */

//角色权限设置
Route::get('/role/permission/edit/{role_id}','Rbac\RolePermissionController@showSetRolePermissionForm');
Route::post('/role/permission/store/{role_id}','Rbac\RolePermissionController@setRolePermission');


/**
 *用户角色相关
 */

//用户角色设置
Route::get('/user/role/edit/{user_id}','Rbac\UserRoleController@showSetUserRoleForm');
Route::post('/user/role/store/{user_id}','Rbac\UserRoleController@setUserRole');


/**
 *故障相关
 */

//故障列表
Route::get('/failure/lists', 'Maintenance\FailureController@failureList');
Route::get('/station/failure/lists/{station_id}', 'Maintenance\FailureController@failureListOfStation');

//故障创建保存
Route::get('/failure/add','Maintenance\FailureController@showAddFailureForm');
Route::post('/failure/store','Maintenance\FailureController@storeNewFailure');

//故障编辑更新
Route::get('/failure/edit/{failure_id}','Maintenance\FailureController@showUpdateFailureForm');
Route::post('/failure/update/{failure_id}','Maintenance\FailureController@updateFailure');

//删除故障
Route::post('/failure/delete/{failure_id}','Maintenance\FailureController@deleteFailure');


/**
 *维修相关
 */

//维修列表
Route::get('/maintenance/lists', 'Maintenance\MaintenanceController@maintenanceList');
Route::get('/station/maintenance/lists/{station_id}', 'Maintenance\MaintenanceController@maintenanceListOfStation');
Route::get('/failure/maintenance/lists/{failure_id}', 'Maintenance\MaintenanceController@maintenanceListOfFailure');

//维修创建保存
Route::get('/maintenance/add/{failure_id}','Maintenance\MaintenanceController@showAddMaintenanceForm');
Route::post('/maintenance/store','Maintenance\MaintenanceController@storeNewMaintenance');

//维修编辑更新
Route::get('/maintenance/edit/{maintenance_id}','Maintenance\MaintenanceController@showUpdateMaintenanceForm');
Route::post('/maintenance/update/{maintenance_id}','Maintenance\MaintenanceController@updateMaintenance');

//删除维修
Route::post('/maintenance/delete/{maintenance_id}','Maintenance\MaintenanceController@deleteMaintenance');

//维修导出
Route::get('/maintenance/export','Maintenance\MaintenanceController@exportToExcel');

/**
 *天气相关
 */

Route::get('/weather/view', 'Weather\WeatherController@showWeather');

/**
 *地图相关
 */

Route::get('/map/view', 'Map\MapController@showMap');

Route::get('/map/show', 'Map\MapController@showMapEmpty');
Route::get('/map/stations', 'Map\MapController@getAllStations');


/**
 *报警相关
 */

//泵站报警列表
Route::get('/warning/warningList', 'Warning\WarningController@warningList');

//泵站报警详细
Route::get('/warning/warningDetail/{station_id}', 'Warning\WarningController@warningDetail');


/**
 *统计报表相关
 */
//按日单机运行时间统计
Route::get('/report/stationStatusDay', 'Reporte\StatusReportController@showStatusReportDay');
//按月单机运行时间统计
Route::get('/report/stationStatusMonth', 'Reporte\StatusReportController@showStatusReportMonth');
//按日泵站总计运行时间统计
Route::get('/report/stationStatusMonthAll', 'Reporte\StatusReportController@showStatusReportMonthAll');


//液位统计
Route::get('/report/stationWater', 'Reporte\ReportController@showWaterReport');
//运行统计
Route::get('/report/stationRunning', 'Reporte\ReportController@showRunningReport');
//启停统计
Route::get('/report/stationStatus', 'Reporte\ReportController@showStatusReport');
//故障统计
Route::get('/report/stationFailure', 'Reporte\ReportController@showFailureReport');
//维修统计
Route::get('/report/stationMaintenance', 'Reporte\ReportController@showMaintenanceReport');

//泵站历史实时信息Ajax
Route::get('/report/realTimeHistory/{station_id}/{start_time}/{end_time}', 'Reporte\ReportController@stationRTHistory');

//泵站启停状态实时信息Ajax
Route::get('/report/realTimeStatusHistory/{station_id}/{start_time}', 'Reporte\ReportController@statusRTHistoryAjax');

//日志
Route::get('/log/lists', 'Log\LogController@lgoList');

// API
Route::get('/api/v1/test/{station_num}', 'Api\TestController@stationRTHistory');
