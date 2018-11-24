@extends('layouts.master')

@section('after_styles')
<style type="text/css">
    label.error{
        display: none;
    }
</style>
@endsection
@section('content')
<div class="col-md-">
    <section class="section section-user">
        <h3 class="title-section">Thông tin tài khoản</h3>        
        <div class="panel box-panel">
            <div class="top">
                <h3 class="title">{{$user['full_name']}}</h3>
            </div>
            <div class="manage-user" >                
                @include('profile.tabs')

                <div id="tab2" class="tabcontent active"  style="display: block;">
                    <div class="form-create ">
                        <form action="{{route('profile.ajax-change-password')}}" class="change-password" id="change-password">
                            <div class="form-group">
                                <label for="">Mật khẩu cũ <span>*</span></label>
                                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Nhập mật khẩu cũ">
                                <label id="current_password-error" class="error">Nhập mật khẩu cũ</label>
                            </div>
                            <div class="form-group">
                                <label for="">Mật khẩu mới <span>*</span></label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới">
                                <label id="password-error" class="error">Nhập mật khẩu mới</label>
                            </div>
                            <div class="form-group">
                                <label for="">Nhập lại mật khẩu mới <span>*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới">
                                <label id="password_confirmation-error" class="error">Nhập lại mật khẩu mới</label>
                            </div>
                            <div class="form-group">
                                <label for="">Nhập mã xác thực <span>*</span></label>
                                <div class="row">
                                    <div class="col-md-8 haft-padding-left">
                                        <input class="ip_text" id="captcha" name="captcha" type="text" placeholder="Nhập mã xác thực">
                                        <label id="captcha-error" for="" class="error">Nhập lại mã xác thực</label>
                                    </div>
                                    <div class="col-md-4 img-code" id="img_captcha">
                                        @captcha
                                    </div>
                                </div>
                            </div>
                            <div class="action">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="submit" class="btn_primary"> Thay đổi mật khẩu</button>
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

    <script type="text/javascript">
        $(document).ready(function() {

            $('#change-password').validate({
                ignore: ".ignore",
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
                                location.reload();
                            });
                        } else {
                            //alert_success(data.msg);
                            if (data.errors) {
                                $('#captcha').val('');
                                $('#img_captcha img').trigger('click');
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