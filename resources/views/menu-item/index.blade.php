@extends('layouts.master')
<?php
$positions = \App\Helpers\General::get_menu_position_options();
?>
@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">Menu item</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Tạo mới</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật Menu</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách Menu item</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects['total'])
                <div class="banner banner-display">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-6">
                                <div class="col-md-2">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-5 no-padding">
                                    <span>Tiêu đề</span>
                                </div>
                                <div class="col-md-5">
                                    <span>Menu cha</span>
                                </div>
                            </div>
                            <div class="col-md-6 no-padding ">
                                <div class="col-md-4 no-padding">
                                    <span>Liên kết</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Vị trí</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Thứ tự</span>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                            <form class="row-filter">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6 no-padding">
                                    <div class="col-md-offset-2 col-md-5">
                                        {!! Form::select("position_filter", ['' => 'Chọn vị trị'] + $positions, @$_GET['position_filter'], ['id' => 'position_filter', 'class' => 'form-control select2']) !!}
                                    </div>
                                    <button type="submit" class="btn btn_primary">Lọc</button>
                                </div>
                            </form>
                        @foreach($objects['data'] as $index => $item)
                            <?php
                            $_objects[$item['id']] = $item;
                            ?>
                            <li class="row">
                                <div class="col-md-6">
                                    <div class="col-md-2">
                                        <span>{{$index+1}}</span>
                                    </div>
                                    <div class="col-md-5 no-padding content">
                                        <span>{{$item['name']}}</span>
                                    </div>
                                    <div class="col-md-5 content">
                                        <span><?=$item['parent_id'] ? trim(@$categories[$item['parent_id']], '-') : '-'?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding">
                                    <div class="col-md-4 no-padding content">
                                        <span><?=$item['type'] == 'page_link' ?$item['page_title']:$item['link'] ?></span>
                                    </div>
                                    <div class="col-md-3 content">
                                        <span><?=$positions[$item['position']] ?? $item['position'] ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span><?=$item['ordering']?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="tooltip update-action" data-id="{{$item['id']}}">
                                            <i class="icon-edit-pen active UpdateAction" aria-hidden="true"></i>
                                            <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Cập nhật</span>
                                        </a>
                                        <a class="tooltip delete-action" data-id="{{$item['id']}}">
                                            <i class="icon-delete active DeleteAction" aria-hidden="true"></i>
                                            <i class="icon-delete-hover DeleteHover" title="Xóa">&nbsp</i>
                                            <span class="tooltiptext">Xóa</span>
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
                <div class="banner-update">
                    <form class="form-create" method="post" id="form_update" action="">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Vị trí <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    {!! Form::select("position", \App\Helpers\General::get_menu_position_options(), '', ['id' => 'position', 'class' => 'form-control select2', 'data-placeholder'=>"Chọn vị trí"]) !!}
                                    <label id="position-error" class="error" for="position" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Menu cha</label>
                                </div>
                                <div class="col-md-10">
                                    {!! Form::select("parent_id",["0" => "Menu chính"] + $categories, '',
                                    ['id' => 'parent_id', 'class' => 'form-control select2', 'data-placeholder'=>"Chọn menu cha"]) !!}
                                    <label id="parent_id-error" class="error" for="parent_id" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Thứ tự</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="number" name="ordering" id="ordering" class="form-control" placeholder="Thứ tự">
                                    <label id="ordering-error" class="error" for="ordering" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tiêu đề <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tiêu đề">
                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên Alias</label>
                                </div>
                                <div class="col-md-10">
                                    <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Alias sẽ được tự động tạo">
                                    <label id="slug-error" class="error" for="slug" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Loại liên kết<span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    {!! Form::select("type", \App\Helpers\General::get_menu_type_options(), '', ['id' => 'type', 'class' => 'form-control select2', 'data-placeholder'=>"Chọn loại liên kết"]) !!}
                                    <label id="type-error" class="error" for="type" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6 type_link type_">
                                <div class="col-md-2 text-right">
                                    <label>Liên kết <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="link" id="link" class="form-control" placeholder="Link liên kết">
                                    <label id="link-error" class="error" for="link" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6 type_page type_">
                                <div class="col-md-2 text-right">
                                    <label>Trang <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    {!! Form::select("page_id",["" => ""]+$pages, '', ['id' => 'page_id', 'class' => 'form-control select2']) !!}
                                    <label id="page_id-error" class="error" for="page_id" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <div class="action">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" name="id" id="id" value="0">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('after_styles')
<style type="text/css">
    .type_{
        display: none;
    }
    .row-filter {
        display: -webkit-box;
        display: -moz-box;
        display: -ms-box;
        width: 100%;
        padding: 8px 0px;
        background: #e8f9ff;
    }
    .row-filter .select2-container {
        padding-top: 0px !important;
    }
