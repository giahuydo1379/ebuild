@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title">Tạo thương hiệu mới</h2>
            </div>
            <div class="view_detail_brand create_brand" >
                <div class="header-panel">
                    <h3 class="title ">Thông tin chi tiết thương hiệu</h3>
                    <div class="wrap_link">
                        <a href="<?=route('brands.index')?>"><i class="fa fa-reply" aria-hidden="true"></i><span>Quay lại</span></a>
                    </div>
                </div>
                <form action="<?=route('brands.store')?>" class="frm_update" method="post">
                <div class="col-md-6">
                    <div class="box box1_edit">
                        <div class="top">
                            <div class="col-md-2">
                                <div class="image-upload">
                                    <label for="file-input">

                                        <img class="preview-banner" src="/html/assets/images/banner-promotion.png" alt="{{config('app.name')}} promotion" />

                                        <img class="btn-loadfile browse-image" id="" src="/html/assets/images/icon_choose_img.png" data-target="image_location" alt="your image"  />
                                    </label>

                                    <!--  <input id="file-input" type='file' name="image_location" onchange="readURL(this);"/> -->
                                    <input type="hidden" value="" name="image_location" id="image_location" data-preview=".frm_update .preview-banner">

                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Thương hiệu mới</h4>
                            </div>
                        </div>
                        <div class="bottom">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tên thương hiệu:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="name" value="" placeholder="Nhập tên thương hiệu">
                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tên Alias:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="alias" value="" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Thuộc danh mục:</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="category_id[]" class="form-control multiselect" multiple="multiple" style="width: 100%;">
                                        @foreach($list_cate as $item)
                                            <option value="<?=$item['category_id']?>" ><?=$item['category']?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Trạng thái:</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="wrapper">
                                        <input value="D" type="hidden" name="status"/>
                                        <input type="checkbox" value="A" id="status" name="status" class="slider-toggle" checked />
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
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box_descript">
                        <div class="header_box">
                            <b class="title"> Mô tả thương hiệu </b>
                        </div>
                        <div class="body_box">
                            <div class="edit edit_vietnam">
                                <textarea class="edit_content" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wrap_btn">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <a href="<?=route("brands.index")?>" class="cancel">Hủy bỏ</a>
                    <button type="submit" class="btn-save">Lưu</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')

@endsection

@section('after_scripts')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>
    <script type="text/javascript">

        $(function(){
            $('.multiselect').multiselect();

            $('.cancel').on('click',function(){
                $(this).closest('form')[0].reset();
            });

            $('.frm_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                },
                messages: {
                    name: "Nhập tên thương hiệu",
                },
                submitHandler: function(form) {
                    // do other things for a valid form
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = '<?=route("brands.index")?>';
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

                    return false;
                }
            });
        })
    </script>
@endsection