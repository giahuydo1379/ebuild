@extends('layouts.master')

@section('after_styles')
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

                <div id="tab1" class="tabcontent active" style="display: block;">
                    <div class="form-create ">
                        <form action="" class="infor-user">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mã số nhân viên <span>*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên đăng nhập" value="{{$user['username']}}" >
                                </div>
                                <div class="form-group">
                                    <label>Họ và tên user <span>*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên user" value="{{$user['full_name']}}">
                                </div>
                                <div class="form-group">
                                    <label>Giới tính <span>*</span></label>
                                    <div class="wrap_select">
                                        {!! Form::select("gender", $genders, $user['gender'], ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email <span>*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập địa chỉ email" value="{{$user['email']}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Số điện thoại <span>*</span></label>
                                    <input type="text" class="form-control" placeholder="Nhập số điện thoại" value="{{$user['phone']}}">
                                </div>
                                <div class="form-group">
                                    <label>Bộ phận làm việc <span>*</span></label>
                                    <div class="wrap_select">
                                        <input type="text" class="form-control" placeholder="" value="{{$user['department_name']}}">
                                    </div>
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
@endsection