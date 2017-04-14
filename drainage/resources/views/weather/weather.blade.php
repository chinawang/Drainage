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
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        天气
                    </div>
                    <div class="panel-body custom-panel-body" id="content-weather">
                        <iframe src="//www.seniverse.com/weather/weather.aspx?uid=U4954D65B6&cid=CHHA000000&l=zh-CHS&p=SMART&a=0&u=C&s=1&m=0&x=1&d=5&fc=&bgc=&bc=&ti=0&in=1&li="
                                frameborder="0" scrolling="no" width="360" height="300"
                                allowTransparency="true"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script>
        (function (T, h, i, n, k, P, a, g, e) {
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
        }(window, document, "script", "tpwidget", "//widget.seniverse.com/widget/chameleon.js"))
    </script>
    <script>
        tpwidget("init", {
            "flavor": "bubble",
            "location": "WW0V9QP93VS8",
            "geolocation": "disabled",
            "position": "top-left",
            "margin": "10px 10px",
            "language": "zh-chs",
            "unit": "c",
            "theme": "chameleon",
            "uid": "U4954D65B6",
            "hash": "4822c3f0c454c300fe8a5d6a3beb95bb"
        });
        tpwidget("show");
    </script>



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
