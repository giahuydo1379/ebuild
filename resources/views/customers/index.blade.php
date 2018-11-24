@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">khách hàng</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Thêm mới khách hàng</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật khách hàng</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách khách hàng</h3>
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
                                    <div class="col-md-4">
                                        <span>Họ tên</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Số ĐT</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Email </span>
                                    </div>
                                </div>
                                <div class="col-md-4 no-padding ">
                                    <div class="col-md-4">
                                        <span>Giới tính</span>
                                    </div>
                                    <div class="col-md-5">
                                        <span>Ngày sinh</span>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>
                            <ul class="category_product">
                                @foreach($objects['data'] as $index => $item)
                                    <?php
                                    $_objects[$item['id']] = $item;
                                    ?>
                                    <li class="row">
                                        <div class="col-md-8">
                                            <div class="col-md-2">
                                                <span>{{$index+1}}</span>
                                            </div>
                                            <div class="col-md-4 content">
                                                <span>{{$item['name']}}</span>
                                            </div>
                                            <div class="col-md-3 content">
                                                <span>{{$item['phone']}}</span>
                                            </div>
                                            <div class="col-md-3 content">
                                                <span>{{$item['email']}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 no-padding">
                                            <div class="col-md-4 content">
                                                <span>
                                                    @if($item['gender'] =='female')
                                                        Nữ
                                                    @elseif($item['gender'] == 'male')
                                                        Nam
                                                    @else
                                                        Khác
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="col-md-5 content">
                                                <?=date('d/m/Y',strtotime($item['birthday']))?>
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
                    <form class="form-create" method="post" id="form_update" action="{{ route($controllerName.".add") }}">
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
                                    <label>Mã khách hàng <span class="required"></span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="code" name="code" id="code" class="form-control" placeholder="Nhập mã khách hàng">
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
                                    <div class="wrap_select">
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="male">Nam</option>
                                            <option value="female">Nữ</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
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
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới khách hàng </button>
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
            init_datepicker('.datepicker');

            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, khách hàng này không?", function () {
                    ajax_loading(true);
                    console.log($(obj).attr('data-id'));
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
                $('#form_update #id').val(item.id);
                $('#form_update #name').val(item.name);
                $('#form_update #code').val(item.code);
                $('#form_update #email').val(item.email);
                $('#form_update #gender').val(item.gender);
                $('#form_update #birthday').val(item.birthday);
                $('#form_update #phone').val(item.phone);
                $('.add-action').click();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    code: "required",
                    phone: "required",
                    email: {
                        "required":true,
                        "email":true
                    },
                    'password_confirmation':{
                        equalTo: "#password"
                    }
                },
                messages: {
                    email: {
                        "required":'Vui lòng nhập email',
                        "email":"Email không đúng định dạng"
                    },
                    code:'Vui lòng nhập mã khách hàng',
                    name:'Vui lòng nhập họ tên',
                    phone:'Vui lòng nhập số điện thoại',
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
    </script>
@endsection