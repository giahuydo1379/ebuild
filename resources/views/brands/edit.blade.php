@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title">Quản lý thương hiệu</h2>
            </div>
            <div class="view_detail_brand edit_brand" >
                <div class="header-panel">
                    <h3 class="title ">Thông tin chi tiết thương hiệu</h3>
                    <div class="wrap_link">
                        <a href="<?=route('brands.index')?>"><i class="fa fa-reply" aria-hidden="true"></i><span>Quay lại</span></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box1">
                        <div class="top">
                            <div class="col-md-2">
                                <div class="wrap-img">
                                    <img src="<?=$data['image_url'].$data['image_location']?>" alt="">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4><?=$data['name']?></h4>
                                <i class="icon-edit" title="Chỉnh sửa">&nbsp</i>
                            </div>
                        </div>
                        <div class="bottom">
                            <p><b>Tên thương hiệu:</b> <?=$data['name']?></p>
                            <p><b>Tên Alias:</b> <?=!empty($data['alias'])?$data['alias']:str_slug($data['name'],'-')?></p>
                            <p><b>Thuộc danh mục:</b> <?=implode(',',$categories)?>  </p>
                            <b>Trạng thái:</b>
                            <div class="wrapper">
                                <input type="checkbox" id="checkbox" class="slider-toggle" <?=$data['status'] == 'A'?'checked':''?>  onclick="return false;" onkeydown="return false;"/>
                                <label class="slider-viewport" for="checkbox">
                                    <div class="slider">
                                        <div class="slider-button">&nbsp;</div>
                                        <div class="slider-content left"><span>On</span></div>
                                        <div class="slider-content right"><span>Off</span></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <form action="<?=route('brands.store')?>" class="frm_update" method="post">
                    <div class="box box1_edit">
                        <div class="top">
                            <div class="col-md-2">
                                <div class="image-upload">
                                    <label for="file-input">
                                        <img class="preview-banner" src="<?=$data['image_url'].$data['image_location']?>" alt="" />
                                        <img class="btn-loadfile browse-image" id="" src="/html/assets/images/icon_choose_img.png" data-target="image_location" alt="your image"  />
                                    </label>
                                    <input type="hidden" value="<?=$data['image_location']?>" name="image_location" id="image_location" data-url="#image_url" data-preview=".frm_update .preview-banner">
                                    <input type="hidden" value="<?=$data['image_url']?>" name="image_url" id="image_url">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4><?=$data['name']?></h4>
                            </div>
                        </div>
                        <div class="bottom">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tên thương hiệu:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="name" value="<?=$data['name']?>" placeholder="Nhập tên thương hiệu">
                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tên Alias:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="alias" value="<?=!empty($data['alias'])?$data['alias']:str_slug($data['name'],'-')?>" placeholder="Nhập tên thương hiệu">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Thuộc danh mục:</label>
                                </div>
                                <div class="col-md-9">
                                    <select name="category_id[]" class="form-control multiselect" multiple="multiple">
                                        @foreach($list_cate as $item)
                                        <option value="<?=$item['category_id']?>" <?=array_key_exists($item['category_id'],$categories) ?'selected':'' ?> ><?=$item['category']?></option>
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
                                        <input type="checkbox" name="status" id="status" value="A" class="slider-toggle" <?=$data['status'] == 'A'?'checked':''?> />
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
                            <div class="wrap_btn">
                                <input type="hidden" name="brand_id" value="<?=@$data['brand_id']?>">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <span href="javascript:void(0)" class="cancel" style="line-height: 25px;"
                                      data-show=".box.box1" data-hide=".box1_edit">Hủy bỏ</span>
                                <button type="submit" class="btn-save">Lưu thay đổi</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="<?=route('brands.store')?>" class="frm_update_description" method="post">
                    <div class="box box_descript">
                        <div class="header_box">
                            <b class="title"> Mô tả thương hiệu </b>
                            <i class="icon-edit" title="Chỉnh sửa">&nbsp</i>
                        </div>
                        <div class="body_box">
                            <div class="part part1">
                                <p><?=$data['description']?></p>
                            </div>
                            <div class="edit edit_vietnam">
                                <textarea class="edit_content" name="description"><?=$data['description']?></textarea>
                            </div>
                            <div class="edit edit_engish">
                                <div class="wrap_btn">
                                    <input type="hidden" name="brand_id" value="<?=@$data['brand_id']?>">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <span href="javascript:void(0)" class="cancel" style="line-height: 25px;"
                                          data-show=".part1, .part2, .box_descript .icon-edit" data-hide=".edit_engish, .edit_vietnam">Hủy bỏ</span>
                                    <button type="submit" class="btn-save" >Lưu thay đổi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
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

                    return false;
                }
            });
            $('.frm_update_description').validate({
                ignore: ".ignore",
                rules: {

                },
                messages: {

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

                    return false;
                }
            });
        })
    </script>
@endsection