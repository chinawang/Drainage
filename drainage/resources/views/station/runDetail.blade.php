@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>郑湾泵站运行详情</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        涵洞水位
                    </div>
                    <div class="panel-body custom-panel-body" id="culvertContainer" style="min-width:400px;height:400px">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        集水池水位
                    </div>
                    <div class="panel-body custom-panel-body" id="tankContainer" style="min-width:400px;height:400px">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script src="https://cdn.hcharts.cn/highcharts/highcharts.js"></script>
    <script src="http://cdn.hcharts.cn/highcharts/themes/dark-unica.js"></script>

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
                                    y = Math.random()*2;
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '涵洞实时水位'
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
                    from: 1.8,
                    to: 1.82,
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
                    for (i = -19; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
                            y: Math.random()*2
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>

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
                                    y = Math.random()*5;
                            series.addPoint([x, y], true, true);
                            activeLastPointToolip(chart)
                        }, 1000);
                    }
                }
            },
            title: {
                text: '集水池实时水位'
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
                    from: 4.5,
                    to: 4.54,
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
                    for (i = -19; i <= 0; i += 1) {
                        data.push({
                            x: time + i * 1000,
                            y: Math.random()*5
                        });
                    }
                    return data;
                }())
            }]
        }, function (c) {
            activeLastPointToolip(c)
        });

    </script>
@endsection

