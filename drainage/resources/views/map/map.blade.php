@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">
    <style type="text/css">
        body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
        #allmap{width:100%;height:600px;}

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
                    {{--<div class="panel-heading">--}}
                    {{--天气--}}
                    {{--</div>--}}
                    <div class="panel-body custom-panel-body">
                        <div id="allmap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=mZk3RUNMpSwneByXW7v5KubwZiXuCgmY"></script>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>

    <script type="text/javascript">
        // 百度地图API功能
        map = new BMap.Map("allmap");
        map.centerAndZoom("北京",13);
        var data_info = [[116.417854, 39.921988, "地址：北京市东城区王府井大街88号乐天银泰百货八层"],
            [116.406605, 39.921585, "地址：北京市东城区东华门大街"],
            [116.412222, 39.912345, "地址：北京市东城区正义路甲5号"]
        ];
        var opts = {
            width: 250,     // 信息窗口宽度
            height: 80,     // 信息窗口高度
            title: "信息窗口", // 信息窗口标题
            enableMessage: true//设置允许信息窗发送短息
        };
        for (var i = 0; i < data_info.length; i++) {
            var marker = new BMap.Marker(new BMap.Point(data_info[i][0], data_info[i][1]));  // 创建标注
            var content = data_info[i][2];
            map.addOverlay(marker);               // 将标注添加到地图中
            addClickHandler(content, marker);
        }
        function addClickHandler(content, marker) {
            marker.addEventListener("click", function (e) {
                        openInfo(content, e)
                    }
            );
        }
        function openInfo(content, e) {
            var p = e.target;
            var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
            var infoWindow = new BMap.InfoWindow(content, opts);  // 创建信息窗口对象
            map.openInfoWindow(infoWindow, point); //开启信息窗口
        }
    </script>

@endsection
