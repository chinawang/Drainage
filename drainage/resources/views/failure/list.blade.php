@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/failure/failure.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>设备故障管理</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-title">
                                故障记录列表
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/failure/add" class="btn btn-primary btn-sm">添加故障记录</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($failures[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>所属泵站</th>
                                    <th>故障设备</th>
                                    <th>故障类型</th>
                                    <th>故障描述</th>
                                    <th>设备状态</th>
                                    <th>报修人</th>
                                    <th>报修时间</th>
                                    <th>维修进度</th>
                                    <th>维修人</th>
                                    <th>维修时间</th>
                                    <th>维修记录</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($failures as $failure)
                                    <tr>
                                        <td>{{ $failure['id'] }}</td>
                                        <td>{{ $failure['station_name'] }}</td>
                                        <td>{{ $failure['equipment_name'] }}</td>
                                        <td>{{ $failure['failure_type'] }}</td>
                                        <td>{{ $failure['failure_description'] }}</td>
                                        <td>{{ $failure['equipment_status'] }}</td>
                                        <td>{{ $failure['reporter_name'] }}</td>
                                        <td>{{ $failure['report_at'] }}</td>
                                        <td>
                                            @if($failure['repair_process'] == 0)
                                                未维修
                                            @elseif($failure['repair_process'] == 1)
                                                维修中
                                            @elseif($failure['repair_process'] == 2)
                                                维修完成
                                            @endif
                                        </td>
                                        <td>{{ $failure['repairer_name'] }}</td>
                                        <td>{{ $failure['repair_at'] }}</td>
                                        <td>
                                            @if($failure['repair_process'] == 0)
                                                暂无
                                            @else
                                                <a href="/failure/maintenance/lists/{{ $failure['id'] }}" class="btn btn-link">查看</a>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/maintenance/add/{{ $failure['id'] }}" class="btn btn-link">添加维修记录</a>
                                            <a href="/failure/edit/{{ $failure['id'] }}" class="btn btn-link">编辑</a>
                                            <a href="#" class="btn btn-link btn-delete-station"
                                               id="btn-delete-alert-{{ $failure['id'] }}">删除</a>
                                            <form role="form" method="POST" style="display: none"
                                                  action="/failure/delete/{{ $failure['id'] }}">
                                                {{ csrf_field() }}
                                                <button type="submit" id="btn-delete-submit-{{ $failure['id'] }}">
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $failures->render() !!}
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

    @foreach ($failures as $failure)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $failure['id'] }}').on("click", function () {
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
                            $("#btn-delete-submit-{{ $failure['id'] }}").click();
                        })
            });
        </script>
    @endforeach

@endsection