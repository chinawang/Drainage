@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/rbac/rbac.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>角色管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/role/lists">角色管理</a>
                <em>›</em>
                <span>设置角色权限</span>
            </h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        设置角色权限
                        <a href="/role/lists" class="btn-link">返回</a>
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/role/permission/store/{{ $role['id'] }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="role_id" value="{{ $role['id'] }}">
                            <div class="form-group">
                                <label for="role" class="col-md-4 control-label">角色</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           disabled="disabled" value="{{ $role['name'] }}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="permission" class="col-md-4 control-label">权限</label>

                                <div class="col-md-6">
                                    @foreach ($permissions as $permission)
                                        <label class="checkbox-inline">
                                            <input name="permissions[]" type="checkbox" id="inlineCheckbox{{$permission['id']}}"
                                                   value="{{$permission['id']}}" {{!in_array($permission['id'], $assignPermissionIDs)?:' checked'}}>{{$permission['name']}}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary btn-custom">
                                        <span class="glyphicon glyphicon-ok-sign"></span>
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
