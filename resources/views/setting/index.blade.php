@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section"><?=$title?></h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật <?=$title?></h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách <?=$title?> mới</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects['total'])
                <div class="banner banner-display">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-7">
                                <div class="col-md-2">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-5 no-padding">
                                    <span>Tên <?=$title?></span>
                                </div>
                                <div class="col-md-5">
                                    <span>Mô tả</span>
                                </div>
                            </div>
                            <div class="col-md-5 no-padding">
                                <div class="col-md-8">
                                    <span>Giá trị</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Trạng thái</span>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                        @foreach($objects['data'] as $index => $item)
                            <?php
                            $_objects[$item['id']] = $item;
                            ?>
                            <li class="row">
                                <div class="col-md-7">
                                    <div class="col-md-2">
                                        <span>{{$index+1}}</span>
                                    </div>
                                    <div class="col-md-5 no-padding content">
                                        <span>{{$item['name']}}</span>
                                    </div>
                                    <div class="col-md-5 content">
                                        <span>{{$item['description']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-5 no-padding">
                                    <div class="col-md-8 content">
                                        <span>{{$item['value']}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="wrapper tooltip">
                                            <input type="checkbox" id="active-{{$index}}" class="slider-toggle" @if ($item['active']) checked @endif/>
                                            <label class="slider-viewport" for="active-{{$index}}" onclick="return false;">
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
                                        <a class="tooltip update-action" data-id="{{$item['id']}}">
                                            <i class="icon-edit-pen active UpdateAction" aria-hidden="true"></i>
                                            <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Cập nhật</span>
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
                    <form class="form-create" method="post" id="form_update" action="{{ route($controllerName.".add") }}">
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Tên <?=$title?> <span>*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input readonly type="text" name="name" id="name" class="form-control" placeholder="Nhập tên <?=$title?> cần tạo">
                                <label id="name-error" class="error" for="title" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Mô tả <?=$title?> <span>*</span></label>
                            </div>
                            <div class="col-md-10">
                                <textarea readonly style="height: auto;" class="form-control" rows="2" name="description" id="description" placeholder="Nhập mô tả <?=$title?>"></textarea>
                                <label id="description-error" class="error" for="description" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Giá trị<span>*</span></label>
                            </div>
                            <div class="col-md-10">
                                <label id="value-error" class="error" for="value" style="display: none;"></label>
                                <div class="field field_image">
                                    <div class="col-md-6" style="padding-right: 20px;">
                                        <input type="text" value="" name="field_image" id="field_image" class="form-control" placeholder="Nhập link ảnh hoặc chọn ảnh"
                                               data-preview=".preview-banner" data-value="#value">
                                        <ul class="wrap_btn" style="margin-top: 10px;">
                                            <li>
                                                <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="field_image">
                                                    <i class="icon-browser">&nbsp;</i>
                                                    <span>Browse ...</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <span class="size-note">Chọn ảnh có kích thước phù hợp với vị trí bạn muốn hiển thị</span>
                                    </div>
                                    <div class="col-md-6">
                                        <img class="preview-banner" src="/assets/images/default.png" onerror="$(this).attr('src', '/assets/images/default.png')" alt="Banner giới thiệu">
                                    </div>
                                </div>
                                <div class="field field_textarea" style="display: none;">
                                    <textarea style="height: auto;" class="form-control" rows="10" name="value" id="value" placeholder="Nhập giá trị <?=$title?>"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2 text-right">
                                <label>Trạng thái <span>*</span></label>
                            </div>
                            <div class="col-md-10">
                                <div class="wrapper">
                                    <input value="0" type="hidden" name="active"/>
                                    <input value="1" type="checkbox" id="active" name="active" class="slider-toggle" checked />
                                    <label class="slider-viewport" for="active">
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
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <div class="action">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" name="id" id="id" value="0">
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

            $('#field_image').on('change', function () {
                $('.preview-banner').attr('src', $(this).val());
                $('#value').val($(this).val());
            });
            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.add-action').on('click', function () {
                add_action();
            });
            function add_action() {
                $('.add-action').hide();
                $('.banner-display').slideUp();
                $('.banner-update').slideDown();

                $('.TitleCreate').show();
                $('.TitleDisplay').hide();
                $('.BackAction').show();
            }
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
            });

            $('.update-action').on('click', function () {
                var item = _objects[$(this).attr('data-id')];
                var field = $.parseJSON(item.field);

                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id);
                $('#form_update #name').val(item.name);
                $('#form_update #description').val(item.description);

                $('div.field').hide();
                if (field.type=='image') {
                    $('div.field_image').show();
                    $('#form_update #field_image').val(item.value).trigger('change');
                } else {
                    $('div.field_textarea').show();
                    $('#form_update #value').val(item.value);
                }

                if (item.active) {
                    $('#form_update #active').attr('checked', 'checked');
                } else {
                    $('#form_update #active').removeAttr('checked');
                }

                add_action();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    value: "required",
                },
                messages: {
                    name: "Vui lòng nhập tên <?=$title?>",
                    value: "Vui lòng chọn hoặc nhập giá trị <?=$title?>",
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
                                    add_action();

                                    $('#is_reload').val(1);
                                    $('#is_next').val(0);
                                    $('#form_update')[0].reset();
                                    CKEDITOR.instances.value.setData( '' );

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