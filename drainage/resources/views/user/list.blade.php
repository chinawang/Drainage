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
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-title">
                                用户列表
                            </div>
                            <div class="col-md-6 col-btn">
                                <a href="/user/add" class="btn btn-primary btn-sm">添加用户</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <table class="table table-hover table-bordered ">
                            <thead>
                            <tr>
                                <th>员工编号</th>
                                <th>姓名</th>
                                <th>职务</th>
                                <th>联系方式</th>
                                <th>登录账号</th>
                                <th>角色</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class=" btn-link">设置角色</a>
                                    <a href="#" class=" btn-link">编辑</a>
                                    <a href="#" class=" btn-link" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<div class="modal fade" id="station-delete-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">提示</h4>
            </div>
            <div class="modal-body" style="font-size: 19px">
                <p>删除之后将无法恢复,确定删除吗?</p>
            </div>
            <div class="modal-footer" style="border-top:none">
                {{--<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>--}}
                <button type="button" class="btn btn-danger btn-sm">确认删除</button>
            </div>
        </div>
    </div>
</div>