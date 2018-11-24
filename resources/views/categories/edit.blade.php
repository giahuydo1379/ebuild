@extends('layouts.master')

@section('content')

    <main >
        <div class="col-md-">
            <div class="wrap_view ">
                <div class="header ">
                    <h2 class="title title-path">Thông tin chi tiết danh mục sản phẩm</h2>
                </div>
                <section class="infor_category_detail">
                    <div class="top">
                        <h3 class="title" style="">Thông tin danh mục</h3>
                        <a href="<?=route('categories.create')?>" class="pull-right link add" style=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                        <a href="<?=route('categories.index')?>" id="back1" class="pull-right link"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            @if(!empty($data))
                            <div class="box box1-infor box_info">
                                <i class="icon_edit" title="Chỉnh sửa">&nbsp</i>
                                <ul>
                                    <li class="row">
                                        <b>Tên danh mục: </b> <span><?=$data['category']?></span>
                                    </li>
                                    <li class="row">
                                        <b>Thư mục cha: </b><span><?=$data['parent_name']?> </span>
                                    </li>
                                    <li class="row">
                                        <b>Tên Alias: </b> <span><?=$data['alias']?></span>
                                    </li>
                                    <li class="row">
                                        <b>Thứ tụ hiển thị: </b> <span><?=$data['position']?></span>
                                    </li>
                                    <li class="row">
                                        <b class="status">Trạng thái danh mục: </b>
                                        <div class="wrapper_">
                                            <input type="checkbox" id="checkbox" class="slider-toggle" <?=$data['status'] == 'A'?'checked':''?>  onclick="return false;" onkeydown="return false;"/>
                                            <label class="slider-viewport" for="checkbox">
                                                <div class="slider">
                                                    <div class="slider-button">&nbsp;</div>
                                                    <div class="slider-content left"><span>On</span></div>
                                                    <div class="slider-content right"><span>Off</span></div>
                                                </div>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <b>Mô tả danh mục: </b>
                                        <?=$data['description']?>
                                    </li>
                                    <li class="row">
                                        <b class="status">Hiển thị frontend: </b>
                                        <div class="wrapper_">
                                            <input type="checkbox" id="checkbox" class="slider-toggle" <?=$data['is_show_frontend'] == '1'?'checked':''?>  onclick="return false;" onkeydown="return false;"/>
                                            <label class="slider-viewport" for="checkbox">
                                                <div class="slider">
                                                    <div class="slider-button">&nbsp;</div>
                                                    <div class="slider-content left"><span>On</span></div>
                                                    <div class="slider-content right"><span>Off</span></div>
                                                </div>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            @endif
                            <div class="box box1-edit box_edit">
                                <form class="frm_update" action="<?=route('categories.store')?>" method="post">
                                <ul>
                                    <li class="row">
                                        <div class="col-md-3">
                                            <label>Tên danh mục:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="category" name="category" value="" placeholder="Nhập tên danh mục">
                                            <label id="category-error" class="error" for="category" style="display: none;"></label>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-3">
                                            <label>Thư mục cha:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="form-control select2" id="parent_id" name="parent_id">
                                                <option value="0">Chính</option>
                                                @foreach($list_all_cate as $item)
                                                <option value="<?=$item['category_id']?>"><?=$item['category']?></option>
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
                                            <input type="text" id="alias" name="alias" value="" placeholder="Nhập tên Alias của danh mục">
                                            <label id="alias-error" class="error" for="alias" style="display: none;"></label>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-3">
                                            <label>Thứ tự hiển thị:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" id="position" name="position" value="" placeholder="Vd: 1">
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
                                                <input type="checkbox" id="status" class="slider-toggle" name="status" value="A" />
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
                                                <input type="checkbox" id="is_show_frontend" class="slider-toggle" name="is_show_frontend" value="1"/>
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
                                            <label>Mô tả danh mục:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <textarea id="description" name="description" cols="" placeholder="Nhập nội dung mô tả danh mục"></textarea>
                                        </div>
                                    </li>
                                </ul>
                                <div class="wrap_btn">
                                    <input type="hidden" name="category_id" id="category_id">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <span class="cancel" data-show=".box1-infor" data-hide=".box1-edit">Hủy bỏ</span>
                                    <button type="submit" class="btn-save">Lưu thay đổi</button>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box2-infor">
                                <p class="title"><b>Bộ lọc</b></p>
                                <div class="wrap_property">
                                @foreach($features as $item)
                                    <span><?=$item['description']?></span>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </main>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript">
        var $data = <?=!empty($data)?json_encode($data):'{}'?>;

        $(function(){
            init_select2('.select2');

            function set_data(){
                $('#category_id').val($data.category_id);
                $('#category').val($data.category);
                $('#parent_id').val($data.parent_id);
                $('#alias').val($data.alias);
                $('#position').val($data.position);

                if($data.status == 'A'){
                    $('#parent_id').val($data.parent_id);
                }

                if($data.status == 'A'){
                    $('#status').attr('checked', 'checked');
                } else {
                    $('#status').removeAttr('checked');
                }

                if($data.is_show_frontend == '1'){
                    $('#is_show_frontend').attr('checked', 'checked');
                } else {
                    $('#is_show_frontend').removeAttr('checked');
                }
            }

            $('.cancel').on('click',function(){
                $('.frm_update')[0].reset();
                set_data();
            });

            $('.box_info').on('click',function(){
                if(jQuery.isEmptyObject($data) < 0)
                    return;
                set_data();
            });

            $('.frm_update').validate({
                ignore: ".ignore",
                rules: {
                    category: "required",
                },
                messages: {
                    category: "Nhập tên danh mục",
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