@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-news BannerAct">
            <h3 class="title-section">Danh mục tư vấn</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Tạo danh mục tin tức</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật danh mục</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách danh mục</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;">
                        <i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif>
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects['total'])
                <div class="banner banner-display">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-6">
                                <div class="col-md-3">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-9">
                                    <span>Tên danh mục</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-5">
                                    <span>Số lượng bài viết </span>
                                </div>
                                <div class="col-md-5">
                                    <span>Trạng thái</span>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                        @foreach($objects['data'] as $index => $item)
                                <?php
                                $_objects[$item['id']] = $item;
                                ?>
                            <li class="row">
                                <div class="col-md-6">
                                    <div class="col-md-3"><span>{{$index+1}}</span></div>
                                    <div class="col-md-9">
                                        <span>{{$item['name']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5">
                                        <span>Có <?=isset($counts[$item['id']]) ? $counts[$item['id']] : 0;?> bài viết</span>
                                    </div>
                                    <div class="col-md-4">
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
                                    <div class="col-md-3">
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
                    <form class="form-create" method="post" id="form_update" action="{{ route("advice-categories.add") }}">
                        <div class="col-md-7 col-md-offset-2">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Tên danh mục <span>*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên danh mục mới cần tạo">
                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Tên Alias</label>
                                </div>
                                <div class="col-md-8">
                                    <input readonly type="text" name="alias" id="alias" class="form-control" placeholder="Alias sẽ được tự động theo tên danh mục">
                                    <label id="alias-error" class="error" for="alias" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Thương hiệu</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="wrap_select">
                                        {!! Form::select("brand_id",['' => ''] + $brand_options, null, ['id' => 'brand_id', 'class' => 'form-control select2','data-placeholder' => 'Chọn thương hiệu']) !!}
                                    </div>
                                    <label id="brand_id-error" class="error" for="ordering" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Thứ tự hiển thị <span>*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="wrap_select">
                                        {!! Form::select("ordering",\App\Helpers\General::get_ordering_options(), null, ['id' => 'ordering', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="ordering-error" class="error" for="ordering" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Trạng thái <span>*</span></label>
                                </div>
                                <div class="col-md-8">
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
                            <div class="form-group wrap-btn">
                                <div class="col-md-4">
                                    <span class="cancel">Hủy bỏ</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" name="id" id="id" value="0">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" value="" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                    <button type="submit" value="" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm danh mục mới</button>
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
    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        $(document).ready(function() {
            init_select2('.select2');
            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, danh mục này không?", function () {
                    ajax_loading(true);
                    $.post('{{route('advice-categories.delete')}}', {
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
                $('.BackAction').show();
                $('.TitleDisplay').hide();
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

                $('.TitleUpdate').hide();
            });

            $('.update-action').on('click', function () {
                var item = _objects[$(this).attr('data-id')];

                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id);
                $('#form_update #name').val(item.name);
                $('#form_update #ordering').val(item.ordering);
                $('#form_update #alias').val(item.alias);
                $('#form_update #brand_id').val(item.brand_id).trigger('change');
                if (item.status) {
                    $('#form_update #status').attr('checked', 'checked');
                } else {
                    $('#form_update #status').removeAttr('checked');
                }

                $('.add-action').click();

                $('.TitleCreate').hide();
                $('.TitleUpdate').show();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    ordering: "required",
                },
                messages: {
                    name: "Vui lòng nhập tên danh mục",
                    ordering: "Vui lòng chọn thứ tự hiển thị",
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
                                    $('#form_update #brand_id').trigger('change');

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