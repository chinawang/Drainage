@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>泵站资料管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/station/lists">泵站资料管理</a>
                <em>›</em>
                <span>{{ $station['name'] }}</span>
            </h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        泵站信息
                        {{--<a href="/station/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/station/update/{{ $station['id'] }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('station_number') ? ' has-error' : '' }}">
                                <label for="station_number" class="col-md-4 control-label">编号</label>

                                <div class="col-md-6">
                                    <input id="station_number" type="text" class="form-control" name="station_number" value="{{ $station['station_number'] }}" placeholder="" required disabled="disabled">

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
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $station['name'] }}" placeholder="" required disabled="disabled">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label for="type" class="col-md-4 control-label">泵站类型</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $station['type'] }}" placeholder="" required disabled="disabled">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                <label for="address" class="col-md-4 control-label">详细地址</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address" value="{{ $station['address'] }}" placeholder="" required disabled="disabled">

                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="point" class="col-md-4 control-label">坐标</label>

                                <div class="col-md-3">
                                    <input id="lng" type="text" class="form-control" name="lng"
                                           placeholder="经度" value="{{ $station['lng'] }}" required  disabled="disabled">
                                </div>
                                <div class="col-md-3">
                                    <input id="lat" type="text" class="form-control" name="lat"
                                           placeholder="维度" value="{{ $station['lat'] }}" required  disabled="disabled">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="role" class="col-md-4 control-label">工作人员</label>

                                <div class="col-md-6">
                                    @if(empty($station['assignEmployees']))
                                        无
                                    @else
                                        @foreach($station['assignEmployees'] as $assignEmployee)
                                            <a href="/employee/info/{{ $assignEmployee['id'] }}" class="btn btn-default btn-xs">{{ $assignEmployee['name'] }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

