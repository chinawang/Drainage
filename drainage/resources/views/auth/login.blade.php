@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/common/login.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>登录</span>--}}
@endsection

@section('content')
    <div class="container">
        <div class="row" style="margin-top: 100px">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">登录</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">账号</label>

                                <div class="col-md-6">
                                    <input id="name" type="name" class="form-control" name="name"
                                           value="{{ old('name') }}" required autofocus placeholder="请输入登录账号">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">密码</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password"
                                           required placeholder="请输入密码">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"
                                                   name="remember" {{ old('remember') ? 'checked' : '' }}> 记住我
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button id="login" type="submit" class="btn btn-primary btn-custom"
                                            data-loading-text="加载中..." autocomplete="off">
                                        登录
                                    </button>

                                    {{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
                                    {{--忘记密码?--}}
                                    {{--</a>--}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $('#login').on('click', function () {
            var btn = $(this).button('loading');
            // business logic...
            setTimeout(function () { btn.button('reset'); },2000);
        })
    </script>
@endsection
