@extends('layouts.app')

@section('stylesheet')
    <link href="{{ asset('css/equipment/equipment.css') }}" rel="stylesheet">
@endsection

@section('subtitle')
    {{--<span>设备资料管理</span>--}}
@endsection

@section('location')
    <div class="location">
        <div class="container">
            <h2>
                <a href="{{ url('/') }}">首页</a>
                <em>›</em>
                <a href="/pump/lists">泵组抽水量管理</a>
                <em>›</em>
                <span>编辑泵组抽水量</span>
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
                        编辑泵组抽水量
                        {{--<a href="/equipment/lists" class="btn-link">返回</a>--}}
                    </div>
                    <div class="panel-body custom-panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="/pump/update/{{ $pump['id'] }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('station') ? ' has-error' : '' }}">
                                <label for="station" class="col-md-4 control-label">所属泵站</label>

                                <div class="col-md-6">
                                    <select class="form-control" id="select" name="station_id">
                                        <option value="" selected="selected" style="display: none">选择泵站</option>
                                        @foreach ($stations as $station)
                                            <option value="{{ $station['id'] }}" {{$pump['station_id'] == $station['id'] ? 'selected="selected"' : ''}}>{{ $station['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('station'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('station') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('flux1') ? ' has-error' : '' }}">
                                <label for="flux1" class="col-md-4 control-label">1号泵抽水量</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="flux1" value="{{ $pump['flux1'] }}" placeholder="请输入1号泵抽水量"  >

                                    @if ($errors->has('flux1'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('flux1') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <label for="flux1" class="col-md-0 control-label">m³/h</label>
                            </div>

                            <div class="form-group{{ $errors->has('flux2') ? ' has-error' : '' }}">
                                <label for="flux2" class="col-md-4 control-label">2号泵抽水量</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="flux2" value="{{ $pump['flux2'] }}" placeholder="请输入2号泵抽水量"  >

                                    @if ($errors->has('flux2'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('flux2') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <label for="flux2" class="col-md-0 control-label">m³/h</label>
                            </div>

                            <div class="form-group{{ $errors->has('flux3') ? ' has-error' : '' }}">
                                <label for="flux3" class="col-md-4 control-label">3号泵抽水量</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="flux3" value="{{ $pump['flux3'] }}" placeholder="请输入3号泵抽水量"  >

                                    @if ($errors->has('flux3'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('flux3') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <label for="flux3" class="col-md-0 control-label">m³/h</label>
                            </div>

                            <div class="form-group{{ $errors->has('flux4') ? ' has-error' : '' }}">
                                <label for="flux4" class="col-md-4 control-label">4号泵抽水量</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="flux4" value="{{ $pump['flux4'] }}" placeholder="请输入4号泵抽水量"  >

                                    @if ($errors->has('flux4'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('flux4') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <label for="flux4" class="col-md-0 control-label">m³/h</label>
                            </div>

                            <div class="form-group{{ $errors->has('flux5') ? ' has-error' : '' }}">
                                <label for="flux5" class="col-md-4 control-label">5号泵抽水量</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="flux5" value="{{ $pump['flux5'] }}" placeholder="请输入5号泵抽水量"  >

                                    @if ($errors->has('flux5'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('flux5') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <label for="flux5" class="col-md-0 control-label">m³/h</label>
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
