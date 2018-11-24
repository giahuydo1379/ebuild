@extends('layouts.master')

@section('content')
    <main >
        <div class="col-md-">
            <div class="wrap_view ">
                <div class="header ">
                    <h2 class="title title-path">Tạo mới danh mục sản phẩm</h2>
                </div>
                <section class="infor_category_detail">
                    <div class="top">
                        <h3 class="title" style="">Thông tin danh mục sản phẩm</h3>
                        <a href="<?=route('categories.index')?>" id="back1" class="pull-right link go_back"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    </div>
                    <form class="frm_update create_category" action="<?=route('categories.store')?>" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="<?=@$object['category_id']?>" type="hidden" name="category_id" id="category_id">
                                <li class="box box1-edit">
                                    <ul>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Tên danh mục: <span class="required"></span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" id="category" name="category" value="<?=@$object['category']?>" placeholder="Nhập tên danh mục">
                                                <label id="category-error" class="error" for="category" style="display: none;"></label>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Danh mục cha:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <select class="form-control select2" id="parent_id" name="parent_id">
                                                    <option value="0">Chính</option>
                                                    @foreach($list_all_cate as $item)
                                                        <option <?=@$object['parent_id']==$item['category_id']?'selected':''?> value="<?=$item['category_id']?>"><?=$item['category']?></option>
                                                    @endforeach
                                                </select>
                                                <label id="parent_id-error" class="error" for="parent_id" style="display: none;"></label>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Tên Alias:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" id="alias" name="alias" value="<?=@$object['alias']?>" placeholder="Nhập tên Alias của danh mục">
                                                <label id="alias-error" class="error" for="alias" style="display: none;"></label>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Thứ tự hiển thị: <span class="required"></span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" id="position" name="position" value="<?=@$object['position']?>" placeholder="Vd: 1">
                                                <label id="position-error" class="error" for="position" style="display: none;"></label>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Trạng thái danh mục:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="wrapper_">
                                                    <input value="H" type="hidden" name="status"/>
                                                    <input type="checkbox" id="status" class="slider-toggle" name="status" value="A" <?=@$object['status']=='A'?'checked':''?> />
                                                    <label class="slider-viewport" for="status">
                                                        <div class="slider">
                                                            <div class="slider-button">&nbsp;</div>
                                                            <div class="slider-content left"><span>On</span></div>
                                                            <div class="slider-content right"><span>Off</span></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Hiển thị frontend:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="wrapper_">
                                                    <input value="0" type="hidden" name="is_show_frontend"/>
                                                    <input type="checkbox" id="is_show_frontend" class="slider-toggle" name="is_show_frontend" value="1" <?=@$object['is_show_frontend']?'checked':''?>/>
                                                    <label class="slider-viewport" for="is_show_frontend">
                                                        <div class="slider">
                                                            <div class="slider-button">&nbsp;</div>
                                                            <div class="slider-content left"><span>On</span></div>
                                                            <div class="slider-content right"><span>Off</span></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Hình ảnh</label>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" value="<?=@$object['image_location']?>" name="image_location" id="image_location" class="form-control" placeholder="Nhập link ảnh hoặc chọn ảnh"
                                                       data-preview=".preview-banner" data-url="#image_url">
                                                <input value="<?=@$object['image_url']?>" type="hidden" name="image_url" id="image_url">
                                                <div class="row" style="margin-top: 10px;">
                                                    <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_location">
                                                        <i class="icon-browser">&nbsp;</i>
                                                        <span>Browse ...</span>
                                                    </a>
                                                </div>
                                                <span class="size-note">Chọn ảnh có kích thước phù hợp với vị trí bạn muốn hiển thị</span>
                                            </div>
                                            <div class="col-md-4">
                                                <img class="preview-banner" src="<?=@$object['image_url'].@$object['image_location']?>" onerror="$(this).attr('src', '/assets/images/default.png')">
                                            </div>
                                        </li>
                                        <li class="row">
                                            <div class="col-md-3">
                                                <label>Mô tả danh mục:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea id="description" name="description"
                                                          rows="3" placeholder="Nhập nội dung mô tả danh mục"><?=@$object['description']?></textarea>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="wrap_btn">
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                    <a href="<?=route('categories.index')?>"><span class="cancel">Hủy bỏ</span></a>
                                    <button type="submit" class="btn-save" onclick="$('#is_next').val(1)" >Lưu & Tạo thêm Category mới</button>
                                    <button type="submit" class="btn-save" onclick="$('#is_next').val(0)" >Lưu</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript">
        $(function(){
            init_select2('.select2');

            $('#image_location').on('change', function () {
                $('.preview-banner').attr('src', $(this).val());
                $('#image_url').val('');
            });

            $('.cancel').on('click',function(){
                $('.frm_update')[0].reset();
            });

            $('.frm_update').validate({
                ignore: ".ignore",
                rules: {
                    category: "required",
                    position: "required",
                },
                messages: {
                    category: "Nhập tên danh mục",
                    position: "Nhập thứ tự",
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
                                if ($('#is_next').val()== 1 ) {
                                    location.reload();
                                } else {
                                    location.href = $('.go_back').attr('href');
                                }
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