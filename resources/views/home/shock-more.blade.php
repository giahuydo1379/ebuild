@extends('layouts.master')
<?php
$ordering_options = \App\Helpers\General::get_ordering_options();
$sort_options = \App\Helpers\General::get_sort_options();
?>
@section('content')
    <div class="col-md-">
        <section class="section section-homepage">
            <h3 class="title-section">Quản lý trang chủ</h3>
            <div class="panel box-panel">
                <div class="home-management" >
                    @include('home.tabs')

                    <div id="tab1" class="tabcontent active" style="display: block;">
                        @if ($object)
                        <div class="banner-promotion">
                            <ul class="list-infor">
                                <li><label for="">Tiêu đề block: </label> <b class="color">{{$object['name']}}</b></li>
                                <li><label for="">Thứ tự hiển thị block: </label> <span>Vị trí {{$ordering_options[$object['ordering']]}}</span></li>
                                <li><label for="">Hiển thị sản phẩm theo: </label> <span>{{$sort_options[$object['sort']]}}</span></li>
                                <li>
                                    <label for="" class="lb-status">Trạng thái chương trình:</label>
                                    <div class="wrapper">
                                        <input type="checkbox" id="status-{{$object['id']}}" class="slider-toggle" @if ($object['status']) checked @endif/>
                                        <label class="slider-viewport" for="status-{{$object['id']}}" onclick="return false;">
                                            <div class="slider">
                                                <div class="slider-button">&nbsp;</div>
                                                <div class="slider-content left"><span>On</span></div>
                                                <div class="slider-content right"><span>Off</span></div>
                                            </div>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                            <div class="action text-right">
                                <button type="button" value="" class="btn btn_primary UpdateAction">Cập nhật</button>
                            </div>
                            <div class="display-block">
                                <label for="" class="title-block">Danh sách sản phẩm bán giá sốc</label>
                                <ul class="list-product no-padding list-shocks quick"></ul>
                            </div>
                            <div class="update-list ShowUpdateShock" style="display: none;">
                                <form class="form-create home-manager no-padding" id="form_update_quick" method="post" action="{{ route("home.add") }}">
                                    <div class="row">
                                        <input type="hidden" id="q_item" value="0">
                                        <input type="hidden" id="q_product_id" name="q_product_id" value="">
                                        <input type="hidden" id="q_name" name="q_name" value="0">
                                        <input type="hidden" id="q_price" name="q_price" value="0">

                                        <div class="col-md-6 no-padding">
                                            <div class="form-group">
                                                <label for="">SKU sản phẩm</label>
                                                <input type="text" name="q_sku" id="q_sku" class="form-control" placeholder="Điền mã SKU sản phẩm">
                                                <label id="q_sku-error" class="error" for="q_sku" style="display: none;"></label>
                                                <label id="q_product_id-error" class="error" for="q_product_id" style="display: none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-left">
                                            <label for="">&nbsp;</label>
                                            <div class="info-sku-shock"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="time-show">
                                            <label for="">Thời gian bán sản phẩm:</label>
                                            <div class="row">
                                                <div class="col-md-6 haft-padding-left">
                                                    <div class="col-md-3 no-padding">
                                                        <span class="lb-time">Bắt đầu từ:</span>
                                                    </div>
                                                    <div class="col-md-3 no-padding">
                                                        <div class="input-group clockpicker">
                                                            <input readonly type="text" name="q_from_time" id="q_from_time" class="form-control" value="">
                                                            <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-time"></span>
                                                                    </span>
                                                        </div>
                                                        <label id="q_from_time-error" class="error" for="q_from_time"></label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="time">
                                                            <div class="input-group date">
                                                                <input name="q_from_date" id="q_from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày bắt đầu">
                                                                <span class="fa fa-calendar"></span>
                                                            </div>
                                                        </div>
                                                        <label id="q_from_date-error" class="error" for="q_from_date" style="display: none;"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 haft-padding-left">
                                                    <div class="col-md-3 no-padding">
                                                        <span class="lb-time">Kết thúc lúc:</span>
                                                    </div>
                                                    <div class="col-md-3 no-padding">
                                                        <div class="input-group clockpicker">
                                                            <input readonly type="text" name="q_to_time" id="q_to_time" class="form-control" value="">
                                                            <span class="input-group-addon">
                                                                        <span class="glyphicon glyphicon-time"></span>
                                                                    </span>
                                                        </div>
                                                        <label id="q_to_time-error" class="error" for="q_to_time"></label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="time">
                                                            <div class="input-group date">
                                                                <input name="q_to_date" id="q_to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày kết thúc">
                                                                <span class="fa fa-calendar"></span>
                                                            </div>
                                                        </div>
                                                        <label id="q_to_date-error" class="error" for="q_to_date" style="display: none;"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-left">
                                                    <button type="submit" class="btn btn-del"> Cập nhật sản phẩm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="no-banner NoDataPromotion">
                            <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                            <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                        </div>
                        @endif
                        <div class="create-landingpage CreatePromotion" style="display: none;">
                            <form class="form-create home-manager no-padding" id="form_update" method="post" action="{{ route("home.add") }}">
                                <div class="field-form">
                                    <div class="add-block">
                                        <div class="row">
                                            <div class="col-md-4 haft-padding-left">
                                                <div class="form-group">
                                                    <label for="">Tiêu đề block</label>
                                                    <input name="name" id="name" value="{{@$object['name']}}" type="text" class="form-control" placeholder="Nhập tiêu đề block">
                                                    <span class="note">(Tối thiểu 05 từ, tối đa 10 từ)</span>
                                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Thứ tự hiển thị block</label>
                                                    <div class="wrap_select">
                                                        {!! Form::select("ordering", $ordering_options, @$object['ordering'], ['id' => 'ordering', 'class' => 'form-control']) !!}
                                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                    </div>
                                                    <label id="ordering-error" class="error" for="ordering" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 haft-padding-right">
                                                <div class="form-group">
                                                    <label for="">Hiển thị sản phẩm theo</label>
                                                    <div class="wrap_select">
                                                        {!! Form::select("sort", \App\Helpers\General::get_sort_options(), @$object['sort'], ['id' => 'sort', 'class' => 'form-control']) !!}
                                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                    </div>
                                                    <label id="sort-error" class="error" for="sort" style="display: none;"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="">Trạng thái chương trình</label>
                                            <div class="wrapper">
                                                <input value="0" type="hidden" name="status"/>
                                                <input value="1" type="checkbox" id="status" name="status" class="slider-toggle" @if (!isset($object['status']) || $object['status']) checked @endif />
                                                <label class="slider-viewport" for="status">
                                                    <div class="slider">
                                                        <div class="slider-button">&nbsp;</div>
                                                        <div class="slider-content left"><span>On</span></div>
                                                        <div class="slider-content right"><span>Off</span></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-block">
                                        <label for="" class="title-block">Thêm sản phẩm bán giá sốc <span class="note">(Tối thiểu 4 sản phẩm, tối đa 20 sản phẩm)</span></label>
                                        <div class="row">
                                            <input type="hidden" id="p_item" value="0">
                                            <input type="hidden" id="p_product_id" value="">
                                            <input type="hidden" id="p_name" value="0">
                                            <input type="hidden" id="p_price" value="0">

                                            <div class="col-md-6 no-padding">
                                                <div class="form-group">
                                                    <label for="">SKU sản phẩm</label>
                                                    <input type="text" name="p_sku" id="p_sku" class="form-control" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="p_sku-error" class="error" for="p_sku" style="display: none;"></label>
                                                    <label id="p_product_id-error" class="error" for="p_product_id" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-left">
                                                <label for="">&nbsp;</label>
                                                <div class="info-sku-shock"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="time-show">
                                                <label for="">Thời gian bán sản phẩm:</label>
                                                <div class="row">
                                                    <div class="col-md-6 haft-padding-left">
                                                        <div class="col-md-3 no-padding">
                                                            <span class="lb-time">Bắt đầu từ:</span>
                                                        </div>
                                                        <div class="col-md-3 no-padding">
                                                            <div class="input-group clockpicker">
                                                                <input readonly type="text" name="p_from_time" id="p_from_time" class="form-control" value="">
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-time"></span>
                                                                </span>
                                                            </div>
                                                            <label id="p_from_time-error" class="error" for="p_from_time"></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="time">
                                                                <div class="input-group date">
                                                                    <input name="p_from_date" id="p_from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày bắt đầu">
                                                                    <span class="fa fa-calendar"></span>
                                                                </div>
                                                            </div>
                                                            <label id="p_from_date-error" class="error" for="p_from_date" style="display: none;"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 haft-padding-left">
                                                        <div class="col-md-3 no-padding">
                                                            <span class="lb-time">Kết thúc lúc:</span>
                                                        </div>
                                                        <div class="col-md-3 no-padding">
                                                            <div class="input-group clockpicker">
                                                                <input readonly type="text" name="p_to_time" id="p_to_time" class="form-control" value="">
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-time"></span>
                                                                </span>
                                                            </div>
                                                            <label id="p_to_time-error" class="error" for="p_to_time"></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="time">
                                                                <div class="input-group date">
                                                                    <input name="p_to_date" id="p_to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày kết thúc">
                                                                    <span class="fa fa-calendar"></span>
                                                                </div>
                                                            </div>
                                                            <label id="p_to_date-error" class="error" for="p_to_date" style="display: none;"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 text-left">
                                                        <a class="btn btn-del BtnSaveProductShock"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm sản phẩm</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-block">
                                        <label for="" class="title-block">Danh sách sản phẩm bán giá sốc</label>
                                        <ul class="list-product no-padding list-shocks">
                                        </ul>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="action text-right">
                                    <input type="hidden" id="block" name="block" value="{{$block}}">
                                    <input type="hidden" id="id" name="id" value="{{@$object['id']}}">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <button type="submit" value="" class="btn btn_primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_styles')
    <link rel="stylesheet" href="{{asset('/assets/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.css')}}">
