@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">
    <link href="{{ asset('css/common/home.css') }}" rel="stylesheet">

    <style>
        html,
        body,
        #map-container {
            width: 100%;
            height: 100%;
            margin: 0px;
        }

        .amap-marker-label {
            font-size: 13px;
            border: 1px solid orange;
            background: #fff;
            border-radius: 10px 0 0 0;
            color: #690441;
        }

    </style>

@endsection

@section('subtitle')
    {{--<span>地图</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>泵站地图</span>

            </h2>
        </div>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-body custom-panel-body">
                        <div id="map-container" style="width: 100%; height: 600px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <!--引入高德地图JSAPI Web服务Key-->
    <script type="text/javascript"
            src="http://webapi.amap.com/maps?v=1.3&key=be195b71e866e63bb1688b420135565c"></script>
    <!--引入UI组件库（1.0版本） -->
    <script src="//webapi.amap.com/ui/1.0/main.js"></script>

    <script type="text/javascript">

        //创建地图
        var map = new AMap.Map('map-container', {
            zoom: 12,
            center: [113.658578, 34.746427],
            mapStyle: 'normal',
            resizeEnable: true
        });

        //设置DomLibrary，jQuery或者Zepto
        AMapUI.setDomLibrary($);

        //加载BasicControl，loadUI的路径参数为模块名中 'ui/' 之后的部分
        AMapUI.loadUI(['control/BasicControl'], function (BasicControl) {

            //缩放控件
            map.addControl(new BasicControl.Zoom({
                position: 'rt' //right top，右上角
            }));

        });

        var defaultIconStyle = 'darkblue', //默认的图标样式
                hoverIconStyle = 'blue', //鼠标hover时的样式
                selectedIconStyle = 'lightblue' //选中时的图标样式
                ;

        //加载SimpleMarker
        AMapUI.loadUI(['overlay/SimpleMarker', 'overlay/SimpleInfoWindow'], function (SimpleMarker, SimpleInfoWindow) {

            var infoWindow = new SimpleInfoWindow({offset: new AMap.Pixel(0, -40)});

                    @foreach ($stations as $station)
            var marker = new SimpleMarker({
                        //使用内置的iconStyle
                        iconStyle: defaultIconStyle,

                        //图标文字
                        iconLabel: {
                            //A,B,C.....
                            innerHTML: '泵',
                            style: {
                                //颜色, #333, red等等，这里仅作示例，取iconStyle中首尾相对的颜色
                                color: '#ffffff'
                            }
                        },

                        //显示定位点
                        //showPositionPoint:true,

                        map: map,
                        position: [{{ $station['lng'] }},{{ $station['lat'] }}],

                        //Marker的label(见http://lbs.amap.com/api/javascript-api/reference/overlay/#Marker)
                        {{--label: {--}}
                            {{--content: '{{ $station['station_number'] }}.{{ $station['name'] }}',--}}
                            {{--offset: new AMap.Pixel(27, 25)--}}
                        {{--}--}}
                        label: {
                            content: '{{ $station['type'] }}',
                            offset: new AMap.Pixel(27, 25)
                        }
                    });

            marker.emit('mouseout', {target: marker});

            marker.on('mouseout', function (e) {
                e.target.setIconStyle(defaultIconStyle);
            });

            marker.emit('mouseover', {target: marker});

            marker.on('mouseover', function (e) {
                e.target.setIconStyle(hoverIconStyle);
                infoWindow.setInfoTitle('<strong style="margin: 10px;">{{ $station['station_number'] }}.{{ $station['name'] }}({{ $station['type'] }})</strong>');
                var contentHtml = '<div class="row" style="width: 360px;margin: 10px 0;">' +
                        '<div class="col-md-3 col-md-offset-0" style="height: 60px;line-height: 60px">' +
                        '<img src="/img/map/dot_{{ $station['status'] }}.png" style="width: 32px;height: 32px;">' +
                        '</div>' +
                        '<div class="col-md-6 col-md-offset-0" style="margin-left: -5px">' +
                        '<div style="font-size: 14px;color:#4a4a4a">已启动泵组: {{ $station['runPump'] }}组</div>' +
                        '<div style="font-size: 14px;color:#4a4a4a">未启动泵组: {{ $station['stopPump'] }}组</div>' +
                        '<div style="font-size: 14px;color:#4a4a4a">涵洞水位: {{ $station['culvertWater'] }}米</div>' +
                        '</div>' +
                        '<div class="col-md-3 col-md-offset-0">' +
                        '<a href="/station/runDetail/{{ $station['id'] }}" class="btn-link" style="font-size: 12px;height: 60px;line-height: 60px">运行详情</a>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row" style="width: 360px;margin-top: 10px ;">' +
                        '<div class="col-md-12 col-md-offset-0">' +
                        '<div class="panel panel-default custom-panel">' +
                        '<div class="panel-body custom-panel-body">' +
                        '<ul class="main-menu">' +
                        '<li style="font-size: 12px;height: 40px;line-height: 40px;width: 33.33%;">' +
                        '<a href="" class="menu-item">' +
                        '<span>泵站设备</span>' +
                        '</a>' +
                        '</li>' +
                        '<li style="font-size: 12px;height: 40px;line-height: 40px;width: 33.33%;">' +
                        '<a href="" class="menu-item">' +
                        '<span>报警信息</span>' +
                        '</a>' +
                        '</li>' +
                        '<li style="font-size: 12px;height: 40px;line-height: 40px;width: 33.33%;">' +
                        '<a href="" class="menu-item">' +
                        '<span>运行报表</span>' +
                        '</a>' +
                        '</li>' +
                        '<li style="font-size: 12px;height: 40px;line-height: 40px;width: 33.33%;">' +
                        '<a href="" class="menu-item">' +
                        '<span>电流统计</span>' +
                        '</a>' +
                        '</li>' +
                        '<li style="font-size: 12px;height: 40px;line-height: 40px;width: 33.33%;">' +
                        '<a href="" class="menu-item">' +
                        '<span>水位统计</span>' +
                        '</a>' +
                        '</li>' +
                        '<li style="font-size: 12px;height: 40px;line-height: 40px;width: 33.33%;">' +
                        '<a href="" class="menu-item">' +
                        '<span>下属人员</span>' +
                        '</a>' +
                        '</li>' +
                        '</ul>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                infoWindow.setInfoBody(contentHtml);
                infoWindow.open(map, e.target.getPosition());
            });

            @endforeach

        });

        map.setFeatures(['road', 'bg'])//要素显示:道路、背景

        map.setFitView();

    </script>

    <script type="text/javascript">

        function getStations() {
            $.ajax({
                type: 'get',
                url: '/map/stations',
                data: '_token = <?php echo csrf_token() ?>',
                success: function (data) {
                    setMapData(data.stations);
                }
            });
        }

        function setMapData(data){
            setInterval("getStations()", 10000);
        }

    </script>

@endsection
