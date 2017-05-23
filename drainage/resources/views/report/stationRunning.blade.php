@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/report/report.css') }}" rel="stylesheet">
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
                <span>泵站统计报表</span>

            </h2>
        </div>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    {{--<div class="panel-heading">--}}
                    {{----}}
                    {{--</div>--}}
                    <div class="panel-body custom-panel-body">
                        <div class="row" style="margin-top: -10px">
                            <ul class="nav nav-tabs">
                                <li class=""><a href="/report/stationWater">泵站水位统计</a></li>
                                <li class="active"><a href="/report/stationRunning">设备运行统计</a></li>
                                <li class=""><a href="/report/stationStatus">设备启停统计</a></li>
                                <li class=""><a href="/report/stationFailure">设备故障统计</a></li>
                                <li class=""><a href="/report/stationMaintenance">设备维修统计</a></li>

                            </ul>
                        </div>
                        <div id="myTabContent" class="tab-content" style="margin-top: 20px">
                            <form class="form-horizontal" role="form" method="GET" action="/report/stationRunning"
                                  style="margin-bottom: 10px">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="station" class="col-md-4 control-label">选择泵站:</label>

                                            <div class="col-md-8">
                                                <select class="form-control" id="select" name="station_id">
                                                    {{--<option value="" selected="selected" style="display: none">选择泵站</option>--}}
                                                    @foreach ($stations as $station)
                                                        <option value="{{ $station['id'] }}">{{ $station['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="repair_at" class="col-md-3 control-label">时间范围:</label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control pick-event-time" id="start-time"
                                                       name="timeStart"
                                                       value="" placeholder="起始时间" data-data="yyyy-mm-dd hh:ii">
                                            </div>
                                            <label for="time" class="col-md-1 control-label">—</label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control pick-event-time" id="end-time"
                                                       name="timeEnd"
                                                       value="" placeholder="截止时间" data-data="yyyy-mm-dd hh:ii">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary btn-custom">
                                                    查询
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            @if (!empty($runList[0]))
                                <table class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>时间</th>
                                        <th>泵站名称</th>
                                        <th>1号泵电流</th>
                                        <th>2号泵电流</th>
                                        <th>3号泵电流</th>
                                        <th>4号泵电流</th>
                                        <th>5号泵电流</th>
                                        <th>AB相线电压</th>
                                        <th>BC相线电压</th>
                                        <th>CA相线电压</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($runList as $run)
                                        <tr>
                                            <td>{{ $run->Time }}</td>
                                            <td>{{ $stationSelect['name'] }}</td>
                                            {{--<td>{{ $run['pumpCurrent1'] }}</td>--}}
                                            {{--<td>{{ $run['pumpCurrent2'] }}</td>--}}
                                            {{--<td>{{ $run['pumpCurrent3'] }}</td>--}}
                                            {{--<td>{{ $run['pumpCurrent4'] }}</td>--}}
                                            {{--<td>{{ $run['pumpCurrent5'] }}</td>--}}
                                            {{--<td>{{ $run['pumpVoltageAB'] }}</td>--}}
                                            {{--<td>{{ $run['pumpVoltageBC'] }}</td>--}}
                                            {{--<td>{{ $run['pumpVoltageCA'] }}</td>--}}
                                            <td>{{ $run->ib1 }}</td>
                                            <td>{{ $run->ib2 }}</td>
                                            <td>{{ $run->ib3 }}</td>
                                            <td>{{ $run->ib4 }}</td>
                                            <td>{{ $run->ib5 }}</td>
                                            <td>{{ $run->uab }}</td>
                                            <td>{{ $run->ubc }}</td>
                                            <td>{{ $run->uca }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="table-pagination">
                                    {!! $runList->render() !!}
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
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {

            // 日期
            var datePickerConfig = {
                format: 'yyyy-mm-dd',
                language: "zh-CN",
                autoclose: true,
                todayHighlight: true,
                minView: 'month',
                maxView: "year",
                showMeridian: true,
                setStartDate: '-1M'
            };
            // 选择查询日期
            $('.pick-event-date').datetimepicker(datePickerConfig);


            var timePickerConfig = {
                language: "zh-CN",
                autoclose: true,
                todayHighlight: true,
                minuteStep: 30,
                maxView: "year",
                showMeridian: true

            };
            // 选择查询时间
            $('.pick-event-time').datetimepicker(timePickerConfig);


        });
    </script>
@endsection

