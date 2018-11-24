@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section"><?=$title?></h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Thêm mới <?=$title?></h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật <?=$title?></h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách <?=$title?></h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                $genders = \App\Helpers\General::get_gender_options();
                ?>
                @if ($objects['total'])
                    <div class="banner banner-display">
                        <div class="table-display">
                            <div class="header_table">
                                <div class="col-md-9">
                                    <div class="col-md-1">
                                        <span>STT</span>
                                    </div>                                    
                                    <div class="col-md-2">
                                        <span>Họ tên</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>CMND</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Điện thoại</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Email </span>
                                    </div>
                                </div>
                                <div class="col-md-3 no-padding ">
                                    <div class="col-md-8">
                                        <span>Địa chỉ</span>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                            <ul class="category_product">
                                @foreach($objects['data'] as $index => $item)
                                    <?php
                                    $item['birthday'] = \App\Helpers\General::output_date($item['birthday'], true);
                                    $_objects[$item['id']] = $item;
                                    ?>
                                    <li class="row">
                                        <div class="col-md-9">
                                            <div class="col-md-1">
                                                <span>{{$index+1}}</span>
                                            </div>
                                            <div class="col-md-3 content">
                                                <span>{{$item['name']}}</span>
                                            </div>
                                            <div class="col-md-2 content">
                                                <span>{{$item['cmnd']}}</span>
                                            </div>
                                            <div class="col-md-3 content">
                                                <span>{{$item['phone']}}</span>
                                            </div>
                                            <div class="col-md-3 content">
                                                <span>{{$item['email']}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 no-padding">
                                            <div class="col-md-8 content">
                                                <?=$item['address']?>
                                            </div>
                                            <div class="col-md-4">
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
                    <form class="form-create" method="post" id="form_update" action="{{ route($controllerName.".index") }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Họ tên <span class="required"></span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nhập họ tên">
                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Mã nhân viên <span class="required"></span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="code" id="code" class="form-control" placeholder="Nhập mã nhân viên">
                                    <label id="code-error" class="error" for="code" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Email <span class="required"></span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Nhập Email">
                                    <label id="email-error" class="error" for="email" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Số điện thoại <span class="required"></span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Nhập số điện thoại">
                                    <label id="phone-error" class="error" for="phone" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>CMND <span class="required"></span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="cmnd" id="cmnd" class="form-control" placeholder="Nhập CMND">
                                    <label id="cmnd-error" class="error" for="cmnd" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Địa chỉ nhà <span class="required"></span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="address" id="address" class="form-control" placeholder="Nhập địa chỉ nhà">
                                    <label id="address-error" class="error" for="address" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Ngày sinh</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="time">
                                        <div class="input-group date">
                                            <input name="birthday" id="birthday" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                    </div>
                                    <label id="birthday-error" class="error" for="birthday" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Giới tính</label>
                                </div>
                                <div class="col-md-10">
                                    {!! Form::select("gender", $genders, null,
                                    ['id' => 'gender', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn giới tính']) !!}
                                    <label id="gender-error" class="error" for="gender" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label for="">Mật khẩu</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu">
                                    <label id="password-error" class="error" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label for="">Nhập lại mật khẩu</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu">
                                    <label id="password_confirmation-error" class="error" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-right">
                                    <label>Hình ảnh</label>
                                </div>
                                <div class="col-md-10">
                                    <label id="value-error" class="error" for="value" style="display: none;"></label>
                                    <div class="field field_image">
                                        <div class="col-md-12" style="padding-bottom: 10px;">
                                            <input type="text" value="" name="image_location" id="image_location" class="form-control" placeholder="Nhập link ảnh hoặc chọn ảnh"
                                                data-preview=".preview-banner" data-url="#image_url">
                                            <input type="hidden" id="image_url" name="image_url" value="">
                                        </div>
                                        <div class="col-md-6" style="padding-right: 20px;">
                                            <ul class="wrap_btn">
                                                <li>
                                                    <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_location">
                                                        <i class="icon-browser">&nbsp;</i>
                                                        <span>Browse ...</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <img class="preview-banner" src="/assets/images/default.png" onerror="$(this).attr('src', '/assets/images/default.png')" alt="Banner giới thiệu">
                                        </div>
                                    </div>
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
                                        <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới <?=$title?> </button>
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

@section('after_styles')
    <style>
        .preview-banner {
            width: 190px;
            height: 140px;
        }
    </style>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        $(document).ready(function() {
            init_datepicker('.datepicker');
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
                confirm_delete("Bạn có muốn xóa, <?=$title?> này không?", function () {
                    ajax_loading(true);
                    var url = '{{route($controllerName.'.index')}}/'+$(obj).attr('data-id');

                    $.ajax({
                        method: "DELETE",
                        url: url,
                        dataType: 'json'
                    })
                        .done(function( res ) {
                            ajax_loading(false);
                            alert_success(data.msg, function () {
                                if(data.rs) location.reload();
                            });
                        })
                        .fail(function(res) {
                            ajax_loading(false);
                            if(res.status==403) {
                                malert('Bạn không có quyền thực hiện tính năng này. Vui lòng liên hệ Admin!');
                            }
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

                add_check_remote();
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
                $('#id').val(0);
                $('#form_update')[0].reset();
                $('#form_update #gender').val('').trigger('change');
                $('#form_update #image_url').val('');
                $('#form_update .preview-banner').attr('src', '/assets/images/default.png');
                add_check_remote();

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
                item.image_url = item.image_url || '';
                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id);
                $('#form_update #name').val(item.name);
                $('#form_update #code').val(item.code);
                $('#form_update #email').val(item.email);
                $('#form_update #gender').val(item.gender).trigger('change');
                $('#form_update #birthday').val(item.birthday);
                $('#form_update #phone').val(item.phone);
                $('#form_update #cmnd').val(item.cmnd);
                $('#form_update #address').val(item.address);
                $('#form_update #image_location').val(item.image_location);
                $('#form_update #image_url').val(item.image_url);
                $('#form_update .preview-banner').attr('src', item.image_url+item.image_location);

                add_check_remote();

                $('.add-action').click();
            });
            $('#image_location').on('change', function () {
                $('#form_update #image_url').val('');
                $('#form_update .preview-banner').attr('src', $(this).val());
            });

            add_rule_phone_number();
            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    code: "required",
                    cmnd: "required",
                    address: "required",
                    'phone': {
                        required: true,
                        minlength: 10,
                        maxlength: 11,
                        rgphone: true,
                        remote: {
                            url: "<?=route('employees.check-phone')?>",
                            type: "post",
                            data: {id: $('#form_update #id').val()}
                        }
                    },
                    email: {
                        "required":true,
                        "email":true,
                        remote: {
                            url: "<?=route('employees.check-email')?>",
                            type: "post",
                            data: {id: $('#form_update #id').val()}
                        }
                    },
                    'password_confirmation':{
                        equalTo: "#password"
                    }
                },
                messages: {
                    email: {
                        "required":'Vui lòng nhập email',
                        "email":"Email không đúng định dạng",
                        remote: "Email đã được sử dụng."
                    },
                    name:'Vui lòng nhập họ tên',
                    code:{
                        required: 'Vui lòng nhập mã nhân viên',
                        remote: "Mã nhân viên đã được sử dụng."
                    },
                    cmnd:{
                        required: 'Vui lòng nhập cmnd',
                        remote: "CMND đã được sử dụng."
                    },
                    address:'Vui lòng nhập địa chỉ nhà',
                    'phone': {
                        required: 'Nhập số điện thoại',
                        minlength: "Số điện thoại tối thiểu 10 số",
                        maxlength: "Số điện thoại tối đa 11 số",
                        remote: "Số điện thoại đã được sử dụng."
                    },
                    'password_confirmation':{
                        equalTo:'Mật khẩu không trùng khớp'
                    }
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
        function add_check_remote() {
            $('#form_update #code').rules( "remove", "remote" );
            $('#form_update #code').rules( "add", {
                remote: {
                    url: "<?=route('employees.check-code')?>",
                    type: "post",
                    data: {id: $('#form_update #id').val()}
                }
            });

            $('#form_update #cmnd').rules( "remove", "remote" );
            $('#form_update #cmnd').rules( "add", {
                remote: {
                    url: "<?=route('employees.check-cmnd')?>",
                    type: "post",
                    data: {id: $('#form_update #id').val()}
                }
            });

            $('#form_update #phone').rules( "remove", "remote" );
            $('#form_update #phone').rules( "add", {
                remote: {
                    url: "<?=route('employees.check-phone')?>",
                    type: "post",
                    data: {id: $('#form_update #id').val()}
                }
            });

            $('#form_update #email').rules( "remove", "remote" );
            $('#form_update #email').rules( "add", {
                remote: {
                    url: "<?=route('employees.check-email')?>",
                    type: "post",
                    data: {id: $('#form_update #id').val()}
                }
            });
        }
    </script>
@endsection