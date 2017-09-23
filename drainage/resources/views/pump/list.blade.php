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
                <span>泵组抽水量管理</span>

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
                                泵组抽水量列表
                            </div>
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'pump-add'))
                                <div class="col-md-6 col-btn">
                                    <a href="/pump/add" class="btn btn-primary btn-sm">添加泵组抽数量</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">

                        @if (!empty($pumps[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    {{--<th style="width: 80px">编号</th>--}}
                                    <th>所属泵站</th>
                                    <th>1号泵抽水量(m³/h)</th>
                                    <th>2号泵抽水量(m³/h)</th>
                                    <th>3号泵抽水量(m³/h)</th>
                                    <th>4号泵抽水量(m³/h)</th>
                                    <th>5号泵抽水量(m³/h)</th>
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'pump-edit'))
                                        <th>操作</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pumps as $pump)
                                    <tr>
                                        <td>{{ $pump['station_name'] }}</td>
                                        <td>{{ $pump['flux1'] }}</td>
                                        <td>{{ $pump['flux2'] }}</td>
                                        <td>{{ $pump['flux3'] }}</td>
                                        <td>{{ $pump['flux4'] }}</td>
                                        <td>{{ $pump['flux5'] }}</td>

                                        @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'pump-edit'))
                                            <td>
                                                <a href="/pump/edit/{{ $pump['id'] }}"
                                                   class="btn btn-link">编辑</a>
                                                <a href="#" class="btn btn-link btn-delete-station"
                                                   id="btn-delete-alert-{{ $pump['id'] }}">删除</a>
                                                <form role="form" method="POST" style="display: none"
                                                      action="/pump/delete/{{ $pump['id'] }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" id="btn-delete-submit-{{ $pump['id'] }}">
                                                    </button>
                                                </form>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $$pumps->render() !!}
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

    @foreach ($pumps as $pump)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $pump['id'] }}').on("click", function () {
                swal({
                            title: "确认删除吗?",
                            text: "删除之后,将无法恢复!",
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonText: "取消",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "确认删除",
                            closeOnConfirm: false
                        },
                        function () {
                            $("#btn-delete-submit-{{ $pump['id'] }}").click();
                        })
            });
        </script>
    @endforeach

@endsection
