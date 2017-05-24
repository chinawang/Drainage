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
                                <li class=""><a href="/report/stationRunning">设备运行统计</a></li>
                                <li class="active"><a href="/report/stationStatus">设备启停统计</a></li>
                                <li class=""><a href="/report/stationFailure">设备故障统计</a></li>
                                <li class=""><a href="/report/stationMaintenance">设备维修统计</a></li>

                            </ul>
                        </div>
                        <div id="myTabContent" class="tab-content" style="margin-top: 20px">
                            <form class="form-horizontal" role="form" method="GET" action="/report/stationStatus"
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
                                                       value="{{ $startTime }}" placeholder="起始时间" data-data="yyyy-mm-dd">
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
                                <div class="col-md-8 col-md-offset-2">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#pump1" data-toggle="tab">1号泵启动趋势</a></li>
                                        <li><a href="#pump2" data-toggle="tab">2号泵启动趋势</a></li>
                                        <li><a href="#pump3" data-toggle="tab">3号泵启动趋势</a></li>
                                        <li><a href="#pump4" data-toggle="tab">4号泵启动趋势</a></li>
                                        <li><a href="#gs1" data-toggle="tab">1号格栅启动趋势</a></li>
                                        <li><a href="#gs2" data-toggle="tab">2号格栅启动趋势</a></li>
                                        <li><a href="#jl" data-toggle="tab">绞龙启动趋势</a></li>
                                    </ul>
                                </div>

                                <div id="myTabContent" class="tab-content">
                                    <div class="tab-pane fade active in" id="pump1">
                                        <div class="panel-body custom-panel-body" id="pump1Container"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pump2">
                                        <div class="panel-body custom-panel-body" id="pump2Container"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pump3">
                                        <div class="panel-body custom-panel-body" id="pump3Container"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pump4">
                                        <div class="panel-body custom-panel-body" id="pump4Container"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="gs1">
                                        <div class="panel-body custom-panel-body" id="gs1Container"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="gs2">
                                        <div class="panel-body custom-panel-body" id="gs2Container"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="jl">
                                        <div class="panel-body custom-panel-body" id="jlContainer"
                                             style="min-width:400px;height:400px">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            @if (!empty($statusList[0]))
                                <table class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>时间</th>
                                        <th>泵站名称</th>
                                        <th>1号泵启停状态</th>
                                        <th>2号泵启停状态</th>
                                        <th>3号泵启停状态</th>
                                        <th>4号泵启停状态</th>
                                        <th>1号格栅启停状态</th>
                                        <th>2号格栅启停状态</th>
                                        <th>绞龙启停状态</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($statusList as $status)
                                        <tr>
                                            <td>{{ $status->Time }}</td>
                                            <td>{{ $stationSelect['name'] }}</td>
                                            {{--<td>{{ $status['pumpStatus1'] }}</td>--}}
                                            {{--<td>{{ $status['pumpStatus2'] }}</td>--}}
                                            {{--<td>{{ $status['pumpStatus3'] }}</td>--}}
                                            {{--<td>{{ $status['pumpStatus4'] }}</td>--}}
                                            {{--<td>{{ $status['removerStatus1'] }}</td>--}}
                                            {{--<td>{{ $status['removerStatus2'] }}</td>--}}
                                            {{--<td>{{ $status['augerStatus'] }}</td>--}}
                                            <td>{{ $status->yx_b1 == '1' ? '运行中' : '未启动' }}</td>
                                            <td>{{ $status->yx_b2 == '1' ? '运行中' : '未启动' }}</td>
                                            <td>{{ $status->yx_b3 == '1' ? '运行中' : '未启动' }}</td>
                                            <td>{{ $status->yx_b4 == '1' ? '运行中' : '未启动' }}</td>
                                            <td>{{ $status->yx_gs1 == '1' ? '运行中' : '未启动' }}</td>
                                            <td>{{ $status->yx_gs2 == '1' ? '运行中' : '未启动' }}</td>
                                            <td>{{ $status->yx_jl == '1' ? '运行中' : '未启动' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="table-pagination">
                                    {!! $statusList->appends(['station_id' => $stationSelect['id'],'timeStart' => $startTime,'timeEnd' => $endTime])->render() !!}
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

            var dateResult = dateStr ;
            var timeArr=dateStr.replace(" ",":").replace(/\:/g,"-").split("-");
            if(timeArr.length==6)
            {
                dateResult = timeArr[1] + '-' + timeArr[2] + ' ' + timeArr[3] + ':' + timeArr[4];
            }

            return dateResult;

        }
    </script>

    <script type="text/javascript">

        function getStationStatusList() {
            var resultValue = [];
            $.ajax({
                type: 'get',
                url: '/report/realTimeStatusHistory/{{ $stationSelect['id'] }}/{{ $startTime }}/{{ $endTime }}',
                data: '_token = <?php echo csrf_token() ?>',
                async: false,//同步
                success: function (data) {
                    resultValue = data;
                }
            });
            return resultValue;
        }

        var statusRTList = getStationStatusList();

    </script>

    <script>

        var categories1 = [];
        var categories2 = [];
        var categories3 = [];
        var categories4 = [];
        var categories5 = [];
        var categories6 = [];
        var categories7 = [];
        var datas1 = [];
        var datas2 = [];
        var datas3 = [];
        var datas4 = [];
        var datas5 = [];
        var datas6 = [];
        var datas7 = [];

        $.each(statusRTList.stationStatusList1,function(i,n){
            categories1[i] = dateStrFormat(n["timeEnd"]);
            datas1[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList2,function(i,n){
            categories2[i] = dateStrFormat(n["timeEnd"]);
            datas2[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList3,function(i,n){
            categories3[i] = dateStrFormat(n["timeEnd"]);
            datas3[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList4,function(i,n){
            categories4[i] = dateStrFormat(n["timeEnd"]);
            datas4[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList5,function(i,n){
            categories5[i] = dateStrFormat(n["timeEnd"]);
            datas5[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList6,function(i,n){
            categories6[i] = dateStrFormat(n["timeEnd"]);
            datas6[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList7,function(i,n){
            categories7[i] = dateStrFormat(n["timeEnd"]);
            datas7[i] = n["timeGap"];
        });

        var chart1 = new Highcharts.Chart('pump1Container', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories1
            },
            yAxis: {
                title: {
                    text: '时间 (分钟)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '分钟'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '1号泵启动时长',
                data: datas1},
            ]
        });

        var chart2 = new Highcharts.Chart('pump2Container', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories2
            },
            yAxis: {
                title: {
                    text: '时间 (分钟)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '分钟'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '2号泵启动时长',
                data: datas2},
            ]
        });

        var chart3 = new Highcharts.Chart('pump3Container', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories1
            },
            yAxis: {
                title: {
                    text: '时间 (分钟)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '分钟'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '3号泵启动时长',
                data: datas3},
            ]
        });

        var chart4 = new Highcharts.Chart('pump4Container', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories4
            },
            yAxis: {
                title: {
                    text: '时间 (分钟)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '分钟'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '4号泵启动时长',
                data: datas4},
            ]
        });

        var chart5 = new Highcharts.Chart('gs1Container', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories5
            },
            yAxis: {
                title: {
                    text: '时间 (分钟)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '分钟'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '1号格栅启动时长',
                data: datas5},
            ]
        });

        var chart6 = new Highcharts.Chart('gs2Container', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories6
            },
            yAxis: {
                title: {
                    text: '时间 (分钟)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '分钟'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '2号格栅启动时长',
                data: datas6},
            ]
        });

        var chart7 = new Highcharts.Chart('jlContainer', {
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories7
            },
            yAxis: {
                title: {
                    text: '时间 (分钟)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '分钟'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '绞龙启动时长',
                data: datas7},
            ]
        });
    </script>
@endsection

