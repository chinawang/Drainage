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
                <span>泵站报警统计</span>

            </h2>
        </div>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">

                    <div class="panel-body custom-panel-body">

                        <div class="row" style="margin-top: -10px">
                            <ul class="nav nav-tabs">
                                <li class="active"><a
                                            href="/report/stationWarningAll?timeStart={{ $startTime }}&timeEnd={{ $endTime }}">所有泵站</a>
                                </li>
                                <li class=""><a
                                            href="/report/stationWarning?timeStart={{ $startTime }}&timeEnd={{ $endTime }}">单个泵站</a>
                                </li>

                            </ul>
                        </div>

                        <div id="myTabContent" class="tab-content" style="margin-top: 20px">
                            <form class="form-horizontal" role="form" method="GET" action="/report/stationWarningAll"
                                  style="margin-bottom: 10px">
                                {{ csrf_field() }}

                                <div class="row">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary btn-custom">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                    查询
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="panel panel-default custom-panel">
                                <div class="panel-body custom-panel-body" id="warningContainer"
                                     style="min-width:400px;height:400px">
                                </div>
                            </div>

                            @if (!empty($stations[0]))
                                <div style="overflow:scroll;">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 80px">编号</th>
                                            <th>泵站名称</th>
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
                                            <th>市电停电</th>
                                            <th>手动急停</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($stations as $station)
                                            <tr>
                                                <td>{{ $station['station_number'] }}</td>
                                                <td>{{ $station['name'] }}</td>
                                                @if($station['alarmPump1'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump1'] }}</td>
                                                @endif
                                                @if($station['alarmPump2'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump2'] }}</td>
                                                @endif
                                                @if($station['alarmPump3'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump3'] }}</td>
                                                @endif
                                                @if($station['alarmPump4'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump4'] }}</td>
                                                @endif

                                                @if($station['station_number'] !=33)
                                                    <td>-</td>
                                                @elseif($station['alarmPump5'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump5'] }}</td>
                                                @endif

                                                @if($station['alarmPump1RQ'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump1RQ'] }}</td>
                                                @endif
                                                @if($station['alarmPump2RQ'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump2RQ'] }}</td>
                                                @endif
                                                @if($station['alarmPump3RQ'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump3RQ'] }}</td>
                                                @endif
                                                @if($station['alarmPump4RQ'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump4RQ'] }}</td>
                                                @endif

                                                @if($station['station_number'] !=33)
                                                    <td>-</td>
                                                @elseif($station['alarmPump5RQ'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmPump5RQ'] }}</td>
                                                @endif

                                                @if($station['alarmAuger'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmAuger'] }}</td>
                                                @endif
                                                @if($station['alarmCleaner1'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmCleaner1'] }}</td>
                                                @endif
                                                @if($station['alarmCleaner2'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmCleaner2'] }}</td>
                                                @endif
                                                @if($station['alarmCity'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmCity'] }}</td>
                                                @endif
                                                @if($station['alarmManual'] == 0)
                                                    <td>无</td>
                                                @else
                                                    <td style="color: red;">{{ $station['alarmManual'] }}</td>
                                                @endif

                                                <td>
                                                    <a href="/report/stationWarning?station_id={{ $station['id'] }}&timeStart={{ $startTime }}&timeEnd={{ $endTime }}"
                                                       class="btn btn-link">报警详细</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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

        function getStationWarningInfo() {
            var resultValue = [];
            $.ajax({
                type: 'get',
                url: '/report/stationWarningCountAll/{{ $startTime }}/{{ $endTime }}',
                data: '_token = <?php echo csrf_token() ?>',
                async: false,//同步
                success: function (data) {
                    resultValue = data;
                }
            });
            return resultValue;
        }

        var stationWarningInfo = getStationWarningInfo();

    </script>

    <script>

        var categories = [];
        var datas = [];
        var datas1 = [];
        var datas2 = [];
        var datas3 = [];
        var datas4 = [];
        var datas5 = [];
        var datas1RQ = [];
        var datas2RQ = [];
        var datas3RQ = [];
        var datas4RQ = [];
        var datas5RQ = [];
        var datasGS1 = [];
        var datasGS2 = [];
        var datasJL = [];
        var datasSD = [];
        var datasJT = [];


        var timeGap = stationWarningInfo.startTime + ' 至 ' + stationWarningInfo.endTime;

        $.each(stationWarningInfo.stations, function (i, n) {
            categories[i] = n["name"];
            datas1[i] = n["alarmPump1"];
            datas2[i] = n["alarmPump2"];
            datas3[i] = n["alarmPump3"];
            datas4[i] = n["alarmPump4"];
            datas5[i] = n["alarmPump5"];
            datas1RQ[i] = n["alarmPump1RQ"];
            datas2RQ[i] = n["alarmPump2RQ"];
            datas3RQ[i] = n["alarmPump3RQ"];
            datas4RQ[i] = n["alarmPump4RQ"];
            datas5RQ[i] = n["alarmPump5RQ"];
            datasGS1[i] = n["alarmCleaner1"];
            datasGS2[i] = n["alarmCleaner2"];
            datasJL[i] = n["alarmAuger"];
            datasSD[i] = n["alarmCity"];
            datasJT[i] = n["alarmManual"];

            datas[i] = n["alarmSum"];
        });

        var chart = new Highcharts.Chart('warningContainer', {
            chart: {
                type: 'column',
                style: {
                    "fontSize": "14px",
                }
            },
            plotOptions: {
                series: {
                    'colorByPoint': true,
                    'animation': true
                }
            },
            title: {
                text: '泵站报警次数',
                x: -20
            },
            subtitle: {
                text: timeGap,
                x: -20
            },
            xAxis: {
                categories: categories,
                labels: {
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: '次数'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '次'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [
                {
                    name: '泵站报警总数',
                    data: datas
                },
//                {
//                    name: '1号泵电机',
//                    data: datas1
//                }, {
//                    name: '2号泵电机',
//                    data: datas2
//                }, {
//                    name: '3号泵电机',
//                    data: datas3
//                }, {
//                    name: '4号泵电机',
//                    data: datas4
//                }, {
//                    name: '5号泵电机',
//                    data: datas5
//                }, {
//                    name: '1号泵软启动器',
//                    data: datas1RQ
//                }, {
//                    name: '2号泵软启动器',
//                    data: datas2RQ
//                }, {
//                    name: '3号泵软启动器',
//                    data: datas3RQ
//                }, {
//                    name: '4号泵软启动器',
//                    data: datas4RQ
//                }, {
//                    name: '5号泵软启动器',
//                    data: datas5RQ
//                }, {
//                    name: '绞笼',
//                    data: datasJL
//                }, {
//                    name: '1号格栅',
//                    data: datasGS1
//                }, {
//                    name: '2号格栅',
//                    data: datasGS2
//                }, {
//                    name: '市电停电',
//                    data: datasSD
//                }, {
//                    name: '手动急停',
//                    data: datasJT
//                },
            ],
        });

    </script>

    <!--Loading-->
    {{--<script>--}}
    {{--//获取浏览器页面可见高度和宽度--}}
    {{--var _PageHeight = document.documentElement.clientHeight,--}}
    {{--_PageWidth = document.documentElement.clientWidth;--}}
    {{--//计算loading框距离顶部和左部的距离（loading框的宽度为215px，高度为61px）--}}
    {{--var _LoadingTop = _PageHeight > 61 ? (_PageHeight - 61) / 2 : 0,--}}
    {{--_LoadingLeft = _PageWidth > 215 ? (_PageWidth - 215) / 2 : 0;--}}
    {{--//在页面未加载完毕之前显示的loading Html自定义内容--}}
    {{--var _LoadingHtml = '<div id="loadingDiv" style="position:absolute;left:0;width:100%;height:' + _PageHeight + 'px;top:0;background:#aaa;opacity:0.8;filter:alpha(opacity=80);z-index:10000;"><div style="position: absolute; cursor1: wait; left: ' + _LoadingLeft + 'px; top:' + _LoadingTop + 'px; width: auto; height: 57px; line-height: 57px; padding-left: 50px; padding-right: 20px; background: #ffffff url(/img/loading.gif) no-repeat scroll 20px 20px; color: #696969; font-family:\'Microsoft YaHei\';box-shadow: 0px 0px 20px rgba(0, 0, 0, .08);    border: 2px solid transparent;border-radius: 4px;">页面加载中，请等待...</div></div>';--}}
    {{--//呈现loading效果--}}
    {{--document.write(_LoadingHtml);--}}
    {{--//window.onload = function () {--}}
    {{--//  var loadingMask = document.getElementById('loadingDiv');--}}
    {{--//  loadingMask.parentNode.removeChild(loadingMask);--}}
    {{--//};--}}
    {{--//监听加载状态改变--}}
    {{--document.onreadystatechange = completeLoading;--}}
    {{--//加载状态为complete时移除loading效果--}}
    {{--function completeLoading() {--}}
    {{--if (document.readyState == "complete") {--}}
    {{--var loadingMask = document.getElementById('loadingDiv');--}}
    {{--loadingMask.parentNode.removeChild(loadingMask);--}}

    {{--}--}}
    {{--}--}}
    {{--</script>--}}

@endsection

