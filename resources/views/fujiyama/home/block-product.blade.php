@extends('layouts.master')
<?php
$ordering_options = \App\Helpers\General::get_ordering_options();
$version_js = \App\Helpers\General::get_version_js();
?>
@section('content')
    <div class="col-md-">
        <section class="section section-homepage">
            <h3 class="title-section">Quản lý trang chủ - Tab sản phẩm</h3>
            <div class="panel box-panel">
                <div class="home-management" >
                    @include('fujiyama.home.tabs')

                    <div id="tab1" class="tabcontent active" style="display: block;">
                        @if ($object)
                        <div class="banner-promotion">
                            <ul class="list-infor">
                                <li><label for="">Tiêu đề block: </label> <b class="color">{{$object['name']}}</b></li>
                                <li><label for="">Thứ tự hiển thị block: </label> <span>Vị trí {{$ordering_options[$object['ordering']]}}</span></li>
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
                            <div class="add-block">
                                <label for="" class="title-block">Danh sách sản phẩm</label>
                                <div class="product show-sku-tab"></div>
                            </div>
                            <div class="action text-right">
                                <button type="button" value="" class="btn btn_primary UpdateAction">Cập nhật</button>
                            </div>
                        </div>
                        @else
                        <div class="no-banner NoDataPromotion">
                            <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                            <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                        </div>
                        @endif
                        <div class="create-landingpage CreatePromotion" style="display: none;">
                            <form class="form-create home-manager no-padding" id="form_update" method="post" action="{{ route("fujiyama.home.add") }}">
                                <div class=" field-form">
                                    <div class="row">
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
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
                                        <div style="clear: both"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Link liên kết</label>
                                                <input name="link" id="link" value="{{@$object['link']}}" type="text" class="form-control" placeholder="Nhập link liên kết">
                                                <label id="link-error" class="error" for="link" style="display: none;"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="add-block">
                                            <label for="" class="title-block">Thêm sản phẩm<span class="note">(Tối đa 08 sản phẩm)</span></label>
                                            <label for="">Danh sách sản phẩm <span class="note">(Điền danh sách sản phẩm, mỗi sản phẩm cách nhau bởi dấu phẩy)</span></label>
                                            <div class="row">
                                                <div class="col-md-6 no-padding">
                                                    <textarea class="form-control" placeholder="Điền danh sách sản phẩm" name="tab_sku" id="tab_sku" cols="30" rows="10"></textarea>
                                                    <label id="tab_sku-error" class="error" for="tab_sku"></label>
                                                </div>
                                                <div class="col-md-6 text-left">
                                                    <div class="info-sku-tab"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="action text-right border-bottom">
                                    <input type="hidden" id="sort" name="sort" value="{{$sort}}">
                                    <input type="hidden" id="block" name="block" value="{{$block}}">
                                    <input type="hidden" id="id" name="id" value="{{@$object['id']}}">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <button type="submit" value="" class="btn btn_primary">Lưu</button>
                                </div>
                            </form>
                        </div>

                        <div class="create-landingpage ShowUpdateBanner" style="display: none;">
                            <form class="form-create home-manager no-padding" id="form_update_quick" method="post" action="{{ route("fujiyama.home.add") }}">
                                <div class="field-form">
                                    <div class="add-block">
                                        <input type="hidden" id="qtab_item" value="{{time()}}">

                                        <label for="" class="title-block">Thêm tab sản phẩm <span class="note">(Tối thiểu 01 tab, tối đa 06 tab sản phẩm)</span></label>
                                        <div class="row">
                                            <div class="col-md-4 haft-padding-left">
                                                <div class="form-group">
                                                    <label for="">Tên tab sản phẩm</label>
                                                    <input name="qtab_name" id="qtab_name" type="text" class="form-control" placeholder="Điền tên tab sản phẩm">
                                                    <label id="qtab_name-error" class="error" for="qtab_name" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Thứ tự hiển thị tab</label>
                                                    <div class="wrap_select">
                                                        {!! Form::select("qtab_ordering", array(''=>'')+$ordering_options, null, ['id' => 'qtab_ordering', 'class' => 'form-control']) !!}
                                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                    </div>
                                                    <label id="tab_ordering-error" class="error" for="tab_ordering" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 haft-padding-right">
                                                <div class="form-group">
                                                    <label for="">Link</label>
                                                    <input name="qtab_link" id="qtab_link" type="text" class="form-control" placeholder="Điền link tab sản phẩm">
                                                    <label id="qtab_link-error" class="error" for="qtab_link" style="display: none;"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="action text-right">
                                    <span class="cancel">Hủy bỏ</span>
                                    <button type="submit" class="btn btn_primary">Cập nhật tab sản phẩm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript" src="/js/home.js?v={{$version_js}}"></script>

    <script type="text/javascript">
        var _block = '{!! @$block.'_'.$sort !!}';
        var _products = {!! @json_encode($products) !!};
        var _product_tabs = {!! isset($product_tabs)?json_encode($product_tabs):'[]' !!};

        $(document).ready(function() {
            if (_products.length > 0) {
                $('.list-products').show();
                var list = [];
                var sku = [];
                $.each(_products, function (index, item) {
                    list.push( get_sku_tab_product(item.name, item.product_id, item.sku) );
                    sku.push(item.sku);
                });
                $('.info-sku-tab').html(list.join(', '));
                $('.show-sku-tab').html(list.join(', '));
                $('#tab_sku').val(sku.join(','));
            }
            if (_product_tabs.length > 0) {
                $.each(_product_tabs, function (index, sp) {
                    add_tab_product(index, sp.name, sp.ordering, sp.link);
                });
            }

            $('#tab_sku').on('change', function () {
                ajax_loading(true);
                $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
                    ajax_loading(false);
                    var list = [];
                    var sku = [];
                    var tmp;
                    $.each(data.products, function (index, item) {
                        // if (index==3) {
                        //     malert('Tối đa 3 sản phẩm');
                        //     return;
                        // }
                        list.push( get_sku_tab_product(item.name, item.product_id, item.sku) );
                        sku.push(item.sku);

                        tmp = data.sku.indexOf(item.sku);
                        data.sku.splice(tmp, 1);
                    });
                    if (list.length==0) {
//                        $('.info-sku-tab').html('Không tìm thấy sản phẩm');
                        $('#tab_sku-error').html('Không tìm thấy sản phẩm với các sku này: '+data.sku.join(', ')+'. Vui lòng kiểm tra lại').show();
                    } else {
                        if (data.sku.length > 0) {
                            $('#tab_sku-error').html('Không tìm thấy sản phẩm với các sku sau: ' + data.sku.join(', ')+'. Vui lòng kiểm tra lại').show();
                        }
                        $('.info-sku-tab').html(list.join(', '));
                    }

                    $('#tab_sku').val(sku.join(','));
                });
            });

            $('#form_update_quick .cancel').on('click', function () {
                $(".banner-promotion").slideDown();
                $('.ShowUpdateBanner').slideUp();
            });

            $('.UpdateAction').on('click', function () {
                $(".banner-promotion").slideUp();
                $(".CreatePromotion").slideDown();
            });

            $('.Cancel').on('click', function () {
                $(".banner-promotion").slideDown();
                $(".CreatePromotion").slideUp();
            });

            $('.BtnSaveTabProduct').on('click', function () {
                $("#tab_name" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập tên tab sản phẩm",
                    }
                });
                $("#tab_ordering" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Chọn thứ tự hiển thị",
                    }
                });
                $("#tab_link" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập link liên kết",
                    }
                });

                var flag1 = $("#tab_name").valid();
                var flag2 = $("#tab_link").valid();
                var flag3 = $("#tab_ordering").valid();

                if ( flag1 && flag3 && flag2 ) {
                    var item = $('#tab_item').val();

                    _product_tabs[item] = {
                        name: $("#tab_name").val(),
                        link: $("#tab_link").val(),
                        ordering: $("#tab_ordering").val(),
                    };

                    add_tab_product(item, $("#tab_name").val(), $("#tab_ordering").val(), $("#tab_link").val());

                    $("#tab_item").val($.now());
                    $("#tab_name").val("");
                    $("#tab_link").val("");
                    $("#tab_ordering").val("");
                }

                $("#tab_name").rules( "remove" );
                $("#tab_link").rules( "remove");
                $("#tab_ordering").rules("remove");

                return false;
            });

            $('#form_update_quick').validate({
                ignore: ".ignore",
                rules: {
                    qtab_name: "required",
                    qtab_link: "required",
                    qtab_ordering: "required"
                },
                messages: {
                    qtab_name: "Nhập tên banner",
                    qtab_link: "Nhập link liên kết",
                    qtab_ordering: "Chọn thứ tự hiển thị",
                },
                submitHandler: function(form) {
                    var item = $('#qtab_item').val();

                    _product_tabs[item] = {
                        name: $("#qtab_name").val(),
                        link: $("#qtab_link").val(),
                        ordering: $("#qtab_ordering").val(),
                    };

                    add_tab_product(item, $("#qtab_name").val(), $("#qtab_ordering").val(), $("#qtab_link").val());

                    $('#form_update').submit();

                    return false;
                }
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    ordering: "required",
                    tab_sku: "required",
                },
                messages: {
                    name: "Nhập tiêu đề block",
                    ordering: "Chọn thứ tự hiển thị block",
                    tab_sku: "Nhập skus sản phẩm",
                },
                submitHandler: function(form) {
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