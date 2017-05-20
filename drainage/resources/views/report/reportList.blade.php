@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>泵站设备实时报警状态</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>泵站统计报表</span>

            </h2>
        </div>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    {{--<div class="panel-heading">--}}
                       {{----}}
                    {{--</div>--}}
                    <div class="panel-body custom-panel-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#water" data-toggle="tab" aria-expanded="false">泵站水位统计</a></li>
                            <li class=""><a href="#run" data-toggle="tab" aria-expanded="true">设备运行统计</a></li>
                            <li class=""><a href="#fail" data-toggle="tab" aria-expanded="true">设备故障统计</a></li>
                            <li class=""><a href="#mantenance" data-toggle="tab" aria-expanded="true">设备维修统计</a></li>

                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade active in" id="water">
                                <div class="well" style="text-align: center; padding: 100px;">
                                    暂无内容
                                </div>
                            </div>
                            <div class="tab-pane fade" id="run">
                                <div class="well" style="text-align: center; padding: 100px;">
                                    暂无内容
                                </div>
                            </div>
                            <div class="tab-pane fade" id="fail">
                                <div class="well" style="text-align: center; padding: 100px;">
                                    暂无内容
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mantenance">
                                <div class="well" style="text-align: center; padding: 100px;">
                                    暂无内容
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

@endsection

