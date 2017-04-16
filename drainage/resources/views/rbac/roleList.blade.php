@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/rbac/rbac.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>角色管理</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-title">
                                角色列表
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/role/add" class="btn btn-primary btn-sm" >添加角色</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($roles[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>角色</th>
                                    <th>权限</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role['id'] }}</td>
                                        <td>{{ $role['name'] }}</td>
                                        <td>
                                            @if(empty($role['assignPermissions']))
                                                暂无
                                            @else
                                                @foreach($role['assignPermissions'] as $assignPermission)
                                                    <span class="label label-default">{{ $assignPermission['name'] }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/role/permission/edit/{{ $role['id'] }}" class="btn btn-link">设置权限</a>
                                            <a href="/role/edit/{{ $role['id'] }}" class="btn btn-link">编辑</a>
                                            <a href="#" class="btn btn-link btn-delete-station"
                                               id="btn-delete-alert-{{ $role['id'] }}">删除</a>
                                            <form role="form" method="POST" style="display: none"
                                                  action="/role/delete/{{ $role['id'] }}">
                                                {{ csrf_field() }}
                                                <button type="submit" id="btn-delete-submit-{{ $role['id'] }}">
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $roles->render() !!}
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

    @foreach ($roles as $role)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $role['id'] }}').on("click", function () {
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
                            $("#btn-delete-submit-{{ $role['id'] }}").click();
                        })
            });
        </script>
    @endforeach

@endsection