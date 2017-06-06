@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>系统日志</span>

            </h2>
        </div>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-title">
                                日志列表
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($logs[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th>时间</th>
                                    <th>操作者</th>
                                    <th>内容</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log['created_at'] }}</td>
                                        <td>{{ $log['name'] }}</td>
                                        <td>{{ $log['log'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $logs->render() !!}
                            </div>
                        @else
                            <div class="well" style="text-align: center; padding: 100px;">
                                暂无内容
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
