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
                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'map-view') ||
                app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'run-view') ||
                app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'alarm-view') ||
                app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'weather-view'))
                    <div class="panel panel-default custom-panel">
                        <div class="panel-heading" style="color:#6f7c85;font-size:18px;background-color:#fafafa">
                            <h3 class="panel-title" style="line-height: 30px;margin-left: 10px;">实时信息监测</h3>
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
                                            <h4>泵站运行状态</h4>
                                        </a>
                                    </li>
                                @endif
                                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'alarm-view'))
                                    <li class="station-warning">
                                        <a href="warning/warningList" class="menu-item">
                                            <img class="menu-icon" src="/img/home/warning_blue.png">
                                            <h4>泵站实时报警</h4>
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
                            </ul>
                        </div>
                    </div>
                @endif

                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                    <div class="panel panel-default custom-panel">
                        <div class="panel-heading" style="color:#6f7c85;font-size:18px;background-color:#fafafa">
                            <h3 class="panel-title" style="line-height: 30px;margin-left: 10px;">运行统计报表</h3>
                        </div>
                        <div class="panel-body custom-panel-body">
                            <ul class="main-menu">
                                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                    <li class="report">
                                        <a href="/report/stationStatusDay" class="menu-item">
                                            <img class="menu-icon" src="/img/home/report_run_blue.png">
                                            <h4>运行抽升统计</h4>
                                        </a>
                                    </li>
                                @endif
                                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                    <li class="report">
                                        <a href="/report/stationRunning" class="menu-item">
                                            <img class="menu-icon" src="/img/home/report_current_blue.png">
                                            <h4>电流电压统计</h4>
                                        </a>
                                    </li>
                                @endif
                                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                    <li class="report">
                                        <a href="/report/stationWater" class="menu-item">
                                            <img class="menu-icon" src="/img/home/report_water_blue.png">
                                            <h4>泵站水位统计</h4>
                                        </a>
                                    </li>
                                @endif
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                        <li class="report">
                                            <a href="/report/stationWarning" class="menu-item">
                                                <img class="menu-icon" src="/img/home/report_warning_blue.png">
                                                <h4>泵站报警统计</h4>
                                            </a>
                                        </li>
                                    @endif
                            </ul>
                        </div>
                    </div>
                @endif

                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'station-list') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'equip-list') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'employee-list'))
                    <div class="panel panel-default custom-panel">
                        <div class="panel-heading" style="color:#6f7c85;font-size:18px;background-color:#fafafa">
                            <h3 class="panel-title" style="line-height: 30px;margin-left: 10px;">泵站基础信息</h3>
                        </div>
                        <div class="panel-body custom-panel-body">
                            <ul class="main-menu">
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
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'pump-list'))
                                        <li class="employee">
                                            <a href="/pump/lists" class="menu-item">
                                                <img class="menu-icon" src="/img/home/pump_blue.png">
                                                <h4>泵组抽水量配置</h4>
                                            </a>
                                        </li>
                                    @endif
                            </ul>
                        </div>
                    </div>
                @endif

                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-list') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'maintenance-list') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                    <div class="panel panel-default custom-panel">
                        <div class="panel-heading" style="color:#6f7c85;font-size:18px;background-color:#fafafa">
                            <h3 class="panel-title" style="line-height: 30px;margin-left: 10px;">故障与维修</h3>
                        </div>
                        <div class="panel-body custom-panel-body">
                            <ul class="main-menu">
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
                                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                    <li class="report">
                                        <a href="/report/stationFailure" class="menu-item">
                                            <img class="menu-icon" src="/img/home/report_failure_blue.png">
                                            <h4>故障统计</h4>
                                        </a>
                                    </li>
                                @endif
                                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'report-view'))
                                    <li class="report">
                                        <a href="/report/stationMaintenance" class="menu-item">
                                            <img class="menu-icon" src="/img/home/report_mainten_blue.png">
                                            <h4>维修统计</h4>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif

                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'user-list') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'role-list') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'action-list') ||
            app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'log-list'))
                    <div class="panel panel-default custom-panel">
                        <div class="panel-heading" style="color:#6f7c85;font-size:18px;background-color:#fafafa">
                            <h3 class="panel-title" style="line-height: 30px;margin-left: 10px;">系统管理</h3>
                        </div>
                        <div class="panel-body custom-panel-body">
                            <ul class="main-menu">
                                @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'user-list'))
                                    <li class="user">
                                        <a href="/user/lists" class="menu-item">
                                            <img class="menu-icon" src="/img/home/user_blue.png">
                                            <h4>账号管理</h4>
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
                @endif
            </div>
        </div>
    </div>
@endsection
