@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/rbac/rbac.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>账户管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/role/lists">角色管理</a>
                <em>›</em>
                <span>设置用户角色</span>
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
                        设置用户角色
                        {{--<a href="/user/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="/user/role/store/{{ $user['id'] }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                <label for="user" class="col-md-4 control-label">用户</label>

                                <div class="col-md-6">
                                    <input id="realname" type="text" class="form-control" name="realname"
                                           disabled="disabled" value="{{ $user['realname'] }}" required>

                                    @if ($errors->has('realname'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('realname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="role" class="col-md-4 control-label">角色</label>

                                <div class="col-md-6">
                                    @foreach ($roles as $role)
                                        <label class="checkbox-inline">
                                            <input name="roles[]" type="checkbox" id="inlineCheckbox{{$role['id']}}"
                                                   value="{{$role['id']}}" {{!in_array($role['id'], $assignRoleIDs)?:' checked'}}>{{$role['name']}}
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
