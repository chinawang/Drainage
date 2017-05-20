@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>泵站报警详情</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/warning/warningList">泵站报警管理</a>
                <em>›</em>
                <span>{{ $station['name'] }}</span>

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
                        实时报警
                    </div>
                    <div class="panel-body custom-panel-body">
                        <div class="row">
                            @if($station['alarmPump1'] == 1)
                                <div class="col-md-4 col-md-offset-0">
                                    <div class="panel panel-danger custom-panel">
                                        <div class="panel-heading">
                                            1号泵报警
                                        </div>
                                        <div class="panel-body custom-panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.gif"
                                                         style="width: 64px;height: 64px;">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading" style="margin-top: 25px">有新的报警,请及时处理!</h5>
                                                </div>
                                                {{--<div class="media-right">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}"--}}
                                                       {{--class="btn btn-info btn-sm"--}}
                                                       {{--style="font-size: 12px;margin-top: 18px">接警</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($station['alarmPump2'] == 1)
                                <div class="col-md-4 col-md-offset-0">
                                    <div class="panel panel-danger custom-panel">
                                        <div class="panel-heading">
                                            2号泵报警
                                        </div>
                                        <div class="panel-body custom-panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.gif"
                                                         style="width: 64px;height: 64px;">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading" style="margin-top: 25px">有新的报警,请及时处理!</h5>
                                                </div>
                                                {{--<div class="media-right">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}"--}}
                                                       {{--class="btn btn-info btn-sm"--}}
                                                       {{--style="font-size: 12px;margin-top: 18px">接警</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($station['alarmPump3'] == 1)
                                <div class="col-md-4 col-md-offset-0">
                                    <div class="panel panel-danger custom-panel">
                                        <div class="panel-heading">
                                            3号泵报警
                                        </div>
                                        <div class="panel-body custom-panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.gif"
                                                         style="width: 64px;height: 64px;">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading" style="margin-top: 25px">有新的报警,请及时处理!</h5>
                                                </div>
                                                {{--<div class="media-right">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}"--}}
                                                       {{--class="btn btn-info btn-sm"--}}
                                                       {{--style="font-size: 12px;margin-top: 18px">接警</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($station['alarmPump4'] == 1)
                                <div class="col-md-4 col-md-offset-0">
                                    <div class="panel panel-danger custom-panel">
                                        <div class="panel-heading">
                                            4号泵报警
                                        </div>
                                        <div class="panel-body custom-panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.gif"
                                                         style="width: 64px;height: 64px;">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading" style="margin-top: 25px">有新的报警,请及时处理!</h5>
                                                </div>
                                                {{--<div class="media-right">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}"--}}
                                                       {{--class="btn btn-info btn-sm"--}}
                                                       {{--style="font-size: 12px;margin-top: 18px">接警</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($station['alarmAuger'] == 1)
                                <div class="col-md-4 col-md-offset-0">
                                    <div class="panel panel-danger custom-panel">
                                        <div class="panel-heading">
                                            绞龙报警
                                        </div>
                                        <div class="panel-body custom-panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.gif"
                                                         style="width: 64px;height: 64px;">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading" style="margin-top: 25px">有新的报警,请及时处理!</h5>
                                                </div>
                                                {{--<div class="media-right">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}"--}}
                                                       {{--class="btn btn-info btn-sm"--}}
                                                       {{--style="font-size: 12px;margin-top: 18px">接警</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($station['alarmCleaner1'] == 1)
                                <div class="col-md-4 col-md-offset-0">
                                    <div class="panel panel-danger custom-panel">
                                        <div class="panel-heading">
                                            1号格栅报警
                                        </div>
                                        <div class="panel-body custom-panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.gif"
                                                         style="width: 64px;height: 64px;">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading" style="margin-top: 25px">有新的报警,请及时处理!</h5>
                                                </div>
                                                {{--<div class="media-right">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}"--}}
                                                       {{--class="btn btn-info btn-sm"--}}
                                                       {{--style="font-size: 12px;margin-top: 18px">接警</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($station['alarmCleaner2'] == 1)
                                <div class="col-md-4 col-md-offset-0">
                                    <div class="panel panel-danger custom-panel">
                                        <div class="panel-heading">
                                            2号格栅报警
                                        </div>
                                        <div class="panel-body custom-panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.gif"
                                                         style="width: 64px;height: 64px;">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="media-heading" style="margin-top: 25px">有新的报警,请及时处理!</h5>
                                                </div>
                                                {{--<div class="media-right">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}"--}}
                                                       {{--class="btn btn-info btn-sm"--}}
                                                       {{--style="font-size: 12px;margin-top: 18px">接警</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($station['alarmPump1'] == 0 && $station['alarmPump2'] == 0 && $station['alarmPump3'] == 0 && $station['alarmPump4'] == 0 && $station['alarmAuger'] == 0 && $station['alarmCleaner1'] == 0 && $station['alarmCleaner2'] == 0)
                                <div style="text-align: center; padding: 50px;">
                                    无报警
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        报警历史记录
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($stationWarningList[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th>记录时间</th>
                                    <th>泵站名称</th>
                                    <th>设备</th>
                                    <th>报警状态</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($stationWarningList as $stationWarning)
                                    <tr>
                                        <td>{{ $stationWarning['Time'] }}</td>
                                        <td>{{ $station['name'] }}</td>
                                        <td>{{ $stationWarning['alarmEquipment'] }}</td>
                                        @if($stationWarning['alarmStatus'] == 0)
                                            <td style="color: grey;">消警</td>
                                        @else
                                            <td style="color: red;">报警</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--<div class="table-pagination">--}}
                                {{--{!! $stations->render() !!}--}}
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
    </div>
@endsection


