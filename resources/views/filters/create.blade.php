@extends('layouts.master')

@section('content')
<div class="col-md-">
    <div class="wrap_view view_product create_product">
        <div class="header">
            @if (isset($object))
                <h2 class="title">Cập nhật danh mục filter</h2>
            @else
                <h2 class="title">Tạo mới danh mục filter</h2>
            @endif
            <a href="<?=route('filters.index')?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
        </div>
        <section class="create_filter">
            <form id="frm_update" action="<?=route('filters.store')?>" method="post" class="form-horizontal">
            <ul class="content col-md-7 col-md-offset-2" >
                <li class="row">
                    <div class="col-md-3"> <label>Tên danh mục filter: *</label></div>
                    <div class="col-md-9">
                        <input type="text" value="<?=@$object['name_vi']?>" name="name_vi" placeholder="Nhập tên danh mục filter">
                    </div>
                </li>
                <li class="row">
                    <div class="col-md-3"><label>Loại filter: *</label></div>
                    <div class="col-md-9">
                        {!! Form::select("type", \App\Helpers\General::get_feature_type_options(), @$object['type'],
                        ['id' => 'type', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn loại filter']) !!}
                    </div>
                </li>
                <li class="row">
                    <div class="col-md-3"><label>Thứ tự:</label></div>
                    <div class="col-md-9">
                        <input value="<?=@$object['position']?>" type="number" name="position" placeholder="Nhập thứ tự">
                    </div>
                </li>
                <li class="row">
                    <div class="col-md-3"><label>Trạng thái:</label></div>
                    <div class="col-md-9">
                        <div class="wrapper_">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" name="status" value="1" id="checkbox0" class="slider-toggle" <?=@$object['status']?'checked':''?>/>
                            <label class="slider-viewport" for="checkbox0">
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
                    <div class="col-md-3"><label>Lọc khoảng giá trị:</label></div>
                    <div class="col-md-9">
                        <div class="wrapper_">
                            <input type="hidden" name="is_range" value="0">
                            <input type="checkbox" name="is_range" value="1" id="checkbox_is_range" class="slider-toggle" <?=@$object['is_range']?'checked':''?>/>
                            <label class="slider-viewport" for="checkbox_is_range">
                                <div class="slider">
                                    <div class="slider-button">&nbsp;</div>
                                    <div class="slider-content left"><span>On</span></div>
                                    <div class="slider-content right"><span>Off</span></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </li>
                <div class="wrap-button col-md-9 col-md-offset-3">
                    <input type="hidden" value="0" id="is_next">
                    <input type="hidden" name="id" id="id" value="<?=@$object['id']?>">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button type="submit" class="btn_save">Lưu</button>
                    <button type="submit" class="btn_save_filter" onclick="$('#is_next').val(1)">Lưu & Tạo danh mục filter mới</button>
                </div>
            </ul>
            </form>
        </section>
    </div>
</div>
@endsection

@section('after_styles')
    <style>
        input[type='number'] {
            width: 100%;
            padding: 0 15px;
            border: 1px solid #e1e1e1;
            height: 36px;
            border-radius: 3px;
        }
    </style>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(function(){
            $('#frm_update').validate({
                ignore: ".ignore",
                rules: {
                    name_vi: "required",
                    type: "required",
                },
                messages: {
                    name_vi: "Nhập tên danh mục",
                    type: "Chọn loại filter",
                },
                submitHandler: function(form) {
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                if ($('#is_next').val()==1) {
                                    location.href = '<?=route('filters.create')?>';
                                    return;
                                }
                                location.href = '<?=route('filters.index')?>';
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