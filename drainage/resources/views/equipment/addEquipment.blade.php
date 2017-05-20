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
                <span>添加设备</span>
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
                        添加设备
                        {{--<a href="/equipment/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/equipment/store">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('station') ? ' has-error' : '' }}">
                                <label for="station" class="col-md-4 control-label">所属泵站</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="station_id">
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

                            <div class="form-group{{ $errors->has('equipment_number') ? ' has-error' : '' }}">
                                <label for="equipment_number" class="col-md-4 control-label">设备编号</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="equipment_number" value="{{ old('equipment_number') }}" placeholder="请输入设备编号" required >

                                    @if ($errors->has('equipment_number'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('equipment_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">设备名称</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="请输入设备名称" required >

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
                                    <input type="text" class="form-control" name="type" value="{{ old('type') }}" placeholder="请输入设备型号" required >

                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('producer') ? ' has-error' : '' }}">
                                <label for="producer" class="col-md-4 control-label">制造单位</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="producer" value="{{ old('producer') }}" placeholder="请输入制造单位" required >

                                    @if ($errors->has('producer'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('producer') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                                <label for="department" class="col-md-4 control-label">使用部门</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="department" value="{{ old('department') }}" placeholder="请输入使用部门" required >

                                    @if ($errors->has('department'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('department') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('leader') ? ' has-error' : '' }}">
                                <label for="leader" class="col-md-4 control-label">负责人</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="leader_id">
                                        <option value="" selected="selected" style="display: none">选择负责人</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user['id'] }}">{{ $user['realname'] }}</option>
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
                                        <option value="" selected="selected" style="display: none">选择管理员</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user['id'] }}">{{ $user['realname'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('custodian'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('custodian') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                                <label for="quantity" class="col-md-4 control-label">数量</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="quantity" value="{{ old('quantity') }}" placeholder="请输入设备数量" required >

                                    @if ($errors->has('quantity'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('quantity') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('alteration') ? ' has-error' : '' }}">
                                <label for="alteration" class="col-md-4 control-label">变更情况</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="alteration" value="{{ old('alteration') }}" placeholder="请输入设备变更情况" required >

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
