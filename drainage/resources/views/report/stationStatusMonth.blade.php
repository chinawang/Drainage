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
                <span>运行抽升统计</span>

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
                                <li class=""><a
                                            href="/report/stationStatusDay?timeStart={{ $startTime }}">每日运行统计</a>
                                </li>
                                <li class="active"><a
                                            href="/report/stationStatusMonth?type={{ $selectType }}&timeStart={{ $startTime }}">每月运行统计</a>
                                </li>
                                <li class=""><a
                                            href="/report/stationStatusMonthAll?type={{ $selectType }}&timeStart={{ $startTime }}">泵站月生产报表</a>
                                </li>

                            </ul>
                        </div>
                        <div id="myTabContent" class="tab-content" style="margin-top: 20px">
                            <form class="form-horizontal" role="form" method="GET" action="/report/stationStatusMonth"
                                  style="margin-bottom: 10px">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="station" class="col-md-4 control-label">泵站类型:</label>

                                            <div class="col-md-8">
                                                <select class="form-control" id="select" name="type">
                                                    <option value="全部" selected="selected">全部</option>
                                                    <option value="雨水" {{$selectType == '雨水' ? 'selected=selected' :''}}>
                                                        雨水
                                                    </option>
                                                    <option value="污水" {{$selectType == '污水' ? 'selected=selected' :''}}>
                                                        污水
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="repair_at" class="col-md-4 control-label">选择月份:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control pick-event-date" id="start-time"
                                                       name="timeStart"
                                                       value="{{ substr($startTime , 0 , 7) }}" placeholder="日期" data-data="yyyy-mm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-1">
                                                <button type="submit" class="btn btn-primary btn-custom">
                                                    查询
                                                </button>
                                            </div>
                                            <div class="col-md-3 col-md-offset-1">
                                                <button type="submit" class="btn btn-default btn-custom">
                                                    导出报表
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="panel panel-default custom-panel">
                                <div class="panel-body custom-panel-body" id="statusContainer"
                                     style="min-width:400px;height:400px">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">泵站名称</th>
                                        <th colspan="4">1号泵</th>
                                        <th colspan="4">2号泵</th>
                                        <th colspan="4">3号泵</th>
                                        <th colspan="4">4号泵</th>
                                    </tr>
                                    <tr>
                                        <th>运行(小时)</th>
                                        <th>连前累计(小时)</th>
                                        <th>抽升量(万吨)</th>
                                        <th>连前累计(万吨)</th>

                                        <th>运行(小时)</th>
                                        <th>连前累计(小时)</th>
                                        <th>抽升量(万吨)</th>
                                        <th>连前累计(万吨)</th>

                                        <th>运行(小时)</th>
                                        <th>连前累计(小时)</th>
                                        <th>抽升量(万吨)</th>
                                        <th>连前累计(万吨)</th>

                                        <th>运行(小时)</th>
                                        <th>连前累计(小时)</th>
                                        <th>抽升量(万吨)</th>
                                        <th>连前累计(万吨)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (!empty($stations[0]))
                                        @foreach ($stations as $station)
                                            <tr>
                                                <td>{{ $station['name'] }}</td>

                                                <td>{{ $station['totalTimeDay1'] }}</td>
                                                <td>{{ $station['totalTimeBefore1'] }}</td>
                                                <td>{{ $station['totalFluxDay1'] }}</td>
                                                <td>{{ $station['totalFluxBefore1'] }}</td>

                                                <td>{{ $station['totalTimeDay2'] }}</td>
                                                <td>{{ $station['totalTimeBefore2'] }}</td>
                                                <td>{{ $station['totalFluxDay2'] }}</td>
                                                <td>{{ $station['totalFluxBefore2'] }}</td>

                                                <td>{{ $station['totalTimeDay3'] }}</td>
                                                <td>{{ $station['totalTimeBefore3'] }}</td>
                                                <td>{{ $station['totalFluxDay3'] }}</td>
                                                <td>{{ $station['totalFluxBefore3'] }}</td>

                                                <td>{{ $station['totalTimeDay4'] }}</td>
                                                <td>{{ $station['totalTimeBefore4'] }}</td>
                                                <td>{{ $station['totalFluxDay4'] }}</td>
                                                <td>{{ $station['totalFluxBefore4'] }}</td>
                                            </tr>
                                        @endforeach

                                    @else
                                        <tr>
                                            <td style="height: 80px" colspan="17">暂无数据</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
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
                format: 'yyyy-mm',
                language: "zh-CN",
                autoclose: true,
                todayHighlight: true,
                minView: "year",
                maxView: "year",
                showMeridian: true
//                setStartDate: '-1M'
            };
            // 选择查询日期
            $('.pick-event-date').datetimepicker(datePickerConfig);


//            var timePickerConfig = {
//                language: "zh-CN",
//                autoclose: true,
//                todayHighlight: true,
//                minuteStep: 30,
//                maxView: "year",
//                showMeridian: true
//
//            };
//            // 选择查询时间
//            $('.pick-event-time').datetimepicker(timePickerConfig);


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

        function getStationStatusList() {
            var resultValue = [];
            $.ajax({
                type: 'get',
                url: '/report/realTimeStatusHistory/{{ $startTime }}',
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

        $.each(statusRTList.stationStatusList1, function (i, n) {
            categories1[i] = dateStrFormat(n["timeEnd"]);
            datas1[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList2, function (i, n) {
            categories2[i] = dateStrFormat(n["timeEnd"]);
            datas2[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList3, function (i, n) {
            categories3[i] = dateStrFormat(n["timeEnd"]);
            datas3[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList4, function (i, n) {
            categories4[i] = dateStrFormat(n["timeEnd"]);
            datas4[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList5, function (i, n) {
            categories5[i] = dateStrFormat(n["timeEnd"]);
            datas5[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList6, function (i, n) {
            categories6[i] = dateStrFormat(n["timeEnd"]);
            datas6[i] = n["timeGap"];
        });
        $.each(statusRTList.stationStatusList7, function (i, n) {
            categories7[i] = dateStrFormat(n["timeEnd"]);
            datas7[i] = n["timeGap"];
        });

        var chart1 = new Highcharts.Chart('pump1Container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories1
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
                data: datas1
            },
            ]
        });

        var chart2 = new Highcharts.Chart('pump2Container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories2
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
                data: datas2
            },
            ]
        });

        var chart3 = new Highcharts.Chart('pump3Container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories1
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
                data: datas3
            },
            ]
        });

        var chart4 = new Highcharts.Chart('pump4Container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories4
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
                data: datas4
            },
            ]
        });

        var chart5 = new Highcharts.Chart('gs1Container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories5
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
                data: datas5
            },
            ]
        });

        var chart6 = new Highcharts.Chart('gs2Container', {
            chart: {
                type: 'column'
            },
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories6
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
                data: datas6
            },
            ]
        });

        var chart7 = new Highcharts.Chart('jlContainer', {
            chart: {
                type: 'column'
            },
            title: {
                text: '',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: categories7
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
                data: datas7
            },
            ]
        });
    </script>
@endsection

