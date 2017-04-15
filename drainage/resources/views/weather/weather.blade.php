@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/weather/weather.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>天气</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    {{--<div class="panel-heading">--}}
                        {{--天气--}}
                    {{--</div>--}}
                    <div class="panel-body custom-panel-body" id="content-weather">
                        <div class="row">
                            <div class="col-md-2 col-md-offset-0">
                                <div class="text-city">
                                    郑州
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="weather-now">
                                    <div class="content-now now-icon">
                                        <img class="weather-icon-big" src="/img/weather/weather_180/0.png">
                                    </div>
                                    <div class="content-now now-temp-text">
                                        <span class="now-temp">20℃</span>
                                        <span class="now-text">晴</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="now-line"></div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-0">
                                <div class="weather-daily">
                                    <div class="content-daily daily-today">今天</div>
                                    <div class="content-daily daily-date">4月14日</div>
                                    <div class="content-daily daily-icon">
                                        <img class="weather-icon-small" src="/img/weather/weather_60/0.png">
                                    </div>
                                    <div class="content-daily daily-temp">15℃/20℃</div>
                                    <div class="content-daily daily-text">晴/多云</div>
                                    <div class="content-daily daily-wind">东南风2级</div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-0">
                                <div class="weather-daily">
                                    <div class="content-daily daily-today">今天</div>
                                    <div class="content-daily daily-date">4月14日</div>
                                    <div class="content-daily daily-icon">
                                        <img class="weather-icon-small" src="/img/weather/weather_60/0.png">
                                    </div>
                                    <div class="content-daily daily-temp">15℃/20℃</div>
                                    <div class="content-daily daily-text">晴/多云</div>
                                    <div class="content-daily daily-wind">东南风2级</div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-0">
                                <div class="weather-daily">
                                    <div class="content-daily daily-today">今天</div>
                                    <div class="content-daily daily-date">4月14日</div>
                                    <div class="content-daily daily-icon">
                                        <img class="weather-icon-small" src="/img/weather/weather_60/0.png">
                                    </div>
                                    <div class="content-daily daily-temp">15℃/20℃</div>
                                    <div class="content-daily daily-text">晴/多云</div>
                                    <div class="content-daily daily-wind">东南风2级</div>
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



    <script>(function (T, h, i, n, k, P, a, g, e) {
            g = function () {
                P = h.createElement(i);
                a = h.getElementsByTagName(i)[0];
                P.src = k;
                P.charset = "utf-8";
                P.async = 1;
                a.parentNode.insertBefore(P, a)
            };
            T["ThinkPageWeatherWidgetObject"] = n;
            T[n] || (T[n] = function () {
                (T[n].q = T[n].q || []).push(arguments)
            });
            T[n].l = +new Date();
            if (T.attachEvent) {
                T.attachEvent("onload", g)
            } else {
                T.addEventListener("load", g, false)
            }
        }(window, document, "script", "tpwidget", "//widget.seniverse.com/widget/chameleon.js"))</script>
    <script>tpwidget("init", {
            "flavor": "bubble",
            "location": "WW0V9QP93VS8",
            "geolocation": "enabled",
            "position": "bottom-left",
            "margin": "10px 10px",
            "language": "zh-chs",
            "unit": "c",
            "theme": "chameleon",
            "uid": "U4954D65B6",
            "hash": "846b9b7cbef65275022421d6638416e0"
        });
        tpwidget("show");</script>

@endsection
