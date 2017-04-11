@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/station/station.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>泵站资料管理</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-title">
                                泵站列表
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/station/add" class="btn btn-primary btn-sm">添加泵站</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        @if (!empty($stations))
                        <table class="table table-hover table-bordered " data-pagination="true"
                               data-show-refresh="true"
                               data-show-toggle="true"
                               data-showColumns="true">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>泵站名称</th>
                                <th>泵站地址<?php echo $stations->data; ?></th>
                                <th>操作<?php echo $stations['total']; ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            {{--@foreach ($stations['data'] as $station)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $station['station_number'] }}</td>--}}
                                {{--<td>{{ $station['name'] }}</td>--}}
                                {{--<td>{{ $station['address'] }}</td>--}}
                                {{--<td>--}}
                                    {{--<a href="/station/edit/{{ $station['id'] }}" class="btn btn-link">编辑</a>--}}
                                    {{--<a href="#" class="btn btn-link" id="btn-delete-station" >删除</a>--}}
                                    {{--<form role="form" method="POST" style="display: none" action="/station/delete">--}}
                                        {{--{{ csrf_field() }}--}}
                                        {{--<input type="hidden" value="{{ $station['id'] }}">--}}
                                        {{--<button type="submit" id="btn-delete">--}}
                                        {{--</button>--}}
                                    {{--</form>--}}
                                    {{--<a href="#" class="btn btn-link" id="btn-delete-station" data-toggle="modal" data-target="#station-delete-modal">删除</a>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}
                            </tbody>
                        </table>
                        <div class="table-pagination">
                            <ul class="pagination">
                                <li><a href="#">&laquo;</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">&raquo;</a></li>
                            </ul>
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
    <script type="text/javascript">
        $('#btn-delete-station').on("click",function () {
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
                $.ajax({
                    type:'post',
                    url:'/station/delete',
                    data:{'_token': $('input[name=_token]').val()}
                });
                $("#btn-delete").click();
            })
        });
    </script>

    {{--<script type="text/javascript">--}}

        {{--$.ajax({--}}
            {{--type: "post",--}}
            {{--url: '/station/delete',--}}
            {{--traditional: true,--}}
            {{--dataType: "json",--}}
            {{--data: {'_token': $('input[name=_token]').val()},--}}
            {{--success: function (data) {--}}
                {{--swal({--}}
                            {{--title: "删除成功!",--}}
                            {{--text: "",--}}
                            {{--type: "success",--}}
                            {{--timer: 2000,--}}
                            {{--showConfirmButton: false--}}
                        {{--},--}}
                        {{--function () {--}}
{{--//                                            alert(data['status']);--}}
                            {{--window.location.reload();--}}
                        {{--})--}}
            {{--},--}}
            {{--error: function () {--}}
                {{--swal({--}}
                    {{--title: "删除失败!",--}}
                    {{--text: "数据未删除成功,请稍后重试!",--}}
                    {{--type: "error",--}}
                    {{--timer: 2000,--}}
                    {{--showConfirmButton: false--}}
                {{--})--}}
            {{--}--}}
        {{--})--}}
    {{--</script>--}}
@endsection