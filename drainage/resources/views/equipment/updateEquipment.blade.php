@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/equipment/equipment.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>设备资料管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/equipment/lists">设备资料管理</a>
                <em>›</em>
                <span>{{ $equipment['name'] }}</span>
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
                        编辑设备
                        {{--<a href="/equipment/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="/equipment/update/{{ $equipment['id'] }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('station') ? ' has-error' : '' }}">
                                <label for="station" class="col-md-4 control-label">所属泵站</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="station_id">
                                        @foreach ($stations as $station)
                                            <option value="{{ $station['id'] }}" {{$equipment['station_id'] == $station['id'] ? 'selected="selected"' : ''}}>{{ $station['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('station'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('station') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{--<div class="form-group{{ $errors->has('equipment_number') ? ' has-error' : '' }}">--}}
                                {{--<label for="equipment_number" class="col-md-4 control-label">设备编号</label>--}}

                                {{--<div class="col-md-6">--}}
                                    {{--<input type="text" class="form-control" name="equipment_number"--}}
                                           {{--value="{{ $equipment['equipment_number'] }}" placeholder="请输入设备编号" required>--}}

                                    {{--@if ($errors->has('equipment_number'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('equipment_number') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">设备名称</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ $equipment['name'] }}"
                                           placeholder="请输入设备名称" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label for="type" class="col-md-4 control-label">型号</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="type" value="{{ $equipment['type'] }}"
                                           placeholder="请输入设备型号" required>

                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('capacity') ? ' has-error' : '' }}">
                                <label for="capacity" class="col-md-4 control-label">容量(选填)</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="capacity" value="{{ $equipment['capacity'] }}" placeholder="请输入设备容量"  >

                                    @if ($errors->has('capacity'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('capacity') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('flux') ? ' has-error' : '' }}">
                                <label for="flux" class="col-md-4 control-label">泵机流量(选填)</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="flux" value="{{ $equipment['flux'] }}" placeholder="请输入泵机流量"  >

                                    @if ($errors->has('flux'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('flux') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <label for="flux" class="col-md-0 control-label">m³/h</label>
                            </div>

                            <div class="form-group{{ $errors->has('range') ? ' has-error' : '' }}">
                                <label for="range" class="col-md-4 control-label">泵机扬程(选填)</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="range" value="{{ $equipment['range'] }}" placeholder="请输入泵机扬程"  >

                                    @if ($errors->has('range'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('range') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <label for="flux" class="col-md-0 control-label">m</label>
                            </div>

                            <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                                <label for="quantity" class="col-md-4 control-label">数量</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="quantity" value="{{ $equipment['quantity'] }}" placeholder="请输入设备数量" required >

                                    @if ($errors->has('quantity'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('purchase_time') ? ' has-error' : '' }}">
                                <label for="purchase_time" class="col-md-4 control-label">购置时间</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="datepicker" name="purchase_time" value="{{ $equipment['purchase_time'] }}" placeholder="请选择购置时间" required >

                                    @if ($errors->has('purchase_time'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('purchase_time') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{--<div class="form-group{{ $errors->has('producer') ? ' has-error' : '' }}">--}}
                                {{--<label for="producer" class="col-md-4 control-label">制造单位</label>--}}

                                {{--<div class="col-md-6">--}}
                                    {{--<input type="text" class="form-control" name="producer"--}}
                                           {{--value="{{ $equipment['producer'] }}" placeholder="请输入制造单位" required>--}}

                                    {{--@if ($errors->has('producer'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('producer') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">--}}
                                {{--<label for="department" class="col-md-4 control-label">使用部门</label>--}}

                                {{--<div class="col-md-6">--}}
                                    {{--<input type="text" class="form-control" name="department"--}}
                                           {{--value="{{ $equipment['department'] }}" placeholder="请输入使用部门" required>--}}

                                    {{--@if ($errors->has('department'))--}}
                                        {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('department') }}</strong>--}}
                                    {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group{{ $errors->has('leader') ? ' has-error' : '' }}">
                                <label for="leader" class="col-md-4 control-label">负责人</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="leader_id">
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee['id'] }}" {{$equipment['leader_id'] == $employee['id'] ? 'selected="selected"' : ''}}>{{ $employee['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('leader'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('leader') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('custodian') ? ' has-error' : '' }}">
                                <label for="custodian" class="col-md-4 control-label">设备管理员</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="custodian_id">
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee['id'] }}" {{$equipment['custodian_id'] == $employee['id'] ? 'selected="selected"' : ''}}>{{ $employee['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('custodian'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('custodian') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('alteration') ? ' has-error' : '' }}">
                                <label for="alteration" class="col-md-4 control-label">备注(选填)</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="alteration"
                                           value="{{ $equipment['alteration'] }}" placeholder="请输入备注" >

                                    @if ($errors->has('alteration'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('alteration') }}</strong>
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
