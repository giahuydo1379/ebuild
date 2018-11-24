@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">Bài viết</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Tạo bài viết mới</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật bài viết</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách bài viết mới</h3>
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
                                    <span>Tên bài viết</span>
                                </div>
                                <div class="col-md-5">
                                    <span>Danh mục bài viết</span>
                                </div>
                            </div>
                            <div class="col-md-6 no-padding ">
                                <div class="col-md-4 no-padding">
                                    <span>Mô tả bài viết </span>
                                </div>
                                <div class="col-md-3">
                                    <span>Keyword</span>
                                </div>
                                <div class="col-md-3">
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
                                    <div class="col-md-2">
                                        <span>{{$index+1}}</span>
                                    </div>
                                    <div class="col-md-5 no-padding content">
                                        <span>{{$item['title']}}</span>
                                    </div>
                                    <div class="col-md-5 content">
                                        <span>{{$item['title']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding">
                                    <div class="col-md-4 no-padding content">
                                        <span>{{strip_tags($item['description'])}}</span>
                                    </div>
                                    <div class="col-md-3 content">
                                        <span>{{$item['keywords']}}</span>
                                    </div>
                                    <div class="col-md-3">
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
                    <form class="form-create" method="post" id="form_update" action="{{ route("news.add") }}">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên bài viết <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề bài viết cần tạo">
                                    <label id="title-error" class="error" for="title" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên Alias</label>
                                </div>
                                <div class="col-md-10">
                                    <input readonly type="text" name="alias" id="alias" class="form-control" placeholder="Alias sẽ được tự động theo tên danh mục">
                                    <label id="alias-error" class="error" for="alias" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Danh mục <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <div class="wrap_select">
                                        {!! Form::select("category_id", ['' => ''] + $categories, '', ['id' => 'category_id', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="category_id-error" class="error" for="category_id" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Keyword <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input name="keywords" id="keywords" type="text" class="form-control" placeholder="Nhập keyword, cách nhau bởi dấu phẩy">
                                    <label id="keywords-error" class="error" for="keywords" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Hình đại diện<span>*</span></label>
                            </div>
                            <div class="col-md-11">
                                <div class="part_create">
                                    <div class="col-md-5 left-load">
                                        <div class="image-upload">
                                            <label for="file-input" class="browse-image" data-target="image_url">
                                                <img src="/html/assets/images/img_upload.png" alt="">
                                                <div class="wrap-bg" style="display: none;">
                                                    <img class="display" src="/html/assets/images/icon-camera.png" alt="your image" >
                                                </div>
                                            </label>
                                        </div>
                                        <div class="infor-img">
                                            <div class="show_file">
                                                <span class="file_name"></span>
                                                <input type="hidden" value="" name="image_url" id="image_url" data-preview="#form_update .preview-banner">
                                                <label id="image_url-error" class="error" for="image_url" style="display: none;"></label>
                                            </div>
                                            <span class="size-note">Kích thước: <b>900 x 300 px</b></span>
                                            <span class="size-note">Dung lượng: <b>500kb</b></span>
                                            <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                        </div>
                                    </div>
                                    <div class="col-md-7 right-load">
                                        <div class="wrap_images image-upload">
                                            <img class="preview-banner" src="/html/assets/images/img-news.jpg" alt="your image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Mô tả ngắn<span>*</span></label>
                            </div>
                            <div class="col-md-11">
                                <textarea class="form-control" name="description" id="description" placeholder="Nhập mô tả bài viết"></textarea>
                                <label id="description-error" class="error" for="description" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Nội dung<span>*</span></label>
                            </div>
                            <div class="col-md-11">
                                <textarea class="form-control" name="content" id="content" placeholder="Nhập nội dung bài viết"></textarea>
                                <label id="content-error" class="error" for="content" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Trạng thái <span>*</span></label>
                            </div>
                            <div class="col-md-11">
                                <div class="col-md-7">
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
                                <div class="col-md-5 text-right">
                                    <div class="action">
                                        <span class="cancel Cancel">Hủy bỏ</span>
                                        <input type="hidden" id="is_reload" value="0">
                                        <input type="hidden" id="is_next" value="0">
                                        <input type="hidden" name="id" id="id" value="0">
                                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                        <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                        <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới bài viết </button>
                                    </div>
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
            $('textarea#description').ckeditor();
            $('textarea#content').ckeditor();

            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, bài viết này không?", function () {
                    ajax_loading(true);
                    $.post('{{route('news.delete')}}', {
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
                $('#form_update .preview-banner').attr('src', '/html/assets/images/img-news.jpg');
                CKEDITOR.instances.description.setData( '' );
                CKEDITOR.instances.content.setData( '' );

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
                $('#form_update #id').val(item.id);
                $('#form_update #title').val(item.title);
                $('#form_update #category_id').val(item.category_id);
                $('#form_update #alias').val(item.alias);
                $('#form_update #description').val(item.description);
                $('#form_update #content').val(item.content);
                $('#form_update #keywords').val(item.keywords);
                $('#form_update #image_url').val(item.image_url);
                $('#form_update .preview-banner').attr('src', item.image_url);
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
                    title: "required",
                    category_id: "required",
                    keywords: "required",
                    image_url: "required",
                    description: "required",
                    content: "required",
                },
                messages: {
                    title: "Vui lòng nhập tiêu đề bài viết",
                    category_id: "Vui lòng chọn danh mục tin tức",
                    keywords: "Vui lòng từ khóa seo",
                    image_url: "Vui lòng chọn ảnh đại diện",
                    description: "Vui lòng nhập mô tả bài viết",
                    content: "Vui lòng nhập nội dung bài viết",
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
                                    $('#form_update .preview-banner').attr('src', '/html/assets/images/img-news.jpg');
                                    CKEDITOR.instances.description.setData( '' );
                                    CKEDITOR.instances.content.setData( '' );

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