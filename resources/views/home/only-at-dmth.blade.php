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
                            <label for="" class="title-table">Danh sách banner</label>
                            <div class="table-display">
                                <div class="header_table">
                                    <div class="col-md-6">
                                        <div class="col-md-2">
                                            <input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check">
                                        </div>
                                        <div class="col-md-5 no-padding">
                                            <span>Tên banner</span>
                                        </div>
                                        <div class="col-md-5">
                                            <span>Hình ảnh banner</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 no-padding ">
                                        <div class="col-md-5 text-left">
                                            <span>Link</span>
                                        </div>
                                        <div class="col-md-5 no-padding">
                                            <span>Trạng thái</span>
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                    </div>
                                </div>
                                <ul class="category_product list-banners quick"></ul>
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
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="add-block">
                                                <label for="" class="title-block">Thêm banner <span class="note">(Tối thiểu 01 banner, tối đa 03 banner)</span></label>
                                                <div class="form-group">
                                                    <label for="">Tên banner</label>
                                                    <input name="b_name" id="b_name" type="text" class="form-control" placeholder="Điền tên banner">
                                                    <label id="b_name-error" class="error" for="b_name"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Link banner</label>
                                                    <input name="b_link" id="b_link" type="text" class="form-control" placeholder="Nhập link banner">
                                                    <label id="b_link-error" class="error" for="b_link"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Trạng thái banner</label>
                                                    <div class="wrapper">
                                                        <input type="checkbox" id="b_status" class="slider-toggle" checked/>
                                                        <label class="slider-viewport" for="b_status">
                                                            <div class="slider">
                                                                <div class="slider-button">&nbsp;</div>
                                                                <div class="slider-content left"><span>On</span></div>
                                                                <div class="slider-content right"><span>Off</span></div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="choose-banner">
                                                        <label>Chọn banner</label>
                                                        <label id="b_location-error" class="error" for="b_location" style="display: none;"></label>
                                                        <div class="wrap-choose">
                                                            <ul class="wrap_btn">
                                                                <li>
                                                                    <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="b_location">
                                                                        <i class="icon-browser">&nbsp;</i>
                                                                        <span>Browse ...</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="img-infor">
                                                        <input type="hidden" id="b_item" value="{{time()}}">
                                                        <input type="hidden" value="" name="b_url" id="b_url">
                                                        <input type="hidden" value="" name="b_location" id="b_location"
                                                               data-url="#b_url" data-preview=".preview-b-banner">
                                                        <span class="size-note">Kích thước: <b>360 x 300 px</b></span>
                                                        <span class="size-note">Dung lượng: <b>500kb</b></span>
                                                        <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="display-banner">
                                                <img class="preview-b-banner" onerror="this.src='/assets/images/360x300.png'" src="" alt="Chỉ có tại {{config('app.name')}}" />
                                            </div>
                                            <div class="text-right action">
                                                <a class="btn btn-del BtnSaveBanner"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm banner</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="" class="title-table">Danh sách banner</label>
                                        <div class="table-display">
                                            <div class="header_table">
                                                <div class="col-md-6">
                                                    <div class="col-md-2">
                                                        <input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check">
                                                    </div>
                                                    <div class="col-md-5 no-padding">
                                                        <span>Tên banner</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <span>Hình ảnh banner</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 no-padding ">
                                                    <div class="col-md-5 text-left">
                                                        <span>Link</span>
                                                    </div>
                                                    <div class="col-md-5 no-padding">
                                                        <span>Trạng thái</span>
                                                    </div>
                                                    <div class="col-md-2">
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="category_product list-banners"></ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="action text-right border-bottom">
                                    <input type="hidden" id="sort" name="sort" value="0">
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
                                <input type="hidden" id="qb_item" value="{{time()}}">

                                <div class=" field-form">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="add-block">
                                                <label for="" class="title-block">Cập nhật banner</label>
                                                <div class="form-group">
                                                    <label for="">Tên banner</label>
                                                    <input name="qb_name" id="qb_name" type="text" class="form-control" placeholder="Điền tên banner">
                                                    <label id="qb_name-error" class="error" for="qb_name" style="display: none;"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Link banner</label>
                                                    <input name="qb_link" id="qb_link" type="text" class="form-control" placeholder="Nhập link banner">
                                                    <label id="qb_link-error" class="error" for="qb_link" style="display: none;"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Trạng thái banner</label>
                                                    <div class="wrapper">
                                                        <input value="1" type="checkbox" id="qb_status" name="qb_status" class="slider-toggle"/>
                                                        <label class="slider-viewport" for="qb_status">
                                                            <div class="slider">
                                                                <div class="slider-button">&nbsp</div>
                                                                <div class="slider-content left"><span>On</span></div>
                                                                <div class="slider-content right"><span>Off</span></div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="choose-banner">
                                                        <label>Chọn banner</label>
                                                        <label id="qb_location-error" class="error" for="qb_location" style="display: none;"></label>
                                                        <div class="wrap-choose">
                                                            <ul class="wrap_btn">
                                                                <li>
                                                                    <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="qb_location">
                                                                        <i class="icon-browser">&nbsp;</i>
                                                                        <span>Browse ...</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="img-infor">
                                                        <input type="hidden" value="" name="qb_url" id="qb_url">
                                                        <input type="hidden" value="" name="qb_location" id="qb_location"
                                                               data-url="#qb_url" data-preview=".preview-qb-banner">

                                                        <span class="size-note">Kích thước: <b>360 x 300 px</b></span>
                                                        <span class="size-note">Dung lượng: <b>500kb</b></span>
                                                        <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="display-banner">
                                                <img class="preview-qb-banner" onerror="this.src='/assets/images/360x300.png'" src="" alt="Chỉ có tại {{config('app.name')}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end box -->
                                <div class="action text-right">
                                    <span class="cancel">Hủy bỏ</span>
                                    <button type="submit" class="btn btn_primary">Cập nhật banner</button>
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

