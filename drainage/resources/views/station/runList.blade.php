@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>泵站实时工作状态</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        泵站工作状态列表
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($stations[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>泵站名称</th>
                                    <th>工作状态</th>
                                    <th>已运行泵组</th>
                                    <th>未运行泵组</th>
                                    <th>涵洞水位</th>
                                    <th>集水池水位</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($stations as $station)
                                    <tr>
                                        <td>{{ $station['station_number'] }}</td>
                                        <td>{{ $station['name'] }}</td>
                                        <td>{{ $station['status'] }}</td>
                                        <td>{{ $station['runPump'] }}</td>
                                        <td>{{ $station['stopPump'] }}</td>
                                        <td>{{ $station['culvertWater'] }}</td>
                                        <td>{{ $station['tankWater'] }}</td>
                                        <td>
                                            <a href="/station/runDetail/{{ $station['id'] }}" class="btn btn-link">运行详细</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $stations->render() !!}
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

@section('javascript')

@endsection
