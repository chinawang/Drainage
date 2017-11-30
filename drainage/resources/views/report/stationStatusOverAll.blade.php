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
                                            href="/report/stationStatusDay?station_id={{$stationSelect['id']}}&timeStart={{ $startTime }}">每日运行统计</a>
                                </li>
                                <li class=""><a
                                            href="/report/stationStatusMonth?timeStart={{ $startTime }}&timeEnd={{ $endTime }}">每月运行统计</a>
                                </li>
                                <li class=""><a
                                            href="/report/stationStatusMonthAll?timeStart={{ $startTime }}&timeEnd={{ $endTime }}">泵站月生产报表</a>
                                </li>
                                <li class="active"><a
                                            href="/report/stationStatusOverAll?station_id={{$stationSelect['id']}}&timeStart={{ $startTime }}&timeEnd={{ $endTime }}">泵站整体运行图</a>
                                </li>

                            </ul>
                        </div>
                        <div id="myTabContent" class="tab-content" style="margin-top: 20px">
                            <form class="form-horizontal" role="form" method="GET" action="/report/stationStatusOverAll"
                                  style="margin-bottom: 10px">
                                {{ csrf_field() }}

                                {{--<div class="row">--}}
                                    {{--<div class="col-md-3">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="station" class="col-md-4 control-label">泵站类型:</label>--}}

                                            {{--<div class="col-md-8">--}}
                                                {{--<select class="form-control" id="select" name="type">--}}
                                                    {{--<option value="全部" selected="selected">全部</option>--}}
                                                    {{--<option value="雨水" {{$selectType == '雨水' ? 'selected=selected' :''}}>--}}
                                                        {{--雨水--}}
                                                    {{--</option>--}}
                                                    {{--<option value="污水" {{$selectType == '污水' ? 'selected=selected' :''}}>--}}
                                                        {{--污水--}}
                                                    {{--</option>--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-6">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<label for="repair_at" class="col-md-3 control-label">时间范围:</label>--}}
                                            {{--<div class="col-md-4">--}}
                                                {{--<input type="text" class="form-control pick-event-date" id="start-time"--}}
                                                       {{--name="timeStart"--}}
                                                       {{--value="{{ $startTime }}" placeholder="起始时间"--}}
                                                       {{--data-data="yyyy-mm-dd">--}}
                                            {{--</div>--}}
                                            {{--<label for="time" class="col-md-1 control-label">—</label>--}}
                                            {{--<div class="col-md-4">--}}
                                                {{--<input type="text" class="form-control pick-event-date" id="end-time"--}}
                                                       {{--name="timeEnd"--}}
                                                       {{--value="{{ $endTime }}" placeholder="截止时间" data-data="yyyy-mm-dd">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-3">--}}
                                        {{--<div class="form-group">--}}
                                            {{--<div class="col-md-5 col-md-offset-0">--}}
                                                {{--<button type="submit" class="btn btn-primary btn-custom">--}}
                                                    {{--<span class="glyphicon glyphicon-search"></span>--}}
                                                    {{--查询--}}
                                                {{--</button>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="repair_at" class="col-md-4 control-label">选择日期:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control pick-event-date" id="start-time"
                                                       name="timeStart"
                                                       value="{{ $startTime }}" placeholder="日期" data-data="yyyy-mm-dd">
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
                                                <a href="/report/exporStatustDay?station_id={{$stationSelect['id']}}&timeStart={{ $startTime }}"
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

                                <div class="panel-body custom-panel-body" id="container"
                                     style="min-width:400px;height:400px">
                                </div>
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

    <script src="https://img.hcharts.cn/highcharts/modules/xrange.js"></script>
    <script src="https://img.hcharts.cn/highcharts/modules/oldie.js"></script>
    <script src="https://img.hcharts.cn/highcharts/themes/grid-light.js"></script>


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

        function getStationStatusList() {
            var resultValue = [];
            $.ajax({
                type: 'get',
                url: '/report/realTimeStatusHistory/{{ $stationSelect['id'] }}/{{ $startTime }}',
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
        var datas1 = [];
        var datas2 = [];
        var datas3 = [];
        var datas4 = [];
        var datas5 = [];
        var dataAll = [];

        //东八区时间差
        var dateGMT = new Date(1970,0,1,16).getTime();

        $.each(statusRTList.stationStatusList1, function (i, n) {
            categories1[i] = dateStrFormat(n["timeEnd"]);
            datas1[i] = n["timeGap"];
            var dataTmp = {x:(new Date((n["timeStart"]).replace(/-/g,'/'))).getTime()+dateGMT,x2:(new Date((n["timeEnd"]).replace(/-/g,'/'))).getTime()+dateGMT,y:0};
            dataAll.push(dataTmp);
        });
        $.each(statusRTList.stationStatusList2, function (i, n) {
            categories2[i] = dateStrFormat(n["timeEnd"]);
            datas2[i] = n["timeGap"];
            var dataTmp = {x:(new Date((n["timeStart"]).replace(/-/g,'/'))).getTime()+dateGMT,x2:(new Date((n["timeEnd"]).replace(/-/g,'/'))).getTime()+dateGMT,y:1};
            dataAll.push(dataTmp);
        });
        $.each(statusRTList.stationStatusList3, function (i, n) {
            categories3[i] = dateStrFormat(n["timeEnd"]);
            datas3[i] = n["timeGap"];
            var dataTmp = {x:(new Date((n["timeStart"]).replace(/-/g,'/'))).getTime()+dateGMT,x2:(new Date((n["timeEnd"]).replace(/-/g,'/'))).getTime()+dateGMT,y:2};
            dataAll.push(dataTmp);
        });
        $.each(statusRTList.stationStatusList4, function (i, n) {
            categories4[i] = dateStrFormat(n["timeEnd"]);
            datas4[i] = n["timeGap"];
            var dataTmp = {x:(new Date((n["timeStart"]).replace(/-/g,'/'))).getTime()+dateGMT,x2:(new Date((n["timeEnd"]).replace(/-/g,'/'))).getTime()+dateGMT,y:3};
            dataAll.push(dataTmp);
        });
        $.each(statusRTList.stationStatusList5, function (i, n) {
            categories5[i] = dateStrFormat(n["timeEnd"]);
            datas5[i] = n["timeGap"];
            var dataTmp = {x:(new Date((n["timeStart"]).replace(/-/g,'/'))).getTime()+dateGMT,x2:(new Date((n["timeEnd"]).replace(/-/g,'/'))).getTime()+dateGMT,y:4};
            dataAll.push(dataTmp);
        });


        var chartAll = new Highcharts.Chart('container', {
            chart: {
                type: 'xrange',
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
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%Y/%m/%d',
                    time: '%h:%m:%s'
                }
            },
            yAxis: {
                title: {
                    text: ''
                },
                categories: ['1号泵', '2号泵', '3号泵', '4号泵', '5号泵'],
                reversed: true
            },
            tooltip: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%Y/%m/%d',
                    time: '%h:%m:%s'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: '泵组运行间隔',
//                borderColor: 'gray',
                pointWidth: 6,
                data: dataAll,
//                data: [
//                        {x: (new Date("2017-10-10")).getTime(), x2: (new Date("2017-10-11")).getTime(), y: 0},
//                    {x: (new Date("2017-10-12")).getTime(), x2: (new Date("2017-10-15")).getTime(), y: 0}
//                    ],
//                dataLabels: {
//                    enabled: true
//                }
            },
            ]
        });


    </script>

    {{--<!--Loading-->--}}
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
    {{--//    window.onload = function () {--}}
    {{--//      var loadingMask = document.getElementById('loadingDiv');--}}
    {{--//      loadingMask.parentNode.removeChild(loadingMask);--}}
    {{--//    };--}}
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