@section('after_scripts')
    <script src="/assets/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.js"></script>

    @include('includes.js-ckeditor')

    <script type="text/javascript" src="/js/home.js?v={{$version_js}}"></script>

    <script type="text/javascript">
        var _block = '{!! @$block !!}';
        var _products = {!! @json_encode($products) !!};
        var _banners_only = {!! isset($object['content'])?$object['content']:'[]' !!};

        $(document).ready(function() {
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

            if (_banners_only.length > 0) {
                $.each(_banners_only, function (index, sp) {
                    add_banner_only(index, sp.name, sp.url, sp.location, sp.link, sp.status);
                });
            }

            $('.BtnSaveBanner').on('click', function () {
                $("#b_name" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Điền tên banner",
                    }
                });
                $("#b_link" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập link liên kết",
                    }
                });
                $("#b_location" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Chọn ảnh hiển thị",
                    }
                });

                var flag1 = $("#b_name").valid();
                var flag2 = $("#b_link").valid();
                var flag3 = $("#b_location").valid();

                if ( flag1 && flag2 && flag3 ) {
                    var item = $('#b_item').val();

                    _banners_only[item] = {
                        name: $("#b_name").val(),
                        link: $("#b_link").val(),
                        location: $("#b_location").val(),
                        url: $("#b_url").val(),
                        status: $("#b_status").is(':checked')?'1':'0',
                    };

                    add_banner_only(item, $("#b_name").val(), $("#b_url").val(), $("#b_location").val(), $("#b_link").val(),
                        _banners_only[item]['status']);

                    $("#b_item").val($.now());
                    $("#b_name").val("");
                    $("#b_link").val("");
                    $("#b_url").val("");
                    $("#b_location").val("");

                    $('.preview-b-banner').attr("src", "");
                }

                $("#b_name").rules( "remove" );
                $("#b_link").rules( "remove");
                $("#b_location").rules("remove");

                return false;
            });

            $('#form_update_quick').validate({
                ignore: ".ignore",
                rules: {
                    qb_name: "required",
                    qb_link: "required",
                    qb_location: "required"
                },
                messages: {
                    qb_name: "Nhập tên banner",
                    qb_link: "Nhập link liên kết",
                    qb_location: "Chọn ảnh hiển thị",
                },
                submitHandler: function(form) {
                    var item = $('#qb_item').val();

                    _banners_only[item] = {
                        name: $("#qb_name").val(),
                        link: $("#qb_link").val(),
                        location: $("#qb_location").val(),
                        url: $("#qb_url").val(),
                        status: $("#qb_status").is(':checked')?'1':'0',
                    };

                    add_banner_only(item, $("#qb_name").val(), $("#qb_url").val(), $("#qb_location").val(), $("#qb_link").val(),
                        _banners_only[item]['status']);

                    $('#form_update').submit();

                    return false;
                }
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
                    if ($('#form_update .list-banners li').length==0) {
                        malert('Vui lòng thêm banner', null, function () {
                            $('#b_name').focus();
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