@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>泵站运行详情</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/station/runList">泵站实时工作状态</a>
                <em>›</em>
                <span>{{ $station['name'] }}</span>
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
                        {{ $station['name'] }}
                        {{--<a href="#" onClick="javascript :history.back(-1);" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <div class="row">
                            <div class="col-md-12 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--涵洞--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="culvertContainer"
                                         style="min-width:400px;height:400px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--集水池--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="tankContainer"
                                         style="min-width:400px;height:400px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--泵组1电流--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpCurrentContainer_1"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--泵组2电流--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpCurrentContainer_2"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--泵组3电流--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpCurrentContainer_3"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--泵组4电流--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpCurrentContainer_4"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($station['station_number'] == '33')
                        <div class="row">
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--泵组5电流--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpCurrentContainer_5"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--系统AB相电压--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpVoltageAContainer"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--系统BC相电压--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpVoltageBContainer"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    {{--<div class="panel-heading">--}}
                                    {{--系统CA相电压--}}
                                    {{--</div>--}}
                                    <div class="panel-body custom-panel-body" id="pumpVoltageCContainer"
                                         style="min-width:400px;height:400px">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        1号格栅除污机运行状态
                                    </div>
                                    <div class="panel-body custom-panel-body" style="text-align: center">
                                        <img class="status-icon" id="remover1Status" src="/img/status/checking.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        2号格栅除污机运行状态
                                    </div>
                                    <div class="panel-body custom-panel-body" style="text-align: center">
                                        <img class="status-icon" id="remover2Status" src="/img/status/checking.png">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-0">
                                <div class="panel panel-default custom-panel">
                                    <div class="panel-heading">
                                        绞笼运行状态
                                    </div>
                                    <div class="panel-body custom-panel-body" style="text-align: center">
                                        <img class="status-icon" id="augerStatus" src="/img/status/checking.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div class="row">--}}
                        {{--<div class="col-md-12 col-md-offset-0">--}}
                        {{--<div class="panel panel-default custom-panel">--}}
                        {{--<div class="panel-heading">--}}
                        {{--格栅除污机--}}
                        {{--</div>--}}
                        {{--<div class="panel-body custom-panel-body">--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-md-6 col-md-offset-0" id="removerVoltageContainer"--}}
                        {{--style="min-width:400px;height:400px">--}}

                        {{--</div>--}}
                        {{--<div class="col-md-6 col-md-offset-0" id="removerCurrentContainer"--}}
                        {{--style="min-width:400px;height:400px">--}}

                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-md-12 col-md-offset-0">--}}
                        {{--<div class="panel panel-default custom-panel">--}}
                        {{--<div class="panel-heading">--}}
                        {{--发电机--}}
                        {{--</div>--}}
                        {{--<div class="panel-body custom-panel-body">--}}
                        {{--<div class="row">--}}
                        {{--<div class="col-md-6 col-md-offset-0" id="alternatorPowerContainer"--}}
                        {{--style="min-width:400px;height:400px">--}}

                        {{--</div>--}}
                        {{--<div class="col-md-6 col-md-offset-0" id="alternatoresistanceContainer"--}}
                        {{--style="min-width:400px;height:400px">--}}

                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script src="https://cdn.hcharts.cn/highcharts/highcharts.js"></script>
    <script src="http://cdn.hcharts.cn/highcharts/themes/dark-unica.js"></script>
    {{--<script src="http://cdn.hcharts.cn/highcharts/themes/gray.js"></script>--}}

    <script type="text/javascript">
        var stationRT;
        function getStationRT() {
            $.ajax({
                type: 'get',
                url: '/station/realTime/{{ $station['station_number'] }}',
                data: '_token = <?php echo csrf_token() ?>',
                success: function (data) {
                    stationRT = data.stationRT;
                }
            });
        }
        $(document).ready(function () {
            setInterval(getStationRT(), 1000);
        });

    </script>

    <script type="text/javascript">

        function getStationRTHistory() {
            var resultValue = [];
            $.ajax({
                type: 'get',
                url: '/station/realTimeHistory/{{ $station['station_number'] }}',
                data: '_token = <?php echo csrf_token() ?>',
                async: false,//同步
                success: function (data) {
                    resultValue = data.stationRTHistory;
                }
            });
            return resultValue;
        }

        var stationRTHistory = getStationRTHistory();
        //        $(document).ready(function () {
        //            stationRTHistory = getStationRTHistory();
        //        });


    </script>

    {{--设备运行状态(格栅、绞龙)--}}
    <script type="text/javascript">

        //监测1号格栅运行状态
        function checkRemover1Status() {
            if(stationRTHistory[0]['yx_gs1'] == '1'){
                document.getElementById("remover1Status").src = "/img/status/running.png";
            }
            else{
                document.getElementById("remover1Status").src = "/img/status/stop_black.png";
            }
        }

        //监测2号格栅运行状态
        function checkRemover2Status() {
            if(stationRTHistory[0]['yx_gs2'] == '1'){
                document.getElementById("remover2Status").src = "/img/status/running.png";
            }
            else{
                document.getElementById("remover2Status").src = "/img/status/stop_black.png";
            }
        }

        //监测绞龙运行状态
        function checkAugerStatus() {
            if(stationRTHistory[0]['yx_jl'] == '1'){
                document.getElementById("augerStatus").src = "/img/status/running.png";
            }
            else{
                document.getElementById("augerStatus").src = "/img/status/stop_black.png";
            }
        }

        function checkStatus() {
            checkRemover1Status();
            checkRemover2Status();
            checkAugerStatus();
        }

        $(document).ready(function () {
            setInterval(checkStatus(), 1000);
        });

    </script>

    {{--涵洞水位--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#culvertContainer').highcharts({
            chart: {
                type: 'areaspline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {

                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random()*2;
                                    y = stationRT['ywhandong'];

                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '涵洞实时水位(米)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '水位高度(米)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                plotBands: [{ // 警戒线
                    from: 11.8,
                    to: 11.82,
                    color: 'rgb(244, 91, 91)',
                    label: {
                        text: '警戒高度',
                        style: {
                            color: '#ffffff'
                        }
                    }
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '水位高度',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ywhandong']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--集水池水位--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#tankContainer').highcharts({
            chart: {
                type: 'areaspline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 5;
                                    y = stationRT['ywjishui'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '集水池实时水位(米)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '水位高度(米)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                plotBands: [{ // 警戒线
                    from: 11.5,
                    to: 11.54,
                    color: 'rgb(244, 91, 91)',
                    label: {
                        text: '警戒高度',
                        style: {
                            color: '#ffffff'
                        }
                    }
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '水位高度',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ywjishui']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--1号泵组电流--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpCurrentContainer_1').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 100;
                                    y = stationRT['ib1'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '1号泵实时电流(毫安)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电流(毫安)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电流',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ib1']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--2号泵组电流--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpCurrentContainer_2').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 100;
                                    y = stationRT['ib2'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '2号泵实时电流(毫安)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电流(毫安)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电流',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ib2']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--3号泵组电流--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpCurrentContainer_3').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 100;
                                    y = stationRT['ib3'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '3号泵实时电流(毫安)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电流(毫安)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电流',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ib3']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--4号泵组电流--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpCurrentContainer_4').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 100;
                                    y = stationRT['ib4'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '4号泵实时电流(毫安)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电流(毫安)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电流',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ib4']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--5号泵组电流--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpCurrentContainer_5').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 100;
                                    y = stationRT['ib5'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '5号泵实时电流(毫安)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电流(毫安)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电流',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ib5']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--泵组电压UAB--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpVoltageAContainer').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 200;
                                    y = stationRT['uab'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '实时A相电压(伏)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电压(伏)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电压',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['uab']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--泵组电压UBC--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpVoltageBContainer').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 200;
                                    y = stationRT['ubc'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '实时B相电压(伏)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电压(伏)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电压',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['ubc']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--泵组电压UCA--}}
    <script type="text/javascript">
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
        function activeLastPointToolip(chart) {
            var points = chart.series[0].points;
            chart.tooltip.refresh(points[points.length - 1]);
        }
        $('#pumpVoltageCContainer').highcharts({
            chart: {
                type: 'spline',
                animation: Highcharts.svg, // don't animate in old IE
                marginRight: 10,
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0],
                                chart = this;
                        setInterval(function () {
                            var x = (new Date()).getTime(), // current time
//                                    y = Math.random() * 200;
                                    y = stationRT['uca'];
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '实时C相电压(伏)'
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150
            },
            yAxis: {
                title: {
                    text: '电压(伏)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                            Highcharts.numberFormat(this.y, 2);
                }
            },
            legend: {
                enabled: false
            },
            exporting: {
                enabled: false
            },
            series: [{
                name: '电压',
                data: (function () {
                    // generate an array of random data
                    var data = [],
                            time = (new Date()).getTime(),
                            i;
                    var valueTmp = stationRTHistory;
                    for (i = -119; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
//                            y: Math.random() * 100
                            y: valueTmp[0 - i]['uca']
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

    {{--格栅除污机电压--}}
    {{--<script type="text/javascript">--}}
    {{--Highcharts.setOptions({--}}
    {{--global: {--}}
    {{--useUTC: false--}}
    {{--}--}}
    {{--});--}}
    {{--function activeLastPointToolip(chart) {--}}
    {{--var points = chart.series[0].points;--}}
    {{--chart.tooltip.refresh(points[points.length - 1]);--}}
    {{--}--}}
    {{--$('#removerVoltageContainer').highcharts({--}}
    {{--chart: {--}}
    {{--type: 'spline',--}}
    {{--animation: Highcharts.svg, // don't animate in old IE--}}
    {{--marginRight: 10,--}}
    {{--events: {--}}
    {{--load: function () {--}}
    {{--// set up the updating of the chart each second--}}
    {{--var series = this.series[0],--}}
    {{--chart = this;--}}
    {{--setInterval(function () {--}}
    {{--var x = (new Date()).getTime(), // current time--}}
    {{--y = Math.random() * 200;--}}
    {{--series.addPoint([x, y], true, true);--}}
    {{--activeLastPointToolip(chart)--}}
    {{--}, 1000);--}}
    {{--}--}}
    {{--}--}}
    {{--},--}}
    {{--title: {--}}
    {{--text: '格栅除污机实时电压(伏)'--}}
    {{--},--}}
    {{--xAxis: {--}}
    {{--type: 'datetime',--}}
    {{--tickPixelInterval: 150--}}
    {{--},--}}
    {{--yAxis: {--}}
    {{--title: {--}}
    {{--text: '电压(伏)'--}}
    {{--},--}}
    {{--plotLines: [{--}}
    {{--value: 0,--}}
    {{--width: 1,--}}
    {{--color: '#808080'--}}
    {{--}]--}}
    {{--},--}}
    {{--tooltip: {--}}
    {{--formatter: function () {--}}
    {{--return '<b>' + this.series.name + '</b><br/>' +--}}
    {{--Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +--}}
    {{--Highcharts.numberFormat(this.y, 2);--}}
    {{--}--}}
    {{--},--}}
    {{--legend: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--exporting: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--series: [{--}}
    {{--name: '电压',--}}
    {{--data: (function () {--}}
    {{--// generate an array of random data--}}
    {{--var data = [],--}}
    {{--time = (new Date()).getTime(),--}}
    {{--i;--}}
    {{--for (i = -59; i <= 0; i += 1) {--}}
    {{--data.push({--}}
    {{--x: time + i * 1000,--}}
    {{--y: Math.random() * 200--}}
    {{--});--}}
    {{--}--}}
    {{--return data;--}}
    {{--}())--}}
    {{--}]--}}
    {{--}, function (c) {--}}
    {{--activeLastPointToolip(c)--}}
    {{--});--}}

    {{--</script>--}}

    {{--格栅除污机电流--}}
    {{--<script type="text/javascript">--}}
    {{--Highcharts.setOptions({--}}
    {{--global: {--}}
    {{--useUTC: false--}}
    {{--}--}}
    {{--});--}}
    {{--function activeLastPointToolip(chart) {--}}
    {{--var points = chart.series[0].points;--}}
    {{--chart.tooltip.refresh(points[points.length - 1]);--}}
    {{--}--}}
    {{--$('#removerCurrentContainer').highcharts({--}}
    {{--chart: {--}}
    {{--type: 'spline',--}}
    {{--animation: Highcharts.svg, // don't animate in old IE--}}
    {{--marginRight: 10,--}}
    {{--events: {--}}
    {{--load: function () {--}}
    {{--// set up the updating of the chart each second--}}
    {{--var series = this.series[0],--}}
    {{--chart = this;--}}
    {{--setInterval(function () {--}}
    {{--var x = (new Date()).getTime(), // current time--}}
    {{--y = Math.random() * 100;--}}
    {{--series.addPoint([x, y], true, true);--}}
    {{--activeLastPointToolip(chart)--}}
    {{--}, 1000);--}}
    {{--}--}}
    {{--}--}}
    {{--},--}}
    {{--title: {--}}
    {{--text: '格栅除污机实时电流(毫安)'--}}
    {{--},--}}
    {{--xAxis: {--}}
    {{--type: 'datetime',--}}
    {{--tickPixelInterval: 150--}}
    {{--},--}}
    {{--yAxis: {--}}
    {{--title: {--}}
    {{--text: '电流(毫安)'--}}
    {{--},--}}
    {{--plotLines: [{--}}
    {{--value: 0,--}}
    {{--width: 1,--}}
    {{--color: '#808080'--}}
    {{--}]--}}
    {{--},--}}
    {{--tooltip: {--}}
    {{--formatter: function () {--}}
    {{--return '<b>' + this.series.name + '</b><br/>' +--}}
    {{--Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +--}}
    {{--Highcharts.numberFormat(this.y, 2);--}}
    {{--}--}}
    {{--},--}}
    {{--legend: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--exporting: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--series: [{--}}
    {{--name: '电流',--}}
    {{--data: (function () {--}}
    {{--// generate an array of random data--}}
    {{--var data = [],--}}
    {{--time = (new Date()).getTime(),--}}
    {{--i;--}}
    {{--for (i = -59; i <= 0; i += 1) {--}}
    {{--data.push({--}}
    {{--x: time + i * 1000,--}}
    {{--y: Math.random() * 100--}}
    {{--});--}}
    {{--}--}}
    {{--return data;--}}
    {{--}())--}}
    {{--}]--}}
    {{--}, function (c) {--}}
    {{--activeLastPointToolip(c)--}}
    {{--});--}}

    {{--</script>--}}

    {{--发电机功率--}}
    {{--<script type="text/javascript">--}}
    {{--Highcharts.setOptions({--}}
    {{--global: {--}}
    {{--useUTC: false--}}
    {{--}--}}
    {{--});--}}
    {{--function activeLastPointToolip(chart) {--}}
    {{--var points = chart.series[0].points;--}}
    {{--chart.tooltip.refresh(points[points.length - 1]);--}}
    {{--}--}}
    {{--$('#alternatorPowerContainer').highcharts({--}}
    {{--chart: {--}}
    {{--type: 'spline',--}}
    {{--animation: Highcharts.svg, // don't animate in old IE--}}
    {{--marginRight: 10,--}}
    {{--events: {--}}
    {{--load: function () {--}}
    {{--// set up the updating of the chart each second--}}
    {{--var series = this.series[0],--}}
    {{--chart = this;--}}
    {{--setInterval(function () {--}}
    {{--var x = (new Date()).getTime(), // current time--}}
    {{--y = Math.random() * 1000;--}}
    {{--series.addPoint([x, y], true, true);--}}
    {{--activeLastPointToolip(chart)--}}
    {{--}, 1000);--}}
    {{--}--}}
    {{--}--}}
    {{--},--}}
    {{--title: {--}}
    {{--text: '发电机功率(千瓦)'--}}
    {{--},--}}
    {{--xAxis: {--}}
    {{--type: 'datetime',--}}
    {{--tickPixelInterval: 150--}}
    {{--},--}}
    {{--yAxis: {--}}
    {{--title: {--}}
    {{--text: '功率(千瓦)'--}}
    {{--},--}}
    {{--plotLines: [{--}}
    {{--value: 0,--}}
    {{--width: 1,--}}
    {{--color: '#808080'--}}
    {{--}]--}}
    {{--},--}}
    {{--tooltip: {--}}
    {{--formatter: function () {--}}
    {{--return '<b>' + this.series.name + '</b><br/>' +--}}
    {{--Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +--}}
    {{--Highcharts.numberFormat(this.y, 2);--}}
    {{--}--}}
    {{--},--}}
    {{--legend: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--exporting: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--series: [{--}}
    {{--name: '功率',--}}
    {{--data: (function () {--}}
    {{--// generate an array of random data--}}
    {{--var data = [],--}}
    {{--time = (new Date()).getTime(),--}}
    {{--i;--}}
    {{--for (i = -59; i <= 0; i += 1) {--}}
    {{--data.push({--}}
    {{--x: time + i * 1000,--}}
    {{--y: Math.random() * 1000--}}
    {{--});--}}
    {{--}--}}
    {{--return data;--}}
    {{--}())--}}
    {{--}]--}}
    {{--}, function (c) {--}}
    {{--activeLastPointToolip(c)--}}
    {{--});--}}

    {{--</script>--}}

    {{--发电机电阻--}}
    {{--<script type="text/javascript">--}}
    {{--Highcharts.setOptions({--}}
    {{--global: {--}}
    {{--useUTC: false--}}
    {{--}--}}
    {{--});--}}
    {{--function activeLastPointToolip(chart) {--}}
    {{--var points = chart.series[0].points;--}}
    {{--chart.tooltip.refresh(points[points.length - 1]);--}}
    {{--}--}}
    {{--$('#alternatoresistanceContainer').highcharts({--}}
    {{--chart: {--}}
    {{--type: 'spline',--}}
    {{--animation: Highcharts.svg, // don't animate in old IE--}}
    {{--marginRight: 10,--}}
    {{--events: {--}}
    {{--load: function () {--}}
    {{--// set up the updating of the chart each second--}}
    {{--var series = this.series[0],--}}
    {{--chart = this;--}}
    {{--setInterval(function () {--}}
    {{--var x = (new Date()).getTime(), // current time--}}
    {{--y = Math.random() * 50;--}}
    {{--series.addPoint([x, y], true, true);--}}
    {{--activeLastPointToolip(chart)--}}
    {{--}, 1000);--}}
    {{--}--}}
    {{--}--}}
    {{--},--}}
    {{--title: {--}}
    {{--text: '发电机电阻(欧)'--}}
    {{--},--}}
    {{--xAxis: {--}}
    {{--type: 'datetime',--}}
    {{--tickPixelInterval: 150--}}
    {{--},--}}
    {{--yAxis: {--}}
    {{--title: {--}}
    {{--text: '电阻(欧)'--}}
    {{--},--}}
    {{--plotLines: [{--}}
    {{--value: 0,--}}
    {{--width: 1,--}}
    {{--color: '#808080'--}}
    {{--}]--}}
    {{--},--}}
    {{--tooltip: {--}}
    {{--formatter: function () {--}}
    {{--return '<b>' + this.series.name + '</b><br/>' +--}}
    {{--Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +--}}
    {{--Highcharts.numberFormat(this.y, 2);--}}
    {{--}--}}
    {{--},--}}
    {{--legend: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--exporting: {--}}
    {{--enabled: false--}}
    {{--},--}}
    {{--series: [{--}}
    {{--name: '电阻',--}}
    {{--data: (function () {--}}
    {{--// generate an array of random data--}}
    {{--var data = [],--}}
    {{--time = (new Date()).getTime(),--}}
    {{--i;--}}
    {{--for (i = -59; i <= 0; i += 1) {--}}
    {{--data.push({--}}
    {{--x: time + i * 1000,--}}
    {{--y: Math.random() * 50--}}
    {{--});--}}
    {{--}--}}
    {{--return data;--}}
    {{--}())--}}
    {{--}]--}}
    {{--}, function (c) {--}}
    {{--activeLastPointToolip(c)--}}
    {{--});--}}

    {{--</script>--}}
@endsection

