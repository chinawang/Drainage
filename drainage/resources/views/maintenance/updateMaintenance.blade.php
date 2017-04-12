@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/maintenance/maintenance.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>设备维修管理</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        编辑维修记录
                        <a href="/maintenance/lists" class="btn-link">返回</a>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/maintenance/update/{{ $maintenance['id'] }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('station') ? ' has-error' : '' }}">
                                <label for="station" class="col-md-4 control-label">所属泵站</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="station_id">
                                        @foreach ($stations as $station)
                                            <option value="{{ $station['id'] }}" {{$maintenance['station_id'] == $station['id'] ? 'selected="selected"' : '';}}>{{ $station['name'] }}</option>
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
                                    <select class="form-control" id="select" name="equipment_id">
                                        @foreach ($equipments as $equipment)
                                            <option value="{{ $equipment['id'] }}" {{$maintenance['equipment_id'] == $equipment['id'] ? 'selected="selected"' : '';}}>{{ $equipment['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('equipment'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('equipment') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('failure_reason') ? ' has-error' : '' }}">
                                <label for="failure_reason" class="col-md-4 control-label">故障原因</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="failure_reason" value="{{ $maintenance['failure_reason'】 }}" placeholder="请输入故障原因" required >

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

                            <div class="form-group{{ $errors->has('repairer') ? ' has-error' : '' }}">
                                <label for="repairer" class="col-md-4 control-label">维修人</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="">
                                        @foreach ($users as $user)
                                            <option value="{{ $user['id'] }}" {{$maintenance['repairer_id'] == $user['id'] ? 'selected="selected"' : '';}}>{{ $user['realname'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('repairer'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('repairer') }}</strong>
                                    </span>
                                    @endif
                                </div>
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
