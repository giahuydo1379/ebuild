@extends('layouts.login')
<?php
$settings = \App\Helpers\General::get_settings();
?>
@section('content')
    <style>
        .captcha {
            padding-right: 0px;
        }
        .captcha img {
            border: 1px solid #e1e1e1;
            border-radius: 3px;
        }
    </style>
    <div class="wp-login">
        <div class="wrap_form_login">
            <a class="logo" href="#">
                <img src="<?=$settings['image_logo_cms_login']['value'] ?? '/html/assets/images/logo-login.png'?>" alt="logo">
            </a>
            <p class="title-login">ĐĂNG NHẬP HỆ THỐNG QUẢN TRỊ {{ config('app.name', '') }}</p>

            <form class="form_login" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="form-content">
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <div class="row">
                            @if ($errors->has('email'))
                                <p class="error">{{ $errors->first('email') }}</p>
                            @endif
                            <input id="email" type="text" class="ip_text" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <div class="row">
                            @if ($errors->has('password'))
                                <p class="error">{{ $errors->first('password') }}</p>
                            @endif
                            <input id="password" type="password" class="ip_text" name="password" value="{{ old('password') }}" required>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label>Nhập mã xác thực</label>
                        <div class="row">
                            <div class="col-md-8">
                                @if ($errors->has('captcha'))
                                    <?php $errMsgCaptcha = "Mã xác thực chưa đúng";?>
                                    <p class="error">{{ $errMsgCaptcha }}</p>
                                @endif
                                <input class="ip_text" type="text" id="captcha" name="captcha">
                            </div>
                            <div class="col-md-4 captcha">
                                <div class="ip_text no-padding">
                                    @captcha
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn_login" type="submit" value="">{{ __('Login') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer-login">
        {{@$settings['copyright_cms']['value']}}
    </div>
@endsection