</style>
@stop
@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        $(document).ready(function() {
            init_select2('.select2');

            $('#position').on('change',function(){
                ajax_loading(true);
                $.get('{{route('menu-item.get-menus-parent')}}', {
                    position: $(this).val()
                }, function(res){
                    ajax_loading(false);
                    var html = '<option value=""></option>';
                    $.each(res.data, function (id, val) {
                        html += '<option value="'+id+'">'+val+'</option>';
                    });
                    $('#parent_id').html(html).val($('#parent_id').attr('data-id')).trigger('change');
                });
            });
            $('#type').on('change',function(){
                if(!$(this).val()){
                    $('.type_link').hide();
                    $('.type_page').hide();
                    $("#link").rules( "remove");
                    $("#page_id").rules( "remove");
                    return false;
                }

                if($(this).val() == 'page_link'){
                    $('.type_link').hide();
                    $('.type_page').show();
                    $("#page_id").rules( "add", {
                        required: true,
                        messages: {
                            required: "Chọn trang liên kết",
                        }
                    });
                    $("#link").rules( "remove");
                }else{
                    $('.type_link').show();
                    $('.type_page').hide();
                    $("#link").rules( "add", {
                        required: true,
                        messages: {
                            required: "Nhập liên kết",
                        }
                    });
                    $("#page_id").rules( "remove");
                }
            });

            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có chắc chắn muốn xóa không?", function () {
                    ajax_loading(true);
                    $.post('{{route('menu-item.delete')}}', {
                        id: $(obj).attr('data-id'),
                        _token: '{!! csrf_token() !!}'
                    }, function(data){
                        ajax_loading(false);
                        alert_success(data.msg, function () {
                            if(data.rs == 1)
                            {
                                location.reload();
                            }
                        });
                    });
                });
            });

            $('.add-action').on('click', function () {
                $(this).hide();
                $('.banner-display').slideUp();
                $('.banner-update').slideDown();

                $('.TitleCreate').show();
                $('.TitleDisplay').hide();
                $('.BackAction').show();
                $('.type_').hide();
            });
            $('.add-action-none').on('click', function () {
                $(this).parent().slideUp();
                $('.banner-update').slideDown();
                $('.BackAction').show();
            });
            $('#form_update .cancel').on('click', function (e) {
                e.preventDefault();

                if ($('#is_reload').val() == '1') {
                    location.reload();
                    return false;
                }

                $('#is_next').val(0);
                $('#form_update')[0].reset();
                $('#form_update #parent_id').val('').trigger('change');
                $('#form_update #page_id').val('').trigger('change');
                $('#form_update #type').val('').trigger('change');
                $('#form_update #position').val('').trigger('change');

                $('.type_').hide();
                $('.add-action-none').parent().slideDown();
                $('.banner-display').slideDown();
                $('.TitleUpdate').hide();
                if ($('.add-action-none').length > 0) {
                    $('.TitleCreate').show();
                    $('.TitleDisplay').hide();
                } else {
                    $('.TitleCreate').hide();

                    $('.TitleDisplay').show();
                    $('.add-action').show();
                }
                $('.BackAction').hide();
                $('.banner-update').slideUp();
            });

            $('.update-action').on('click', function () {
                
                $('.add-action').click();
                $('.TitleCreate').hide();
                $('.TitleUpdate').show();
                var item = _objects[$(this).attr('data-id')];
                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id);
                $('#form_update #name').val(item.name);
                $('#form_update #parent_id').attr('data-id', item.parent_id);
                $('#form_update #parent_id').val(item.parent_id).trigger('change');
                $('#form_update #slug').val(item.slug);
                $('#form_update #link').val(item.link);
                $('#form_update #page_id').val(item.page_id).trigger('change');
                $('#form_update #type').val(item.type).trigger('change');                
                $('#form_update #position').val(item.position).trigger('change');
                $('#form_update #ordering').val(item.ordering);

                if (item.is_booking) {
                    $('#form_update #is_booking').attr('checked', 'checked');
                } else {
                    $('#form_update #is_booking').removeAttr('checked');
                }
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    type: "required",
                    position: "required",
                },
                messages: {
                    name: "Nhập tiêu đề",
                    type: "Chọn loại liên kết",
                    position: "Chọn vị trí",
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
                                if ($('#is_next').val()=='1') {
                                    $('.add-action').click();
                                    $('#id').val(0);
                                    $('#is_reload').val(1);
                                    $('#is_next').val(0);
                                    $('.type_').hide();
                                    $('#form_update')[0].reset();

                                } else {
                                    location.reload();
                                }
                            });
                        } else {
                            alert_success(data.msg);
                            if (data.errors) {
                                $.each(data.errors, function (key, msg) {
                                    $('#'+key+'-error').html(msg).show();
                                });
                            }
                            $('#alias').val(data.data.alias);
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endsection