<?php
$settings = \App\Helpers\General::get_settings();
?>
<header>
    <!-- Navigation -->
    <nav>
        <div class="navbar topnav">
            <div class="row">
                <div class="wrap-logo">
                    <a href="{{url('/')}}" class="logo">
                        <img src="<?=$settings['image_logo_cms']['value'] ?? '/html/assets/images/Logo.png'?>">
                    </a>
                    <div class="click-hamburger">
                        <i class="hamburger" data-toggle="offcanvas" role="button">&nbsp</i>
                    </div>
                </div>
                <div class="header">
                    <div class="name_page" style="display: none;">
                        <a href="#">
                            <i class="fa icon-toolaff" aria-hidden="true"></i>
                        </a>
                        <p class="title">Quản lý chung</p>
                    </div>
                    <div class="pull-right">
                        <form class="form-search" id="form-quick-search">
                            <span class="label-search">Tìm kiếm thông tin theo</span>
                            <select id="by-search" class="selectpicker" data-live-search="true" title="Mã đơn hàng">
                                <option value="order_code">Mã đơn hàng</option>
                                <option value="product_code">Mã sản phẩm</option>
                                <option value="order_name">Tên sản phẩm</option>
                            </select>
                            <input type="text" placeholder="Điền thông tin" class="form-control" id="input-search">
                            <button type="submit" class="btn btn-search"><span class="icon-search"></span></button>
                        </form>
                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                                <li class="block-list user-menu ">
                                    <div class="item-img">
                                        <img class="user-image" src="/html/assets/images/account-img.png"
                                             alt="thien hoa">
                                    </div>
                                    <div class="item-info dropdown control-menu ">
                                        <a href="javascript:void(0)" class="item-description dropdown-toggle" data-toggle="dropdown" title="<?=\Auth::user()->full_name?>">{{ \Auth::user()->full_name }}
                                            <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                        <ul class="dropdown-menu account-menu">
                                            <li class="notifi-item">
                                                <a href="/profile/info" class="user-infor">
                                                    <p>Thông tin User: <?=\Auth::user()->full_name?></p>
                                                </a>
                                            </li>
                                            <li class="notifi-item">
                                                <a href="/profile/change-password" class="change-password">
                                                    <p>Đổi mật khẩu</p>
                                                </a>
                                            </li>
                                            <li class="notifi-item">
                                                <a href="{{ route('logout') }}" class="logout">
                                                    <p>Thoát</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </nav>

</header>