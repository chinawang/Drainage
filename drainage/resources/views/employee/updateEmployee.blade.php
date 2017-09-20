@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/employee/employee.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>工作人员管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/employee/lists">工作人员管理</a>
                <em>›</em>
                <span>{{ $employee['name'] }}</span>
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
                        编辑工作人员
                        {{--<a href="/equipment/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="/employee/update/{{ $employee['id'] }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">姓名</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name"
                                           value="{{ $employee['name'] }}" placeholder="请输入姓名" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                                <label for="number" class="col-md-4 control-label">编号</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="number" value="{{ $employee['name'] }}"
                                           placeholder="请输入员工编号" required>

                                    @if ($errors->has('number'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('job') ? ' has-error' : '' }}">
                                <label for="job" class="col-md-4 control-label">职务</label>

                                <div class="col-md-6">
                                    <input id="job" type="text" class="form-control" name="job"
                                           value="{{ $employee['job'] }}" placeholder="请输入工作职务"
                                    >

                                    @if ($errors->has('job'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('job') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                                <label for="department" class="col-md-4 control-label">部门</label>

                                <div class="col-md-6">
                                    <input id="department" type="text" class="form-control" name="department"
                                           value="{{ $employee['department'] }}" placeholder="请输入所属部门" >

                                    @if ($errors->has('department'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('department') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('cellphone') ? ' has-error' : '' }}">
                                <label for="cellphone" class="col-md-4 control-label">手机</label>

                                <div class="col-md-6">
                                    <input id="cellphone" type="text" class="form-control" name="cellphone"
                                           value="{{ $employee['cellphone'] }}" placeholder="请输入手机号" >

                                    @if ($errors->has('cellphone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('cellphone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('voip') ? ' has-error' : '' }}">
                                <label for="voip" class="col-md-4 control-label">IP电话</label>

                                <div class="col-md-6">
                                    <input id="voip" type="text" class="form-control" name="voip"
                                           value="{{ $employee['voip'] }}" placeholder="请输入IP电话" >

                                    @if ($errors->has('voip'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('voip') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('call') ? ' has-error' : '' }}">
                                <label for="call" class="col-md-4 control-label">语音电话</label>

                                <div class="col-md-6">
                                    <input id="call" type="text" class="form-control" name="call"
                                           value="{{ $employee['call'] }}" placeholder="请输入语音电话" >

                                    @if ($errors->has('call'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('call') }}</strong>
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
