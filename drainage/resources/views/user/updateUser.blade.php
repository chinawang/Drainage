@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/user/user.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>账户管理</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        编辑用户
                        <a href="javascript:history.back(-1)" class="btn-link">返回</a>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="#">

                            <div class="form-group{{ $errors->has('employee_number') ? ' has-error' : '' }}">
                                <label for="employee_number" class="col-md-4 control-label">编号</label>

                                <div class="col-md-6">
                                    <input id="employee_number" type="text" class="form-control" name="employee_number" value="{{ old('employee_number') }}" placeholder="请输入员工编号" required autofocus>

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
                                    <input id="realname" type="text" class="form-control" name="realname" value="{{ old('realname') }}" placeholder="请输入真实姓名" required autofocus>

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
                                    <input id="office" type="text" class="form-control" name="office" value="{{ old('office') }}" placeholder="请输入用户职务" required autofocus>

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
                                    <input id="contact" type="text" class="form-control" name="contact" value="{{ old('contact') }}" placeholder="请输入用户联系方式" required autofocus>

                                    @if ($errors->has('contact'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">登录账号</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="请输入登录账号" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">登录密码</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" placeholder="请输入登录密码" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
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
