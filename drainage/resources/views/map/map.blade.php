@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">
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
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=be195b71e866e63bb1688b420135565c"></script>
    <!--引入UI组件库（1.0版本） -->
    <script src="//webapi.amap.com/ui/1.0/main.js"></script>

    <script type="text/javascript">

        //创建地图
        var map = new AMap.Map('map-container',{
            zoom: 13,
            center:[113.658578,34.746427],
            mapStyle:'light',
            resizeEnable: true
        });

        //设置DomLibrary，jQuery或者Zepto
        AMapUI.setDomLibrary($);

        //加载BasicControl，loadUI的路径参数为模块名中 'ui/' 之后的部分
        AMapUI.loadUI(['control/BasicControl'], function(BasicControl) {

            //缩放控件
            map.addControl(new BasicControl.Zoom({
                position: 'rb', //right bottom，右下角
            }));

            //图层切换控件
            map.addControl(new BasicControl.LayerSwitcher({
                position: 'rt' //right top，右上角
            }));

        });

        //标记位置
        var lnglats=[
            [113.655831,34.805647],[113.69394,34.779145],
            [113.606393,34.763634],[113.684671,34.732885],
            [113.610856,34.735142],[113.652398,34.754326],
            [113.637635,34.781118],[113.679864,34.698456]
        ];

        //创建标记位置
        for(var i = 0, marker; i < lnglats.length; i++){
            marker=new AMap.Marker({
                position:lnglats[i],
                map:map
            });
        }

        map.setFitView();

    </script>

@endsection
