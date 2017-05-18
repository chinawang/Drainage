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
                    <div class="panel-body custom-panel-body">


                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        实时报警状态
                    </div>
                    <div class="panel-body custom-panel-body">
                        <div class="row">
                            <div class="col-md-3 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        1号泵报警
                                    </div>
                                    <div class="panel-body custom-panel-body">
                                        @if($station['alarmPump1'] == 1)
                                            <span style="color: red;">报警</span>
                                        @else
                                            <span>无</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        2号泵报警
                                    </div>
                                    <div class="panel-body custom-panel-body">
                                        @if($station['alarmPump2'] == 1)
                                            <span style="color: red;">报警</span>
                                        @else
                                            <span>无</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        3号泵报警
                                    </div>
                                    <div class="panel-body custom-panel-body">
                                        @if($station['alarmPump3'] == 1)
                                            <span style="color: red;">报警</span>
                                        @else
                                            <span>无</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        4号泵报警
                                    </div>
                                    <div class="panel-body custom-panel-body">
                                        @if($station['alarmPump4'] == 1)
                                            <span style="color: red;">报警</span>
                                        @else
                                            <span>无</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        绞龙报警
                                    </div>
                                    <div class="panel-body custom-panel-body">
                                        @if($station['alarmAuger'] == 1)
                                            <span style="color: red;">报警</span>
                                        @else
                                            <span>无</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        1号格栅报警
                                    </div>
                                    <div class="panel-body custom-panel-body">
                                        @if($station['alarmCleaner1'] == 1)
                                            <span style="color: red;">报警</span>
                                        @else
                                            <span>无</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        2号格栅报警
                                    </div>
                                    <div class="panel-body custom-panel-body">
                                        @if($station['alarmCleaner2'] == 1)
                                            <span style="color: red;">报警</span>
                                        @else
                                            <span>无</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

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


