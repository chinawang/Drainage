@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/maintenance/maintenance.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>设备维修管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>设备维修管理</span>

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
                                故障报修列表
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($failures[0]))
                            <table class="table table-hover table-bordered " id="tb_failure">
                                <thead>
                                <tr>
                                    <th style="width: 80px">序号</th>
                                    <th>所属泵站</th>
                                    <th>故障设备及其他</th>
                                    <th>故障类型</th>
                                    <th>故障描述</th>
                                    <th>设备状态</th>
                                    <th>报修人</th>
                                    <th>报修时间</th>
                                    <th>维修进度</th>
                                    <th hidden="hidden">维修人</th>
                                    <th hidden="hidden">维修时间</th>
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-add-maintenance') || app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-view-maintenance'))
                                        <th>操作</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($failures as $failure)
                                    <tr>
                                        <td>{{ $failure['id'] }}</td>
                                        <td>{{ $failure['station_name'] }}</td>
                                        <td>{{ $failure['equipment'] }}</td>
                                        <td>{{ $failure['failure_type'] }}</td>
                                        <td>{{ $failure['failure_description'] }}</td>
                                        <td>{{ $failure['equipment_status'] }}</td>
                                        <td>{{ $failure['reporter'] }}</td>
                                        <td>{{ $failure['report_at'] }}</td>
                                        <td>
                                            @if($failure['repair_process'] == 0)
                                                报修
                                            @elseif($failure['repair_process'] == 1)
                                                维修中
                                            @elseif($failure['repair_process'] == 2)
                                                维修完成
                                            @endif
                                        </td>
                                        <td hidden="hidden">{{ $failure['repairer'] }}</td>
                                        <td hidden="hidden">{{ $failure['repair_at'] }}</td>

                                        @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-add-maintenance') && app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-view-maintenance'))
                                            <td>
                                                <a href="/maintenance/add/{{ $failure['id'] }}"
                                                   class="btn btn-link">添加维修</a>
                                                <a href="/failure/maintenance/lists/{{ $failure['id'] }}"
                                                   class="btn btn-link">查看维修</a>
                                            </td>
                                        @elseif(app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-add-maintenance'))
                                            <td>
                                                <a href="/maintenance/add/{{ $failure['id'] }}"
                                                   class="btn btn-link">添加维修</a>
                                            </td>
                                        @elseif(app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-view-maintenance'))
                                            <td>
                                                <a href="/failure/maintenance/lists/{{ $failure['id'] }}"
                                                   class="btn btn-link">查看维修</a>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--<div class="table-pagination">--}}
                                {{--{!! $failures->render() !!}--}}
                            {{--</div>--}}
                        @else
                            <div class="well" style="text-align: center; padding: 100px;">
                                暂无内容
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-title">
                                维修记录列表
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($maintenances[0]))
                            <table class="table table-hover table-bordered " id="tb_mainten">
                                <thead>
                                <tr>
                                    <th style="width: 80px">序号</th>
                                    <th>所属泵站</th>
                                    <th>故障设备及其他</th>
                                    <th>故障原因</th>
                                    <th>解决办法</th>
                                    <th>维修结果</th>
                                    <th>维修人</th>
                                    <th>维修时间</th>
                                    <th>备注</th>
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'maintenance-edit'))
                                        <th>操作</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($maintenances as $maintenance)
                                    <tr>
                                        <td>{{ $maintenance['id'] }}</td>
                                        <td>{{ $maintenance['station_name'] }}</td>
                                        <td>{{ $maintenance['equipment'] }}</td>
                                        <td>{{ $maintenance['failure_reason'] }}</td>
                                        <td>{{ $maintenance['repair_solution'] }}</td>
                                        <td>{{ $maintenance['result'] }}</td>
                                        <td>{{ $maintenance['repairer'] }}</td>
                                        <td>{{ $maintenance['repair_at'] }}</td>
                                        <td>{{ $maintenance['remark'] }}</td>

                                        @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'maintenance-edit'))
                                            <td>
                                                <a href="/maintenance/edit/{{ $maintenance['id'] }}"
                                                   class="btn btn-link">编辑</a>
                                                <a href="#" class="btn btn-link btn-delete-station"
                                                   id="btn-delete-alert-{{ $maintenance['id'] }}">删除</a>
                                                <form role="form" method="POST" style="display: none"
                                                      action="/maintenance/delete/{{ $maintenance['id'] }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit"
                                                            id="btn-delete-submit-{{ $maintenance['id'] }}">
                                                    </button>
                                                </form>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $maintenances->render() !!}
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/maintenance/export" class="btn btn-default btn-sm">
                                    <span class="glyphicon glyphicon-export"></span>
                                    导出Excel</a>
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

    @foreach ($maintenances as $maintenance)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $maintenance['id'] }}').on("click", function () {
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
                            $("#btn-delete-submit-{{ $maintenance['id'] }}").click();
                        })
            });
        </script>
    @endforeach

    <script>
        $(function () {
            //$('table tr:not(:first)').remove();
//            var len = $('table#tb_mainten tr').length;
            for (var i = 1; i < $('table#tb_failure tr').length; i++) {
                $('table#tb_failure tr:eq(' + i + ') td:first').text(i);
            }

        });

        $(function () {
            //$('table tr:not(:first)').remove();
//            var len = $('table#tb_mainten tr').length;
            for (var i = 1; i < $('table#tb_mainten tr').length; i++) {
                $('table#tb_mainten tr:eq(' + i + ') td:first').text(i);
            }

        });
    </script>

@endsection