@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>泵站资料管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>泵站资料管理</span>

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
                                泵站列表
                            </div>
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'station-add'))
                                <div class="col-md-6 col-btn">
                                    <a href="/station/add" class="btn btn-primary btn-sm">添加泵站</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($stations[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th style="width: 80px">编号</th>
                                    <th>泵站名称</th>
                                    <th>泵站类型</th>
                                    <th>泵站地址</th>
                                    <th>工作人员</th>
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'station-edit'))
                                        <th>操作</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($stations as $station)
                                    <tr>
                                        <td>{{ $station['station_number'] }}</td>
                                        <td>{{ $station['name'] }}</td>
                                        <td>{{ $station['type'] }}</td>
                                        <td>{{ $station['address'] }}</td>
                                        <td>
                                            @if(empty($station['assignEmployees']))
                                                暂无
                                            @else
                                                @foreach($station['assignEmployees'] as $assignEmployee)
                                                    <span class="label label-default"><a href="/employee/info/{{ $assignEmployee['id'] }}" class="btn btn-info btn-sm">{{ $assignEmployee['name'] }}</a></span>
                                                @endforeach
                                            @endif
                                        </td>

                                        @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'station-edit'))
                                            <td>
                                                <a href="/station/employee/edit/{{ $station['id'] }}" class="btn btn-link">设置人员</a>
                                                <a href="/station/edit/{{ $station['id'] }}" class="btn btn-link">编辑</a>
                                                <a href="#" class="btn btn-link btn-delete-station"
                                                   id="btn-delete-alert-{{ $station['id'] }}">删除</a>
                                                <form role="form" method="POST" style="display: none"
                                                      action="/station/delete/{{ $station['id'] }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" id="btn-delete-submit-{{ $station['id'] }}">
                                                    </button>
                                                </form>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $stations->render() !!}
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

    @foreach ($stations as $station)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $station['id'] }}').on("click", function () {
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
                            $("#btn-delete-submit-{{ $station['id'] }}").click();
                        })
            });
        </script>
    @endforeach

    {{--<script type="text/javascript">--}}

    {{--$.ajax({--}}
    {{--type: "post",--}}
    {{--url: '/station/delete',--}}
    {{--traditional: true,--}}
    {{--dataType: "json",--}}
    {{--data: {'_token': $('input[name=_token]').val()},--}}
    {{--success: function (data) {--}}
    {{--swal({--}}
    {{--title: "删除成功!",--}}
    {{--text: "",--}}
    {{--type: "success",--}}
    {{--timer: 2000,--}}
    {{--showConfirmButton: false--}}
    {{--},--}}
    {{--function () {--}}
    {{--//                                            alert(data['status']);--}}
    {{--window.location.reload();--}}
    {{--})--}}
    {{--},--}}
    {{--error: function () {--}}
    {{--swal({--}}
    {{--title: "删除失败!",--}}
    {{--text: "数据未删除成功,请稍后重试!",--}}
    {{--type: "error",--}}
    {{--timer: 2000,--}}
    {{--showConfirmButton: false--}}
    {{--})--}}
    {{--}--}}
    {{--})--}}
    {{--</script>--}}
@endsection

