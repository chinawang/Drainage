@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/common/home.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>门户</span>--}}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading" style="color:#666;background-color:#fafafa" >
                        <h3 class="panel-title" >泵站实时信息</h3>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <ul class="main-menu">
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'map-view'))
                                <li class="map">
                                    <a href="/map/view" class="menu-item">
                                        <img class="menu-icon" src="/img/home/map_blue.png">
                                        <h4>泵站地图</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'run-view'))
                                <li class="status">
                                    <a href="/station/runList" class="menu-item">
                                        <img class="menu-icon" src="/img/home/status_blue.png">
                                        <h4>泵站工作状态</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'alarm-view'))
                                <li class="station-warning">
                                    <a href="warning/warningList" class="menu-item">
                                        <img class="menu-icon" src="/img/home/warning_blue.png">
                                        <h4>泵站报警管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                <li class="report">
                                    <a href="/report/stationWater" class="menu-item">
                                        <img class="menu-icon" src="/img/home/report_blue.png">
                                        <h4>统计报表</h4>
                                    </a>
                                </li>
                            @endif

                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'station-list'))
                                <li class="station">
                                    <a href="/station/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/station_blue.png">
                                        <h4>泵站资料管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'equip-list'))
                                <li class="equipment">
                                    <a href="/equipment/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/equipment_blue.png">
                                        <h4>设备资料管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'employee-list'))
                                <li class="employee">
                                    <a href="/employee/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/employee_blue.png">
                                        <h4>工作人员管理</h4>
                                    </a>
                                </li>
                            @endif

                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'weather-view'))
                                <li class="weather">
                                    <a href="/weather/view" class="menu-item">
                                        <img class="menu-icon" src="/img/home/weather_blue.png">
                                        <h4>天气预报</h4>
                                    </a>
                                </li>
                            @endif

                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-list'))
                                <li class="failure">
                                    <a href="/failure/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/failure_blue_new.png">
                                        <h4>设备故障管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'maintenance-list'))
                                <li class="maintenance">
                                    <a href="/maintenance/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/maintenance_blue.png">
                                        <h4>设备维修管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'user-list'))
                                <li class="user">
                                    <a href="/user/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/user_blue.png">
                                        <h4>账户管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'role-list'))
                                <li class="role">
                                    <a href="/role/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/role_blue.png">
                                        <h4>角色管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'action-list'))
                                <li href="permission">
                                    <a href="/permission/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/permission_blue.png">
                                        <h4>行为权限管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'log-list'))
                                <li class="log">
                                    <a href="/log/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/log_blue.png">
                                        <h4>系统日志</h4>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="panel panel-default custom-panel">
                    <div class="panel-heading" style="color:#666;background-color:#fafafa" >
                        <h3 class="panel-title" >泵站实时信息</h3>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <ul class="main-menu">
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'map-view'))
                                <li class="map">
                                    <a href="/map/view" class="menu-item">
                                        <img class="menu-icon" src="/img/home/map_blue.png">
                                        <h4>泵站地图</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'run-view'))
                                <li class="status">
                                    <a href="/station/runList" class="menu-item">
                                        <img class="menu-icon" src="/img/home/status_blue.png">
                                        <h4>泵站工作状态</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'alarm-view'))
                                <li class="station-warning">
                                    <a href="warning/warningList" class="menu-item">
                                        <img class="menu-icon" src="/img/home/warning_blue.png">
                                        <h4>泵站报警管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                <li class="report">
                                    <a href="/report/stationWater" class="menu-item">
                                        <img class="menu-icon" src="/img/home/report_blue.png">
                                        <h4>统计报表</h4>
                                    </a>
                                </li>
                            @endif

                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'station-list'))
                                <li class="station">
                                    <a href="/station/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/station_blue.png">
                                        <h4>泵站资料管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'equip-list'))
                                <li class="equipment">
                                    <a href="/equipment/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/equipment_blue.png">
                                        <h4>设备资料管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'employee-list'))
                                <li class="employee">
                                    <a href="/employee/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/employee_blue.png">
                                        <h4>工作人员管理</h4>
                                    </a>
                                </li>
                            @endif

                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'weather-view'))
                                <li class="weather">
                                    <a href="/weather/view" class="menu-item">
                                        <img class="menu-icon" src="/img/home/weather_blue.png">
                                        <h4>天气预报</h4>
                                    </a>
                                </li>
                            @endif

                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-list'))
                                <li class="failure">
                                    <a href="/failure/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/failure_blue_new.png">
                                        <h4>设备故障管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'maintenance-list'))
                                <li class="maintenance">
                                    <a href="/maintenance/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/maintenance_blue.png">
                                        <h4>设备维修管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'user-list'))
                                <li class="user">
                                    <a href="/user/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/user_blue.png">
                                        <h4>账户管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'role-list'))
                                <li class="role">
                                    <a href="/role/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/role_blue.png">
                                        <h4>角色管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'action-list'))
                                <li href="permission">
                                    <a href="/permission/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/permission_blue.png">
                                        <h4>行为权限管理</h4>
                                    </a>
                                </li>
                            @endif
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'log-list'))
                                <li class="log">
                                    <a href="/log/lists" class="menu-item">
                                        <img class="menu-icon" src="/img/home/log_blue.png">
                                        <h4>系统日志</h4>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
