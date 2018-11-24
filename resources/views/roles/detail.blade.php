@extends('layouts.master')

@section('after_styles')
    <link href="/assets/plugins/iCheck/skins/flat/green.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-easyui-1.5.3/themes/bootstrap/easyui.css">
    <link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-easyui-1.5.3/themes/icon.css">
@endsection

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <div class="panel box-panel Panel">
                <div id="page-title">
                    <h2 class="page-header text-overflow"> Quản lý vai trò</h2>
                </div>

                <ol class="breadcrumb">
                    <li>
                        <a href="{!! route('roles.index') !!}">
                            Danh sách vai trò
                        </a>
                    <li class="active">Phân quyền cho người dùng</li>
                </ol>

                <div id="page-content">
                    <form role="form" class="form-horizontal" method="post">
                        <div class="form-group">
                            <label class="col-lg-4 col-sm-3 control-label" for="form-field-1">Vai trò</label>
                            <div class="col-lg-8 col-sm-9">
                                <label class="control-label">{{$object['name']}}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="form-field-1">
                                    Các chức năng được quyền thao tác
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
                                        @foreach(array_keys($has_permissions) as $id)
                                            <?php
                                            $count = count($has_permissions[$id]);
                                            $ac = $count ? array_shift($has_permissions[$id]) : false;
                                            ?>
                                            <tr>
                                                <td class="group-permission" rowspan="{{$count}}">
                                                    {{ $parent_permissions[$id] }}
                                                </td>
                                                @if ($ac)
                                                    <td>{{ $ac['name_label'] }}</td>
                                                    <td><input disabled="disabled" type="checkbox" checked/></td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            </tr>

                                            @if(isset($has_permissions[$id]))
                                                @foreach($has_permissions[$id] as $ac)
                                                    <tr>
                                                        <td>{{ $ac['name_label'] }}</td>
                                                        <td><input disabled="disabled" type="checkbox" checked/></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Danh sách nhân sự có vai trò</div>
                                    <div class="panel-body">
                                        <div class="row text-center">
                                            <div class="col-sm-offset-3 col-sm-3">
                                                <select id="search" class="easyui-combogrid form-control" style="width:100%"></select>
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="button" class="btn btn-success btn-block add-users"><i class="fa fa-check-square-o" aria-hidden="true"></i> Thêm nhân sự</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <table id="danhsachnhanvien" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead class="thead-default">
                                            <tr>
                                                <th>ID</th>
                                                <th>Mã nhân sự</th>
                                                <th>Tên nhân sự</th>
                                                <th class="text-center">Bộ phận</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Chức năng</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $item)
                                            <tr>
                                                <td>{{$item['id']}}</td>
                                                <td>{{$item['username']}}</td>
                                                <td>{{$item['full_name']}}</td>
                                                <td class="text-center">{{$item['department_name']}}</td>
                                                <td class="text-center">{{$item['email']}}</td>
                                                <td>
                                                    <a href="{{route('roles.remove-user', ['role_id' => $object['id'], 'user_id' => $item['id']])}}" class="btn btn-xs btn-default" data-button-type="delete"><i class="fa fa-trash"></i> Xoá</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-footer">
                                        <a href='{!! route('roles.index') !!}' class="btn btn-success btn-labeled"><i class="fa fa-arrow-left""></i> Danh sách vai trò</a>
                                    </div>
                                </div>
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
    <script src="/assets/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-easyui-1.5.3/jquery.easyui.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            $('.add-users').on('click', function () {
                var vals = $('#search').combogrid('getValues');
                if (!vals || vals=='') {
                    malert('Vui lòng chọn nhân sự.');
                    return false;
                }
                ajax_loading(true);
                $.post('{{route('roles.add-users')}}', {role_id: '{{$object['id']}}', user_ids: vals}, function (data) {
                    show_pnotify("Thông báo", data.msg);

                    if (data.rs) {
                        location.reload();
                    }
                });
            });

            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $('#search').combogrid({
                panelWidth:'50%',
                showOn: true,
                url: '{{route('admin-users.get-combogrid-data')}}',
                idField:'id',
                textField:'username',
                mode:'remote',
                fitColumns:true,
                multiple: true,
                columns:[[
                    {field:'id',title:'ID',width:60},
                    {field:'username',title:'Mã nhân sự',width:60},
                    {field:'full_name',title:'Tên nhân sự',width:150},
                    {field:'email',title:'Email',align:'right',width:150},
                    {field:'department_name',title:'Bộ phận',align:'right',width:100}
                ]]
            });
        });
        // ask for confirmation before deleting an item
        $(document).on('click', "[data-button-type=delete]", function(e) {
            e.preventDefault();
            var delete_button = $(this);
            var delete_url = $(this).attr('href');

            if (confirm("Bạn có muốn xoá nhân sự ra khỏi vai trò này không?") == true) {
                $.ajax({
                    url: delete_url,
                    type: 'DELETE',
                    success: function(result) {
                        // Show an alert with the result
                        new PNotify({
                            title: "Thông báo",
                            text: "Xoá thành công.",
                            type: "success"
                        });
                        // delete the row from the table
                        delete_button.parentsUntil('tr').parent().remove();
                    },
                    error: function(result) {
                        // Show an alert with the result
                        new PNotify({
                            title: "Thông báo",
                            text: "Xoá không thành công.",
                            type: "warning"
                        });
                    }
                });
            }
        });
    </script>
@endsection