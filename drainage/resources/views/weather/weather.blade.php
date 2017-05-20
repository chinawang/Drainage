@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/weather/weather.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>天气</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>天气预报</span>

            </h2>
        </div>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel" style="background: {{ $backgroundStyle }}" id="content-weather">
                    {{--<div class="panel-heading">--}}
                        {{--天气--}}
                    {{--</div>--}}
                    <div class="panel-body custom-panel-body">
                        <div class="row">
                            <div class="col-md-12 col-md-offset-0">
                                <div class="text-city">
                                    郑州
                                    <a href="/weather/view" class="btn-link" style="float: right">刷新</a>
                                </div>
                                <div class="text-updatetime">
                                    {{ $nowWeather['last_update'] }}发布
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="weather-now">
                                    <div class="content-now now-icon">
                                        <img class="weather-icon-big" src="/img/weather/weather_180/{{ $nowWeather['now']['code'] }}.png">
                                    </div>
                                    <div class="content-now now-temp-text">
                                        <span class="now-temp">{{ $nowWeather['now']['temperature'] }}°</span>
                                        <span class="now-text">{{ $nowWeather['now']['text'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="now-line"></div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-0">
                                <div class="weather-daily">
                                    <div class="content-daily daily-today">今天</div>
                                    <div class="content-daily daily-date">{{ $dailyWeather['daily'][0]['date'] }}</div>
                                    <div class="content-daily daily-icon">
                                        <img class="weather-icon-small" src="/img/weather/weather_60/{{ $dailyWeather['daily'][0]['code_day'] }}.png">
                                    </div>
                                    <div class="content-daily daily-temp">{{ $dailyWeather['daily'][0]['low'] }}° / {{ $dailyWeather['daily'][0]['high'] }}°</div>
                                    <div class="content-daily daily-text">{{ $dailyWeather['daily'][0]['text_day'] }} / {{ $dailyWeather['daily'][0]['text_night'] }}</div>
                                    <div class="content-daily daily-wind">{{ $dailyWeather['daily'][0]['wind_direction'] }}风{{ $dailyWeather['daily'][0]['wind_scale'] }}级</div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-0">
                                <div class="weather-daily">
                                    <div class="content-daily daily-today">明天</div>
                                    <div class="content-daily daily-date">{{ $dailyWeather['daily'][1]['date'] }}</div>
                                    <div class="content-daily daily-icon">
                                        <img class="weather-icon-small" src="/img/weather/weather_60/{{ $dailyWeather['daily'][1]['code_day'] }}.png">
                                    </div>
                                    <div class="content-daily daily-temp">{{ $dailyWeather['daily'][1]['low'] }}° / {{ $dailyWeather['daily'][1]['high'] }}°</div>
                                    <div class="content-daily daily-text">{{ $dailyWeather['daily'][1]['text_day'] }} / {{ $dailyWeather['daily'][1]['text_night'] }}</div>
                                    <div class="content-daily daily-wind">{{ $dailyWeather['daily'][1]['wind_direction'] }}风{{ $dailyWeather['daily'][1]['wind_scale'] }}级</div>
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-0">
                                <div class="weather-daily">
                                    <div class="content-daily daily-today">后天</div>
                                    <div class="content-daily daily-date">{{ $dailyWeather['daily'][2]['date'] }}</div>
                                    <div class="content-daily daily-icon">
                                        <img class="weather-icon-small" src="/img/weather/weather_60/{{ $dailyWeather['daily'][2]['code_day'] }}.png">
                                    </div>
                                    <div class="content-daily daily-temp">{{ $dailyWeather['daily'][2]['low'] }}° / {{ $dailyWeather['daily'][2]['high'] }}°</div>
                                    <div class="content-daily daily-text">{{ $dailyWeather['daily'][2]['text_day'] }} / {{ $dailyWeather['daily'][2]['text_night'] }}</div>
                                    <div class="content-daily daily-wind">{{ $dailyWeather['daily'][2]['wind_direction'] }}风{{ $dailyWeather['daily'][2]['wind_scale'] }}级</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

