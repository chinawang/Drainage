@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/user/user.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>账户管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/user/profile/{{ $user['id'] }}">个人信息</a>
                <em>›</em>
                <span>编辑</span>
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
                        编辑个人信息
                        {{--<a href="/user/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/user/updateProfile/{{ $user['id'] }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('employee_number') ? ' has-error' : '' }}">
                                <label for="employee_number" class="col-md-4 control-label">编号</label>

                                <div class="col-md-6">
                                    <input id="employee_number" type="text" class="form-control" name="employee_number" value="{{ $user['employee_number'] }}" placeholder="" disabled="disabled" required >

                                    @if ($errors->has('employee_number'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('employee_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('realname') ? ' has-error' : '' }}">
                                <label for="realname" class="col-md-4 control-label">姓名</label>

                                <div class="col-md-6">
                                    <input id="realname" type="text" class="form-control" name="realname" value="{{ $user['realname'] }}" placeholder="请输入真实姓名" required >

                                    @if ($errors->has('realname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('realname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('office') ? ' has-error' : '' }}">
                                <label for="office" class="col-md-4 control-label">职务</label>

                                <div class="col-md-6">
                                    <input id="office" type="text" class="form-control" name="office" value="{{ $user['office'] }}" placeholder="请输入用户职务" required >

                                    @if ($errors->has('office'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('office') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
                                <label for="contact" class="col-md-4 control-label">联系方式</label>

                                <div class="col-md-6">
                                    <input id="contact" type="text" class="form-control" name="contact" value="{{ $user['contact'] }}" placeholder="请输入用户联系方式" required >

                                    @if ($errors->has('contact'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
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
