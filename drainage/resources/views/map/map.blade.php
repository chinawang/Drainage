@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">

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

        .amap-ui-smp-ifwn-info-title {
            border-bottom: 0;
            padding: 10px 10px 0 10px;
        }

        .amap-ui-smp-ifwn-info-content {
            padding: 10px;
        }
    </style>
@endsection

@section('subtitle')
    <span>地图</span>
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

    <!--引入高德地图JSAPI -->
    <script type="text/javascript"
            src="http://webapi.amap.com/maps?v=1.3&key=be195b71e866e63bb1688b420135565c"></script>
    <!--引入UI组件库（1.0版本） -->
    <script src="//webapi.amap.com/ui/1.0/main.js"></script>

    <script type="text/javascript">

        //创建地图
        var map = new AMap.Map('map-container', {
            zoom: 13,
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

        //标记位置
        var lngLats = [
            [113.655831, 34.805647], [113.69394, 34.779145],
            [113.606393, 34.763634], [113.684671, 34.732885],
            [113.610856, 34.735142], [113.652398, 34.754326],
            [113.637635, 34.781118], [113.679864, 34.698456],
            [113.726642, 34.76032], [113.697803, 34.749601],
            [113.671195, 34.73874], [113.617808, 34.74283],
            [113.62519, 34.722798], [113.596007, 34.723926],
            [113.621757, 34.75623], [113.602874, 34.742478]
        ];

        //        //创建标记位置
        //        for(var i = 0, marker; i < lngLats.length; i++){
        //            marker=new AMap.Marker({
        //                position:lnglats[i],
        //                map:map
        //            });
        //        }

        var defaultIconStyle = 'darkblue', //默认的图标样式
                hoverIconStyle = 'blue', //鼠标hover时的样式
                selectedIconStyle = 'lightblue' //选中时的图标样式
                ;

        //加载SimpleMarker
        AMapUI.loadUI(['overlay/SimpleMarker', 'overlay/SimpleInfoWindow'], function (SimpleMarker, SimpleInfoWindow) {

            var infoWindow = new SimpleInfoWindow({offset: new AMap.Pixel(0, -40)});

            for (var i = 0, len = lngLats.length; i < len; i++) {

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
                    position: lngLats[i],

                    //Marker的label(见http://lbs.amap.com/api/javascript-api/reference/overlay/#Marker)
                    label: {
                        content: '金水路泵站',
                        offset: new AMap.Pixel(27, 25)
                    }
                });

                marker.emit('mouseout', {target: marker});

                marker.on('mouseout', function (e) {
                    e.target.setIconStyle(defaultIconStyle);
                });

                marker.emit('mouseover', {target: marker});

                var contentHtml = '<div class="row" style="width: 260px;margin: 10px 0;">' +
                        '<div class="col-md-3 col-md-offset-0">' +
                        '<img src="/img/map/dot_yellow.png" style="width: 32px;height: 32px;">' +
                        '</div>' +
                        '<div class="col-md-6 col-md-offset-0" style="margin-left: -5px">' +
                        '<div style="font-size: 14px;color:#4a4a4a">已启动泵组: 3组</div>' +
                        '<div style="font-size: 14px;color:#4a4a4a">未启动泵组: 2组</div>' +
                        '<div style="font-size: 14px;color:#4a4a4a">水位: 2.8米</div>' +
                        '</div>' +
                        '<div class="col-md-3 col-md-offset-0">' +
                        '<a href="/station/lists" class="btn-link" style="font-size: 12px;height: 40px;line-height: 40px">详情</a>' +
                        '</div>' +
                        '</div>';

                marker.on('mouseover', function (e) {
                    e.target.setIconStyle(hoverIconStyle);
                    infoWindow.setInfoTitle('<strong style="margin: 10px;">金水路泵站</strong>');
                    infoWindow.setInfoBody(contentHtml);
                    infoWindow.open(map, e.target.getPosition());
                });
            }

        });

        map.setFeatures(['road', 'bg'])//要素显示:道路、背景

        map.setFitView();

    </script>

@endsection
