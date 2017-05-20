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
 *设备相关
 */

//设备列表
Route::get('/equipment/lists', 'Station\EquipmentController@equipmentList');
Route::get('/station/equipment/lists/{station_id}', 'Station\EquipmentController@equipmentListOfStation');

//设备创建保存
Route::get('/equipment/add','Station\EquipmentController@showAddEquipmentForm');
Route::post('/equipment/store','Station\EquipmentController@storeNewEquipment');

//设备编辑更新
Route::get('/equipment/edit/{equipment_id}','Station\EquipmentController@showUpdateEquipmentForm');
Route::post('/equipment/update/{equipment_id}','Station\EquipmentController@updateEquipment');

//删除设备
Route::post('/equipment/delete/{equipment_id}','Station\EquipmentController@deleteEquipment');


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

//统计报表
Route::get('/report/reportList', 'Reporte\ReportController@showReport');