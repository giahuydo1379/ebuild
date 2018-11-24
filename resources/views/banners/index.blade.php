@extends('layouts.master')

@section('content')
    <style>
        .row-filter {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-box;
            width: 100%;
            padding: 8px 0px;
            background: #e8f9ff;
        }
    </style>
    <div class="col-md-" ng-app="myApp" ng-controller="myCtrl">
        <section class="section section-promotion">
            <h3 class="title-section">Banner</h3>
            <div class="panel box-panel">
                <div class="top">
                    <h3 class="title TitleCreate">Thêm mới Banner</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật banner</h3>
                </div>
                <div class="promotion" >
                    <div class="tab">
                        <a href="{{route('banners.index')}}" class="tablinks home">Trang chủ Desktop</a>
                        <a href="{{route('banners.type', ['type' => 'home-wap'])}}" class="tablinks home-wap">Trang chủ Mobile</a>
                        <a href="{{route('banners.type', ['type' => 'category-wap'])}}" class="tablinks category-wap">Danh mục sản phẩm Mobile</a>
                        <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif>
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                    </div>

                    <div id="home" class="tabcontent active" style="display: block;">
                        <?php
                        $_objects = [];
                        ?>
                        @if ($objects['total'])
                        <div class="banner-promotion banner-display">
                            <div class="table-display">
                                <div class="header_table">
                                    <div class="col-md-7">
                                        <div class="col-md-1">
                                            <input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check">
                                        </div>
                                        <div class="col-md-4 no-padding">
                                            <span>Tên banner</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span>Hình banner</span>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <span>Vị trí</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 no-padding ">
                                        <div class="col-md-3 no-padding text-center">
                                            <span>Thứ tự hiển thị</span>
                                        </div>
                                        <div class="col-md-4 text-left">
                                            <span>Link</span>
                                        </div>
                                        <div class="col-md-2 no-padding">
                                            <span>Trạng thái</span>
                                        </div>
                                        <div class="col-md-3">
                                        </div>
                                    </div>
                                </div>
                                <ul class="category_product">
                                    <form class="row-filter">
                                        <div class="col-md-7">
                                            <div class="col-md-offset-8 col-md-4 no-padding">
                                                <div class="wrap_select">
                                                    {!! Form::select("position_filter", ['' => 'Chọn vị trị'] + $positions_options, null, ['id' => 'position_filter', 'class' => 'form-control']) !!}
                                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 no-padding">
                                            <button type="submit" class="btn btn_primary">Lọc</button>
                                        </div>
                                    </form>

                                    @foreach($objects['data'] as $index => $item)
                                        <?php
                                        $_objects[$item['id']] = $item;
                                        ?>
                                    <li class="row">
                                        <div class="col-md-7">
                                            <div class="col-md-1">
                                                <input type="checkbox" name="choose" class="checkbox_check">
                                            </div>
                                            <div class="col-md-4 no-padding content">
                                                <span>{{$item['name']}}</span>
                                            </div>
                                            <div class="col-md-4 wrap-banner">
                                                <img src="{{$item['image_url'] . $item['image_location']}}" alt="{{config('app.name')}} promotion">
                                            </div>
                                            <div class="col-md-3 content text-center" title="{{@$positions[$item['position_id']]['description']}}">
                                                <span>{{@$positions[$item['position_id']]['description']}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-5 no-padding">
                                            <div class="col-md-3 text-center">
                                                <span>{{$item['ordering']}}</span>
                                            </div>
                                            <div class="col-md-4 content no-padding">
                                                <span>{{$item['url']}}</span>
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
                            <form class="form-create" method="post" id="form_update" action="{{ route("banners.add") }}">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="col-md-3 text-right">
                                            <label>Tên banner <span>*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên banner mới cần tạo">
                                            <label id="name-error" class="error" for="name" style="display: none;"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 text-right">
                                            <label>Vị trí <span>*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="wrap_select">
                                                {!! Form::select("position_id", $positions_options, null,
                                                ['id' => 'position_id', 'class' => 'form-control']) !!}
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                            <label id="position_id-error" class="error" for="position_id" style="display: none;"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 text-right">
                                            <label>Thứ tự hiển thị <span>*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="wrap_select">
                                                {!! Form::select("ordering",\App\Helpers\General::get_ordering_options(), null, ['id' => 'ordering', 'class' => 'form-control']) !!}
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                            <label id="ordering-error" class="error" for="ordering" style="display: none;"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 text-right">
                                            <label>Link <span>*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="url" id="url" class="form-control" placeholder="Nhập link liên kết">
                                            <label id="url-error" class="error" for="url" style="display: none;"></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 text-right">
                                            <label>Trạng thái <span>*</span></label>
                                        </div>
                                        <div class="col-md-9">
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
                                        <div class="col-md-3 text-right">
                                            <span class="cancel">Hủy bỏ</span>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="hidden" name="page" value="{{$page}}">
                                            <input type="hidden" id="is_reload" value="0">
                                            <input type="hidden" id="is_next" value="0">
                                            <input type="hidden" name="id" id="id" value="0">
                                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                            <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm banner mới</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="choose-banner">
                                        <label>Hình banner <span>*</span></label>
                                        <div class="wrap-choose">
                                            <ul class="wrap_btn">
                                                <li>
                                                    <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_location">
                                                        <i class="icon-browser">&nbsp;</i>
                                                        <span>Browse ...</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <span class="size-note">Kích thước: <b class="size">470 x 570 px</b></span>
                                            <span class="size-note">Dung lượng: <b class="file_size">500kb</b></span>
                                            <span class="size-note">Định dạng: <b class="file_type">jpg, png</b></span>
                                        </div>
                                        <label id="image_location-error" class="error" for="image_location" style="display: none;"></label>
                                    </div>
                                    <div class="display-banner">
                                        <input type="hidden" value="" name="image_location" id="image_location" data-preview="#form_update .preview-banner">
                                        <img class="preview-banner" src="/html/assets/images/banner-promotion.png" alt="{{config('app.name')}} promotion" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>

    <script type="text/javascript">
        var _objects = {!! @json_encode($_objects) !!};
        var _positions = {!! @json_encode($positions) !!};
        $(document).ready(function() {
            $('.tablinks.{{$page}}').addClass('active');

            $('#position_id').on('change', function () {
                var item = _positions[$(this).val()];
                $('.file_size').text(item.max_file_size);
                $('.file_type').text(item.file_type);
                $('.size').text(item.size);
            });
            $('#position_id').trigger('change');

            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.switch_status').on('click', function () {
                var ids = [];
                $('.item_check:checked').each(function (key, msg) {
                    ids.push($(this).val());
                });
                ajax_loading(true);
                $.post('{{route('job-opening.change-status')}}', {
                    ids: ids,
                    status: $('#status_action').val(),
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

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, banner này không?", function () {
                    ajax_loading(true);
                    $.post('{{route('banners.delete')}}', {
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

                $('#position_id').trigger('change');
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

                $('#form_update #image_location').val('');
                $('#form_update .preview-banner').attr('src', '/html/assets/images/banner-promotion.png');
            });

            $('.update-action').on('click', function () {
                var item = _objects[$(this).attr('data-id')];

                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id);
                $('#form_update #name').val(item.name);
                $('#form_update #position_id').val(item.position_id);
                $('#form_update #ordering').val(item.ordering);
                $('#form_update #url').val(item.url);
                $('#form_update #page').val(item.page);
                $('#form_update #image_location').val(item.image_location);
                $('#form_update .preview-banner').attr('src', item.image_url + item.image_location);
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
                    name: "required",
                    position_id: "required",
                    ordering: "required",
                    url: "required",
                    image_location: "required"
                },
                messages: {
                    name: "Vui lòng nhập tên banner",
                    position_id: "Vui lòng chọn vị trí hiển thị",
                    ordering: "Vui lòng chọn thứ tự hiển thị",
                    url: "Vui lòng nhập link liên kết",
                    image_location: "Vui lòng chọn hình ảnh"
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
                                    $('#form_update #image_location').val('');
                                    $('#form_update .preview-banner').attr('src', '/html/assets/images/banner-promotion.png');

                                    $('#position_id').trigger('change');
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