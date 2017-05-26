@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/user/user.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>账户管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>账户管理</span>

            </h2>
        </div>

    </div>

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-title">
                                用户列表
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/user/add" class="btn btn-primary btn-sm">添加用户</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($users[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th style="width: 80px">编号</th>
                                    <th>姓名</th>
                                    <th>职务</th>
                                    <th>联系方式</th>
                                    <th>登录账号</th>
                                    <th>角色</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user['employee_number'] }}</td>
                                        <td>{{ $user['realname'] }}</td>
                                        <td>{{ $user['office'] }}</td>
                                        <td>{{ $user['contact'] }}</td>
                                        <td>{{ $user['name'] }}</td>
                                        <td>
                                            @if(empty($user['assignRoles']))
                                                暂无
                                            @else
                                                @foreach($user['assignRoles'] as $assignRole)
                                                    <span class="label label-default">{{ $assignRole['name'] }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/user/role/edit/{{ $user['id'] }}" class="btn btn-link">设置角色</a>
                                            <a href="/user/edit/{{ $user['id'] }}" class="btn btn-link">编辑</a>
                                            <a href="/user/password/reset/{{ $user['id'] }}"
                                               class="btn btn-link">重置密码</a>
                                            <a href="#" class="btn btn-link btn-delete-station"
                                               id="btn-delete-alert-{{ $user['id'] }}">删除</a>
                                            <form role="form" method="POST" style="display: none"
                                                  action="/user/delete/{{ $user['id'] }}">
                                                {{ csrf_field() }}
                                                <button type="submit" id="btn-delete-submit-{{ $user['id'] }}">
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $users->render() !!}
                            </div>
                        @else
                            <div class="well" style="text-align: center; padding: 100px;">
                                暂无内容
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    @foreach ($users as $user)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $user['id'] }}').on("click", function () {
                swal({
                            title: "确认删除吗?",
                            text: "删除之后,将无法恢复!",
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonText: "取消",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "确认删除",
                            closeOnConfirm: false
                        },
                        function () {
                            $("#btn-delete-submit-{{ $user['id'] }}").click();
                        })
            });
        </script>
    @endforeach

    <!--Loading-->
    <script>
        //获取浏览器页面可见高度和宽度
        var _PageHeight = document.documentElement.clientHeight,
                _PageWidth = document.documentElement.clientWidth;
        //计算loading框距离顶部和左部的距离（loading框的宽度为215px，高度为61px）
        var _LoadingTop = _PageHeight > 61 ? (_PageHeight - 61) / 2 : 0,
                _LoadingLeft = _PageWidth > 215 ? (_PageWidth - 215) / 2 : 0;
        //在页面未加载完毕之前显示的loading Html自定义内容
        var _LoadingHtml = '<div id="loadingDiv" style="position:absolute;left:0;width:100%;height:' + _PageHeight + 'px;top:0;background:#aaa;opacity:0.8;filter:alpha(opacity=80);z-index:10000;"><div style="position: absolute; cursor1: wait; left: ' + _LoadingLeft + 'px; top:' + _LoadingTop + 'px; width: auto; height: 57px; line-height: 57px; padding-left: 50px; padding-right: 20px; background: #ffffff url(/img/loading.gif) no-repeat scroll 20px 20px; color: #696969; font-family:\'Microsoft YaHei\';box-shadow: 0px 0px 20px rgba(0, 0, 0, .08);    border: 2px solid transparent;border-radius: 4px;">页面加载中，请等待...</div></div>';
        //呈现loading效果
        document.write(_LoadingHtml);
        //window.onload = function () {
        //  var loadingMask = document.getElementById('loadingDiv');
        //  loadingMask.parentNode.removeChild(loadingMask);
        //};
        //监听加载状态改变
        document.onreadystatechange = completeLoading;
        //加载状态为complete时移除loading效果
        function completeLoading() {
            if (document.readyState == "complete") {
                var loadingMask = document.getElementById('loadingDiv');
                loadingMask.parentNode.removeChild(loadingMask);
            }
        }
    </script>

@endsection