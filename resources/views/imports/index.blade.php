@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view ">
            <div class="header ">
                <h2 class="title">Import sản phẩm</h2>
            </div>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#1" data-toggle="tab">Sản phẩm mới</a>
                </li>
                <li><a href="#2" data-toggle="tab">Số lượng sản phẩm</a>
                </li>
                <li><a href="#3" data-toggle="tab">Giá bán sản phẩm</a>
                </li>
            </ul>
            <div class="tab-content tab-content-import">
                <!-- tab content 1 -->
                <div class="tab-pane active" id="1">
                    <div class="content_import">
                        <form method="post" id="frm_import" action="{{ route("imports.products") }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <ul class="wrap_btn row">
                                    <li class="col-md-3">
                                        <a href="#">
                                            <i class="icon-browser">&nbsp</i>
                                            <span>Browse ...</span>
                                            <input type="file" name="newfile">
                                        </a>
                                    </li>
                                    <li class="col-md-5">
                                        <a href="javascript:void(0)" onclick="$('#frm_import').submit()">
                                            <i class="icon-import">&nbsp</i>
                                            <span>Import sản phẩm mới</span>
                                        </a>
                                    </li>
                                    <li class="col-md-4">
                                        <a href="<?=asset('/files/products.XLSX')?>">
                                            <i class="icon-excel">&nbsp</i>
                                            <span>Tải file Excell mẫu</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="show_file">
                                    <span class="file_name"></span>
                                    <label id="newfile-error" class="error" for="newfile"></label>
                                </div>
                                <div class="note">
                                    <b>Lưu ý:</b>
                                    <ul>
                                        <li class="row"><span>- File excell phải đầy đủ thông tin</span></li>
                                        <li class="row"><span>- File excell chỉ giới hạn 3000 dòng, nếu dài hơn thì tách ra làm nhiều file.</span></li>
                                        <li class="row"><span>- Việc import danh sách sản phẩm rất quan trọng, cần sự cẩn thận, kỹ càng, chi tiết.</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </form>
                    </div>
                </div>
                <!-- tab content 2 -->
                <div class="tab-pane" id="2">
                    <div class="content_import">
                        <form method="post" id="frm_import_count" action="{{ route("imports.products-count") }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <ul class="wrap_btn row">
                                    <li class="col-md-3">
                                        <a href="#">
                                            <i class="icon-browser">&nbsp</i>
                                            <span>Browse ...</span>
                                            <input type="file" name="numberfile">
                                        </a>
                                    </li>
                                    <li class="col-md-5">
                                        <a href="javascript:void(0)" onclick="$('#frm_import_count').submit()">
                                            <i class="icon-import">&nbsp</i>
                                            <span>Import số lượng sản phẩm</span>
                                        </a>
                                    </li>
                                    <li class="col-md-4">
                                        <a href="<?=asset('/files/products-count.XLSX')?>">
                                            <i class="icon-excel">&nbsp</i>
                                            <span>Tải file Excell mẫu</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="show_file">
                                    <span class="file_name"></span>
                                    <label id="numberfile-error" class="error" for="numberfile"></label>
                                </div>
                                <div class="note">
                                    <b>Lưu ý:</b>
                                    <ul>
                                        <li class="row"><span>- File excell phải đầy đủ thông tin</span></li>
                                        <li class="row"><span>- File excell chỉ giới hạn 3000 dòng, nếu dài hơn thì tách ra làm nhiều file.</span></li>
                                        <li class="row"><span>- Việc import danh sách sản phẩm rất quan trọng, cần sự cẩn thận, kỹ càng, chi tiết.</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </form>
                    </div>
                </div>
                <!-- tab content 3 -->
                <div class="tab-pane" id="3">
                    <div class="content_import">
                        <form method="post" id="frm_import_price" action="{{ route("imports.products-price") }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <ul class="wrap_btn row">
                                    <li class="col-md-3">
                                        <a href="#">
                                            <i class="icon-browser">&nbsp</i>
                                            <span>Browse ...</span>
                                            <input type="file" name="pricefile">
                                        </a>
                                    </li>
                                    <li class="col-md-5">
                                        <a href="javascript:void(0)" onclick="$('#frm_import_price').submit()">
                                            <i class="icon-import">&nbsp</i>
                                            <span>Import giá sản phẩm</span>
                                        </a>
                                    </li>
                                    <li class="col-md-4">
                                        <a href="<?=asset('/files/products-price.XLSX')?>">
                                            <i class="icon-excel">&nbsp</i>
                                            <span>Tải file Excell mẫu</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="show_file">
                                    <span class="file_name"></span>
                                    <label id="pricefile-error" class="error" for="pricefile"></label>
                                </div>
                                <div class="note">
                                    <b>Lưu ý:</b>
                                    <ul>
                                        <li class="row"><span>- File excell phải đầy đủ thông tin</span></li>
                                        <li class="row"><span>- File excell chỉ giới hạn 3000 dòng, nếu dài hơn thì tách ra làm nhiều file.</span></li>
                                        <li class="row"><span>- Việc import danh sách sản phẩm rất quan trọng, cần sự cẩn thận, kỹ càng, chi tiết.</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#frm_import').validate({
                ignore: ".ignore",
                rules: {
                    newfile: "required",
                },
                messages: {
                    newfile: "Vui lòng chọn file danh sách sản phẩm mới",
                },
                submitHandler: function(form) {
//                    form.submit(); return true;
                    // do other things for a valid form
                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: "JSON",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (data, status)
                        {
                            console.log(data, status);
                            ajax_loading(false);
                            if(data.rs == 1)
                            {
                                alert_success(data.msg, function () {
                                    location.reload();
                                });
                            } else {
                                alert_success(data.msg);
                                if (data.errors) {
                                    $.each(data.errors, function (key, msg) {
                                        $('#'+key+'-error').html(msg).show();
                                    });
                                }
                            }
                        },
                        error: function (xhr, desc, err)
                        {
                            ajax_loading(false);
                            malert('Import <b>sản phẩm</b> không thành công! Liên hệ Admin để được hỗ trợ!<br>'+
                                desc.status+': '+err, 'Lỗi hệ thống');
                        }
                    });
                    return false;
                }
            });
            $('#frm_import_count').validate({
                ignore: ".ignore",
                rules: {
                    numberfile: "required",
                },
                messages: {
                    numberfile: "Vui lòng chọn file danh sách số lượng sản phẩm",
                },
                submitHandler: function(form) {
//                    form.submit(); return true;
                    // do other things for a valid form
                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: "JSON",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (data, status)
                        {
                            console.log(data, status);
                            ajax_loading(false);
                            if(data.rs == 1)
                            {
                                alert_success(data.msg, function () {
                                    location.reload();
                                });
                            } else {
                                alert_success(data.msg);
                                if (data.errors) {
                                    $.each(data.errors, function (key, msg) {
                                        $('#'+key+'-error').html(msg).show();
                                    });
                                }
                            }
                        },
                        error: function (xhr, desc, err)
                        {
                            ajax_loading(false);
                            malert('Import <b>Số lượng sản phẩm</b> không thành công! Liên hệ Admin để được hỗ trợ!<br>'+
                                xhr.status+': '+err, 'Lỗi hệ thống');
                        }
                    });
                    return false;
                }
            });
            $('#frm_import_price').validate({
                ignore: ".ignore",
                rules: {
                    pricefile: "required",
                },
                messages: {
                    pricefile: "Vui lòng chọn file danh sách giá sản phẩm",
                },
                submitHandler: function(form) {
//                    form.submit(); return true;
                    // do other things for a valid form
                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: "JSON",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        success: function (data, status)
                        {
                            console.log(data, status);
                            ajax_loading(false);
                            if(data.rs == 1)
                            {
                                alert_success(data.msg, function () {
                                    location.reload();
                                });
                            } else {
                                alert_success(data.msg);
                                if (data.errors) {
                                    $.each(data.errors, function (key, msg) {
                                        $('#'+key+'-error').html(msg).show();
                                    });
                                }
                            }
                        },
                        error: function (xhr, desc, err)
                        {
                            ajax_loading(false);
                            malert('Import <b>giá sản phẩm</b> không thành công! Liên hệ Admin để được hỗ trợ!<br>'+
                                desc.status+': '+err, 'Lỗi hệ thống');
                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endsection