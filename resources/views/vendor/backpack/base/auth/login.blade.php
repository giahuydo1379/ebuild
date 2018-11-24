@extends('layouts.login')

@section('content')
    <div class="wrap-login">
        <div class="wrap_form_login">
            <a class="logo" href="#">
                <img src="/html/assets/images/logo-login.png" alt="logo">
            </a>
            <p class="title-login">ĐĂNG NHẬP HỆ THỐNG QUẢN TRỊ {{config('app.name')}}</p>
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
                    <div class="form-group">
                        <label>Nhập mã xác thực</label>
                        <div class="row">
                            <div class="col-md-8">
                                @if ($errors->has('captcha'))
                                    <?php $errMsgCaptcha = "Mã xác thực chưa đúng";?>
                                    <p class="error">{{ $errMsgCaptcha }}</p>
                                @endif
                                <input class="ip_text" type="text" id="captcha" name="captcha">
                            </div>
                            <div class="col-md-4">
                                {!! $captcha !!}
                            </div>
                        </div>
                    </div>
                    <button class="btn_login" type="submit" value="">Đăng nhập</button>
                </div>
            </form>
        </div>
        <div class="footer-login">
            <p>Được tạo ra bằng cả <i class="fa fa-heart" aria-hidden="true"></i> bởi Thien Hoa Online team</p>
        </div>
    </div>
@endsection
