@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/error/error.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel"
                     id="content-weather">
                    <div class="panel-body custom-panel-body">
                        <div style="text-align: center;margin-top: 40px">
                            <img style="width: 200px" src="/img/error/sorry.jpg">
                        </div>
                        <div style="text-align: center;margin-top: 40px">
                            <img style="width: 100px" src="/img/error/404.png">
                        </div>
                        <div style="text-align: center;margin-top: 40px;color: #75757d;">很抱歉, 系统异常, 请稍后重试。</div>
                        <div style="text-align: center;margin-top: 20px;margin-bottom: 20px"><a href="{{ url('/') }}">返回首页</a></div>
                        <div style="text-align: center;margin-top: 20px;margin-bottom: 20px;color: #DBDBDB;">Error: {{ $message }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

