@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/rbac/rbac.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    <span>账户管理</span>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        设置用户角色
                        <a href="javascript:history.back(-1)" class="btn-link">返回</a>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="#">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                <label for="user" class="col-md-4 control-label">用户</label>

                                <div class="col-md-6">
                                    <input id="disabledInput" type="text" class="form-control" name="user" value=""  required >
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="permission" class="col-md-4 control-label">角色</label>

                                <div class="col-md-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> 管理员
                                        </label>
                                        <label>
                                            <input type="checkbox"> 操作员
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary btn-custom">
                                        保存
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
