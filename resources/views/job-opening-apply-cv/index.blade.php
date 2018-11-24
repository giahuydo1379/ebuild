@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-career BannerAct">
            <h3 class="title-section">CV ứng tuyển</h3>
            <a href="#" class="list-career pull-right ListAction" style="display: none;">
                <i class="fa fa-file-text" aria-hidden="true"></i> <span>Danh sách ứng viên</span> </a>
            <div class="panel box-panel Panel" ng-app="myApp" ng-controller="myCtrl">
                <div class="top">
                    <h3 class="title TitleDisplay">Danh sách ứng viên</h3>
                    <div class="navigate TitleDisplayDetail" style="display: none;">
                        <ul>
                            <li><a href="javascript:void(0)" class="title"><b>Hồ sơ ứng viên</b></a></li>
                            <li><a href="javascript:void(0)" class="title">Nhân viên Marketing</a></li>
                            <li><a href="javascript:void(0)" class="title">Nguyễn Thị Ngọc Lan</a></li>
                        </ul>
                        <a href="<?='{{ item.cv_file }}'?>" class="upload pull-right"><i class="fa fa-upload" aria-hidden="true"></i> <span>Tải hồ sơ ứng viên</span></a>
                    </div>
                    <a href="javascript:void(0)" class="pull-right link BackAction" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link AddAction" style="display: none;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <div class="banner banner-display DisplayData">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-7">
                                <div class="col-md-1">
                                    <input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check">
                                </div>
                                <div class="col-md-4 no-padding">
                                    <span>Tên vị trí</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Danh mục</span>
                                </div>
                                <div class="col-md-3 no-padding">
                                    <span>Tỉnh/ Thành phố</span>
                                </div>
                            </div>
                            <div class="col-md-5 no-padding ">
                                <div class="col-md-4 no-padding">
                                    <span>Tên ứng viên </span>
                                </div>
                                <div class="col-md-4 no-padding">
                                    <span>Ngày ứng tuyển</span>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                            <form id="frm-filter-cv" class="row-filter text-center">
                                <div class="col-md-offset-2 col-md-3 no-padding">
                                    <div class="wrap_select">
                                        {!! Form::select("category_filter", ['' => 'Chọn danh mục']+$categories, null, ['id' => 'category_filter', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="wrap_select">
                                        <select class="form-control provinces" name="province_filter" id="province_filter" data-id="{{@$params['province_filter']}}">
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-2 no-padding">
                                    <div class="time">
                                        <div class="input-group date" >
                                            <input class="form-control datepicker" name="date_from" size="20" type="text" value="" placeholder="Ngày bắt đầu">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="time">
                                        <div class="input-group date" >
                                            <input class="form-control datepicker" size="20" name="date_to" type="text" value="" placeholder="Ngày kết thúc">
                                            <span class="fa fa-calendar"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 text-left no-padding submit">
                                    <button type="submit" onclick="$('#frm-filter-cv').submit()" class="btn btn_primary">Lọc</button>
                                </div>
                            </form>

                            @foreach($objects['data'] as $index => $item)
                                <?php
                                $_objects[$item['apply_id']] = $item;
                                ?>
                            <li class="row">
                                <div class="col-md-7">
                                    <div class="col-md-1">
                                        <input type="checkbox" name="choose"  class="checkbox_check">
                                    </div>
                                    <div class="col-md-4 no-padding content">
                                        <span>{{$item['position']}}</span>
                                    </div>
                                    <div class="col-md-4 content">
                                        <span>{{@$categories[$item['job_category_id']]}}</span>
                                    </div>
                                    <div class="col-md-3 content">
                                        <span>{{@$item['province_name']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-5 no-padding">
                                    <div class="col-md-4 no-padding content">
                                        <span>{{$item['name']}}</span>
                                    </div>
                                    <div class="col-md-4 content text-center">
                                        <span>{{$item['apply_at']}}</span>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <a class="tooltip" href="{{$item['cv_file']}}">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                            <span class="tooltiptext">Tải xuống</span>
                                        </a>
                                        <a class="tooltip ViewAction" ng-click="viewDetail({{$item['apply_id']}})">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <span class="tooltiptext">Xem chi tiết</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach

                            @if (!$objects['total'])
                                <div class="no-banner NoData">
                                    <p>Chưa có thông tin ứng viên dự tuyển!</p>
                                </div>
                            @endif
                        </ul>

                        @include('includes.paginator')
                    </div>
                </div>
                <div class="wrap-detail DetailCV">
                    <div class="wrap-block">
                        <h3 class="title-detail"><i class="fa fa-info-circle" aria-hidden="true"></i> Thông tin ứng viên</h3>
                        <div class="box">
                            <div class="col-md-2 no-padding">
                                <div class="wrap-img">
                                    <img src="<?='{{ item.avatar }}'?>" onerror="this.src='/html/assets/images/user.png'">
                                </div>
                            </div>
                            <div class="col-md-10 no-padding">
                                <ul class="list-infor">
                                    <li><b>Vị trí ứng tuyển:</b> <?='{{ item.position }}'?></li>
                                    <li><b>Nguyện vọng làm việc tại:</b> <?='{{ item.expectations_at_work }}'?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="wrap-block">
                        <h3 class="title-detail"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Thông tin cá nhân</h3>
                        <div class="box">
                            <div class="col-md-5 no-padding">
                                <ul class="list-infor">
                                    <li><b>Họ và tên:</b> <?='{{ item.name }}'?></li>
                                    <li><b>Ngày sinh:</b> <?='{{ item.birthday }}'?></li>
                                    <li><b>Giới tính:</b> <?='{{ item.gender }}'?></li>
                                    <li><b>Nơi sinh:</b> <?='{{ item.place_of_birth }}'?></li>
                                    <li><b>Số CMND:</b> <?='{{ item.cmnd }}'?></li>
                                    <li><b>Chiều cao:</b> <?='{{ item.height }}'?> - <b>Cân nặng:</b> <?='{{ item.weight }}'?></li>
                                </ul>
                            </div>
                            <div class="col-md-7">
                                <ul class="list-infor">
                                    <li><b>Tình trạng hôn nhân:</b> <?='{{ item.marital_status }}'?></li>
                                    <li><b>Địa chỉ liên hệ:</b> <?='{{ item.address }}'?></li>
                                    <li><b>Điện thoại: </b> <?='{{ item.phone }}'?></li>
                                    <li><b>Thu nhập mong muốn:</b> <?='{{ item.expected_income }}'?></li>
                                    <li><b>Email liên hệ:</b> <?='{{ item.email }}'?></li>
                                    <li><b>Lý do ứng tuyển:</b> <?='{{ item.reason_apply }}'?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="wrap-block">
                        <h3 class="title-detail"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Trình độ học vấn</h3>
                        <div class="box">
                            <ul class="list-infor">
                                <li><b>Trường:</b> <?='{{ item.schools }}'?></li>
                                <li><b>Ngành:</b> <?='{{ item.study }}'?></li>
                                <li><b>Hệ đào tạo:</b> <?='{{ item.class_study }}'?></li>
                                <li><b>Các chứng chỉ khác:</b> <?='{{ item.other_certificates }}'?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="wrap-block">
                        <h3 class="title-detail"><i class="fa fa-university" aria-hidden="true"></i></i> Quá trình làm việc</h3>
                        <div class="box work_progress" ng-bind-html="item.work_progress | unsafe"></div>
                    </div>
                    <div class="action pull-right">
                        <a type="button" class="btn-upload" href="<?='{{ item.cv_file }}'?>"><i class="fa fa-upload" aria-hidden="true"></i> Tải hồ sơ ứng viên</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>

    <script type="text/javascript">
        var _objects = {!! @json_encode($_objects) !!};
        $(document).ready(function() {
            init_datepicker('.datepicker');

            get_provinces('.provinces');
        });

        var app = angular.module('myApp', []);
        app.controller('myCtrl', function($scope) {
            $scope.viewDetail = function(apply_id) {
                $scope.item = _objects[apply_id];
                console.log($scope.item);
            }
        });
        app.filter('unsafe', function($sce) { return $sce.trustAsHtml; });
    </script>
@endsection