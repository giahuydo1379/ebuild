@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-promotion">
            <h3 class="title-section">Khuyến mãi quà tặng</h3>
            <div class="panel box-panel">
                <div class="top">
                    <h3 class="title TitleCreate">Thêm mới chương trình khuyến mãi</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật chương trình khuyến mãi</h3>
                    <h3 class="title TitleDisplay" style="display: none;">Danh sách khuyến mãi</h3>
                </div>
                <div class="promotion" >
                    <div class="tab">
                        @include('microsites.menu')  
                        <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif>
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                    </div>

                    <div id="saleoff" class="tabcontent active" style="display: block;">
                        <?php
                        $layouts = \App\Helpers\General::get_miscrosite_layout();
                        $_objects = [];
                        $product_ids = [];
                        $brand_ids = [];
                        ?>
                        @if ($objects['total'])
                            <div class="banner-promotion banner-display">
                                <div class="table-display">
                                    <div class="header_table">
                                        <div class="col-md-7">
                                            <div class="col-md-4 ">
                                                <span>Tên chương trình</span>
                                            </div>
                                            <div class="col-md-4">
                                                <span>Loại giao diện</span>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <span>Thời gian áp dụng</span>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="col-md-4 no-padding">
                                                <span>Trạng thái</span>
                                            </div>
                                            <div class="col-md-4 no-padding text-center">
                                                <span>Dừng chương trình</span>
                                            </div>
                                            <div class="col-md-4 text-center">
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="promotion-gift panel-group" id="accordion3">
                                        @foreach($objects['data'] as $index => $item)
                                            <?php
                                            $item['from_time'] = \App\Helpers\General::output_time_of_date($item['from_date'], true);
                                            $item['to_time'] = \App\Helpers\General::output_time_of_date($item['to_date'], true);

                                            $item['from_date'] = \App\Helpers\General::output_date($item['from_date'], true);
                                            $item['to_date'] = \App\Helpers\General::output_date($item['to_date'], true);

                                            $item['banner'] = json_decode($item['banner'], 1);
                                            $item['banners_other'] = json_decode($item['banners_other'], 1);
                                            $item['products'] = json_decode($item['products'], 1);
                                            $item['floors_products'] = json_decode($item['floors_products'], 1);

                                            $_objects[$item['id']] = $item;
                                            ?>
                                            <li class="row panel">
                                                <div class="col-md-7">
                                                    <div class="col-md-4 content">
                                                        <a target="_blank" href="<?=\App\Helpers\General::get_link_gold_time($item);?>" title="{{$item['name']}}"><span>{{$item['name']}}</span></a>
                                                    </div>
                                                    <div class="col-md-4 content">
                                                        <span>{{$layouts[$item['layout']]}}</span>
                                                    </div>
                                                    <div class="col-md-4 text-center date-time">
                                                        <span>{{$item['from_date']}} - {{$item['from_time']}} <br> {{$item['to_date']}} - {{$item['to_time']}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="col-md-4">
                                                        <div class="wrapper tooltip">
                                                            <input type="checkbox" id="status-{{$index}}" class="slider-toggle" @if ($item['status']) checked @endif/>
                                                            <label class="slider-viewport" for="status-{{$index}}" onclick="return false;">
                                                                <div class="slider">
                                                                    <div class="slider-button">&nbsp;</div>
                                                                    <div class="slider-content left"><span>On</span></div>
                                                                    <div class="slider-content right"><span>Off</span></div>
                                                                </div>
                                                            </label>
                                                            <span class="tooltiptext">Chưa kích hoạt</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        @if ($item['status'])
                                                            <button class="btn btn-stop" data-id="{{$item['id']}}">Dừng</button>
                                                        @else
                                                            <button class="btn btn-start" data-id="{{$item['id']}}">Kích hoạt</button>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <a class="tooltip update-action" data-id="{{$item['id']}}">
                                                            <i class="icon-edit-pen active UpdateAction" aria-hidden="true" ></i>
                                                            <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                                            <span class="tooltiptext">Cập nhật</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @include('includes.paginator')
                                </div>
                            </div>
                        @else
                            <div class="no-banner">
                                <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                                <button class="btn add-action-none"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                            </div>
                        @endif

                        <div class="create-landingpage banner-update">
                            <form class="form-create landing-page frm_update" method="post" id="form_update" action="{{ route("microsites.add-gold-time") }}">
                                <input type="hidden" name="id" id="id" value="0">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                <div class="box">
                                    <div class="col-md-6 left-block">
                                        <h3 class="title-block">Thông tin chương trình</h3>
                                        <div class="body-block">
                                            <div class="form-group">
                                                <label for="">Tên chương trình/ Call to action page</label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Điền tên chương trình">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Link chương trình</label>
                                                <input readonly name="alias" id="alias" type="text" class="form-control" placeholder="Hệ thống tự auto generate link chương trình">
                                                <span class="note">https://dienmaythienhoa.vn/gt/< tenchuongtrinh>-< id>.html</span>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="lb-status">Trạng thái chương trình: </label>
                                                <div class="wrapper">
                                                    <input value="0" type="hidden" name="status"/>
                                                    <input value="1" type="checkbox" id="status" name="status" class="slider-toggle" checked />
                                                    <label class="slider-viewport" for="status">
                                                        <div class="slider">
                                                            <div class="slider-button">&nbsp;</div>
                                                            <div class="slider-content left"><span>On</span></div>
                                                            <div class="slider-content right"><span>Off</span></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group time-show">
                                                <label for="">Thời gian diễn ra chương trình:</label>
                                                <div class="row">
                                                    <div class="col-md-3 no-padding">
                                                        <span class="lb-time">Bắt đầu từ:</span>
                                                    </div>
                                                    <div class="col-md-3 no-padding">
                                                        <div class="input-group clockpicker">
                                                            <input readonly type="text" name="from_time" id="from_time" class="form-control" value="">
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-time"></span>
                                                            </span>
                                                        </div>
                                                        <label id="from_time-error" class="error" for="from_time"></label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="time">
                                                            <div class="input-group date">
                                                                <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày bắt đầu">
                                                                <span class="fa fa-calendar"></span>
                                                            </div>
                                                        </div>
                                                        <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 no-padding">
                                                        <span class="lb-time">Kết thúc lúc:</span>
                                                    </div>
                                                    <div class="col-md-3 no-padding">
                                                        <div class="input-group clockpicker">
                                                            <input readonly type="text" name="to_time" id="to_time" class="form-control" value="">
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-time"></span>
                                                            </span>
                                                        </div>
                                                        <label id="to_time-error" class="error" for="to_time"></label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="time">
                                                            <div class="input-group date">
                                                                <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày kết thúc">
                                                                <span class="fa fa-calendar"></span>
                                                            </div>
                                                        </div>
                                                        <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 right-block">
                                        <h3 class="title-block">GIAO DIỆN TRANG KHUYẾN MÃI GIỜ VÀNG GIÁ SỐC</h3>
                                        <div class="body-block">
                                            <ul class="list-radio">
                                                <li>
                                                    <div class="radio active">
                                                        <input id="radio1" value="normal" type="radio" name="layout" checked>
                                                        <label for="radio1">Giao diện bình thường. <span class="view">(Xem giao diện)</span></label>
                                                    </div>
                                                </li>
                                                {{--<li>--}}
                                                    {{--<div class="radio">--}}
                                                        {{--<input id="radio2" value="horizontal" type="radio" name="layout">--}}
                                                        {{--<label for="radio2">Giao diện menu ngang. <span class="view">(Xem giao diện)</span></label>--}}
                                                    {{--</div>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                    {{--<div class="radio">--}}
                                                        {{--<input id="radio3" value="vertical" type="radio" name="layout">--}}
                                                        {{--<label for="radio3">Giao diện menu dọc. <span class="view">(Xem giao diện)</span></label>--}}
                                                    {{--</div>--}}
                                                {{--</li>--}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="box">
                                    <h3 class="title-block">Banner trang khuyến mãi</h3>
                                    <div class="body-block">
                                        <div class="block">
                                            <strong>1 - Desktop</strong>
                                            <div class="part_create">
                                                <label id="image_location_desktop-error" class="error" for="image_location_desktop"></label>
                                                <div class="col-md-4 left-block">
                                                    <div class="image-upload">
                                                        <label for="file-input" class="browse-image" data-target="image_location_desktop">
                                                            <img src="/html/assets/images/img_upload.png" alt="">
                                                            <div class="wrap-bg" style="display: none;">
                                                                <img class="display " src="/html/assets/images/icon-camera.png" alt="Banner desktop">
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="infor-img">
                                                        <div class="show_file">
                                                            <span class="file_name"></span>
                                                            <input type="hidden" value="" name="banner[desktop][image_url]" id="image_url_desktop">
                                                            <input type="hidden" value="" name="banner[desktop][image_location]"
                                                                   data-url="#image_url_desktop"
                                                                   id="image_location_desktop" data-preview=".preview-banner-desktop">
                                                        </div>
                                                        <span class="size-note">Kích thước: <b>1920 x 450 px</b></span>
                                                        <span class="size-note">Dung lượng: <b>500kb</b></span>
                                                        <span class="size-note">Số lượng: <b>1 banner</b></span>
                                                        <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 right-block">
                                                    <div class="wrap_images image-upload">
                                                        <img class="preview-banner-desktop" src="" onerror="this.src='/assets/images/1920x450.png'" alt="Banner desktop" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block">
                                            <strong>2 - Tablet</strong>
                                            <div class="part_create">
                                                <label id="image_location_tablet-error" class="error" for="image_location_tablet"></label>
                                                <div class="col-md-4 left-block">
                                                    <div class="image-upload">
                                                        <label for="file-input" class="browse-image" data-target="image_location_tablet">
                                                            <img src="/html/assets/images/img_upload.png" alt="">
                                                            <div class="wrap-bg" style="display: none;">
                                                                <img class="display " src="/html/assets/images/icon-camera.png" alt="Banner Tablet" >
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="infor-img">
                                                        <div class="show_file">
                                                            <span class="file_name"></span>
                                                            <input type="hidden" value="" name="banner[tablet][image_url]" id="image_url_tablet">
                                                            <input type="hidden" value="" name="banner[tablet][image_location]"
                                                                   data-url="#image_url_tablet"
                                                                   id="image_location_tablet" data-preview=".preview-banner-tablet">
                                                        </div>
                                                        <span class="size-note">Kích thước: <b>768 x 250 px</b></span>
                                                        <span class="size-note">Dung lượng: <b>500kb</b></span>
                                                        <span class="size-note">Số lượng: <b>1 banner</b></span>
                                                        <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 right-block">
                                                    <div class="wrap_images image-upload">
                                                        <img class="preview-banner-tablet" src="" onerror="this.src='/assets/images/768x250.png'" alt="Banner Tablet" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block">
                                            <strong>3 - Mobile</strong>
                                            <div class="part_create">
                                                <label id="image_location_tablet-error" class="error" for="image_location_mobile"></label>
                                                <div class="col-md-4 left-block">
                                                    <div class="image-upload">
                                                        <label for="file-input" class="browse-image" data-target="image_location_mobile">
                                                            <img src="/html/assets/images/img_upload.png" alt="">
                                                            <div class="wrap-bg" style="display: none;">
                                                                <img class="display " src="/html/assets/images/icon-camera.png" alt="your image" >
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="infor-img">
                                                        <div class="show_file">
                                                            <span class="file_name"></span>
                                                            <input type="hidden" value="" name="banner[mobile][image_url]" id="image_url_mobile">
                                                            <input type="hidden" value="" name="banner[mobile][image_location]"
                                                                   data-url="#image_url_mobile"
                                                                   id="image_location_mobile" data-preview=".preview-banner-mobile">
                                                        </div>
                                                        <span class="size-note">Kích thước: <b>375 x 150 px</b></span>
                                                        <span class="size-note">Dung lượng: <b>500kb</b></span>
                                                        <span class="size-note">Số lượng: <b>1 banner</b></span>
                                                        <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 right-block">
                                                    <div class="wrap_images image-upload">
                                                        <img class="preview-banner-mobile" src="" onerror="this.src='/assets/images/375x150.png'" alt="landing page" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h3 class="title-block">Nội dung giới thiệu chương trình khuyến mãi</h3>
                                            <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Nhập nội dung giới thiệu chương trình khuyến mãi"></textarea>
                                            <label id="description-error" class="error" for="description" style="display: none;"></label>
                                        </div>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="box load-product-promotion LoadProductShock">
                                    <h3 class="title-block TitleCreate">Thêm Danh sách sản phẩm bán giá sốc</h3>
                                    <h3 class="title-block TitleUpdate" style="display: none;">Cập nhật Danh sách sản phẩm bán giá sốc</h3>
                                    <h3 class="title-block TitleDisplay" style="display: none;">Danh sách sản phẩm bán giá sốc</h3>
                                    <a href="javascript:void(0)" style="position: absolute;right: 100px;margin-top: -40px;"
                                       class="pull-right link add-shock-product"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>

                                    <div class="no-banner add-shock-product">
                                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                                        <a class="btn btn-load"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</a>
                                    </div>
                                    <div class="CreateLoad" style="display: none;">
                                        <div class="col-md-6 haft-padding-left">
                                            <div class="form-group">
                                                <label for="">SKU sản phẩm</label>
                                                <input name="p_sku" id="p_sku" type="text" class="form-control" placeholder="Điền SKU sản phẩm">
                                                <label id="p_sku-error" class="error" for="p_sku" style="display: none;"></label>
                                                <label id="p_product_id-error" class="error" for="p_product_id" style="display: none;"></label>
                                            </div>
                                            <div class="col-md-12 no-padding info-sku-shock">

                                            </div>
                                        </div>
                                        <div class="col-md-6 haft-padding-right">
                                            <div class="form-group time-show">
                                                <label for="">Thời gian bán giá sốc</label>
                                                <div class="row">
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
                                                <div class="row">
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
                                            </div>
                                        </div>
                                        <div class="action text-right">
                                            <span class="cancel cancel-shock">Hủy bỏ</span>
                                            <input type="hidden" id="p_item" value="0">
                                            <input type="hidden" id="p_product_id" value="">
                                            <input type="hidden" id="p_name" value="0">
                                            <input type="hidden" id="p_price" value="0">
                                            <input type="hidden" id="is_next_shock" value="0">
                                            <button type="button" value="" onclick="$('#is_next_shock').val(0)" class="btn btn_primary BtnSaveProductShock">lưu</button>
                                            <button type="button" value="" onclick="$('#is_next_shock').val(1)" class="btn btn_primary BtnSaveProductShock">Lưu & Thêm mới</button>
                                        </div>
                                    </div>
                                    <div class="display-product" style="display: none;">
                                        <h3 class="title-block TitleHide" style="display: none;">Danh sách sản phẩm bán giá sốc</h3>
                                        <ul class="list-pro no-padding list-shocks">
                                        </ul>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="box load-product-promotion LoadProduct">
                                    <h3 class="title-block TitleCreate">thêm tầng sản phẩm khuyến mãi (giảm giá/ có quà tặng/...)</h3>
                                    <h3 class="title-block TitleUpdate" style="display: none;">cập nhật tầng sản phẩm khuyến mãi (giảm giá/ có quà tặng/...)</h3>
                                    <h3 class="title-block TitleDisplay" style="display: none;">Danh sách tầng sản phẩm khuyến mãi (giảm giá/ có quà tặng/...)</h3>
                                    <a href="javascript:void(0)" style="position: absolute;right: 100px;margin-top: -40px;"
                                       class="pull-right link add-giff-product"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                                    <div class="no-banner add-giff-product">
                                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                                        <a class="btn btn-load"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</a>
                                    </div>
                                    <div class="CreateLoad" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Điền tầng sản phẩm</label>
                                                <input name="fp_name" id="fp_name" type="text" class="form-control" placeholder="Điền tên tầng sản phẩm">
                                                <label id="fp_name-error" class="error" for="fp_name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Thứ tự hiển thị</label>
                                                <div class="wrap_select">
                                                    {!! Form::select("fp_ordering",\App\Helpers\General::get_ordering_options(), '', [
                                                    'id' => 'fp_ordering', 'class' => 'form-control', 'placeholder' => 'Chọn thứ tự hiển thị']) !!}
                                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                </div>
                                                <label id="fp_ordering-error" class="error" for="fp_ordering" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Hiển thị sản phẩm theo</label>
                                                <div class="wrap_select">
                                                    <select name="fp_sort" id="fp_sort" class="form-control" placeholder="Chọn hiển thị sản phẩm theo">
                                                        <option value="price-asc">Giá từ thấp đến cao</option>
                                                        <option value="price-desc">Gia từ cao đến thấp</option>
                                                        <option value="number-desc">Số lượng</option>
                                                    </select>
                                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                </div>
                                                <label id="fp_sort-error" class="error" for="fp_sort" style="display: none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Danh sách sản phẩm khuyến mãi</label>
                                            <span class="note-color">(Điền mã SKU sản phẩm khuyến mãi, mỗi sản phẩm cách nhau bởi dấu phẩy)</span>
                                            <input type="hidden" id="fp_item" name="fp_item" value="">
                                            <textarea class="form-control" name="fp_sku" id="fp_sku" cols="30" rows="10" placeholder="Điền mã SKU sản phẩm khuyến mãi"></textarea>
                                            <label id="fp_sku-error" class="error" for="fp_sku" style="display: none;"></label>
                                            <div class="col-md-12 list-products" style="display: none;">
                                                <label for="">Danh sách sản phẩm khuyến mãi: </label>
                                                <div class="color"></div>
                                            </div>
                                        </div>
                                        <div class="action text-right">
                                            <span class="cancel cancel-giff">Hủy bỏ</span>
                                            <input type="hidden" id="is_next_giff" value="0">
                                            <button type="button" value="" onclick="$('#is_next_giff').val(0)" class="btn btn_primary BtnSaveProductGiff">lưu</button>
                                            <button type="button" value="" onclick="$('#is_next_giff').val(1)" class="btn btn_primary BtnSaveProductGiff">Lưu & Thêm mới</button>
                                        </div>
                                    </div>
                                    <div class="display-product DisplayLoad" style="display: none;">
                                        <h3 class="title-block TitleHide" style="display: none;">Danh sách sản phẩm khuyến mãi</h3>
                                        <ol class="list-level"></ol>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="box">
                                    <h3 class="title-block">Banner các chương trình khuyến mãi khác</h3>
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
                                            <div class="col-md-8 right-block right-banner-other">
                                                <div class="wrap_images image-upload"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="action text-right">
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" id="is_next" value="0">
                                    <span class="cancel main-cancel">Hủy bỏ</span>
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới</button>
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
$version_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')
    <script src="{{asset('assets/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.js')}}"></script>

    @include('includes.js-ckeditor')

    <script type="text/javascript" src="{{asset('js/microsite-gold-time.js?v='.$version_js)}}"></script>

    <script type="text/javascript">
        var _objects = {!! @json_encode($_objects) !!};
        var _product_ids = {!! @json_encode($product_ids) !!};
        var _products = [];
        var _floors_products = [];
        var _shocks_products = [];
        $(document).ready(function() {

            $('.clockpicker').clockpicker({
                autoclose: true
            });

        });
    </script>
@endsection