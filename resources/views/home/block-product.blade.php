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
                            <div class="add-block content-block-banner">
                                <label for="" class="title-block">Banner hiển thị</label>
                                <div class="wrap_images image-upload"></div>
                            </div>
                            <div class="add-block">
                                <label for="" class="title-block">Danh sách sản phẩm</label>
                                <div class="product show-sku-tab"></div>
                            </div>
                            <div class="add-block" style="padding-top: 10px;">
                                <label for="" class="title-block">Link xem thêm</label>
                                <a href="" class="link_more"></a>
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
                            <form class="form-create home-manager no-padding landing-page" id="form_update" method="post" action="{{ route("home.add") }}">
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
                                    <div class="row">
                                        <div class="add-block">
                                            <label for="" class="title-block">Liên kết nút "xem thêm"</label>
                                            <label for="">Link liên kết</label>
                                            <div class="row">
                                                <div class="col-md-6 no-padding">
                                                    <input name="link_more" id="link_more" value="" type="text" class="form-control" placeholder="Nhập link liên kết">
                                                    <label id="link_more-error" class="error" for="link_more" style="display: none;"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h3 class="title-block">Banner</h3>
                                        <div class="body-block">
                                            <div class="part_create block">
                                                <div class="col-md-4 left-block">
                                                    <div class="left-banner-other">
                                                        <div class="image-upload">
                                                            <label for="file-input" class="browse-image" data-target="bo_location">
                                                                <img class="preview-banner-other" src="/html/assets/images/img_upload.png" alt="">
                                                                <div class="wrap-bg" style="display: none;">
                                                                    <img class="display " src="/html/assets/images/icon-camera.png" alt="Banner other" >
                                                                </div>
                                                            </label>
                                                        </div>
                                                        <div class="infor-img">
                                                            <div class="show_file">
                                                                <span class="file_name"></span>
                                                                <input type="hidden" value="" id="bo_url">
                                                                <input type="hidden" value="" id="bo_location"
                                                                       data-url="#bo_url"
                                                                       data-preview=".preview-banner-other">
                                                            </div>
                                                            <span class="size-note">Kích thước: <b>543 x 238 px</b></span>
                                                            <span class="size-note">Dung lượng: <b>500kb</b></span>
                                                            <span class="size-note">Số lượng: <b>6 banner</b></span>
                                                            <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Link banner</label>
                                                        <input type="text" id="bo_link" class="form-control" placeholder="Nhập link banner">
                                                        <label for="" class="banner-other error" style="display: none;"></label>
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <a class="btn button-update add-banner-other">Thêm banner</a>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="block right-block right-banner-other">
                                                <label for="">Danh sách banners</label>
                                                <div class="wrap_images image-upload"></div>
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

@section('after_styles')
<style type="text/css">
    .part_create .image-upload label{
        position: relative !important;
    }
    .part_create .wrap_images{
        height: auto;
    }
    .wrap_images img{
        max-height: 100px;
    }
    .wrap_images .row{
        padding: 10px;
    }
</style>
@stop

@section('after_scripts')
    <script type="text/javascript" src="/js/home.js?v={{$version_js}}"></script>
@include('includes.js-ckeditor')
    <script type="text/javascript">
        var _block = '{!! @$block.'_'.$sort !!}';
        var _products = {!! @json_encode($products) !!};
        var _content = {!! @json_encode($content) !!};
        var _banner = {!! @json_encode($banner) !!};

        $(document).ready(function() {
            if(_content){
                $('.link_more').attr('href',_content.link_more);
                $('.link_more').html(_content.link_more);
            }
            if (_banner) {
                $.each(_banner, function (index, other) {
                    add_banner_index(other.image_location, other.image_url, other.link, index);
                });                    
            }
            $('.add-banner-other').on('click', function () {
                var bo_url = $('#bo_url').val();
                var bo_location = $('#bo_location').val();
                var bo_link = $('#bo_link').val();

                if (bo_location=='' || bo_link=='') {
                    $('.banner-other.error').html('Chọn ảnh và nhập link cho banner').show();
                    $('#bo_link').focus();

                    return false;
                }
                $('.banner-other.error').hide();

                add_banner_other(bo_location, bo_url, bo_link, $.now());

                $('#bo_url').val('');
                $('#bo_location').val('');
                $('#bo_link').val('');
                $('.preview-banner-other').attr('src', '/html/assets/images/img_upload.png');
            });

            $(document).on('click', '.delete-banner-so', function () {
                $(this).closest('.delete_row').remove();
            });

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
                if (_banner) {
                    $.each(_banner, function (index, other) {
                        add_banner_other(other.image_location, other.image_url, other.link, index);
                    });                    
                }



                $('#link_more').val(_content.link_more);
            });

            $('.Cancel').on('click', function () {
                $(".banner-promotion").slideDown();
                $(".CreatePromotion").slideUp();

                $('#form_update .right-block .wrap_images').html('');
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
                   if ($('#form_update .right-block .wrap_images img').length==0) {
                        malert('Vui lòng thêm banner', null, function () {
                            $('#bo_link').focus();
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
        function add_banner_other(location, url, link, index) {
            var html = '<div class="row delete_row"><div class="col-md-3">' +
                '<span class="delete-banner-so" aria-hidden="true">×</span>' +
                '<img src="'+url+location+'" alt="Banner" />' +
                
                '</div>';
                html+='<div class="col-md-9">'+
                '<input type="hidden" value="'+location+'" name="banner['+index+'][image_location]"/>' +
                '<input type="hidden" value="'+url+'" name="banner['+index+'][image_url]"/>' +
                '<input type="hidden" value="'+link+'" name="banner['+index+'][link]"/ readonly>' +
                '<p><a href="'+link+'">'+link+'</a></p>'+
                '</div></div>';

            $('.right-banner-other .wrap_images').append(html);
        }
        function add_banner_index(location, url, link, index){
            var html = '<div class="row"><div class="col-md-3"><img src="'+url+location+'" alt="Banner" /></div><div class="col-md-9"><a href="'+link+'">'+link+'</a></div></div>';                
            $('.content-block-banner .wrap_images').append(html);
        }
    </script>
@endsection