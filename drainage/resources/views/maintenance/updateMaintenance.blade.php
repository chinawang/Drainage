@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/maintenance/maintenance.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>设备维修管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/failure/lists">设备故障管理</a>
                <em>›</em>
                <span>编辑维修记录</span>
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
                        编辑维修记录
                        {{--<a href="/failure/maintenance/lists/{{ $maintenance['failure_id'] }}" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/maintenance/update/{{ $maintenance['id'] }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="failure_id" value="{{ $maintenance['failure_id'] }}">
                            <div class="form-group{{ $errors->has('station') ? ' has-error' : '' }}">
                                <label for="station" class="col-md-4 control-label">所属泵站</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="station_id" required>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station['id'] }}" {{$maintenance['station_id'] == $station['id'] ? 'selected="selected"' : ''}}>{{ $station['name'] }}</option>
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
                                <label for="equipment" class="col-md-4 control-label">故障设备</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="equipment" value="{{ $maintenance['equipment'] }}" placeholder="请输入故障设备" required >

                                    @if ($errors->has('equipment'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('equipment') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                {{--<div class="col-md-6">--}}
                                    {{--<select class="form-control" id="select" name="equipment_id" required>--}}
                                        {{--@foreach ($equipments as $equipment)--}}
                                            {{--<option value="{{ $equipment['id'] }}" {{$maintenance['equipment_id'] == $equipment['id'] ? 'selected="selected"' : ''}}>{{ $equipment['name'] }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--@if ($errors->has('equipment'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('equipment') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            </div>

                            <div class="form-group{{ $errors->has('failure_reason') ? ' has-error' : '' }}">
                                <label for="failure_reason" class="col-md-4 control-label">故障原因</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="failure_reason" value="{{ $maintenance['failure_reason'] }}" placeholder="请输入故障原因" required >

                                    @if ($errors->has('failure_reason'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('failure_reason') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('repair_solution') ? ' has-error' : '' }}">
                                <label for="repair_solution" class="col-md-4 control-label">解决办法</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="repair_solution" value="{{ $maintenance['repair_solution'] }}" placeholder="请输入解决办法" required >

                                    @if ($errors->has('repair_solution'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('repair_solution') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('result') ? ' has-error' : '' }}">
                                <label for="result" class="col-md-4 control-label">维修结果</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="result" value="{{ $maintenance['result'] }}" placeholder="请输入维修结果"  >

                                    @if ($errors->has('result'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('result') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('repairer') ? ' has-error' : '' }}">
                                <label for="repairer" class="col-md-4 control-label">维修人</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="repairer" value="{{ $maintenance['repairer'] }}" placeholder="请输入维修人" required >

                                    @if ($errors->has('repairer'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('repairer') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                {{--<div class="col-md-6">--}}
                                    {{--<select class="form-control" id="select" name="repairer_id" required>--}}
                                        {{--@foreach ($employees as $employee)--}}
                                            {{--<option value="{{ $employee['id'] }}" {{$maintenance['repairer_id'] == $employee['id'] ? 'selected="selected"' : ''}}>{{ $employee['name'] }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--@if ($errors->has('repairer'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('repairer') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            </div>

                            <div class="form-group{{ $errors->has('repair_at') ? ' has-error' : '' }}">
                                <label for="repair_at" class="col-md-4 control-label">维修时间</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="datetimepicker" name="repair_at" value="{{ $maintenance['repair_at'] }}" placeholder="请输入维修时间" required >

                                    @if ($errors->has('repair_at'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('repair_at') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                <label for="remark" class="col-md-4 control-label">备注</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="remark" value="{{ $maintenance['remark'] }}" placeholder="请输入备注"  >

                                    @if ($errors->has('remark'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('remark') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('	repair_process') ? ' has-error' : '' }}">
                                <label for="equipment" class="col-md-4 control-label">维修进度</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="repair_process">
                                        <option value="1" {{$maintenance['repair_process'] == 0 ? 'selected="selected"' : ''}}>报修</option>
                                        <option value="1" {{$maintenance['repair_process'] == 1 ? 'selected="selected"' : ''}}>维修中</option>
                                        <option value="2" {{$maintenance['repair_process'] == 2 ? 'selected="selected"' : ''}}>维修完成</option>
                                    </select>
                                    @if ($errors->has('	repair_process'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('	repair_process') }}</strong>
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
