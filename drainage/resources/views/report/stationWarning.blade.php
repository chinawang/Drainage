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
                                <li class=""><a
                                            href="/report/stationWarningAll?timeStart={{ $startTime }}&timeEnd={{ $endTime }}">所有泵站</a>
                                </li>
                                <li class="active"><a
                                            href="/report/stationWarning?timeStart={{ $startTime }}&timeEnd={{ $endTime }}">单个泵站</a>
                                </li>

                            </ul>
                        </div>

                        <div id="myTabContent" class="tab-content" style="margin-top: 20px">
                            <form class="form-horizontal" role="form" method="GET" action="/report/stationWarning"
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

                            @if (!empty($warningList[0]))
                                <table class="table table-hover table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>报警时间</th>
                                        <th>报警设备</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($warningList as $warning)
                                        <tr>
                                            <td>{{ $warning['Time'] }}</td>
                                            <td>{{ $warning['alarmEquipment'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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

    {{--<script src="https://cdn.hcharts.cn/highcharts/highcharts.js"></script>--}}

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
                url: '/report/stationWarningCount/{{ $stationSelect['id'] }}/{{ $startTime }}/{{ $endTime }}',
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
        var timeGap = stationWarningInfo.startTime + ' 至 ' + stationWarningInfo.endTime;

        $.each(stationWarningInfo.stationWarningCountList, function (i, n) {
            categories[i] = n["alarmEquipment"];
            datas[i] = n["alarmCount"];
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
            series: [{
                name: '报警次数',
                data: datas
            }
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

