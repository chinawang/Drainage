<!-- Powered by wangyx -->
<!-- chinawangyx@hotmail.com -->
<!-- May the force be with you. -->
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="/img/header/logo_title_blue.png" type="image/x-icon"/>

    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    {{--<link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">--}}
    {{--<link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">--}}
    {{--<link href="http://cdn.bootcss.com/bootswatch/3.3.7/flatly/bootstrap.min.css" rel="stylesheet">--}}
    <link href="{{ asset('css/bootstrap-flatly.min.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('css/bootstrap-cerulean.min.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common/header.css') }}" rel="stylesheet">

{{--    <link href="{{ asset('css/pace/themes/silver/pace-theme-loading-bar.css') }}" rel="stylesheet">--}}

    <link href="{{ asset('css/pace/themes/yellow/pace-theme-flash.css') }}" rel="stylesheet">

@yield('stylesheet')

<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar">1</span>
                    <span class="icon-bar">2</span>
                    <span class="icon-bar">3</span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/img/header/logo.png" alt="logo">
                    {{ config('app.name', 'Laravel') }}
                </a>

                {{--<div class="navbar-line"></div>--}}

                <div class="navbar-subtitle">
                    @yield('subtitle')
                </div>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">

                    {{--<li class="dateStr" id="dateStr"></li>--}}

                    <li class="back-to-home" style="padding-top: 15px;">
                        <div id="tp-weather-widget"></div>
                    </li>

                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}" class="navbar-button">登录</a></li>
                        {{--<li><a href="{{ route('register') }}">注册</a></li>--}}
                    @else
                        {{--<li class="back-to-home"><a href="{{ url('/') }}">返回首页</a></li>--}}

                        <li class="sayhello"><span>你好,</span></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle navbar-button" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->realname }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="/user/profile/{{ Auth::user()->id }}">
                                        个人信息
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        退出
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        {{--<li class="back-to-home"><a href="{{ url('/') }}">首页</a></li>--}}
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @yield('location')
    @yield('content')

    <div class="side-bar" style="width: 40px;
    position: fixed;
    bottom: 20px;
    right: 10px;
    z-index: 100;" data-toggle="tooltip" data-placement="left" title="" data-original-title="全屏">
        <a onclick="toggleFullScreen();" 
           style="width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    display: inline-block;
    background-color: #fafafa;
    margin-bottom: 2px;cursor: pointer" >
            <span class="glyphicon glyphicon-fullscreen" style="color: #cccccc"></span>
        </a>
    </div>

    <footer class="footer" style="width: 100%;height: 50px;margin-top: 10px">
        <div class="container" style="background: rgba(0, 0, 0, 0);color: rgba(255, 255, 255, 0.02);text-align: center">
            <span>Powered By Wangyx</span>
            <br/>
            <span>Eamil: chinawangyx@hotmail.com</span>
        </div>
    </footer>
</div>

<!-- Scripts -->
{{--<script src="{{ asset('js/app.js') }}"></script>--}}

{{--<script src="http://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>--}}
{{--<script src="http://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}

<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-3.3.7.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.zh-CN.js') }}"></script>
<script src="{{ asset('js/zh-CN.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>

<script src="{{ asset('js/pace.min.js') }}"></script>

<script>
    $("[data-toggle='tooltip']").tooltip();
</script>

<script type="text/javascript">

    <!-- 日期选择 -->
    $("#datetimepicker").datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        autoclose: true,
        todayBtn: false,
        todayHighlight: true,
        showMeridian: true,
        keyboardNavigation: true,
        forceParse: true,
        weekStart: 1,
        bootcssVer: 3,
        pickerPosition: "bottom-right",
        language: 'zh-CN',//中文，需要引用zh-CN.js包
        startView: 2,//月视图
        minView: 0//日期时间选择器所能够提供的最精确的时间选择视图
    });

</script>

<script type="text/javascript">
    $(document).ready(function (e) {
        function showTime() {
            var date = new Date();
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var hour = date.getHours();
            var minute = date.getMinutes();
            var second = date.getSeconds();
            var dateStr = year + "年" + month + "月" + day + "日" + hour + "时" + minute + "分" + second + "秒";
            $("#dateStr").html(dateStr);
        }

        var interval = window.setInterval(showTime, 100);
    });
</script>

<!--天气-->
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
        "flavor": "slim",
        "location": "WW0V9QP93VS8",
        "geolocation": "disabled",
        "language": "zh-chs",
        "unit": "c",
        "theme": "chameleon",
        "container": "tp-weather-widget",
        "bubble": "enabled",
        "alarmType": "badge",
        "color": "#FFFFFF",
        "uid": "U4954D65B6",
        "hash": "45ac23d099f73c1ab7a6f95c18371794"
    });
    tpwidget("show");
</script>

<script>
    function toggleFullScreen() {
        if (!document.fullscreenElement && // alternative standard method
                !document.mozFullScreenElement && !document.webkitFullscreenElement) {// current working methods
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullscreen) {
                document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
        }
    }
</script>

@yield('javascript')

@include('common.flash_message')

</body>
</html>
