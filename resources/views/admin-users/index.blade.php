@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">người dùng CMS</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Thêm mới người dùng CMS</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật người dùng CMS</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách người dùng CMS</h3>
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
                                    <div class="col-md-6 no-padding">
                                        <span>Email </span>
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
                                    $_objects[$item['id']] = $item;
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
                                                <span>{{$item['full_name']}}</span>
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
                                            <div class="col-md-6 no-padding content">
                                                <span>{{$item['email']}}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="wrapper tooltip">
                                                    <input type="checkbox" id="status-{{$index}}" class="slider-toggle" @if ($item['is_enabled']) checked @endif/>
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
                    <form class="form-create" method="post" id="form_update" action="{{ route("admin-users.add") }}">
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
                                    <label>Mật khẩu</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Nhập password">
                                    <label id="password-error" class="error" for="password" style="display: none;"></label>
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
                                    <label>Phân quyền</label>
                                </div>
                                <div class="col-md-10">
                                    {!! Form::select("role_id[]", $roles, '', ['id' => 'role_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn quyền', 'multiple' => 'multiple']) !!}
                                    <label id="role_id-error" class="error" for="role_id" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Họ tên</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Nhập họ tên">
                                    <label id="full_name-error" class="error" for="full_name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Giới tính</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="wrap_select">
                                        {!! Form::select("gender", $genders, '', ['id' => 'gender', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="gender-error" class="error" for="gender" style="display: none;"></label>
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
                                        <input value="0" type="hidden" name="is_enabled"/>
                                        <input value="1" type="checkbox" id="is_enabled" name="is_enabled" class="slider-toggle" checked />
                                        <label class="slider-viewport" for="is_enabled">
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
                                        <input type="hidden" name="id" id="id" value="0">
                                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                        <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                        <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới người dùng CMS </button>
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
                confirm_delete("Bạn có muốn xóa, người dùng CMS này không?", function () {
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

                if( parseInt( $('#id').val() ) == 0){
                    $("#password").rules( "add", {
                        required: true,
                        messages: {
                            required: "Nhập mật khẩu",
                        }
                    });
                }
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

                $("#password").rules( "remove");
                $('#is_next').val(0);
                $('#form_update #id').val(0);   
                $('#form_update')[0].reset();                
                $('#role_id').select2('val', '');

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
                $('#form_update #username').val(item.username);

                $('#form_update #full_name').val(item.full_name);
                $('#form_update #email').val(item.email);
                $('#form_update #gender').val(item.gender);
                 $('#form_update #role_id').val(item.role_id);
                
                if (item.is_enabled) {
                    $('#form_update #is_enabled').attr('checked', 'checked');
                } else {
                    $('#form_update #is_enabled').removeAttr('checked');
                }

                $.post(_base_url+'/admin-users/get-role',{id:item.id},function(res){
                    if(res.rs == 1){
                        $('#role_id').select2('val', res.data);
                    }
                });

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
                                    $('#form_update #id').val(0);
                                    $('#role_id').select2('val', '');
                                    $("#password").rules( "remove");
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