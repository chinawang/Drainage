@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/user/user.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>账户管理</span>
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
                                    <th>员工编号</th>
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
                                        <td>暂无</td>
                                        <td>
                                            <a href="/user/role/edit/{{ $user['id'] }}" class="btn btn-link">设置角色</a>
                                            <a href="/user/edit/{{ $user['id'] }}" class="btn btn-link">编辑</a>
                                            <a href="/user/password/reset/{{ $user['id'] }}" class="btn btn-link">重置密码</a>
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

@endsection