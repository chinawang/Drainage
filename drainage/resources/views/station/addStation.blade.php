@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>泵站资料管理</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        添加泵站
                        <a href="/station/lists" class="btn-link">返回</a>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/station/store">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('station_number') ? ' has-error' : '' }}">
                                <label for="station_number" class="col-md-4 control-label">编号</label>

                                <div class="col-md-6">
                                    <input id="station_number" type="text" class="form-control" name="station_number"
                                           value="{{ old('station_number') }}" placeholder="请输入泵站编号" required>

                                    @if ($errors->has('station_number'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('station_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">名称</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ old('name') }}" placeholder="请输入泵站名称" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                <label for="address" class="col-md-4 control-label">详细地址</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address"
                                           value="{{ old('address') }}" placeholder="请输入泵站详细地址"
                                           required>

                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div>
                                    <a href="" class="btn btn-primary btn-sm" onclick="geocoder()">搜索坐标</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="point" class="col-md-4 control-label">坐标</label>

                                <div class="col-md-3">
                                    <input id="lat" type="text" class="form-control" name="lat" value="{{ old('lat') }}"
                                           placeholder="经度" required disabled="disabled">
                                </div>
                                <div class="col-md-3">
                                    <input id="lang" type="text" class="form-control" name="lang"
                                           value="{{ old('lang') }}" placeholder="维度" required disabled="disabled">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="point" class="col-md-4 control-label">选取坐标</label>

                                <div class="col-md-6">
                                    <div style="width: 100%;height: 300px" id="map-container">

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary btn-custom">
                                        保存
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <!--引入高德地图JSAPI -->
    <script type="text/javascript"
            src="http://webapi.amap.com/maps?v=1.3&key=be195b71e866e63bb1688b420135565c&plugin=AMap.Geocoder"></script>
    <!--引入UI组件库（1.0版本） -->
    <script src="//webapi.amap.com/ui/1.0/main.js"></script>

    <script type="text/javascript">

        //创建地图
        var map = new AMap.Map('map-container', {
            zoom: 13,
            mapStyle: 'normal',
            resizeEnable: true
        });

        //设置DomLibrary，jQuery或者Zepto
//        AMapUI.setDomLibrary($);

        //加载BasicControl，loadUI的路径参数为模块名中 'ui/' 之后的部分
//        AMapUI.loadUI(['control/BasicControl', 'misc/PositionPicker'], function (BasicControl, PositionPicker) {
//
//            //缩放控件
//            map.addControl(new BasicControl.Zoom({
//                position: 'rt' //right top，右上角
//            }));
//
//            var positionPicker = new PositionPicker({
//                mode: 'dragMap',
//                map: map
//            });
//
//            positionPicker.on('success', function (positionResult) {
//                document.getElementById('lat').value = positionResult.position.getLat();
//                document.getElementById('lang').value = positionResult.position.getLang();
//            });
//
//        });

        function geocoder() {
            var geocoder = new AMap.Geocoder({
                city: "郑州", //城市，默认：“全国”
                radius: 1000 //范围，默认：500
            });
            var address = document.getElementById('address').value;

            //地理编码,返回地理编码结果
            geocoder.getLocation("郑州火车站", function(status, result) {
                if (status === 'complete' && result.info === 'OK') {
                    alert('succcess!');
                    geocoder_CallBack(result);
                }else {
                    alert('fail!');
                }
            });
        }

        function addMarker(i, d) {
            var marker = new AMap.Marker({
                map: map,
                position: [ d.location.getLng(),  d.location.getLat()]
            });
            var infoWindow = new AMap.InfoWindow({
                content: d.formattedAddress,
                offset: {x: 0, y: -30}
            });
            marker.on("mouseover", function(e) {
                infoWindow.open(map, marker.getPosition());
            });
        }

        //地理编码返回结果展示
        function geocoder_CallBack(data) {
            //地理编码结果数组
            var geocode = data.geocodes;
            addMarker(0, geocode[0]);
            map.setFitView();
            document.getElementById("lat").value = geocode[0].location.getLat();
            document.getElementById("lang").value = geocode[0].location.getLang();
        }

        map.setFeatures(['road', 'bg', 'point'])//要素显示:道路、背景、标记

        map.setFitView();

    </script>

@endsection