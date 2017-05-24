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
                                                        <option value="{{ $station['id'] }}" {{$station['id'] == $stationSelect['id'] ? 'selected=selected' :''}}>{{ $station['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="repair_at" class="col-md-3 control-label">时间范围:</label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control pick-event-date" id="start-time"
                                                       name="timeStart"
                                                       value="{{ $startTime }}" placeholder="起始时间"
                                                       data-data="yyyy-mm-dd">
                                            </div>
                                            <label for="time" class="col-md-1 control-label">—</label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control pick-event-date" id="end-time"
                                                       name="timeEnd"
                                                       value="{{ $endTime }}" placeholder="截止时间" data-data="yyyy-mm-dd">
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

                            <div class="panel panel-default custom-panel">
                                <div class="col-md-3 col-md-offset-4">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#current" data-toggle="tab">泵组电流趋势</a></li>
                                        <li><a href="#voltage" data-toggle="tab">泵组电压趋势</a></li>
                                    </ul>
                                </div>

                                <div id="myTabContent" class="tab-content">
                                    <div class="tab-pane fade active in" id="current">
                                        <div class="panel-body custom-panel-body" id="currentContainer"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="voltage">
                                        <div class="panel-body custom-panel-body" id="voltageContainer"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>

                                </div>
                            </div>

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
                                    {!! $runList->appends(['station_id' => $stationSelect['id'],'timeStart' => $startTime,'timeEnd' => $endTime])->render() !!}
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

    <script src="https://cdn.hcharts.cn/highcharts/highcharts.js"></script>

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

    <script>
        function dateStrFormat(dateStr) {

            var dateResult = dateStr;
            var timeArr = dateStr.replace(" ", ":").replace(/\:/g, "-").split("-");
            if (timeArr.length == 6) {
                dateResult = timeArr[1] + '-' + timeArr[2] + ' ' + timeArr[3] + ':' + timeArr[4];
            }

            return dateResult;

        }
    </script>

    <script type="text/javascript">

        function getStationRTHistory() {
            var resultValue = [];
            $.ajax({
                type: 'get',
                url: '/report/realTimeHistory/{{ $stationSelect['id'] }}/{{ $startTime }}/{{ $endTime }}',
                data: '_token = <?php echo csrf_token() ?>',
                async: false,//同步
                success: function (data) {
                    resultValue = data.stationRTHistory;
                }
            });
            return resultValue;
        }

        var stationRTHistory = getStationRTHistory();

    </script>

    <script>

        var categories = [];
        var datas1 = [];
        var datas2 = [];
        var datas3 = [];
        var datas4 = [];
        var datas5 = [];

        var datas6 = [];
        var datas7 = [];
        var datas8 = [];

        $.each(stationRTHistory, function (i, n) {
            categories[i] = dateStrFormat(n["Time"]);
            datas1[i] = n["ib1"];
            datas2[i] = n["ib2"];
            datas3[i] = n["ib3"];
            datas4[i] = n["ib4"];
            datas5[i] = n["ib5"];

            datas6[i] = n["uab"];
            datas7[i] = n["ubc"];
            datas8[i] = n["uca"];
        });

        var chart1 = new Highcharts.Chart('currentContainer', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: '电流 (毫安)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '毫安'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '1号泵电流',
                data: datas1
            }, {
                name: '2号泵电流',
                data: datas2
            }, {
                name: '3号泵电流',
                data: datas3
            }, {
                name: '4号泵电流',
                data: datas4
            }, {
                name: '5号泵电流',
                data: datas5
            },

            ]
        });

        var chart2 = new Highcharts.Chart('voltageContainer', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: '电压 (伏)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '伏'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'AB相线电压',
                data: datas6
            }, {
                name: 'BC相线电压',
                data: datas7
            }, {
                name: 'CA相线电压',
                data: datas8
            },

            ]
        });
    </script>
@endsection
