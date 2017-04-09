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
                                <a href="#" class="btn btn-primary btn-sm">添加泵站</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <table class="table table-hover table-bordered ">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>泵站名称</th>
                                <th>泵站地址</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Column content</td>
                                <td>Column content</td>
                                <td>
                                    <a href="#" class="btn btn-default btn-sm">编辑</a>
                                    <a href="#" class="btn btn-default btn-sm">删除</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>content</td>
                                <td>content</td>
                                <td>content</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Column content content</td>
                                <td>Column content content</td>
                                <td>Column content content</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection