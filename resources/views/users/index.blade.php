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
                                <div class="col-md-6">
                                    <div class="col-md-2">
                                        <span>STT</span>
                                    </div>
                                    <div class="col-md-3 no-padding">
                                        <span>Tên đăng nhập</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>Họ tên</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Giới tính</span>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding ">
                                    <div class="col-md-3 no-padding">
                                        <span>Email </span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Số ĐT</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Trạng thái</span>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>
                            <ul class="category_product">
                                @foreach($objects['data'] as $index => $item)
                                    <?php
                                    $_objects[$item['user_id']] = $item;
                                    ?>
                                    <li class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-2">
                                                <span>{{$index+1}}</span>
                                            </div>
                                            <div class="col-md-3 no-padding content">
                                                <span>{{$item['username']}}</span>
                                            </div>
                                            <div class="col-md-4 content">
                                                <span>{{$item['fullname']}}</span>
                                            </div>
                                            <div class="col-md-3 content">
                                                <span>
                                                    @if(!$item['gender'])
                                                        Nữ
                                                    @elseif($item['gender'] == 1)
                                                        Nam
                                                    @else
                                                        Khác
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 no-padding">
                                            <div class="col-md-3 no-padding content">
                                                <span>{{$item['email']}}</span>
                                            </div>
                                            <div class="col-md-3 no-padding content">
                                                <span>{{$item['phone']}}</span>
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
                                            <div class="col-md-3">
                                                <a class="tooltip update-action" data-id="{{$item['user_id']}}">
                                                    <i class="icon-edit-pen active UpdateAction" aria-hidden="true"></i>
                                                    <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                                    <span class="tooltiptext">Cập nhật</span>
                                                </a>
                                                <a class="tooltip delete-action" data-id="{{$item['user_id']}}">
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
                    <form class="form-create" method="post" id="form_update" action="{{ route("users.add") }}">
                        <div class="form-group">

                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên đăng nhập <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Nhập tên đăng nhập">
                                    <label id="username-error" class="error" for="username" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Địa chỉ</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="address" id="address" class="form-control" placeholder="Nhập địa chỉ">
                                    <label id="address-error" class="error" for="address" style="display: none;"></label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Nhập Email">
                                    <label id="email-error" class="error" for="email" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Phường/Xã</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="ward" id="ward" class="form-control" placeholder="Nhập Phường/Xã">
                                    <label id="ward-error" class="error" for="ward" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Họ tên</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Nhập họ tên">
                                    <label id="fullname-error" class="error" for="fullname" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Quận/Huyện</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="district" id="district" class="form-control" placeholder="Nhập Quận/Huyện">
                                    <label id="district-error" class="error" for="district" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Giới tính</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="wrap_select">
                                        {!! Form::select("gender", $genders, '', ['user_id' => 'gender', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="gender-error" class="error" for="gender" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tỉnh/TP</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="city" id="city" class="form-control" placeholder="Nhập Tỉnh/TP">
                                    <label id="city-error" class="error" for="city" style="display: none;"></label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Số điện thoại</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Nhập số điện thoại">
                                    <label id="phone-error" class="error" for="phone" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Số CMND</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="personal_id" id="personal_id" class="form-control" placeholder="Nhập số CMND">
                                    <label id="personal_id-error" class="error" for="personal_id" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Skype</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="skype" id="skype" class="form-control" placeholder="Nhập nick skype">
                                    <label id="skype-error" class="error" for="skype" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Nơi cấp CMND</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="place_of_issue" id="place_of_issue" class="form-control" placeholder="Nhập nơi cấp CMND">
                                    <label id="place_of_issue-error" class="error" for="place_of_issue" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Chủ tài khoản VCB</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="vcb_account_holder" id="vcb_account_holder" class="form-control" placeholder="Nhập chủ tài khoản VCB">
                                    <label id="vcb_account_holder-error" class="error" for="vcb_account_holder" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Ngày cấp CMND</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="time">
                                        <div class="input-group date">
                                            <input name="date_of_issue" id="date_of_issue" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                    </div>
                                    <label id="date_of_issue-error" class="error" for="date_of_issue" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Số tài khoản VCB</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="vcb_account_holder" id="vcb_account_holder" class="form-control" placeholder="Nhập số tài khoản VCB">
                                    <label id="vcb_account_holder-error" class="error" for="vcb_account_holder" style="display: none;"></label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Chi nhánh ngân hàng tại VCB</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="vcb_account_branch" id="vcb_account_branch" class="form-control" placeholder="Nhập chi nhánh ngân hàng tại VCB">
                                    <label id="vcb_account_branch-error" class="error" for="vcb_account_branch" style="display: none;"></label>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Hình CMND mặt trước</label>
                            </div>
                            <div class="col-md-11">
                                <div class="part_create">
                                    <div class="col-md-5 left-load">
                                        <div class="image-upload">
                                            <label for="file-input" class="browse-image" data-target="front_image_url">
                                                <img src="/html/assets/images/img_upload.png" alt="">
                                                <div class="wrap-bg" style="display: none;">
                                                    <img class="display" src="/html/assets/images/icon-camera.png" alt="your image" >
                                                </div>
                                            </label>
                                        </div>
                                        <div class="infor-img">
                                            <div class="show_file">
                                                <span class="file_name"></span>
                                                <input type="hidden" value="" name="front_image_url" id="front_image_url" data-preview="#form_update .preview-front-banner">
                                                <label id="front_image_url-error" class="error" for="front_image_url" style="display: none;"></label>
                                            </div>
                                            <span class="size-note">Dung lượng: <b>500kb</b></span>
                                            <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                        </div>
                                    </div>
                                    <div class="col-md-7 right-load">
                                        <div class="wrap_images image-upload">
                                            <img class="preview-front-banner" src="/html/assets/images/img-news.jpg" alt="your image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Hình CMND mặt sau</label>
                            </div>
                            <div class="col-md-11">
                                <div class="part_create">
                                    <div class="col-md-5 left-load">
                                        <div class="image-upload">
                                            <label for="file-input" class="browse-image" data-target="back_image_url">
                                                <img src="/html/assets/images/img_upload.png" alt="">
                                                <div class="wrap-bg" style="display: none;">
                                                    <img class="display" src="/html/assets/images/icon-camera.png" alt="your image" >
                                                </div>
                                            </label>
                                        </div>
                                        <div class="infor-img">
                                            <div class="show_file">
                                                <span class="file_name"></span>
                                                <input type="hidden" value="" name="back_image_url" id="back_image_url" data-preview="#form_update .preview-back-banner">
                                                <label id="back_image_url-error" class="error" for="back_image_url" style="display: none;"></label>
                                            </div>
                                            <span class="size-note">Dung lượng: <b>500kb</b></span>
                                            <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                        </div>
                                    </div>
                                    <div class="col-md-7 right-load">
                                        <div class="wrap_images image-upload">
                                            <img class="preview-back-banner" src="/html/assets/images/img-news.jpg" alt="your image" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Trạng thái <span>*</span></label>
                            </div>
                            <div class="col-md-11">
                                <div class="col-md-6">
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
                                <div class="col-md-6 text-right">
                                    <div class="action">
                                        <span class="cancel Cancel">Hủy bỏ</span>
                                        <input type="hidden" id="is_reload" value="0">
                                        <input type="hidden" id="is_next" value="0">
                                        <input type="hidden" name="user_id" id="user_id" value="0">
                                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                        <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                        <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới khách hàng </button>
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

                    $.post('{{route('admin-users.delete')}}', {
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
                $('#form_update .preview-front-banner').attr('src', '/html/assets/images/img-news.jpg');
                $('#form_update .preview-back-banner').attr('src', '/html/assets/images/img-news.jpg');

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
                $('#form_update #user_id').val(item.user_id);
                $('#form_update #username').val(item.username);

                $('#form_update #fullname').val(item.fullname);
                $('#form_update #email').val(item.email);
                $('#form_update #gender').val(item.gender);
                $('#form_update #birthday').val(item.birthday);
                $('#form_update #personal_id').val(item.personal_id);
                $('#form_update #place_of_issue').val(item.place_of_issue);
                $('#form_update #date_of_issue').val(item.date_of_issue);
                $('#form_update #address').val(item.address);
                $('#form_update #ward').val(item.ward);
                $('#form_update #district').val(item.district);
                $('#form_update #city').val(item.city);
                $('#form_update #phone').val(item.phone);
                $('#form_update #skype').val(item.skype);
                $('#form_update #vcb_account_holder').val(item.vcb_account_holder);
                $('#form_update #vcb_account_number').val(item.vcb_account_number);
                $('#form_update #vcb_account_branch').val(item.vcb_account_branch);

                $('#form_update #front_image_url').val(item.front_image_url);
                $('#form_update .preview-front-banner').attr('src', item.front_image_url);
                $('#form_update #back_image_url').val(item.back_image_url);
                $('#form_update .preview-back-banner').attr('src', item.back_image_url);
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
                    username: "required",
                },
                messages: {
                    username: "Vui lòng nhập tên đăng nhập",
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
                                    $('#form_update .preview-front-banner').attr('src', '/html/assets/images/img-news.jpg');
                                    $('#form_update .preview-back-banner').attr('src', '/html/assets/images/img-news.jpg');


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