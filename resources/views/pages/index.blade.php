@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">Quản lý Pages</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Thêm mới Pages</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật Pages</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách Pages</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if (!empty($objects['data']))
                    <div class="banner banner-display">
                        <div class="table-display">
                            <div class="header_table">
                                <div class="col-md-12">
                                    <div class="col-md-1">
                                        <span>STT</span>
                                    </div>
                                    <div class="col-md-2 no-padding">
                                        <span>Tiêu đề</span>
                                    </div>
                                    <div class="col-md-2 no-padding">
                                        <span>Tên page</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Hình ảnh</span>
                                    </div>                                
                                    <div class="col-md-2 no-padding">
                                        <span>Keyword</span>
                                    </div>
                                    <div class="col-md-2">
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
                                        <div class="col-md-12">
                                            <div class="col-md-1">
                                                <span>{{$index+1}}</span>
                                            </div>
                                            <div class="col-md-2 no-padding content">
                                                <span>{{$item['title']}}</span>
                                            </div>
                                            <div class="col-md-2 no-padding content">
                                                <span>{{$item['page_name']}}</span>
                                            </div>
                                            <div class="col-md-2 content">
                                                <span><img src="{{@$item['image']}}"></span>
                                            </div>
                                            <div class="col-md-2 contnte">
                                                <span>{{$item['seo_keyword']}}</span>
                                            </div>
                                            <div class="col-md-2">
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
                                            <div class="col-md-1">
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
                    <form class="form-create" method="post" id="form_update" action="{{ route("pages.add") }}">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Title<span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề page">
                                    <label id="title-error" class="error" for="title" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên Alias</label>
                                </div>
                                <div class="col-md-10">
                                    <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Alias sẽ được tự động theo tên danh mục">
                                    <label id="slug-error" class="error" for="slug" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên page<span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="page_name" id="page_name" class="form-control" placeholder="Nhập tên page">
                                    <label id="page_name-error" class="error" for="page_name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Template</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="template" id="template" class="form-control" placeholder="">
                                    <label id="template-error" class="error" for="template" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Extras</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="extras" id="extras" class="form-control" placeholder="Nhập Extras">
                                    <label id="extras-error" class="error" for="extras" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Seo title</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="seo_title" id="seo_title" class="form-control" placeholder="Nhập seo title">
                                    <label id="seo_title-error" class="error" for="seo_title" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Keyword<span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="seo_keyword" id="seo_keyword" class="form-control" placeholder="Nhập keyword, cách nhau bởi dấu phẩy">
                                    <label id="seo_keyword-error" class="error" for="seo_keyword" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Seo description</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="seo_description" id="seo_description">
                                    </textarea>
                                    <label id="seo_description-error" class="error" for="seo_description" style="display: none;"></label>
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
                                            <label for="file-input" class="browse-image" data-target="image">
                                                <img src="/html/assets/images/img_upload.png" alt="">
                                                <div class="wrap-bg" style="display: none;">
                                                    <img class="display" src="/html/assets/images/icon-camera.png" alt="your image" >
                                                </div>
                                            </label>
                                        </div>
                                        <div class="infor-img">
                                            <div class="show_file">
                                                <span class="file_name"></span>
                                                <input type="hidden" value="" name="image" id="image" data-preview="#form_update .preview-banner">
                                                <label id="image-error" class="error" for="image" style="display: none;"></label>
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
                                        <button type="submit" class="btn btn_primary BtnUpdate btnNext" onclick="$('#is_next').val(1)">Lưu & Thêm mới page </button>
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
                confirm_delete("Bạn có chắc chắn muốn xóa?", function () {
                    ajax_loading(true);
                    $.post('{{route('pages.delete')}}', {
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
                $('.btnNext').show();
                $('#form_update .preview-banner').attr('src', '/html/assets/images/img-news.jpg');
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
                $('#form_update #slug').val(item.slug);
                $('#form_update #page_name').val(item.page_name);
                $('#form_update #template').val(item.template);
                $('#form_update #content').val(item.content);
                $('#form_update #image').val(item.image);
                $('#form_update #extras').val(item.extras);
                $('#form_update #seo_title').val(item.seo_title);
                $('#form_update #seo_description').val(item.seo_description);
                $('#form_update #seo_keyword').val(item.seo_keyword);
                
                $('#form_update .preview-banner').attr('src', item.image);
                if (item.status) {
                    $('#form_update #status').attr('checked', 'checked');
                } else {
                    $('#form_update #status').removeAttr('checked');
                }

                $('.add-action').click();
                $('.btnNext').hide();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    title: "required",
                    page_name: "required",
                    seo_keyword: "required",
                    image: "required",
                    content: "required",
                },
                messages: {
                    title: "Vui lòng nhập tiêu đề page",
                    page_name: "Vui lòng nhập tên page",
                    seo_keyword: "Vui lòng từ khóa seo",
                    image: "Vui lòng chọn ảnh đại diện",
                    content: "Vui lòng nhập nội dung bài viết",
                },
                submitHandler: function(form) {
                    // do other things for a valid form
                    var data = $(form).serializeArray();
                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data) {
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
                                    CKEDITOR.instances.content.setData( '' );

                                } else {
                                    location.reload();
                                }
                            });
                        } else {
                            $('#is_reload').val(0);
                            alert_success(data.msg);
                            if (data.errors) {
                                $.each(data.errors, function (key, msg) {
                                    $('#'+key+'-error').html(msg).show();
                                });
                            }
                            $('#slug').val(data.data.slug);
                        }
                    });

                    return false;
                }
            });
        });
    </script>
@endsection