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

    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=be195b71e866e63bb1688b420135565c"></script>

    <script type="text/javascript">

        var map = new AMap.Map('map-container',{
            zoom: 15,
            resizeEnable: true
        });

        map.setCity("郑州");

    </script>

@endsection
