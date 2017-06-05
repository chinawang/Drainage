@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel"
                     id="content-weather">
                    <div class="panel-body custom-panel-body">
                        <div>
                            <img src="/img/error/404.png">
                        </div>
                        <div class="col-md-6 col-md-offset-3">很抱歉,系统异常,请稍后重试。</div>
                        <div class="col-md-6 col-md-offset-3"><a href="{{ url('/') }}">返回首页</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

