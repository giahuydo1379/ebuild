@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">Trạng thái đơn hàng</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects) style="display: none;" @endif>Thêm mới trạng thái đơn hàng</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật trạng thái đơn hàng</h3>
                    <h3 class="title TitleDisplay" @if (!$objects) style="display: none;" @endif>Danh sách trạng thái đơn hàng</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects) style="display: none;" @endif><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects)
                    <div class="banner banner-display">
                        <div class="table-display">
                            <div class="header_table">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <span>STT</span>
                                    </div>
                                    <div class="col-md-3 no-padding">
                                        <span>Tên trạng thái</span>
                                    </div>
                                    <div class="col-md-2 no-padding">
                                        <span>vị trí </span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Trạng thái</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Chức năng</span>
                                    </div>
                                </div>
                            </div>
                            <ul class="category_product">
                                @foreach($objects as $index => $item)
                                    <?php
                                    $_objects[$item['id']] = $item;
                                    ?>
                                    <li class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2">
                                                <span>{{$index+1}}</span>
                                            </div>
                                            <div class="col-md-3 no-padding content">
                                                <span>{{$item['booking_status_name']}}</span>
                                            </div>
                                            <div class="col-md-2 contnte">
                                                <span>{{$item['position']}}</span>
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
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Tên trạng thái <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="booking_status_name" id="booking_status_name" class="form-control" placeholder="Nhập tên trạng thái">
                                    <label id="booking_status_name-error" class="error" for="booking_status_name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Vị trí</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="position" id="position" class="form-control" placeholder="Nhập vị trí trạng thái">
                                    <label id="position-error" class="error" for="position" style="display: none;"></label>                         
                                </div>
                            </div>
                            <div class="form-group" style="display: none;">
                                <div class="col-md-2 text-right">
                                    <label>Css class</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="css_class" id="css_class" class="form-control" placeholder="Nhập Class css">
                                    <label id="css_class-error" class="error" for="css_class" style="display: none;"></label>                         
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
                            <div class="form-group">
                                <div class="action text-right">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <input type="hidden" name="id" id="id" value="0">
                                    <input type="hidden" name="action" id="action" value="add">
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="btn btn_primary BtnUpdate btnSave" onclick="$('#is_next').val(0)">Lưu</button>
                                    <button type="submit" class="btn btn_primary BtnUpdate btnNext" onclick="$('#is_next').val(1)">Lưu & Thêm mới </button>
                                </div>  
                            </div>
                        </div>
                        <div class="col-md-2"></div>
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
                    $.post('{{route($controllerName.'.delete')}}', {
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

                $('.btnNext').show();
                $('.TitleUpdate').hide();

                $('#id').removeAttr('readonly');
                $( "#id" ).rules( "add", {
                    remote: {
                        url: "/<?=$controllerName?>/check-id",
                        type: "post"
                    }
                });
                $('#action').val('add');
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

                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id).attr('readonly', true);
                $( "#form_update #id" ).rules( "remove", "remote" );
                $('#action').val('update');
                $('#form_update #booking_status_name').val(item.booking_status_name);
                $('#form_update #position').val(item.position);
                $('#form_update #css_class').val(item.css_class);

                if (item.status) {
                    $('#form_update #status').attr('checked', 'checked');
                } else {
                    $('#form_update #status').removeAttr('checked');
                }

                $('.add-action').click();
                $('.btnNext').hide();
                $('.TitleCreate').hide();
                $('.TitleUpdate').show();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    booking_status_name: "required",
                },
                messages: {
                    booking_status_name: "Vui lòng nhập tên trạng thái đơn hàng",
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