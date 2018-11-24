@extends('layouts.master')
@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <div class="panel box-panel Panel">
                <div id="page-title">
                    <h2 class="page-header text-overflow"> Quản lý Permission</h2>
                </div>

                <ol class="breadcrumb">
                    <li>
                        <a href="{!! url("/departments/show-all") !!}">
                            Danh sách Permission
                        </a>
                    <li class="active">Thêm mới</li>
                </ol>

                <div id="page-content">
                    <form role="form" class="form-horizontal" method="post">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="form-field-1">
                                            Parent
                                        </label>
                                        <div class="col-sm-5">
                                            {!! Form::select("parent_id", $parentPermissions, null, ['class' => 'form-control' , "id" => "parent_id"]) !!}
                                            <span class="help-block has-error">{!! $errors->first("parent_id") !!}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="form-field-1">
                                            Name
                                        </label>
                                        <div class="col-sm-5">
                                            {!! Form::text("name", null, ['class' => 'form-control', 'required']) !!}
                                            <span class="help-block has-error">{!! $errors->first("name") !!}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="form-field-1">
                                            Route
                                        </label>
                                        <div class="col-sm-5">
                                            {!! Form::text("route", null, ['class' => 'form-control', 'required']) !!}
                                            <span class="help-block has-error">{!! $errors->first("route") !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <a href='{!! url("/{$controllerName}/show-all") !!}' class="btn btn-success btn-labeled fa fa-arrow-left pull-left"> Danh sách Permission</a>
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button class="btn btn-primary btn-labeled fa fa-save"> Save</button>
                                <button type="reset" class="btn btn-default btn-labeled fa fa-refresh"> Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection