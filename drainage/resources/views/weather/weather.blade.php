@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/rbac/rbac.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>天气</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        天气
                    </div>
                    <div class="panel-body custom-panel-body" id="content-weather">
                        <iframe src="//www.seniverse.com/weather/weather.aspx?uid=U4954D65B6&cid=CHHA000000&l=zh-CHS&p=SMART&a=0&u=C&s=1&m=0&x=1&d=5&fc=&bgc=&bc=&ti=0&in=1&li="
                                frameborder="0" scrolling="no" width="800" height="300"
                                allowTransparency="true"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')




    {{--<script type="text/javascript">--}}

    {{--var url = "{!! $weatherURL !!}";--}}

    {{--$.ajax({--}}
    {{--url: url,--}}
    {{--type: 'GET',--}}
    {{--dataType: 'JSONP',--}}
    {{--success: function (data) {--}}
    {{--var obj = document.getElementById('content-weather');--}}
    {{--var weather = data.results[0]--}}
    {{--var text = [];--}}
    {{--text.push("Location: " + weather.location.name);--}}
    {{--text.push("Weather: " + weather.now.text);--}}
    {{--text.push("Temperature: " + weather.now.temperature);--}}

    {{--obj.innerText = text.join("\n")--}}
    {{--}--}}
    {{--});--}}

    {{--</script>--}}

@endsection
