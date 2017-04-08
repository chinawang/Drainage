@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="main-menu">
                        <li class="station">
                            <a href="/station/lists">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h3>泵站资料管理</h3>
                            </a>
                        </li>
                        <li class="equipment">
                            <a href="/equipment/lists">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h3>设备资料管理</h3>
                            </a>
                        </li>
                        <li class="failure">
                            <a href="/failure/lists">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h3>设备维修管理</h3>
                            </a>
                        </li>
                        <li class="map">
                            <a href="">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h3>泵站地图</h3>
                            </a>
                        </li>
                        <li class="status">
                            <a href="">
                                <img class="menu-icon" src="/img/home/test.png">
                                <h3>泵站工作状态</h3>
                            </a>
                        </li>
                        <li class="station-warning">
                            <a href="">
                                <img class="menu-icon" src="/img/home/test.png">
                                <span>泵站报警管理</span>
                            </a>
                        </li>
                        <li class="report">
                            <a href="">
                                <img class="menu-icon" src="/img/home/test.png">
                                <span>统计报表</span>
                            </a>
                        </li>
                        <li class="weather">
                            <a href="">
                                <img class="menu-icon" src="/img/home/test.png">
                                <span>天气预报</span>
                            </a>
                        </li>
                        <li class="user">
                            <a href="/user/lists">
                                <img class="menu-icon" src="/img/home/test.png">
                                <span>账户管理</span>
                            </a>
                        </li>
                        <li class="role">
                            <a href="/role/lists">
                                <img class="menu-icon" src="/img/home/test.png">
                                <span>角色管理</span>
                            </a>
                        </li>
                        <li href="permission">
                            <a href="/permission/lists">
                                <img class="menu-icon" src="/img/home/test.png">
                                <span>行为权限管理</span>
                            </a>
                        </li>
                        <li class="log">
                            <a href="">
                                <img class="menu-icon" src="/img/home/test.png">
                                <span>系统日志管理</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
