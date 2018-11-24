@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-career BannerAct">
            <h3 class="title-section"><?=$title?></h3>
            <div class="panel box-panel Panel" ng-app="myApp" ng-controller="myCtrl">
                <div class="top">
                    <h3 class="title TitleDisplay">Danh sách khách hàng</h3>
                </div>
                @if ($objects['total'])
                <div class="banner banner-display DisplayData">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-8">
                                <div class="col-md-1">
                                    <input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check">
                                </div>
                                <div class="col-md-2 no-padding">
                                    <span>ID</span>
                                </div>
                                <div class="col-md-6">
                                    <span>Email</span>
                                </div>
                                <div class="col-md-3 no-padding">
                                    <span>Số điện thoại</span>
                                </div>
                            </div>
                            <div class="col-md-4 no-padding ">
                                <div class="col-md-4 no-padding">
                                    <span>Điểm tích lũy</span>
                                </div>
                                <div class="col-md-4 no-padding">
                                    <span>Điểm sử dụng</span>
                                </div>
                                <div class="col-md-4 no-padding">
                                    <span>Điểm còn lại</span>
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                            <form class="row-filter text-center">
                                <div class="col-md-offset-3 col-md-3">
                                    <input class="form-control" size="20" name="kw" type="text" value="<?=@$params['kw']?>" placeholder="Nhập ID, Email, phone">
                                </div>
                                <div class="text-left no-padding submit">
                                    <button type="submit" value="" class="btn btn_primary">Tìm kiếm</button>
                                    <a href="/point" class="btn btn-default">Làm lại</a>
                                    <button type="submit" name="is_export" value="1" class="btn btn_primary">Xuất file Excel</button>
                                </div>
                            </form>

                            @foreach($objects['data'] as $index => $item)
                                <?php
                                ?>
                            <li class="row">
                                <div class="col-md-8">
                                    <div class="col-md-1">
                                        <input type="checkbox" name="choose"  class="checkbox_check">
                                    </div>
                                    <div class="col-md-2 no-padding content">
                                        <span>{{$item['user_id']}}</span>
                                    </div>
                                    <div class="col-md-6 content">
                                        <span>{{$item['email']}}</span>
                                    </div>
                                    <div class="col-md-3 content">
                                        <span>{{$item['phone']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 no-padding">
                                    <div class="col-md-4 no-padding content">
                                        <span>{{$item['amount_put']}}</span>
                                    </div>
                                    <div class="col-md-4 no-padding content">
                                        <span>{{$item['amount_used']}}</span>
                                    </div>
                                    <div class="col-md-4 content text-center">
                                        <span>{{$item['amount_rest']}}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach

                        </ul>

                        @include('includes.paginator')
                    </div>
                </div>
                @else
                <div class="no-banner NoData">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            init_datepicker('.datepicker');
        });
    </script>
@endsection