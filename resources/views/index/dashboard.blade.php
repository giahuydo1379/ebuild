@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="svg-wrap">
            <svg width="14" height="14" viewBox="0 0 14 14">
                <path id="arrow-left-4" d="M15.946 48l0.003-10.33 47.411 0.003v-11.37h-47.414l0.003-10.304-15.309 16z"></path>
            </svg>
            <svg width="14" height="14" viewBox="0 0 14 14">
                <path id="arrow-right-4" d="M48.058 48l-0.003-10.33-47.414 0.003v-11.37h47.418l-0.003-10.304 15.306 16z"></path>
            </svg>
        </div>
        <?php
        $now = \Carbon\Carbon::now();
        $date = \Carbon\Carbon::now();
        $format_date = \App\Helpers\General::get_format_date();
        ?>
        <section class="section section-one">
            <div class="row color-4">
                <div class="swiper-container nav-roundslide swiper-container-horizontal">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide swiper-slide-active" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->format($format_date)?>&to_date=<?=$date->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Hôm nay</h3>
                                    <span class="date">{{$date->format($format_date)}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($day, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($day, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status up"><i class="fa fa-caret-up" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                        <?php
                        $date = \Carbon\Carbon::yesterday();
                        ?>
                        <div class="swiper-slide swiper-slide-next" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->format($format_date)?>&to_date=<?=$date->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Hôm qua</h3>
                                    <span class="date">{{$date->format($format_date)}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($yesterday, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($yesterday, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status down"><i class="fa fa-caret-down" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                        <?php
                        $date = \Carbon\Carbon::now()->startOfWeek();
                        ?>
                        <div class="swiper-slide" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->format($format_date)?>&to_date=<?=$now->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Tuần này</h3>
                                    <span class="date">{{$date->format('W-Y')}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($week, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($week, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status up"><i class="fa fa-caret-up" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                        <?php
                        $date = \Carbon\Carbon::now()->addWeek(-1);
                        ?>
                        <div class="swiper-slide" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->startOfWeek()->format($format_date)?>&to_date=<?=$date->endOfWeek()->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Tuần trước</h3>
                                    <span class="date">{{$date->format('W-Y')}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($lastweek, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($lastweek, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status up"><i class="fa fa-caret-up" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                        <?php
                        $date = \Carbon\Carbon::now()->startOfMonth();
                        ?>
                        <div class="swiper-slide" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->format($format_date)?>&to_date=<?=$now->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Tháng này</h3>
                                    <span class="date">{{$date->format('m/Y')}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($month, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($month, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status down"><i class="fa fa-caret-down" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                        <?php
                        $date = \Carbon\Carbon::now()->addMonth(-1);
                        ?>
                        <div class="swiper-slide" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->startOfMonth()->format($format_date)?>&to_date=<?=$date->endOfMonth()->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Tháng trước</h3>
                                    <span class="date">{{$date->format('m/Y')}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($lastmonth, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($lastmonth, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status up"><i class="fa fa-caret-up" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                        <?php
                        $date = \Carbon\Carbon::now();
                        ?>
                        <div class="swiper-slide" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->startOfYear()->format($format_date)?>&to_date=<?=$now->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Năm này</h3>
                                    <span class="date">{{$date->format('Y')}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($year, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($year, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status up"><i class="fa fa-caret-up" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                        <?php
                        $date = \Carbon\Carbon::now()->addYear(-1);
                        ?>
                        <div class="swiper-slide" style="width: 251px; margin-right: 20px;">
                            <a href="<?=route('order.index')?>?from_date=<?=$date->startOfYear()->format($format_date)?>&to_date=<?=$date->endOfYear()->format($format_date)?>">
                                <div class="left">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <h3 class="title-slide">Năm trước</h3>
                                    <span class="date">{{$date->format('Y')}}</span>
                                </div>
                                <div class="right">
                                    <div class="top-right">
                                        <span>Doanh thu</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($lastyear, 'sales') )}}</fm-number> <span>vnđ</span></p>
                                        <span>Đơn hàng</span>
                                        <p class="number-color"><fm-number>{{array_sum( array_column($lastyear, 'total') )}}</fm-number> <span>đơn</span></p>
                                    </div>
                                    {{--<div class="bottom-right">--}}
                                        {{--<span class="status up"><i class="fa fa-caret-up" aria-hidden="true"></i> 3% từ tuần trước</span>--}}
                                        {{--<a class="text-right" href="#">Chi tiết <i class="fa fa-caret-right" aria-hidden="true"></i></a>--}}
                                    {{--</div>--}}
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- Add Arrows -->
                    <nav class="nav-roundslide">
                        <a class="prev swiper-button-prev swiper-button-disabled" href="#">
                            <span class="icon-wrap"><svg class="icon" width="18" height="18" viewBox="0 0 64 64"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-left-4"></use></svg></span>
                            <h3>Prev</h3>
                        </a>
                        <a class="next swiper-button-next" href="#">
                            <span class="icon-wrap"><svg class="icon" width="18" height="18" viewBox="0 0 64 64"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#arrow-right-4"></use></svg></span>
                            <h3>Next</h3>
                        </a>
                    </nav>
                </div>
            </div>
        </section>
        <!-- section 1 -->
        <section class="section section-two" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel box-panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Tổng doanh thu <i class="fa fa-eye" aria-hidden="true" title="Xem chi tiết"></i></h3>
                            <div class="pull-right time-display">
                                <?php
                                $options_time = \App\Helpers\General::get_time_options();
                                ?>
                                <div class="col-md-5">
                                    <label>Kiểu hiển thị:</label>
                                    <div class="wrap_select">
                                        {!! Form::select("select_time_home", $options_time, 'this_week', ['id' => 'select_time_home', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <label>Thời gian:</label>
                                    <div class="time"><span class="show_time">01/09/2017 - 07/09/2017</span> <i class="fa fa-calendar" aria-hidden="true"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body panel-padding">
                            <div class="chart_revenue">
                                <canvas class="areaChart" style="height: 496px; width: 992px;" height="496" width="992"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- section 2 -->
        <section class="section section-three">
          <div class="row">
            <div class="col-md-12">
              <div class="box3">
                <div class="header-box">
                  <p class="title">Thống kê đơn hàng</p>
                </div>
                <div class="body-box">
                  <div class="table table-sliiped">
                    <div class="header-table">
                      <div class="row">
                        <div class="col-md-5">
                          <div class="col-md-1"><span>#</span></div>
                          <div class="col-md-11"><span>Trạng thái</span></div>
                        </div>
                        <div class="col-md-7">
                          <div class="col-md-2"><p>Hôm nay</p></div>
                          <div class="col-md-2"><p>Trong tuần</p></div>
                          <div class="col-md-3"><p>Trong tháng</p></div>
                          <div class="col-md-2"><p>Trong năm</p></div>
                          <div class="col-md-3"><p>Năm Trước</p></div>
                        </div>
                      </div>
                    </div>
                    <div class="content collapse in">
                    <div class="scrollbar">
                        <ul class="body-table">
@foreach ($order_status as $index => $item)
                            <li class="row">
                              <div class="col-md-5">
                                <div class="col-md-1"><span class="id">{{$index+1}}</span></div>
                                <div class="col-md-11"><p class="status">{{$item['order_status_name']}}</p></div>
                              </div>
                              <div class="col-md-7">
                                <div class="col-md-2">
                                    <a href="<?=route('order.index')?>?from_date=<?=$now->format($format_date)?>&to_date=<?=$now->format($format_date)?>&status[]=<?=$item['id']?>">
                                        <span class="number">{{isset($day[$item['id']]) ? $day[$item['id']]['total']:0}}</span>
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    $date = \Carbon\Carbon::now();
                                    ?>
                                    <a href="<?=route('order.index')?>?from_date=<?=$date->startOfWeek()->format($format_date)?>&to_date=<?=$now->format($format_date)?>&status[]=<?=$item['id']?>">
                                        <span class="number">{{isset($week[$item['id']]) ? $week[$item['id']]['total']:0}}</span>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?=route('order.index')?>?from_date=<?=$date->startOfMonth()->format($format_date)?>&to_date=<?=$now->format($format_date)?>&status[]=<?=$item['id']?>">
                                    <span class="number">{{isset($month[$item['id']]) ? $year[$item['id']]['total']:0}}</span>
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?=route('order.index')?>?from_date=<?=$date->startOfYear()->format($format_date)?>&to_date=<?=$now->format($format_date)?>&status[]=<?=$item['id']?>">
                                    <span class="number">{{isset($year[$item['id']]) ? $year[$item['id']]['total']:0}}</span>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <?php
                                    $date = \Carbon\Carbon::now()->addYear(-1);
                                    ?>
                                    <a href="<?=route('order.index')?>?from_date=<?=$date->startOfYear()->format($format_date)?>&to_date=<?=$date->endOfYear()->format($format_date)?>&status[]=<?=$item['id']?>">
                                    <span class="number">
                                        {{isset($lastyear[$item['id']]) ? $lastyear[$item['id']]['total']:0}}
                                    </span>
                                    </a>
                                </div>
                              </div>
                            </li>
@endforeach
                        </ul>
                    </div>
                    </div>
                    <ul class="footer">
                      <li class="row">
                        <div class="col-md-5">
                          <div class="merge col-md-12"><p class="total">Tổng đơn hàng</p></div>
                        </div>
                        <div class="col-md-7">
                          <div class="col-md-2">
                              <a href="<?=route('order.index')?>?from_date=<?=$now->format($format_date)?>&to_date=<?=$now->format($format_date)?>">
                              <span class="number">{{array_sum( array_column($day, 'total') )}}</span>
                              </a>
                          </div>
                          <div class="col-md-2">
                              <?php
                              $date = \Carbon\Carbon::now();
                              ?>
                              <a href="<?=route('order.index')?>?from_date=<?=$date->startOfWeek()->format($format_date)?>&to_date=<?=$now->format($format_date)?>">
                              <span class="number">{{array_sum( array_column($week, 'total') )}}</span>
                              </a>
                          </div>
                          <div class="col-md-3">
                              <a href="<?=route('order.index')?>?from_date=<?=$date->startOfMonth()->format($format_date)?>&to_date=<?=$now->format($format_date)?>">
                                  <span class="number">{{array_sum( array_column($month, 'total') )}}</span></a>
                          </div>
                          <div class="col-md-2">
                              <a href="<?=route('order.index')?>?from_date=<?=$date->startOfYear()->format($format_date)?>&to_date=<?=$now->format($format_date)?>">
                                  <span class="number">{{array_sum( array_column($year, 'total') )}}</span></a>
                          </div>
                          <div class="col-md-3">
                              <?php
                              $date = \Carbon\Carbon::now()->addYear(-1);
                              ?>
                              <a href="<?=route('order.index')?>?from_date=<?=$date->startOfYear()->format($format_date)?>&to_date=<?=$date->endOfYear()->format($format_date)?>">
                                  <span class="number">{{array_sum( array_column($lastyear, 'total') )}}</span></a>
                          </div>
                        </div>
                      </li>
                      <li class=" row">
                        <div class="col-md-5">
                          <div class="merge col-md-12"><p class="total">Tổng doanh thu</p></div>
                        </div>
                        <div class="col-md-7">
                            <div class="col-md-2"><span class="number">{{array_sum( array_column($day, 'sales') )}}</span></div>
                            <div class="col-md-2"><span class="number">{{array_sum( array_column($week, 'sales') )}}</span></div>
                            <div class="col-md-3"><span class="number">{{array_sum( array_column($month, 'sales') )}}</span></div>
                            <div class="col-md-2"><span class="number">{{array_sum( array_column($year, 'sales') )}}</span></div>
                            <div class="col-md-3"><span class="number">{{array_sum( array_column($lastyear, 'sales') )}}</span></div>
                        </div>
                      </li>
                      <li class="row">
                        <div class="col-md-5">
                          <div class="merge col-md-12"><p class="total">Đã thu</p></div>
                        </div>
                        <div class="col-md-7">
                            <div class="col-md-2"><span class="number">{{array_sum( array_column($day, 'revenue') )}}</span></div>
                            <div class="col-md-2"><span class="number">{{array_sum( array_column($week, 'revenue') )}}</span></div>
                            <div class="col-md-3"><span class="number">{{array_sum( array_column($month, 'revenue') )}}</span></div>
                            <div class="col-md-2"><span class="number">{{array_sum( array_column($year, 'revenue') )}}</span></div>
                            <div class="col-md-3"><span class="number">{{array_sum( array_column($lastyear, 'revenue') )}}</span></div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <!-- end table -->
                </div>  
              </div>
            </div>        
          </div>
        </section>
        <!-- section3 -->
        <section class="section section-four">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="box2 box">
                            <div class="header-box">
                                <p class="title">Thành viên</p>
                                <a href="#" class="view-all">Xem tất cả</a>
                            </div>
                            <div class="body-box">
                                <?php
                                $tmp = $admin_count + $user_count;
                                ?>
                                <p><span class="count_number"><fm-number>{{$tmp}}</fm-number></span> <span>Thành viên</span></p>
                                <div class="pad">
                                    <!-- Progress bars -->
                                    <div class="clearfix">
                                        <span class="pull-left">Quản trị viên</span>
                                        <small class="pull-right"><fm-number>{{$admin_count}}</fm-number></small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-red" style="width: {{$admin_count/$tmp*100}}%;"></div>
                                    </div>

                                    <div class="clearfix">
                                        <span class="pull-left">Khách hàng</span>
                                        <small class="pull-right"><fm-number>{{$user_count}}</fm-number></small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: {{$user_count/$tmp*100}}%;"></div>
                                    </div>
                                </div><!-- /.pad -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box4 box">
                          <div class="header-box">
                            <p class="title">Yêu cầu hỗ trợ mới nhất</p>
                            <a href="#" class="view-all">Xem tất cả</a>
                          </div>
                          <div class="body-box">
                            <div class="content collapse in">
                              <div class="scrollbar">
                                <ul class="list-requier">

@foreach ($support_requests as $item)

                                  <li class="item-requier">
                                    <div class="account">
                                      <span class="name">{{$item['fullname']}}</span>
                                      <p class="time">{{$item['date_created']}}</p>
                                    </div>
                                    <a href="#" type="button" class="detail">#{{$item['order_code']}}</a>
                                    <p class="sub_content">{{$item['request']}}</p>
                                  </li>
@endforeach

                                </ul>
                              </div>
                            </div>
                          </div>    
                        </div>            
                    </div>
                    <div class="col-md-4">
                        <div class="box5 box">
                          <div class="header-box">
                            <p class="title">Nhận xét mới nhất</p>
                            <a href="<?=route('product-comments.index')?>" class="view-all">Xem tất cả</a>
                          </div>
                          <div class="body-box">
                            <div class="content collapse in">
                              <div class="scrollbar">
                                <ul class="list-commend">

@foreach ($product_comments as $item)
                                  <li class="item-commend">
                                    <div class="col-md-2">
                                      <div class="wrap-img">
                                        <img src="{!! $item['avatar']?$item['avatar']:'/html/assets/images/no-account.png' !!}" alt="">
                                      </div>
                                      <!-- <div class="rate-star">
                                        <span class="starRating">
                                          <input id="rating5" type="radio" name="rating" value="5">
                                          <label for="rating5">5</label>
                                          <input id="rating4" type="radio" name="rating" value="4" checked>
                                          <label for="rating4">4</label>
                                          <input id="rating3" type="radio" name="rating" value="3" >
                                          <label for="rating3">3</label>
                                          <input id="rating2" type="radio" name="rating" value="2" >
                                          <label for="rating2">2</label>
                                          <input id="rating1" type="radio" name="rating" value="1">
                                          <label for="rating1">1</label>
                                        </span>
                                      </div> -->
                                    </div>
                                    <div class="col-md-10">
                                      <p class="name">{{$item['fullname'] ? $item['fullname'] : $item['fullname_visitor']}} <span class="time">{{$item['date_created']}}</span></p>
                                      <a href="#" class="product">{{$item['product_name']}}</a>
                                      <div class="wrap-commend">
                                         <p class="commend-detail">{{$item['comment']}}</p>
                                        <div class="comment-simplebox-arrow"><div class="arrow-inner"></div><div class="arrow-outer"></div></div>
                                      </div>
                                    </div>
                                  </li>
@endforeach
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        </section>
        <!-- section 4 -->
        <section class="section section-five">
          <div class="row">
           <h3 class="chart-title">Thống kê sản phẩm</h3>
            <div class="col-md-6" style="padding-left: 0;">
              <div class="box-category">
                <p class="title">Thống kê danh mục sản phẩm</p>
                <div class="total">
                    <?php
                    $chart_categories = [];
                    $tmp = 0;
                    foreach ($categories as $key => $value) {
                        $chart_categories[] = ["country" => $categories_status[$key], "litres" => $value];
                        $tmp += $value;
                    }
                    ?>
                  <p>Tổng cộng: <span class="number">{{$tmp}}</span></p>
                </div>
                <div id="chartdiv_category"></div>
              </div>
            </div>
            <div class="col-md-6" style="padding-right: 0;">
              <div class="box-product">
                <p class="title">Thống kê sản phẩm hiện có</p>
                <div class="total">
                    <?php
                    $chart_products = [];
                    $tmp = 0;
                    foreach ($products as $key => $value) {
                        $chart_products[] = ["country" => $products_status[$key], "litres" => $value];
                        $tmp += $value;
                    }
                    ?>

                  <p>Tổng cộng: <span class="number">{{$tmp}}</span></p>
                </div>
                <div id="chartdiv_product"></div>
              </div>
            </div>
          </div>
        </section>
    </div>
@endsection

@section('after_styles')
    <style>
    .footer a {
        color: white;
    }
    </style>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            init_chart_category(<?=json_encode($chart_categories)?>);
            init_chart_product("chartdiv_product", <?=json_encode($chart_products)?>);
            init_select_time_home('#select_time_home', '.show_time');
            $('#select_time_home').trigger('change');
        });
    </script>
@endsection