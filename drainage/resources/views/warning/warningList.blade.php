@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>泵站设备实时报警状态</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>泵站报警管理</span>

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
                        设备实时报警状态列表
                        <a href="/warning/warningList" class="btn-link"><span
                                    class="glyphicon glyphicon-refresh"></span>刷新</a>
                    </div>
                    <div class="panel-body custom-panel-body table-responsive">
                        @if (!empty($stations[0]))
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 80px">编号</th>
                                    <th>泵站名称</th>
                                    <th style="width: 100px">时间</th>
                                    <th>1号泵电机</th>
                                    <th>2号泵电机</th>
                                    <th>3号泵电机</th>
                                    <th>4号泵电机</th>
                                    <th>5号泵电机</th>
                                    <th>1号泵软启动器</th>
                                    <th>2号泵软启动器</th>
                                    <th>3号泵软启动器</th>
                                    <th>4号泵软启动器</th>
                                    <th>5号泵软启动器</th>
                                    <th>绞笼</th>
                                    <th>1号格栅</th>
                                    <th>2号格栅</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($stations as $station)
                                    <tr>
                                        <td>{{ $station['station_number'] }}</td>
                                        <td>{{ $station['name'] }}</td>
                                        <td>{{ $station['Time'] }}</td>
                                        @if($station['alarmPump1'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmPump2'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmPump3'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmPump4'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif

                                        @if($station['station_number'] !=33)
                                            <td>-</td>
                                        @elseif($station['alarmPump5'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif

                                        @if($station['alarmPump1RQ'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmPump2RQ'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmPump3RQ'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmPump4RQ'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif

                                        @if($station['station_number'] !=33)
                                            <td>-</td>
                                        @elseif($station['alarmPump5RQ'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif

                                        @if($station['alarmAuger'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmCleaner1'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif
                                        @if($station['alarmCleaner2'] == 1)
                                            <td style="color: red;">报警</td>
                                        @else
                                            <td>无</td>
                                        @endif

                                        <td>
                                            <a href="/warning/warningDetail/{{ $station['id'] }}" class="btn btn-link">报警详细</a>
                                        </td>
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

@endsection

