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

                    <div id="tab4" class="tabcontent active" style="display: block;">
                    	@if ($object)
                    	<div class="banner-promotion">
						    <ul class="list-infor">
						        <li><label for="">Tiêu đề block: </label> <b class="color">{{$object['name']}}</b></li>
						        <li><label for="">Thứ tự hiển thị block: </label> <span>{{$ordering_options[$object['ordering']]}}</span></li>
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
						    <div class="table-display">
						        <div class="header_table">
						            <div class="col-md-4">
						                <span>Tên banner</span>
						            </div>
						            <div class="col-md-4">
						                <span>Hình ảnh banner</span>
						            </div>
						            <div class="col-md-4 no-padding ">
						                <span>Link</span>
						            </div>
						        </div>
						        <ul class="category_product">
						            <li class="row">
						                <div class="col-md-4 content">
						                    <span>{{$banner['name']}}</span>
						                </div>
						                <div class="col-md-4 wrap-banner">
						                    <img src="{{$banner['image_url'].$banner['image_location']}}" alt="thien hoa promotion">
						                </div>
						                <div class="col-md-4 content no-padding">
						                    <span>{{$banner['link']}}</span>
						                </div>   
						            </li>
						        </ul>
						    </div>
						    <div class="add-block list-border">
						        <label for="" class="title-block">Danh sách tab sản phẩm</label>
						        <ul class="list-product no-padding list-shocks quick">
						        </ul>
						    </div>
						    <div class="ShowUpdateShock" style="display: none;">
                                <form class="form-create home-manager no-padding" id="form_update_quick" method="post" action="{{ route("home.add") }}">
                                    <input type="hidden" id="q_item" value="0">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Tên tab</label>                   
                                                <input type="text" name="q_tab_name" id="q_tab_name" class="form-control" placeholder="Nhập tên tab">
                                                <label id="q_tab_name-error" class="error" for="q_tab_name" style="display: none;"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Liên kết tab</label>
                                                <input type="text" name="q_tab_link" id="q_tab_link" class="form-control" placeholder="Nhập tên tab">
                                                <label id="q_tab_link-error" class="error" for="q_tab_link" style="display: none;"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-del"> Cập nhật sản phẩm</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
						    <div class="action text-right">
						        <button type="button" value="" class="btn btn_primary UpdateAction">Cập nhật</button>
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
												<label for="" class="title-block">Thêm banner <span class="note">(01 banner)</span></label>
												<div class="form-group">
													<label for="">Tên banner</label>
													<input type="text" name="banner_name" id="banner_name" class="form-control" placeholder="Điền tên banner" value="{{$banner['name']}}">
												</div>						
												<div class="form-group">
													<label for="">Link banner</label>
													<input type="text" name="banner_link" id="banner_link" class="form-control" placeholder="Nhập link banner" value="{{$banner['link']}}">
												</div>
												<div class="form-group">
													<div class="choose-banner">
										                <label>Chọn banner</label>
										                <div class="wrap-choose">
										                    <ul class="wrap_btn">
										                        <li>
										                            <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_location">
										                                <i class="icon-browser">&nbsp;</i>
										                                <span>Browse ...</span>
										                                <input type="file" name="newfile">
										                            </a>
										                        </li>
										                    </ul>
										                </div> 
										            </div>
										            <div class="img-infor">
										            	<div class="show_file">
                                                            <span class="file_name"></span>
                                                            <input type="hidden" value="{{$banner['image_url']}}" name="image_url" id="image_url">
                                                            <input type="hidden" value="{{$banner['image_location']}}" name="image_location" id="image_location" data-url="#image_url" data-preview="#form_update .preview-banner">
                                                            <label id="image_location-error" class="error" for="image_location" style="display: none;"></label>
                                                        </div>
									                	<span class="size-note">Kích thước: <b>1140 x 500 px</b></span>
									                    <span class="size-note">Dung lượng: <b>500kb</b></span>
									                    <span class="size-note">Định dạng: <b>jpg, png</b></span>
									                </div>
												</div>		
											</div>
										</div>
										<div class="col-md-5">				           
								            <div class="display-banner">
								                <img id="blah" class="preview-banner" src=" {{$banner['image_url'].$banner['image_location']}}" alt="your image" alt="thien hoa promotion" />
								            </div>
										</div>
									</div>
									<div class="row">	
										<div class="add-block">
											<label for="" class="title-block">Thêm tab sản phẩm <span class="note">(Tối thiểu 01 tab, tối đa 05 tab)</span></label>
											<div class="row">
												<input type="hidden" id="p_item" value="">
												<div class="col-md-6 haft-padding-left">
													<div class="form-group">
														<label for="">Tên tab sản phẩm</label>
														<input type="text" name="tab_product_name" id="tab_product_name" class="form-control" placeholder="Nhập tên tab sản phẩm">
                                                        <label id="tab_product_name-error" class="error" for="tab_product_name" style="display: none;"></label>
													</div>	
												</div>
												<div class="col-md-6 haft-padding-right">
													<div class="form-group">
                                                        <label for="">Link</label>
                                                        <input type="text" class="form-control" name="tab_product_link" id="tab_product_link" placeholder="Điền link tab sản phẩm">
                                                        <label id="tab_product_link-error" class="error" for="tab_product_link" style="display: none;"></label>  
                                                    </div>	
												</div>
											</div>
											<div class="text-right action">
								            	<a class="btn btn-del BtnSaveProductShock"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm tab sản phẩm</a>
								            </div>
										</div>
									</div>
									<div class="add-block">
										<label for="" class="title-block">Danh sách tab sản phẩm</label>
										<ul class="list-product no-padding list-shocks">
										</ul>
									</div>
								</div>
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
<?php
$ver_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript" src="/js/home.js?v={{$ver_js}}"></script>

    <script type="text/javascript">
        var _block = '{!! @$block !!}';
        var _tab_brand = [];
        var tab_brand = {!! @json_encode($tab_brand) !!}
        $(document).ready(function() {
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
                    add_band_tab(k,v.name,v.link);
                })
            }

            $('#form_update_quick').validate({
                ignore: ".ignore",
                rules: {
                    q_tab_name: "required",
                    q_tab_link: "required",
                },
                messages: {
                    q_tab_name: "Nhập tên banner",
                    q_tab_link: "Nhập link liên kết",
                },
                submitHandler: function(form) {
                    var item = $('#q_item').val();

                    _tab_brand[item] = {
                        name: $("#q_tab_name").val(),
                        link: $("#q_tab_link").val(),
                    };

                    add_band_tab(item,$("#q_tab_name").val(),$("#q_tab_link").val());
                    $('#form_update').submit();

                    return false;
                }
            });

            $('.BtnSaveProductShock').on('click', function () {
                $('#tab_product_name').rules('add',{
                    required: true,
                    messages: {
                        required: "Nhập tên tab sản phẩm",
                    }
                });

                $('#tab_product_link').rules('add',{
                    required: true,
                    messages: {
                        required: "Nhập link liên kết",
                    }
                });

                var flag1 = $("#tab_product_name").valid();
                var flag2 = $("#tab_product_link").valid();

                if ( flag1 && flag2  ) {

                    var item = $('#p_item').val() || $.now();

                    _tab_brand[item] = {
                        name: $("#tab_product_name").val(),
                        link: $("#tab_product_link").val()
                    };

                    add_band_tab(item,$("#tab_product_name").val(),$("#tab_product_link").val());

                    $("#p_item").val($.now());
                    $("#tab_product_name").val('');
                    $("#tab_product_link").val('');

                    $('.BtnSaveProductShock').html('<i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm sản phẩm');
                }
                $("#tab_product_name").rules( "remove");
                $("#tab_product_link").rules("remove");

                return false;
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    ordering: "required",
                    sort: "required",
                    banner_name: "required",
                    banner_link: "required",
                    image_location: "required",
                },
                messages: {
                    name: "Nhập tiêu đề block",
                    ordering: "Chọn thứ tự hiển thị block",
                    sort: "Chọn hiển thị sản phẩm theo",
                    banner_name: "Nhập tên banner",
                    banner_link: "Nhập link liên kết",
                    image_location: "Phải chọn hình"
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