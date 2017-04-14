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
    <script>
        var obj = document.getElementById('73cd93ca-5573-11e6-beb8-9e71128cae77');
//        obj.onmouseover;
        alert(obj.innerHTML);
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
