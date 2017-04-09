@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/equipment/equipment.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>设备资料管理</span>
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
                            <div class="col-md-6 col-btn">
                                <a href="/equipment/add" class="btn btn-primary btn-sm">添加设备</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <table class="table table-striped table-hover table-responsive ">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>所属泵站</th>
                                <th>设备名称</th>
                                <th>设备型号</th>
                                <th>制造单位</th>
                                <th>使用部门</th>
                                <th>负责人</th>
                                <th>设备管理员</th>
                                <th>数量</th>
                                <th>变动情况</th>
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
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs">编辑</a>
                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#station-delete-modal">删除</a>
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
                <button type="button" class="btn btn-primary btn-custom">确认</button>
            </div>
        </div>
    </div>
</div>