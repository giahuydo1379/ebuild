@extends('layouts.master')
@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <div class="panel box-panel Panel">
                <div id="page-title">
                    <h2 class="page-header text-overflow"> Quản lý vài trò</h2>
                </div>

                <ol class="breadcrumb">
                    <li>
                        <a href="{!! route('roles.index') !!}">
                            Danh sách vài trò
                        </a>
                    <li class="active">Chỉnh sửa</li>
                </ol>

                <div id="page-content">
                    <form role="form" class="form-horizontal" method="post">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="form-group">
                                        <label for="name">Tên vai trò</label>
                                        {!! Form::text("name", $object['name'], ['class' => 'form-control', 'required']) !!}
                                        <span class="help-block has-error">{!! $errors->first("name") !!}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="form-field-1">
                                            Quyền
                                        </label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="header_table">
                                                    <td>Nhóm</td>
                                                    <td>Chức năng</td>
                                                    <td>Cho phép</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($parentPermissions as $item)
                                                <?php
                                                $count = count($group_permissions[$item['id']]);
                                                $ac = $count ? array_shift($group_permissions[$item['id']]) : false;
                                                ?>
                                                <tr>
                                                    <td class="group-permission" rowspan="{{$count}}">
                                                        {{ $item['name_label'] }}<br/>
                                                    </td>
                                                    @if ($ac)
                                                        <td>{{ $ac['name_label'] }}</td>
                                                        <td><input type="checkbox" name="permissions[]" id="permission-{{ $ac['id'] }}"
                                                                   value="{{ $ac['id'] }}" <?php if (isset($object['permissions'][$ac['id']])) {echo 'checked';}; ?> class="flat" /></td>
                                                    @else
                                                        <td></td>
                                                        <td></td>
                                                    @endif
                                                </tr>

                                            @if(isset($group_permissions[$item['id']]))
                                                @foreach($group_permissions[$item['id']] as $ac)
                                                    <tr>
                                                        <td>{{ $ac['name_label'] }}</td>
                                                        <td><input type="checkbox" name="permissions[]" id="permission-{{ $ac['id'] }}"
                                                                   value="{{ $ac['id'] }}" <?php if (isset($object['permissions'][$ac['id']])) {echo 'checked';}; ?> class="flat" /></td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <a href='{!! route('roles.index') !!}' class="btn btn-success btn-labeled fa fa-arrow-left pull-left"> Danh sách vài trò</a>
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

@section('after_scripts')
    <style>
        .group-permission {
            vertical-align: middle !important;
            font-weight: 700;
        }
    </style>
    <!-- iCheck -->
    <link href="/assets/plugins/iCheck/skins/flat/green.css" rel="stylesheet">
    <script src="/assets/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });

    </script>
@endsection