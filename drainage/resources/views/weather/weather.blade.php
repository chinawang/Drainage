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
        /******** 本示例仅做开发参考使用，不建议在生产环节下暴露 key！ ********/
        var UID = "ID：U4954D65B6"; // 测试用 用户ID，请更换成您自己的用户ID
        var KEY = "rybl3izena2a0lug"; // 测试用key，请更换成您自己的 Key
        var API = "http://api.thinkpage.cn/v3/weather/now.json"; // 获取天气实况
        var LOCATION = "zhengzhou"; // 除拼音外，还可以使用 v3 id、汉语等形式
        // 获取当前时间戳
        var ts = Math.floor((new Date()).getTime() / 1000);
        // 构造验证参数字符串
        var str = "ts="+ts+"&uid=" + UID;
        // 使用 HMAC-SHA1 方式，以 API 密钥（key）对上一步生成的参数字符串（raw）进行加密
        // 并将加密结果用 base64 编码，并做一个 urlencode，得到签名 sig
        var sig = CryptoJS.HmacSHA1(str, KEY).toString(CryptoJS.enc.Base64);
        sig = encodeURIComponent(sig);
        str = str + "&sig=" + sig;
        // 构造最终请求的 url
        var url = API + "?location=" + LOCATION + "&" + str + "&callback=?";
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
