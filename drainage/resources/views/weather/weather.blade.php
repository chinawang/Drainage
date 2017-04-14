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

    {{--<script>(function (T, h, i, n, k, P, a, g, e) {--}}
            {{--g = function () {--}}
                {{--P = h.createElement(i);--}}
                {{--a = h.getElementsByTagName(i)[0];--}}
                {{--P.src = k;--}}
                {{--P.charset = "utf-8";--}}
                {{--P.async = 1;--}}
                {{--a.parentNode.insertBefore(P, a)--}}
            {{--};--}}
            {{--T["ThinkPageWeatherWidgetObject"] = n;--}}
            {{--T[n] || (T[n] = function () {--}}
                {{--(T[n].q = T[n].q || []).push(arguments)--}}
            {{--});--}}
            {{--T[n].l = +new Date();--}}
            {{--if (T.attachEvent) {--}}
                {{--T.attachEvent("onload", g)--}}
            {{--} else {--}}
                {{--T.addEventListener("load", g, false)--}}
            {{--}--}}
        {{--}(window, document, "script", "tpwidget", "//widget.seniverse.com/widget/chameleon.js"))--}}
    {{--</script>--}}
    {{--<script>--}}
        {{--tpwidget("init", {--}}
            {{--"flavor": "bubble",--}}
            {{--"location": "WW0V9QP93VS8",--}}
            {{--"geolocation": "disabled",--}}
            {{--"position": "top-left",--}}
            {{--"margin": "200px 300px",--}}
            {{--"language": "zh-chs",--}}
            {{--"unit": "c",--}}
            {{--"theme": "chameleon",--}}
            {{--"uid": "U4954D65B6",--}}
            {{--"hash": "53726de14b08605c08ed73005e8c9109"--}}
        {{--});--}}
        {{--tpwidget("show");--}}
    {{--</script>--}}

    <script src="https://cdn.bootcss.com/jsSHA/2.2.0/sha1.js"></script>

    <script>

        var url = "{{ $weatherURL }}";
        alert(url);
        // 直接发送请求进行调用，手动处理回调函数
        $.getJSON(url, function(data) {
            var obj = document.getElementById('content-weather');
            var weather = data.results[0]
            var text = [];
            text.push("Location: " + weather.location.path);
            text.push("Weather: " + weather.now.text);
            text.push("Temperature: " + weather.now.temperature);
            obj.innerText = text.join("\n")
        });
    </script>

@endsection
