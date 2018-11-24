@extends('layouts.master')
@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <div class="panel box-panel Panel">
                <div id="page-title">
                    <h2 class="page-header text-overflow">Quản lý {{ $title }}</h2>
                </div>

                <ol class="breadcrumb">
                    <li class="">Quản lý {{ $title }}</li>
                    <li class="active">{{ $title }}</li>
                </ol>

                @if(Session::has('success-message'))
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                        {{ Session::get('success-message') }}
                    </div>
                @elseif (Session::has('error-message'))
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        {{ Session::get('error-message') }}
                    </div>
                @endif

                <div id="page-content">
                    <div class="panel">
                        <div class="panel-body">
                            <div id="table-toolbar">
                                <a href="{!! url("/{$controllerName}/add") !!}" class="btn btn-primary btn-labeled fa fa-plus"> Thêm mới</a>
                            </div>
                            <table id="demo-custom-toolbar" class="demo-add-niftycheck" data-toggle="table"
                                   data-locale="vi-VN"
                                   data-toolbar="#table-toolbar"
                                   data-url="{!! url("/{$controllerName}/ajax-data") !!}"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-show-toggle="true"
                                   data-show-columns="true"
                                   data-pagination="true"
                                   data-side-pagination="server"
                                   data-page-size="{{ PAGE_LIST_COUNT }}"
                                   data-query-params="queryParams"
                                   data-cookie="true"
                                   data-cookie-id-table="departments-show-all"
                                   data-cookie-expire="{!! config('params.bootstrapTable.extension.cookie.cookieExpire') !!}"
                            >
                                <thead>
                                <tr>
                                    <th data-field="check_id" data-checkbox="true">ID</th>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name_label" data-sortable="true">Tên</th>
                                    <th data-field="route" data-sortable="true">Route</th>
                                    <th data-field="parent_id" data-sortable="true">Nhóm</th>
                                    <th data-field="created_at" data-sortable="true">Ngày tạo</th>
                                    <th data-field="id" data-align="center" data-formatter="actionColumn">Chức năng</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">
    <script src="{{ asset('assets/plugins/bootstrap-table/bootstrap-table.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-table/locale/bootstrap-table-vi-VN.min.js') }}"></script>

    <style type="text/css">
        .bootstrap-select {
            margin: 0;
        }
    </style>

    <script type="text/javascript">

        $('#reset-page').click(function (){
            var url = window.location.href;
            window.location = url;
        });

        function actionColumn(value, row, index) {

            var editBtn = [
                '<a href="{{ url("/{$controllerName}/edit") }}/' + value + '" ',
                'class="add-tooltip btn btn-info btn-xs" data-placement="top" data-original-title="Sửa">',
                '<i class="fa fa-pencil"></i></a>'
            ].join('');

            var removeBtn = [
                '<a href="#" onclick="removeItems([' + value + '], event)"',
                'class="add-tooltip btn btn-danger btn-icon icon-lg btn-xs" data-placement="top" data-original-title="Đổi trạng thái">',
                '<i class="fa fa-trash-o"></i></a>'
            ].join('');

            return [editBtn].join(' ');
        }

        function notifyMsg(msg) {
            $.niftyNoty({
                type: 'dark',
                title: 'Thông báo',
                message: msg,
                container: 'floating',
                timer: 5000
            });
        }

        function queryParams(params) {
            return params;
        }

        $(document).ready(function() {

            @if (session('msg'))
                notifyMsg('{{ session('msg') }}');
            @endif

        });

    </script>
@endsection