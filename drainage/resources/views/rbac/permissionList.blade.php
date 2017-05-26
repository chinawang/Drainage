@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/rbac/rbac.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>行为权限管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <span>行为权限管理</span>

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
                                权限列表
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/permission/add" class="btn btn-primary btn-sm">添加权限</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($permissions[0]))
                            <table class="table table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th style="width: 80px">序号</th>
                                    <th>行为权限</th>
                                    <th>别名</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission['id'] }}</td>
                                        <td>{{ $permission['name'] }}</td>
                                        <td>{{ $permission['slug'] }}</td>
                                        <td>
                                            <a href="/permission/edit/{{ $permission['id'] }}" class="btn btn-link">编辑</a>
                                            <a href="#" class="btn btn-link btn-delete-station"
                                               id="btn-delete-alert-{{ $permission['id'] }}">删除</a>
                                            <form role="form" method="POST" style="display: none"
                                                  action="/permission/delete/{{ $permission['id'] }}">
                                                {{ csrf_field() }}
                                                <button type="submit" id="btn-delete-submit-{{ $permission['id'] }}">
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="table-pagination">
                                {!! $permissions->render() !!}
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

    @foreach ($permissions as $permission)
        <script type="text/javascript">
            $('#btn-delete-alert-{{ $permission['id'] }}').on("click", function () {
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
                            $("#btn-delete-submit-{{ $permission['id'] }}").click();
                        })
            });
        </script>
    @endforeach

    <script>
        $(function(){
            //$('table tr:not(:first)').remove();
//            var len = $('table#tb_mainten tr').length;
            for(var i = 1;i<$('table tr').length;i++){
                $('table tr:eq('+i+') td:first').text(i);
            }

        });
    </script>

@endsection