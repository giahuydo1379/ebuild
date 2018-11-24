@extends('layouts.master')
<?php
$ordering_options = \App\Helpers\General::get_ordering_options();
?>

@section('content')
    <div class="col-md-">
        <section class="section section-homepage">
            <h3 class="title-section">Quản lý trang chủ</h3>
            <div class="panel box-panel">
                <div class="home-management" >
                    @include('home.tabs')

                    <div id="tab5" class="tabcontent active" style="display: block;">
                        @if ($object)
                        <div class="banner-promotion DisplayPromotion">
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
                            <label for="" class="title-table">Danh sách logo & sản phẩm yêu thích</label>
                            <div class="table-display">
                                <div class="header_table">
                                    <div class="col-md-6">
                                        <div class="col-md-2">
                                            <input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check">
                                        </div>
                                        <div class="col-md-3 no-padding">
                                            <span>Tên logo</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span>Hình ảnh logo</span>
                                        </div>
                                        <div class="col-md-3 no-padding text-center">
                                            <span>Vị trí hiển thị</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 no-padding ">
                                        <div class="col-md-7 text-left">
                                            <span>Sản phẩm yêu thích</span>
                                        </div>
                                        <div class="col-md-3 no-padding">
                                            <span>Trạng thái</span>
                                        </div>
                                        <div class="col-md-2">
                                        </div>
                                    </div>
                                </div>
                                <ul class="category_product quick">
                                    @foreach($tab_brand as $key => $value)
                                    <li class="row logo-item">
                                        <div class="col-md-6">
                                            <div class="col-md-2">
                                                <input type="checkbox" name="choose"  class="checkbox_check">
                                            </div>
                                            <div class="col-md-3 no-padding content">
                                                <span>{{$value['name']}}</span>
                                            </div>
                                            <div class="col-md-4 wrap-banner">
                                                <div class="inside-logo">
                                                    <img src="{{$value['url'].$value['location']}}" alt="thien hoa">
                                                </div>
                                            </div>
                                            <div class="col-md-3 no-padding text-center">
                                                <span>Vị trí {{$value['ordering']}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 no-padding">
                                            <div class="col-md-7 content no-padding">
                                                <span>
                                                @foreach($value['product_ids'] as $product_id)
                                                {{$list_product_name[$product_id]}},
                                                @endforeach
                                                </span>
                                            </div>   
                                            <div class="col-md-3">
                                                <div class="wrapper tooltip">
                                                  <input type="checkbox" class="slider-toggle" onclick="return false;" onkeydown="return false;" {!!($value['status'] == 1)?'checked':''!!} />
                                                    <label class="slider-viewport" for="checkbox">
                                                        <div class="slider">
                                                            <div class="slider-button">&nbsp;</div>
                                                            <div class="slider-content left"><span>On</span></div>
                                                            <div class="slider-content right"><span>Off</span></div>
                                                        </div>
                                                    </label>
                                                    <span class="tooltiptext">Chưa kích hoạt</span>
                                                </div>
                                            </div>                            
                                            <div class="col-md-2">
                                                <a class="tooltip btn_update_quick" data-id="{{$key}}">
                                                    <i class="icon-edit-pen active UpdateAction1" aria-hidden="true"></i>
                                                    <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                                    <span class="tooltiptext">Cập nhật</span>
                                                </a>
                                                <a class="tooltip btn_delete_quick" onclick="delete_brand_popular(this,{{$key}})">
                                                    <i class="icon-delete active DeleteAction" aria-hidden="true"></i>
                                                    <i class="icon-delete-hover DeleteHover" title="Xóa">&nbsp</i>
                                                    <span class="tooltiptext">Xóa</span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>                                
                            </div>    
                        </div>
                        @else
                        <div class="no-banner NoDataPromotion" >
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
                                                <label for="" class="title-block">Thêm logo thương hiệu <span class="note">(Tối thiểu = Tối đa 08 banner)</span></label>
                                                <div class="form-group">
                                                    <label for="">Tên logo</label>
                                                    <input type="text" class="form-control" placeholder="Điền tên logo" id="b_name" name="b_name">
                                                    <label id="b_name-error" class="error" for="b_name"></label>
                                                </div>      
                                                <div class="form-group">
                                                    <label for="">Thứ tự hiển thị logo</label>
                                                    <div class="wrap_select">
                                                        {!! Form::select("b_ordering", $ordering_options,"", ['id' => 'b_ordering', 'class' => 'form-control']) !!}
                                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                    </div>
                                                </div>              
                                                <div class="form-group">
                                                    <label for="">Link</label>
                                                    <input type="text" class="form-control" placeholder="Nhập link logo" id="b_link" name="b_link">
                                                    <label id="b_link-error" class="error" for="b_link"></label>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Trạng thái logo</label>
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
                                        </div>
                                    </div>
                                    <div class="row">   
                                        <div class="add-block">
                                            <label for="" class="title-block">Thêm sản phẩm yêu thích<span class="note">(Tối thiểu = tối đa 06 sản phẩm)</span></label>
                                            <label for="">Danh sách sản phẩm yêu thích <span class="note">(Điền danh sách sản phẩm yêu thích, mỗi sản phẩm cách nhau bởi dấu phẩy)</span></label>
                                            <div class="row">
                                                <textarea class="form-control" name="fp_sku" id="fp_sku" cols="30" rows="10" placeholder="Điền mã SKU sản phẩm (tối thiểu 05 sản phẩm, tối đa 20 sản phẩm)"></textarea>
                                                <label id="fp_sku-error" class="error" for="fp_sku" style="display: none;"></label>
                                                <div class="col-md-12 list-products" style="display: none;">
                                                    <label for="">Danh sách sản phẩm tìm thấy: </label>
                                                    <div class="color"></div>
                                                </div>
                                            </div>
                                            <div class="text-right action">
                                                <a class="btn btn-del BtnSaveProductShock"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm logo & Sản phẩm yêu thích</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-block">
                                        <label for="" class="title-block">Danh sách thương hiệu và sản phẩm yêu thích</label>
                                        <ul class="list-product no-padding list-shocks">
                                        </ul>
                                    </div>
                                </div>
                                <div class="action text-right border-bottom">
                                    <input type="hidden" id="b_item" value="{{time()}}">
                                    <input type="hidden" id="block" name="block" value="{{$block}}">
                                    <input type="hidden" id="id" name="id" value="{{@$object['id']}}">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <button type="submit" value="" class="btn btn_primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                        <div class="create-landingpage UpatePromotion1" style="display: none;">
                            <form class="form-create home-manager no-padding" id="form_update_quick">
                                <input type="hidden" name="q_item" id="q_item">
                                <div class=" field-form">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="add-block">
                                                <label for="" class="title-block">Cập nhật logo thương hiệu</label>
                                                <div class="form-group">
                                                    <label for="">Tên logo</label>
                                                    <input type="text" class="form-control" placeholder="Điền tên logo" id="q_name" name="q_name">
                                                </div>      
                                                <div class="form-group">
                                                    <label for="">Thứ tự hiển thị logo</label>
                                                    <div class="wrap_select">
                                                        {!! Form::select("q_ordering", $ordering_options, @$object['ordering'], ['id' => 'q_ordering', 'class' => 'form-control']) !!}
                                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                    </div>
                                                </div>              
                                                <div class="form-group">
                                                    <label for="">Link</label>
                                                    <input type="text" class="form-control" placeholder="Nhập link logo" id="q_link" name="q_link">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Trạng thái logo</label>
                                                    <div class="wrapper">
                                                      <input type="checkbox" id="q_status" name="q_status" class="slider-toggle" checked />
                                                        <label class="slider-viewport" for="q_status">
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
                                                        <label>Chọn logo</label>
                                                        <div class="wrap-choose">
                                                            <ul class="wrap_btn">
                                                                <li>
                                                                    <a href="#" class="btn-loadfile browse-image" data-target="q_location">
                                                                        <i class="icon-browser">&nbsp;</i>
                                                                        <span>Browse ...</span>
                                                                        <input type="file" name="newfile" onchange="readURL4(this);">
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div> 
                                                    </div>
                                                    <div class="img-infor">
                                                        <input type="hidden" value="" name="q_url" id="q_url">
                                                        <input type="hidden" value="" name="q_location" id="q_location"
                                                               data-url="#q_url" data-preview=".preview-qb-banner">
                                                        <span class="size-note">Kích thước: <b>132 x 100 px</b></span>
                                                        <span class="size-note">Dung lượng: <b>500kb</b></span>
                                                        <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                                    </div>
                                                </div>      
                                            </div>
                                        </div>
                                        <div class="col-md-5">                         
                                            <div class="display-banner upload-logo">
                                                <img class="preview-qb-banner" src="" alt="thien hoa promotion" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">   
                                        <div class="add-block">
                                            <label for="" class="title-block">Thêm sản phẩm yêu thích<span class="note">(Tối thiểu = tối đa 06 sản phẩm)</span></label>
                                            <div class="row">
                                                <textarea class="form-control" placeholder="Điền danh sách sản phẩm yêu thích" name="qp_sku" id="qp_sku" cols="30" rows="10"></textarea>
                                            </div>
                                            <div class="col-md-12 list-products" style="display: none;">
                                                    <label for="">Danh sách sản phẩm tìm thấy: </label>
                                                    <div class="color"></div>
                                                </div>
                                            <div class="text-right action">
                                                <input type="hidden" id="q_item" value="{{time()}}">
                                                <span class="cancel Cancel">Hủy bỏ</span>
                                                <button type="submit" value="" class="btn btn_primary">Cập nhật</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
<?php
$ver_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript" src="/js/home.js?v={{$ver_js}}"></script>

    <script type="text/javascript">
        var _block = '{!! @$block !!}';        
        var _list_product_name = [];
        var _list_product_sku = [];
        var _tab_brand = [];

        var tab_brand = {!! @json_encode($tab_brand) !!}
        var list_product = {!! @json_encode($list_product) !!}
        var list_product_name = {!! @json_encode($list_product_name) !!}
        var list_product_sku = {!! @json_encode($list_product_sku) !!}
        $(document).ready(function() {

            if(!jQuery.isEmptyObject(list_product_name))
                _list_product_name = list_product_name;

            if(!jQuery.isEmptyObject(list_product_sku))
                _list_product_sku = list_product_sku;


            $('#fp_sku, #qp_sku').on('change', function () {
                var $this =$(this);
                ajax_loading(true);
                $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
                    ajax_loading(false);
                    $('.list-products').show();
                    var list = [];
                    var sku = [];
                    $.each(data.products, function (index, item) {
                        list.push( get_sku_item_brand_popular(item.name, item.product_id, item.sku) );
                        sku.push(item.sku);
                    });
                    $('.list-products .color').html(list.join(', '));
                    $this.val(sku.join(','));
                });
            });

            $('.UpdateAction').on('click', function () {
                $(".banner-promotion").slideUp();
                $(".CreatePromotion").slideDown();
            });

            $('.Cancel').on('click', function () {
                $(".banner-promotion").slideDown();
                $(".CreatePromotion").slideUp();
            });

            if(!jQuery.isEmptyObject(tab_brand)){
                $.each(tab_brand,function(k,v){
                    _tab_brand[k] = v;
                    add_band_popular(k,list_product[k]);
                })
            }

            $('#form_update_quick').validate({
                ignore: ".ignore",
                rules: {
                    q_name: "required",
                    q_link: "required",
                    q_location: "required",
                    qp_sku: "required",
                },
                messages: {
                    q_name: "Nhập tên logo",
                    q_link: "Nhập link liên kết",
                    q_location: "Chọn ảnh hiển thị",
                    qp_sku: "Nhập sku sản phẩm",
                },
                submitHandler: function(form) {
                    var item = $('#q_item').val();

                    var product_ids = [];
                    $('#form_update_quick input[name^="product_ids"]').each(function(){
                        product_ids.push($(this).val());
                        _list_product_name[$(this).val()] = $(this).data('name');
                        _list_product_sku[$(this).val()] = $(this).data('sku');
                    });

                    _tab_brand[item] = {
                        name: $("#q_name").val(),
                        link: $("#q_link").val(),
                        location: $("#q_location").val(),
                        ordering: $("#q_ordering").val(),
                        status: $("#q_status").is(':checked')?'1':'0',
                        product_ids: product_ids
                    };
                    add_band_popular(item,product_ids);

                    $('#form_update').submit();

                    return false;
                }
            });

            $('.BtnSaveProductShock').on('click', function () {

                $("#b_name" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Điền tên logo",
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
                $("#fp_sku" ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập sku sản phẩm",
                    }
                });
                

                var flag1 = $("#b_name").valid();
                var flag2 = $("#b_link").valid();
                var flag3 = $("#b_location").valid();
                var flag4 = $("#fp_sku").valid();

                if ( flag1  ) {

                    var item = $('#b_item').val() || $.now();
                    var product_ids = [];
                    $('#form_update input[name^="product_ids"].product_ids').each(function(){
                        product_ids.push($(this).val());
                        _list_product_name[$(this).val()] = $(this).data('name');
                        _list_product_sku[$(this).val()] = $(this).data('sku');
                    });

                    _tab_brand[item] = {
                        name: $("#b_name").val(),
                        link: $("#b_link").val(),
                        location: $("#b_location").val(),
                        ordering: $("#b_ordering").val(),
                        status: $("#b_status").is(':checked')?'1':'0',
                        product_ids: product_ids
                    };
                    add_band_popular(item,product_ids);

                    $("#b_item").val($.now());
                    $("#b_name").val('');
                    $("#b_link").val('');
                    $("#b_ordering").val(1);
                    $("#b_location").val(''),
                    $("#fp_sku").val('').trigger('change');
                    $("#b_status").prop('checked',true);
                    $('.preview-b-banner').attr('src','');

                    $('.BtnSaveProductShock').html('<i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm logo & Sản phẩm yêu thích');
                }
                $("#b_name").rules( "remove");
                $("#b_link").rules("remove");
                $("#b_location").rules("remove");
                $("#fp_sku").rules("remove");


                return false;
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    ordering: "required"
                },
                messages: {
                    name: "Nhập tiêu đề block",
                    ordering: "Chọn thứ tự hiển thị block"
                },
                submitHandler: function(form) {
                    if ($('.list-shocks input').length==0) {
                        malert('Vui lòng thêm tab thương hiệu', null, function () {
                            $('#tab_product_name').focus();
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