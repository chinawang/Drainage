@extends('layouts.app')

@section('subtitle')
    <span>门户</span>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="panel panel-default custom-panel">
                <div class="panel-body custom-panel-body">
                    <ul class="main-menu">
                        <li class="station">
                            <a href="/station/lists" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>泵站资料管理</h4>
                            </a>
                        </li>
                        <li class="equipment">
                            <a href="/equipment/lists" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>设备资料管理</h4>
                            </a>
                        </li>
                        <li class="failure">
                            <a href="/failure/lists" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>设备维修管理</h4>
                            </a>
                        </li>
                        <li class="map">
                            <a href="" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>泵站地图</h4>
                            </a>
                        </li>
                        <li class="status">
                            <a href="" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>泵站工作状态</h4>
                            </a>
                        </li>
                        <li class="station-warning">
                            <a href="" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>泵站报警管理</h4>
                            </a>
                        </li>
                        <li class="report">
                            <a href="" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>统计报表</h4>
                            </a>
                        </li>
                        <li class="weather">
                            <a href="" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>天气预报</h4>
                            </a>
                        </li>
                        <li class="user">
                            <a href="/user/lists" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>账户管理</h4>
                            </a>
                        </li>
                        <li class="role">
                            <a href="/role/lists" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>角色管理</h4>
                            </a>
                        </li>
                        <li href="permission">
                            <a href="/permission/lists" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>行为权限管理</h4>
                            </a>
                        </li>
                        <li class="log">
                            <a href="" class="menu-item">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h4>系统日志管理</h4>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
