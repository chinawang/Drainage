@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/failure/failure.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>设备故障管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/failure/lists">设备故障管理</a>
                <em>›</em>
                <span>添加故障记录</span>
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
                        添加故障记录
                        {{--<a href="/failure/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/failure/store">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('station') ? ' has-error' : '' }}">
                                <label for="station" class="col-md-4 control-label">所属泵站</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="station_id" required>
                                        <option value="" selected="selected" style="display: none">选择泵站</option>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station['id'] }}">{{ $station['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('station'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('station') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('equipment') ? ' has-error' : '' }}">
                                <label for="equipment" class="col-md-4 control-label">故障设备及其他</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="equipment" value="{{ old('equipment') }}" placeholder="请输入故障设备" required >

                                    @if ($errors->has('equipment'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('equipment') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                {{--<div class="col-md-6">--}}
                                    {{--<select class="form-control" id="select" name="equipment_id" required>--}}
                                        {{--<option value="" selected="selected" style="display: none">选择设备</option>--}}
                                        {{--@foreach ($equipments as $equipment)--}}
                                            {{--<option value="{{ $equipment['id'] }}">{{ $equipment['name'] }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--@if ($errors->has('equipment'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('equipment') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            </div>

                            <div class="form-group{{ $errors->has('failure_type') ? ' has-error' : '' }}">
                                <label for="failure_type" class="col-md-4 control-label">故障类型</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="failure_type" value="{{ old('failure_type') }}" placeholder="请输入故障类型" required >

                                    @if ($errors->has('failure_type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('failure_type') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('failure_description') ? ' has-error' : '' }}">
                                <label for="failure_description" class="col-md-4 control-label">故障描述</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="failure_description" value="{{ old('failure_description') }}" placeholder="请输入故障描述" required >

                                    @if ($errors->has('failure_description'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('failure_description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('	equipment_status') ? ' has-error' : '' }}">
                                <label for="equipment" class="col-md-4 control-label">设备状态</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="equipment_status">
                                        <option value="运行中">运行中</option>
                                        <option value="已停止运行">已停止运行</option>
                                    </select>
                                    @if ($errors->has('	equipment_status'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('	equipment_status') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('reporter') ? ' has-error' : '' }}">
                                <label for="reporter" class="col-md-4 control-label">报修人</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="reporter" value="{{ old('reporter') }}" placeholder="请输入报修人" required >

                                    @if ($errors->has('reporter'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('reporter') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                {{--<div class="col-md-6">--}}
                                    {{--<select class="form-control" id="select" name="reporter_id" required>--}}
                                        {{--<option value="" selected="selected" style="display: none">选择报修人</option>--}}
                                        {{--@foreach ($employees as $employee)--}}
                                            {{--<option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--@if ($errors->has('reporter'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('reporter') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            </div>

                            <div class="form-group {{ $errors->has('report_at') ? ' has-error' : '' }}">
                                <label for="report_at" class="col-md-4 control-label">报修时间</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="datetimepicker" name="report_at" value="{{ old('report_at') }}" placeholder="请输入报修时间" required >

                                    @if ($errors->has('report_at'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('report_at') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary btn-custom">
                                        <span class="glyphicon glyphicon-ok-sign"></span>
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
