@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>泵站报警详情</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        {{ $station['name'] }}
                        <a href="#" onClick="javascript :history.back(-1);" class="btn-link">返回</a>
                    </div>

                </div>
            </div>
        </div>
        {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{{ url('/') }}">首页</a></li>--}}
        {{--<li><a href="/warning/warningList">报警实时列表</a></li>--}}
        {{--<li class="active">{{ $station['name'] }}</li>--}}
        {{--</ol>--}}
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
                                            {{--<div class="row" style="width: 280px;margin: 10px 0;">--}}
                                                {{--<div class="col-md-3 col-md-offset-0"--}}
                                                     {{--style="height: 60px;line-height: 60px">--}}
                                                    {{--<img src="/img/warning/alarm.png"--}}
                                                         {{--style="width: 48px;height: 48px;">--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-6 col-md-offset-0">--}}
                                                    {{--<div style="font-size: 14px;color:#4a4a4a">有新的报警,请及时处理!</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-3 col-md-offset-0">--}}
                                                    {{--<a href="/station/runDetail/{{ $station['id'] }}" class="btn btn-default btn-sm"--}}
                                                       {{--style="font-size: 12px;height: 60px;line-height: 60px">接警</a>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<span style="color: red;">报警</span>--}}
                                            <div class="media">
                                                <div class="media-left">
                                                    <img class="media-object" src="/img/warning/alarm.png" style="width: 48px;height: 48px;">
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">有新的报警,请及时处理!</h4>

                                                </div>
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
                                            <span style="color: red;">报警</span>
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
                                            <span style="color: red;">报警</span>
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
                                            <span style="color: red;">报警</span>
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
                                            <span style="color: red;">报警</span>
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
                                            <span style="color: red;">报警</span>
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
                                            <span style="color: red;">报警</span>
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


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


