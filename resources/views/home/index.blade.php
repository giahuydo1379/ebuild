@extends('layouts.master')
<?php
$ordering_options = \App\Helpers\General::get_ordering_options();
$version_js = \App\Helpers\General::get_version_js();
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
                            <div class="add-block">
                                <label for="" class="title-block">Danh sách tab sản phẩm</label>
                                <ul class="list-product no-padding list-tabs quick"></ul>
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
                                    <div class="row" style="margin-left: -15px;">
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
                                    </div>
                                    <div class="row">
                                        <div class="add-block">
                                            <input type="hidden" id="tab_item" value="{{time()}}">

                                            <label for="" class="title-block">Thêm tab sản phẩm <span class="note">(Tối thiểu 01 tab, tối đa 06 tab sản phẩm)</span></label>
                                            <div class="row">
                                                <div class="col-md-4 haft-padding-left">
                                                    <div class="form-group">
                                                        <label for="">Tên tab sản phẩm</label>
                                                        <input name="tab_name" id="tab_name" type="text" class="form-control" placeholder="Điền tên tab sản phẩm">
                                                        <label id="tab_name-error" class="error" for="tab_name" style="display: none;"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Thứ tự hiển thị tab</label>
                                                        <div class="wrap_select">
                                                            {!! Form::select("tab_ordering", array(''=>'')+$ordering_options, null, ['id' => 'tab_ordering', 'class' => 'form-control']) !!}
                                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                        </div>
                                                        <label id="tab_ordering-error" class="error" for="tab_ordering" style="display: none;"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="add-block">
                                                    <label for="">Danh sách sản phẩm <span class="note">(Điền danh sách sản phẩm, mỗi sản phẩm cách nhau bởi dấu phẩy, Tối đa 05 sản phẩm)</span></label>
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
                                            <div class="text-right action">
                                                <a class="btn btn-del BtnSaveTabProduct"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm tab sản phẩm</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-block">
                                        <label for="" class="title-block">Danh sách tab sản phẩm</label>
                                        <ul class="list-product no-padding list-tabs"></ul>
                                    </div>
                                </div>
                                <div class="action text-right border-bottom">
                                    
                                    <input type="hidden" id="block" name="block" value="{{$block}}">
                                    <input type="hidden" id="id" name="id" value="{{@$object['id']}}">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <button type="submit" value="" class="btn btn_primary">Lưu</button>
                                </div>
                            </form>
                        </div>

                        <div class="create-landingpage ShowUpdateBanner" style="display: none;">
                            <form class="form-create home-manager no-padding" id="form_update_quick" method="post" action="{{ route("home.add") }}">
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
                                                    <label id="qtab_ordering-error" class="error" for="qtab_ordering" style="display: none;"></label>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="add-block">
                                        <label for="" class="title-block">Thêm sản phẩm<span class="note">(Tối đa 08 sản phẩm)</span></label>
                                        <label for="">Danh sách sản phẩm <span class="note">(Điền danh sách sản phẩm, mỗi sản phẩm cách nhau bởi dấu phẩy)</span></label>
                                        <div class="row">
                                            <div class="col-md-6 no-padding">
                                                <textarea class="form-control" placeholder="Điền danh sách sản phẩm" name="qtab_sku" id="qtab_sku" cols="30" rows="10"></textarea>
                                                <label id="qtab_sku-error" class="error" for="qtab_sku"></label>
                                            </div>
                                            <div class="col-md-6 text-left">
                                                <div class="info-sku-tab"></div>
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
        var _block = '{!! @$block!!}';        
        var _tabs = {!! @json_encode($tabs) !!};
        $(document).ready(function() {
            if (_tabs.length > 0) {                
                $('.list-products').show();
                $.each(_tabs, function (index, sp) {
                    $.post(_base_url + '/promotion/get-list-products-by-ids', {
                        ids: sp.products,
                    }, function (data) {
                        var sku = [];
                        $.each(data.products, function (index, item) {
                            sku.push(item.product_code);
                        });
                        sku.join(', ')
                        _tabs[index] = {
                            name: sp.name,
                            ordering: sp.ordering,
                            products: sp.products,
                            sku: sku
                        };
                        add_tab_product(index, sp.name, sp.ordering, sp.products,sku);
                    });
                })
            }
            $('#tab_sku').on('change', function () {
                ajax_loading(true);
                $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
                    ajax_loading(false);
                    var list = [];
                    var sku = [];
                    var tmp;
                    $.each(data.products, function (index, item) {
                        
                        list.push( get_sku_tab_product(item.name, item.product_id, item.sku) );
                        sku.push(item.sku);

                        tmp = data.sku.indexOf(item.sku);
                        data.sku.splice(tmp, 1);
                    });
                    if (list.length==0) {
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

            $('#qtab_sku').on('change', function () {
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

                        $('#qtab_sku-error').html('Không tìm thấy sản phẩm với các sku này: '+data.sku.join(', ')+'. Vui lòng kiểm tra lại').show();
                    } else {
                        if (data.sku.length > 0) {
                            $('#qtab_sku-error').html('Không tìm thấy sản phẩm với các sku sau: ' + data.sku.join(', ')+'. Vui lòng kiểm tra lại').show();
                        }
                        $('.info-sku-tab').html(list.join(', '));
                    }

                    $('#qtab_sku').val(sku.join(','));
                });
            });

            $('#form_update_quick .cancel').on('click', function () {
                $(".banner-promotion").slideDown();
                $('.ShowUpdateBanner').slideUp();
                $('.info-sku-tab').html('');
            });

            $('.UpdateAction').on('click', function () {
                $(".banner-promotion").slideUp();
                $(".CreatePromotion").slideDown();
                $('#form_update')[0].reset();
                $('.info-sku-tab').html('');
                $('.BtnSaveTabProduct').html('Thêm tab sản phẩm');
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
                $("#tab_sku" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập sản phẩm",
                    }
                });

                var flag1 = $("#tab_name").valid();
                var flag2 = $("#tab_sku").valid();
                var flag3 = $("#tab_ordering").valid();

                if ( flag1 && flag3 && flag2 ) {
                    var item = $('#tab_item').val();
                    var product_id = []
                    $('#form_update .product_id_tmp').each(function(){
                        product_id.push($(this).val());
                    });
                    _tabs[item] = {
                        name: $("#tab_name").val(),
                        sku: $("#tab_sku").val(),
                        ordering: $("#tab_ordering").val(),
                        products: product_id
                    };
                    console.log(_tabs[item]);
                    add_tab_product(item, $("#tab_name").val(), $("#tab_ordering").val(), product_id,$("#tab_sku").val());

                    $("#tab_item").val($.now());
                    $("#tab_name").val("");
                    $("#tab_sku").val("");
                    $("#tab_ordering").val("");
                    $('.info-sku-tab').html('');
                }

                $("#tab_name").rules( "remove" );
                $("#tab_sku").rules( "remove");
                $("#tab_ordering").rules("remove");                

                return false;
            });

            $('#form_update_quick').validate({
                ignore: ".ignore",
                rules: {
                    qtab_name: "required",
                    qtab_sku: "required",
                    qtab_ordering: "required"
                },
                messages: {
                    qtab_name: "Nhập tên banner",
                    qtab_sku: "Nhập sản phẩm",
                    qtab_ordering: "Chọn thứ tự hiển thị",
                },
                submitHandler: function(form) {
                    var item = $('#qtab_item').val();

                    var product_id = []
                    $('#form_update_quick .product_id_tmp').each(function(){
                        product_id.push($(this).val());
                    });
                    _tabs[item] = {
                        name: $("#qtab_name").val(),
                        sku: $("#qtab_sku").val(),
                        ordering: $("#qtab_ordering").val(),
                        products: product_id
                    };

                    add_tab_product(item, $("#qtab_name").val(), $("#qtab_ordering").val(),product_id, $("#qtab_sku").val());

                    $('#form_update').submit();

                    return false;
                }
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    ordering: "required",
                },
                messages: {
                    name: "Nhập tiêu đề block",
                    ordering: "Chọn thứ tự hiển thị block",
                },
                submitHandler: function(form) {
                    if ($('#form_update .list-tabs li').length==0) {
                        malert('Vui lòng thêm tab sản phẩm', null, function () {
                            $('#tab_name').focus();
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