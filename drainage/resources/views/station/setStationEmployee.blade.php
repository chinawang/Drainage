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
                <a href="/station/lists">泵站资料管理</a>
                <em>›</em>
                <span>设置工作人员</span>
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
                        设置工作人员
                        {{--<a href="/station/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="/station/employee/store/{{ $station['id'] }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="station_id" value="{{ $station['id'] }}">
                            <div class="form-group{{ $errors->has('employee') ? ' has-error' : '' }}">
                                <label for="station" class="col-md-4 control-label">泵站</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           disabled="disabled" value="{{ $station['name'] }}" required>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="employee" class="col-md-4 control-label">工作人员</label>

                                <div class="col-md-6">
                                    @foreach ($employees as $employee)
                                        <label class="checkbox-inline">
                                            <input name="employees[]" type="checkbox" id="inlineCheckbox{{$employee['id']}}"
                                                   value="{{$employee['id']}}" {{!in_array($employee['id'], $assignEmployeeIDs)?:' checked'}}>{{$employee['name']}}
                                        </label>
                                    @endforeach
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
