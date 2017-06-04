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
                <a href="/failure/lists">设备故障管理</a>
                <em>›</em>
                <span>维修记录</span>
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
                                维修记录列表
                            </div>
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'failure-add-maintenance'))
                                <div class="col-md-6 col-btn">
                                    {{--<a href="/failure/lists" class="btn-link">返回</a>--}}
                                    <a href="/maintenance/add/{{ $failureID }}" style="margin-right: 20px"
                                       class="btn btn-primary btn-sm">添加维修记录</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($maintenances[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th style="width: 80px">序号</th>
                                    <th>所属泵站</th>
                                    <th>故障设备</th>
                                    <th>故障原因</th>
                                    <th>解决办法</th>
                                    <th>维修人</th>
                                    <th>维修时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($maintenances as $maintenance)
                                    <tr>
                                        <td>{{ $maintenance['id'] }}</td>
                                        <td>{{ $maintenance['station_name'] }}</td>
                                        <td>{{ $maintenance['equipment_name'] }}</td>
                                        <td>{{ $maintenance['failure_reason'] }}</td>
                                        <td>{{ $maintenance['repair_solution'] }}</td>
                                        <td>{{ $maintenance['repairer_name'] }}</td>
                                        <td>{{ $maintenance['repair_at'] }}</td>
                                        <td>
                                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'maintenance-edit'))
                                                <a href="/maintenance/edit/{{ $maintenance['id'] }}"
                                                   class="btn btn-link">编辑</a>
                                                <a href="#" class="btn btn-link btn-delete-station"
                                                   id="btn-delete-alert-{{ $maintenance['id'] }}">删除</a>
                                                <form role="form" method="POST" style="display: none"
                                                      action="/maintenance/delete/{{ $maintenance['id'] }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" id="btn-delete-submit-{{ $maintenance['id'] }}">
                                                    </button>
                                                </form>
                                            @else
                                                无
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $maintenances->render() !!}
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
            for (var i = 1; i < $('table tr').length; i++) {
                $('table tr:eq(' + i + ') td:first').text(i);
            }

        });
    </script>

@endsection