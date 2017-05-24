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
                                <div class="panel-body custom-panel-body" id="statusContainer"
                                     style="min-width:400px;height:400px">
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
                url: '/report/realTimeHistory/{{ $stationSelect['id'] }}/{{ $startTime }}/{{ $endTime }}',
                data: '_token = <?php echo csrf_token() ?>',
                async: false,//同步
                success: function (data) {
                    resultValue = data.stationStatusList;
                }
            });
            return resultValue;
        }

        var stationStatusList = getStationStatusList();

    </script>

    <script>

        var categories = [];
        var datas1 = [];

        $.each(stationStatusList,function(i,n){
            categories[i] = dateStrFormat(n["timeEnd_b1"]);
            datas1[i] = n["timeGap_b1"];
        });

        var chart = new Highcharts.Chart('statusContainer', {
            title: {
                text: '泵组启停趋势',
                x: -20
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories:categories
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
                name: '1号泵启停时间',
                data: datas1},
            ]
        });
    </script>
@endsection