@endsection
<?php
$ver_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')
    <script src="/assets/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.js"></script>
    <script type="text/javascript" src="/js/home.js?v={{$ver_js}}"></script>

    <script type="text/javascript">
        var _block = '{!! @$block !!}';
        var _products = {!! @json_encode($products) !!};
        var $shock_products = {!! @json_encode($shock_products) !!};
        var _shocks_products = [];

        $(document).ready(function() {
            init_datepicker('.datepicker');

            $('.UpdateAction').on('click', function () {
                $(".banner-promotion").slideUp();
                $(".CreatePromotion").slideDown();
            });

            $('.Cancel').on('click', function () {
                $(".banner-promotion").slideDown();
                $(".CreatePromotion").slideUp();
            });

            if (_products.length > 0) {
                var other;
                $.each(_products, function (index, sp) {
                    other = $shock_products[sp.product_id];

                    _shocks_products[index] = {
                        name: sp.name,
                        product_id: sp.product_id,
                        price: sp.price,
                        sku: sp.sku,
                        from_time: other.from_time,
                        from_date: other.from_date,
                        to_time: other.to_time,
                        to_date: other.to_date
                    };
                    add_shock_product(index, sp.product_id, sp.name, sp.price, sp.sku, other.from_time, other.from_date,
                        other.to_time, other.to_date);
                });
            }
            $('#q_sku').on('change', function () {
                $('#q_product_id').val('');
                $('.info-sku-shock').html('');
                if ($(this).val()=='') {
                    return false;
                }
                ajax_loading(true);
                $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
                    ajax_loading(false);
                    var tmp = data.products[0];
                    if (tmp) {
                        $('#q_product_id').val(tmp.product_id);
                        $('#q_product_id').valid();

                        $('#q_name').val(tmp.name);
                        $('#q_price').val(tmp.price);
                        $('#q_sku').val(tmp.sku);
                        $('#form_update_quick .info-sku-shock').html('Sản phẩm <a class="color">' + tmp.name + '</a> - giá <a class="color">' + numeral(tmp.price).format() + ' VNĐ;</a>');
                    } else {
                        $('#form_update_quick .info-sku-shock').html('<a class="color">Không tìm thấy sản phẩm</a>');
                    }
                });
            });
            $('#p_sku').on('change', function () {
                $('#p_product_id').val('');
                $('.info-sku-shock').html('');
                if ($(this).val()=='') {
                    return false;
                }

                ajax_loading(true);
                $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
                    ajax_loading(false);
                    var tmp = data.products[0];
                    if (tmp) {
                        $('#p_product_id').val(tmp.product_id);
                        $('#p_name').val(tmp.name);
                        $('#p_price').val(tmp.price);
                        $('#p_sku').val(tmp.sku);
                        $('.info-sku-shock').html('Sản phẩm <a class="color">' + tmp.name + '</a> - giá <a class="color">' + numeral(tmp.price).format() + ' VNĐ;</a>');
                    } else {
                        $('.info-sku-shock').html('<a class="color">Không tìm thấy sản phẩm</a>');
                    }
                });
            });

            $('#form_update_quick').validate({
                ignore: ".ignore",
                rules: {
                    q_sku: "required",
                    q_product_id: "required",
                    q_from_time: "required",
                    q_from_date: "required",
                    q_to_time: "required",
                    q_to_date: "required",
                },
                messages: {
                    q_sku: "Điền sku sản phẩm bán giá sốc",
                    q_product_id: "Không tìm thấy sản phẩm với sku trên",
                    q_from_time: "Chọn giờ bắt đầu",
                    q_from_date: "Chọn ngày bắt đầu",
                    q_to_time: "Chọn giờ kết thúc",
                    q_to_date: "Chọn ngày kết thúc",
                },
                submitHandler: function(form) {
                    var item = $('#q_item').val();

                    _shocks_products[item] = {
                        from_time: $("#q_from_time").val(),
                        from_date: $("#q_from_date").val(),
                        to_time: $("#q_to_time").val(),
                        to_date: $("#q_to_date").val(),
                        sku: $("#q_sku").val(),
                        product_id: $("#q_product_id").val(),
                        price: $("#q_price").val(),
                        name: $("#q_name").val()
                    };

                    add_shock_product(item, $("#q_product_id").val(), $("#q_name").val(), $("#q_price").val(), $("#q_sku").val(),
                        $("#q_from_time").val(), $("#q_from_date").val(), $("#q_to_time").val(), $("#q_to_date").val());

                    $('#form_update').submit();

                    return false;
                }
            });

            $('.BtnSaveProductShock').on('click', function () {
                $("#p_sku" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Điền sku sản phẩm bán giá sốc",
                    }
                });
                $("#p_product_id" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Không tìm thấy sản phẩm với sku trên",
                    }
                });
                $("#p_from_time" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Chọn giờ bắt đầu",
                    }
                });
                $("#p_from_date" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Chọn ngày bắt đầu",
                    }
                });
                $("#p_to_time").rules( "add", {
                    required: true,
                    messages: {
                        required: "Chọn giờ kết thúc",
                    }
                });
                $("#p_to_date").rules( "add", {
                    required: true,
                    messages: {
                        required: "Chọn ngày kết thúc",
                    }
                });

                var flag1 = $("#p_sku").valid() && $("#p_product_id").valid();
                var flag2 = $("#p_from_time").valid();
                var flag3 = $("#p_from_date").valid();
                var flag4 = $("#p_to_time").valid();
                var flag5 = $("#p_to_date").valid();

                if ( flag1 && flag2 && flag3 && flag4 && flag5 ) {
                    var item = $('#p_item').val();

                    _shocks_products[item] = {
                        from_time: $("#p_from_time").val(),
                        from_date: $("#p_from_date").val(),
                        to_time: $("#p_to_time").val(),
                        to_date: $("#p_to_date").val(),
                        sku: $("#p_sku").val(),
                        product_id: $("#p_product_id").val(),
                        price: $("#p_price").val(),
                        name: $("#p_name").val()
                    };

                    add_shock_product(item, $("#p_product_id").val(), $("#p_name").val(), $("#p_price").val(), $("#p_sku").val(),
                        $("#p_from_time").val(), $("#p_from_date").val(), $("#p_to_time").val(), $("#p_to_date").val());

                    $("#p_item").val($.now());
                    $("#p_sku").val("");
                    $("#p_from_time").val("");
                    $("#p_from_date").val("");
                    $("#p_to_time").val("");
                    $("#p_to_date").val("");
                    $("#p_product_id").val("");
                    $('.info-sku-shock').html("");

                    $('.BtnSaveProductShock').html('<i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm sản phẩm');
                }

                $("#p_sku").rules( "remove" );
                $("#p_product_id").rules( "remove");
                $("#p_from_time").rules("remove");
                $("#p_from_date").rules("remove");
                $("#p_to_time").rules("remove");
                $("#p_to_date").rules("remove");

                return false;
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    ordering: "required",
                    sort: "required",
                },
                messages: {
                    name: "Nhập tiêu đề block",
                    ordering: "Chọn thứ tự hiển thị block",
                    sort: "Chọn hiển thị sản phẩm theo",
                },
                submitHandler: function(form) {
                    if ($('.list-shocks input').length==0) {
                        malert('Vui lòng thêm sản phẩm', null, function () {
                            $('#fp_sku').focus();
                        });
                        return false;
                    }

                    submitHandler(form);
                    return false;
                }
            });

            function submitHandler(form) {
                // do other things for a valid form
                var data = $(form).serializeArray();
                var url = $(form).attr('action');
                ajax_loading(true);
                $.post(url, data).done(function(data){
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
                });
            }
        });
    </script>
@endsection