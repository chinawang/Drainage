@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/employee/employee.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>工作人员管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>工作人员管理</span>

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
                                工作人员列表
                            </div>
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'employee-add'))
                                <div class="col-md-6 col-btn">
                                    <a href="/employee/add" class="btn btn-primary btn-sm">添加工作人员</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($employees[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>编号</th>
                                    <th>职务</th>
                                    <th>部门</th>
                                    <th>手机</th>
                                    <th>IP电话</th>
                                    <th>语音电话</th>
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'employee-edit'))
                                        <th>操作</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee['name'] }}</td>
                                        <td>{{ $employee['number'] }}</td>
                                        <td>{{ $employee['job'] }}</td>
                                        <td>{{ $employee['department'] }}</td>
                                        <td>{{ $employee['cellphone'] }}</td>
                                        <td>{{ $employee['voip'] }}</td>
                                        <td>{{ $employee['call'] }}</td>

                                        @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'employee-edit'))
                                            <td>
                                                <a href="/employee/edit/{{ $employee['id'] }}"
                                                   class="btn btn-link">编辑</a>
                                                <a href="#" class="btn btn-link btn-delete-station"
                                                   id="btn-delete-alert-{{ $employee['id'] }}">删除</a>
                                                <form role="form" method="POST" style="display: none"
                                                      action="/employee/delete/{{ $employee['id'] }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" id="btn-delete-submit-{{ $employee['id'] }}">
                                                    </button>
                                                </form>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $employees->render() !!}
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/employee/export" class="btn btn-default btn-sm">导出Excel</a>
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

    @foreach ($employees as $employee)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $employee['id'] }}').on("click", function () {
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
                            $("#btn-delete-submit-{{ $employee['id'] }}").click();
                        })
            });
        </script>
    @endforeach

@endsection
