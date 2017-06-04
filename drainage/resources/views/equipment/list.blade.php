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
                <span>设备资料管理</span>

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
                                设备列表
                            </div>
                            @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'equip-add'))
                                <div class="col-md-6 col-btn">
                                    <a href="/equipment/add" class="btn btn-primary btn-sm">添加设备</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($equipments[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th style="width: 80px">编号</th>
                                    <th>所属泵站</th>
                                    <th>设备名称</th>
                                    <th>型号</th>
                                    <th>制造单位</th>
                                    <th>使用部门</th>
                                    <th>负责人</th>
                                    <th>设备管理员</th>
                                    <th>数量</th>
                                    <th>变动情况</th>
                                    @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'equip-edit'))
                                        <th>操作</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($equipments as $equipment)
                                    <tr>
                                        <td>{{ $equipment['equipment_number'] }}</td>
                                        <td>{{ $equipment['station_name'] }}</td>
                                        <td>{{ $equipment['name'] }}</td>
                                        <td>{{ $equipment['type'] }}</td>
                                        <td>{{ $equipment['producer'] }}</td>
                                        <td>{{ $equipment['department'] }}</td>
                                        <td>{{ $equipment['leader_name'] }}</td>
                                        <td>{{ $equipment['custodian_name'] }}</td>
                                        <td>{{ $equipment['quantity'] }}</td>
                                        <td>{{ $equipment['alteration'] }}</td>

                                        @if (app('App\Http\Logic\Rbac\RbacLogic')->check(Auth::user()->id, 'equip-edit'))
                                            <td>
                                                <a href="/equipment/edit/{{ $equipment['id'] }}"
                                                   class="btn btn-link">编辑</a>
                                                <a href="#" class="btn btn-link btn-delete-station"
                                                   id="btn-delete-alert-{{ $equipment['id'] }}">删除</a>
                                                <form role="form" method="POST" style="display: none"
                                                      action="/equipment/delete/{{ $equipment['id'] }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" id="btn-delete-submit-{{ $equipment['id'] }}">
                                                    </button>
                                                </form>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $equipments->render() !!}
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/equipment/add" class="btn btn-default btn-sm">导出Excel</a>
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

    @foreach ($equipments as $equipment)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $equipment['id'] }}').on("click", function () {
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
                            $("#btn-delete-submit-{{ $equipment['id'] }}").click();
                        })
            });
        </script>
    @endforeach

@endsection
