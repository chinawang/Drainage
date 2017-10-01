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
                                                       value="{{ substr($startTime , 0 , 7) }}" placeholder="日期"
                                                       data-data="yyyy-mm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-1">
                                                <button type="submit" class="btn btn-primary btn-custom">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                    查询
                                                </button>
                                            </div>
                                            <div class="col-md-3 col-md-offset-1">
                                                <a href="/report/exporStatustMonth?type={{ $selectType }}&timeStart={{ $startTime }}"
                                                   class="btn btn-default btn-custom">
                                                    <span class="glyphicon glyphicon-export"></span>
                                                    导出报表
                                                </a>
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
                                        <th colspan="4">5号泵</th>
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

                                                <td>{{ $station['totalTimeDay5'] }}</td>
                                                <td>{{ $station['totalTimeBefore5'] }}</td>
                                                <td>{{ $station['totalFluxDay5'] }}</td>
                                                <td>{{ $station['totalFluxBefore5'] }}</td>
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

    {{--<script type="text/javascript">--}}

        {{--function getStationStatusList() {--}}
            {{--var resultValue = [];--}}
            {{--$.ajax({--}}
                {{--type: 'get',--}}
                {{--url: '/report/realTimeStatusMonth/{{ $selectType }}/{{ $startTime }}',--}}
                {{--data: '_token = <?php echo csrf_token() ?>',--}}
                {{--async: false,//同步--}}
                {{--success: function (data) {--}}
                    {{--resultValue = data.stations;--}}
                {{--}--}}
            {{--});--}}
            {{--return resultValue;--}}
        {{--}--}}

        {{--var statusRTList = getStationStatusList();--}}

    {{--</script>--}}

    {{--<script>--}}

        {{--var categories = [];--}}
        {{--var datas1 = [];--}}
        {{--var datas2 = [];--}}
        {{--var datas3 = [];--}}
        {{--var datas4 = [];--}}
        {{--var datas5 = [];--}}

        {{--$.each(statusRTList, function (i, n) {--}}
            {{--categories[i] = n["name"];--}}
            {{--datas1[i] = n["totalTimeDay1"];--}}
            {{--datas2[i] = n["totalTimeDay2"];--}}
            {{--datas3[i] = n["totalTimeDay3"];--}}
            {{--datas4[i] = n["totalTimeDay4"];--}}
            {{--datas5[i] = n["totalTimeDay5"];--}}
        {{--});--}}

        {{--var chart1 = new Highcharts.Chart('statusContainer', {--}}
            {{--chart: {--}}
                {{--type: 'areaspline'--}}
            {{--},--}}
            {{--title: {--}}
                {{--text: '',--}}
                {{--x: -20--}}
            {{--},--}}
            {{--subtitle: {--}}
                {{--text: '',--}}
                {{--x: -20--}}
            {{--},--}}
            {{--xAxis: {--}}
                {{--categories: categories--}}
            {{--},--}}
            {{--yAxis: {--}}
                {{--title: {--}}
                    {{--text: '时长 (小时)'--}}
                {{--},--}}
                {{--plotLines: [{--}}
                    {{--value: 0,--}}
                    {{--width: 1,--}}
                    {{--color: '#808080'--}}
                {{--}]--}}
            {{--},--}}
            {{--tooltip: {--}}
                {{--valueSuffix: '小时'--}}
            {{--},--}}
            {{--legend: {--}}
                {{--layout: 'vertical',--}}
                {{--align: 'right',--}}
                {{--verticalAlign: 'middle',--}}
                {{--borderWidth: 0--}}
            {{--},--}}
            {{--series: [{--}}
                {{--name: '1号泵运行时长',--}}
                {{--data: datas1--}}
            {{--}, {--}}
                {{--name: '2号泵运行时长',--}}
                {{--data: datas2--}}
            {{--}, {--}}
                {{--name: '3号泵运行时长',--}}
                {{--data: datas3--}}
            {{--}, {--}}
                {{--name: '4号泵运行时长',--}}
                {{--data: datas4--}}
            {{--},--}}
                {{--{--}}
                    {{--name: '5号泵运行时长',--}}
                    {{--data: datas5--}}
                {{--},--}}
            {{--]--}}
        {{--});--}}
    {{--</script>--}}
@endsection

