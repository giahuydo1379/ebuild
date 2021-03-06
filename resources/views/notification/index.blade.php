@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section"><?=$title?></h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Tạo <?=$title?> mới</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật <?=$title?></h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách <?=$title?> mới</h3>
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
                            <div class="col-md-8">
                                <div class="col-md-2">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-6 no-padding">
                                    <span><?=$title?></span>
                                </div>
                                <div class="col-md-4">
                                    <span>Danh mục</span>
                                </div>
                            </div>
                            <div class="col-md-4 no-padding">
                                <div class="col-md-7">
                                    <span>Trạng thái</span>
                                </div>
                                <div class="col-md-5">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                        @foreach($objects['data'] as $index => $item)
                            <?php
                            $_objects[$item['notification_id']] = $item;
                            ?>
                            <li class="row">
                                <div class="col-md-8">
                                    <div class="col-md-2">
                                        <span>{{$index+1}}</span>
                                    </div>
                                    <div class="col-md-6 no-padding content">
                                        <span>{{$item['object_display_name']}}</span>
                                    </div>
                                    <div class="col-md-4 content">
                                        <span>{{$categories[$item['notification_category_id']]}}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 no-padding">
                                    <div class="col-md-7">
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
                                    <div class="col-md-5">
                                        <a class="tooltip update-action" data-id="{{$item['notification_id']}}">
                                            <i class="icon-edit-pen active UpdateAction" aria-hidden="true"></i>
                                            <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Cập nhật</span>
                                        </a>
                                        <a class="tooltip delete-action" data-id="{{$item['notification_id']}}">
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
                    <form class="form-create" method="post" id="form_update" action="{{ route("notification.add") }}">
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Tiêu đề <?=$title?> <span>*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="object_display_name" id="object_display_name" class="form-control" placeholder="Nhập tiêu đề <?=$title?> cần tạo">
                                <label id="object_display_name-error" class="error" for="title" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Danh mục <span>*</span></label>
                            </div>
                            <div class="col-md-7">
                                {!! Form::select("notification_category_id", ['' => ''] + $categories, '', ['id' => 'notification_category_id', 'class' => 'form-control select2']) !!}
                                <label id="notification_category_id-error" class="error" for="notification_category_id" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Nội dung<span>*</span></label>
                            </div>
                            <div class="col-md-10">
                                <textarea class="form-control" name="object_content" id="object_content" placeholder="Nhập nội dung <?=$title?>"></textarea>
                                <label id="object_content-error" class="error" for="object_content" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Trạng thái <span>*</span></label>
                            </div>
                            <div class="col-md-10">
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
                                <span class="note">Chọn để kích hoạt trạng thái</span>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <div class="col-md-2 text-right">
                                <label>Loại thông báo <span>*</span></label>
                            </div>
                            <div class="col-md-10">
                                <label class="radio-inline">
                                    <input name="notification_type" type="radio" class="notification_type" value="public" checked> Tất cả
                                </label>
                                <label class="radio-inline">
                                    <input name="notification_type" type="radio" class="notification_type" value="private"> Người dùng
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <div class="action">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" name="notification_id" id="notification_id" value="0">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới <?=$title?> </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        $(document).ready(function() {
            init_select2('.select2');
            $('textarea#object_content').ckeditor();

            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, <?=$title?> này không?", function () {
                    ajax_loading(true);
                    $.post('{{route('notification.delete')}}', {
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
                CKEDITOR.instances.object_content.setData( '' );

                $('.add-action-none').parent().slideDown();
                $('.banner-display').slideDown();

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
                var item = _objects[$(this).attr('data-id')];

                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #notification_id').val(item.notification_id);
                $('#form_update #object_display_name').val(item.object_display_name);
                $('#form_update #notification_category_id').val(item.notification_category_id).trigger('change');
                $('#form_update #object_content').val(item.object_content);
                if (item.status) {
                    $('#form_update #status').attr('checked', 'checked');
                } else {
                    $('#form_update #status').removeAttr('checked');
                }

                $('.add-action').click();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    object_display_name: "required",
                    notification_category_id: "required",
                    object_content: "required",
                },
                messages: {
                    object_display_name: "Vui lòng nhập tiêu đề <?=$title?>",
                    notification_category_id: "Vui lòng chọn danh mục <?=$title?>",
                    object_content: "Vui lòng nhập nội dung <?=$title?>",
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

                                    $('#is_reload').val(1);
                                    $('#is_next').val(0);
                                    $('#form_update')[0].reset();
                                    CKEDITOR.instances.object_content.setData( '' );

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
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endsection