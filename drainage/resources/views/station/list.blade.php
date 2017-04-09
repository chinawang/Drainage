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
                            <div class="col-md-6 ">
                                泵站列表
                            </div>
                            <div class="col-md-6 ">
                                <a href="#" class="btn btn-primary btn-sm">添加泵站</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <table class="table table-striped table-hover ">
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
                                <td>Column content</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection