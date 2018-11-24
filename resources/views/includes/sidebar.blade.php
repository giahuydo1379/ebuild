<?php
$user = \App\Helpers\Auth::getUserInfo();
$permissions = \App\Helpers\Auth::get_permissions($user);
$ac = \App\Helpers\General::get_controller_action();
?>
<aside class=" main-sidebar" style="margin-right: 50px">
    <div class="menu" data-spy="affix" data-offset-top="50" >
        <div class="content">
            <div class="scrollbar">
                <ul class="panel-group list-menu" id="accordion">
                <?php
                    $hp = \App\Helpers\Auth::has_permission('home.index', $user, $permissions)
//                        || \App\Helpers\Auth::has_permission('crud.project-type.index', $user, $permissions)
//                        || \App\Helpers\Auth::has_permission('crud.project-status.index', $user, $permissions)
                    ;

                    if ($hp) {
                    $active = $ac['controller']=='HomeController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel <?=$active?>">
                        <a href="<?=route('home.index')?>">
                            <i class="icon icon-home">&nbsp</i>
                            <span>Quản lý trang chủ</span>
                        </a>
                    </li>
                <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission(['order.index', 'order-status.index'], $user, $permissions);

                    if ($hp) {
                    $active = $ac['controller']=='OrderController'
                        || $ac['controller']=='OrderStatusController'
                            ? 'active-menu' : '';
                    ?>
                        <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_orders">
                            <i class="icon icon-donhang">&nbsp</i>
                            <span>Đơn hàng</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse_orders" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $hp = \App\Helpers\Auth::has_permission('order.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='OrderController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('order.index')?>">
                                        <p>Xem đơn hàng</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('order-status.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='OrderStatusController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('order-status.index') }}">
                                        <p>Trạng thái đơn hàng</p>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission(['products.index', 'product-service.index',
                        'categories.index', 'brands.index', 'supplier.index', 'warehouse.index', 'surcharge.index',
                        'units.index', 'filters.index', 'imports.index', 'exports.index'],
                        $user, $permissions);

                    if ($hp) {
                    $active = $ac['controller']=='CategoriesController'
                    || $ac['controller']=='BrandController'
                    || $ac['controller']=='FilterController'
                    || $ac['controller']=='ProductController'
                    || $ac['controller']=='ImportController'
                    || $ac['controller']=='ExportController'
					|| $ac['controller']=='SupplierController'
                    || $ac['controller']=='WarehouseController'
                    || $ac['controller']=='UnitsController'
					|| $ac['controller']=='ProductServiceController'
                    || $ac['controller']=='SurchargeController'                    
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapsep">
                            <i class="icon icon-khovan">&nbsp</i>
                            <span>Sản phẩm</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapsep" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $hp = \App\Helpers\Auth::has_permission('products.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='ProductController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('products.index')?>">
                                        <p>Sản phẩm</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('product-service.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='ProductServiceController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('product-service.index')?>">
                                        <p>Dịch vụ</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('categories.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='CategoriesController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('categories.index') }}">
                                        <p>Category</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('brands.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='BrandController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('brands.index')?>">
                                        <p>Thương hiệu</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('supplier.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='SupplierController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('supplier.index')?>">
                                        <p>Nhà cung cấp</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('warehouse.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='WarehouseController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('warehouse.index')?>">
                                        <p>Nhà kho</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('surcharge.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='SurchargeController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('surcharge.index')?>">
                                        <p>Phụ phí</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('units.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='UnitsController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('units.index')?>">
                                        <p>Đơn vị sản phẩm</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('filters.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='FilterController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('filters.index')?>">
                                        <p>Hệ thống Filter</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('imports.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='ImportController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('imports.index')?>">
                                        <p>Tool import</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('exports.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='ExportController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="<?=route('exports.index')?>">
                                        <p>Tool export</p>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission(['banners.index', 'banners.about'], $user, $permissions);

                    if ($hp) {
                    $active = $ac['namespace']=="App\Http\Controllers" && ( $ac['controller']=='BannerController'
                        ) ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse8">
                            <i class="icon icon-ad">&nbsp</i>
                            <span>Quản lý quảng cáo</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse8" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $hp = \App\Helpers\Auth::has_permission('banners.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $active && $ac['controller']=='BannerController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('banners.index') }}">
                                        <p>Banner</p>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('banners.about', $user, $permissions);
                                if ($hp) {
                                $sub_active = $active && $ac['controller']=='BannerController' && $ac['action']=='about' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('banners.about')}}">
                                        <p>Hình ảnh</p>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission(['promotions.index', 'microsites.index',
                        'coupons.index', 'sale-hot.index'], $user, $permissions);

                    if ($hp) {
                    $active = $ac['controller']=='PromotionController'
                    || $ac['controller']=='MicrositeController' || $ac['controller']=='CouponsController'
                    || $ac['controller']=='SaleHotController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse16">
                            <i class="icon icon-ad">&nbsp</i>
                            <span>Quản lý khuyến mãi</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse16" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $hp = \App\Helpers\Auth::has_permission('promotions.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='PromotionController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('promotions.index')}}">
                                        <p>Tặng quà</p>
                                    </a>
                                </li>
                                    <?php } ?>

                                    <?php
                                    $hp = \App\Helpers\Auth::has_permission('microsites.index', $user, $permissions);
                                    if ($hp) {
                                    $sub_active = $ac['controller']=='MicrositeController' ? 'active' : '';
                                    ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('microsites.index')}}">
                                        <p>Promotion landing page</p>
                                    </a>
                                </li>
                                    <?php } ?>

                                    <?php
                                    $hp = \App\Helpers\Auth::has_permission('coupons.index', $user, $permissions);
                                    if ($hp) {
                                $sub_active = $ac['controller']=='CouponsController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('coupons.index')}}">
                                        <p>Coupons</p>
                                    </a>
                                </li>
                                    <?php } ?>

                                    <?php
                                    $hp = \App\Helpers\Auth::has_permission('sale-hot.index', $user, $permissions);
                                    if ($hp) {
                                $sub_active = $ac['controller']=='SaleHotController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('sale-hot.index')}}">
                                        <p>Khuyến mãi hot</p>
                                    </a>
                                </li>
                                    <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission(['chain-store.index', 'static-pages.index'], $user, $permissions);

                    if ($hp) {
                    $active = $ac['namespace']=="App\Http\Controllers" && ($ac['controller']=='ChainStoreController'
                    || $ac['controller']=='IntroductionController'
                    || $ac['controller']=='AmortizationController'
                    || $ac['controller']=='SaleB2BController'
                        ) ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">
                            <i class="icon icon-web">&nbsp</i>
                            <span>Về {{config('app.name')}}</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse7" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $hp = \App\Helpers\Auth::has_permission('chain-store.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $active && $ac['controller']=='ChainStoreController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/chain-store') }}">
                                        <p>Hệ thống siêu thị</p>
                                    </a>
                                </li>
                                <?php } ?>

                                    <?php
                                    $hp = \App\Helpers\Auth::has_permission('static-pages.index', $user, $permissions);
                                    if ($hp) {
                                    $static_pages = \App\Helpers\General::getStaticPages();
                                    $slug = $_GET['slug']??'';
                                    ?>
                                @if (isset($static_pages['about']))
                                    @foreach($static_pages['about'] as $item)
                                        <?php
                                        $sub_active = $ac['controller']=='StaticPagesController'
                                        && $ac['action']=='index'
                                        && $slug == $item['slug'] ? 'active' : '';
                                        ?>
                                        <li class="item-sub {{$sub_active}}">
                                            <a href="{{ route('static-pages.index', ['slug' => $item['slug']]) }}">
                                                <p><?=$item['page_name']?></p>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                                    <?php } ?>
                            </ul>
                      </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission(['service-center.index'], $user, $permissions);
                    if ($hp) {
                        $static_pages = \App\Helpers\General::getStaticPages();

                        $active = $ac['namespace']=="App\Http\Controllers" && ($ac['controller']=='ServiceCenterController')
                            ? 'active-menu' : '';
                        ?>
                        <li class="item-menu panel {{$active}}">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
                                <i class="icon icon-admin">&nbsp</i>
                                <span>Hỗ trợ khách hàng</span>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <div id="collapse6" class="panel-collapse collapse <?=$active?'in':''?>">
                                <ul class="sub-menu" >
                                    @if (isset($static_pages['support']))
                                        @foreach($static_pages['support'] as $item)
                                            <?php
                                            $sub_active = $ac['controller']=='StaticPagesController'
                                            && $ac['action']=='index'
                                            && $slug == $item['slug'] ? 'active' : '';
                                            ?>
                                            <li class="item-sub {{$sub_active}}">
                                                <a href="{{ route('static-pages.index', ['slug' => $item['slug']]) }}">
                                                    <p><?=$item['page_name']?></p>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('news.index', $user, $permissions);

                    if ($hp) {
                        $active = $ac['controller']=='NewsCategoriesController'
                        || $ac['controller']=='NewController'
                            ? 'active-menu' : '';
                        ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse9">
                            <i class="icon icon-news">&nbsp</i>
                            <span>Tin tức</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse9" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='NewsCategoriesController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/news-categories') }}">
                                        <p>Danh mục tin tức</p>
                                    </a>
                                </li>
                                <?php
                                $sub_active = $ac['controller']=='NewController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/news') }}">
                                        <p>Tin tức</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('job-opening.index', $user, $permissions);

                    if ($hp) {
                    $active = $ac['controller']=='JobCategoryController'
                    || $ac['controller']=='JobOpeningController'
                    || $ac['route_name']=='job-opening.apply-cv'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse10">
                            <i class="icon icon-jobs">&nbsp</i>
                            <span>Tuyển dụng</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse10" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='JobCategoryController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('job-categories.index')}}">
                                        <p>Danh mục tuyển dụng</p>
                                    </a>
                                </li>
                                    <?php
                                    $sub_active = $ac['controller']=='JobOpeningController' && $ac['action']=='index' ? 'active' : '';
                                    ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('job-opening.index')}}">
                                        <p>Vị trí tuyển dụng</p>
                                    </a>
                                </li>
                                    <?php
                                    $sub_active = $ac['route_name']=='job-opening.apply-cv' ? 'active' : '';
                                    ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{route('job-opening.apply-cv')}}">
                                        <p>CV ứng tuyển</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('admin-users.index', $user, $permissions);

                    if ($hp) {
                    $active = $ac['controller']=='AdminUsersController'
                        || $ac['controller']=='RolesController'
                        || $ac['controller']=='PermissionsController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse11">
                            <i class="icon icon-admin">&nbsp</i>
                            <span>Người dùng CMS</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse11" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='AdminUsersController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('admin-users.index') }}">
                                        <p>Danh sách</p>
                                    </a>
                                </li>

                                <?php
                                $hp = \App\Helpers\Auth::has_permission('roles.index', $user, $permissions);
                                if ($hp) {
                                    $sub_active = $ac['controller']=='RolesController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('roles.index') }}">
                                        <span>Phân quyền</span></a></li>
                                <?php } ?>

                                <?php
                                if (\App\Helpers\Auth::is_admin($user)) {
                                    $sub_active = $ac['controller']=='PermissionsController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('permissions.index') }}">
                                        <span>Chức năng</span></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission(['setting.index', 'menu-item.index'], $user, $permissions);

                    if ($hp) {

                    $active = $ac['controller']=='SettingController'
                    || $ac['controller']=='MenuItemController'
                    || $ac['controller']=='EmailConfigController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_setting">
                            <i class="icon icon-admin">&nbsp</i>
                            <span>Cài đặt</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse_setting" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $hp = \App\Helpers\Auth::has_permission('setting.index', $user, $permissions);
                                if ($hp) {
                                $sub_active = $ac['controller']=='SettingController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('setting.index') }}">
                                        <p>Cấu hình</p>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php
                                    $hp = \App\Helpers\Auth::has_permission('menu-item.index', $user, $permissions);
                                    if ($hp) {
                                $sub_active = $ac['controller']=='MenuItemController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('menu-item.index') }}">
                                        <p>Menu</p>
                                    </a>
                                </li>
                                    <?php } ?>
                                <?php
                                    $hp = \App\Helpers\Auth::has_permission('menu-item.index', $user, $permissions);
                                    if ($hp) {
                                $sub_active = $ac['controller']=='EmailConfigController' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('supplier-email.index') }}">
                                        <p>Cấu hình email</p>
                                    </a>
                                </li>
                                    <?php } ?>    
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('contact.index', $user, $permissions);

                    if ($hp) {
                    $active = $ac['namespace']=="App\Http\Controllers" && ( $ac['controller']=='ContactController' )
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse20">
                            <i class="icon icon-admin">&nbsp</i>
                            <span>Liên hệ</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse20" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='ContactController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('contact.index') }}">
                                        <p>Danh sách</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('advice.index', $user, $permissions);

                    if ($hp) {
                    $active = $ac['controller']=='AdviceCategoriesController'
                    || $ac['controller']=='AdviceController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse21">
                            <i class="icon icon-news">&nbsp</i>
                            <span>Tư vấn sản phẩm</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse21" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='AdviceCategoriesController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/advice-categories') }}">
                                        <p>Danh mục tư vấn</p>
                                    </a>
                                </li>
                                <?php
                                $sub_active = $ac['controller']=='AdviceController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/advices') }}">
                                        <p>Bài viết tư vấn</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>


                    <?php
                    $hp = \App\Helpers\Auth::has_permission('report.product', $user, $permissions);
                    if ($hp) {
                    $active = $ac['controller']=='ReportController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse22">
                            <i class="icon icon-news">&nbsp</i>
                            <span>Báo cáo</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse22" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='ReportController' && ($ac['action']=='product' || $ac['action']=='category' || $ac['action']=='coupon') ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('report.product') }}">
                                        <p>Báo cáo</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('users.index', $user, $permissions);
                    if ($hp) {
                    $active = $ac['controller']=='UsersController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse12">
                            <i class="icon icon-admin">&nbsp</i>
                            <span>Khách hàng</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse12" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='UsersController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('users.index') }}">
                                        <p>Danh sách</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('product-comments.index', $user, $permissions);
                    if ($hp) {
                    $active = $ac['controller']=='ProductCommentsController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse13">
                            <i class="icon icon-admin">&nbsp</i>
                            <span>Bình luận sản phẩm</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse13" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='ProductCommentsController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('product-comments.index') }}">
                                        <p>Danh sách</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('support-requests.index', $user, $permissions);
                    if ($hp) {
                    $active = $ac['controller']=='SupportRequestsController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse14">
                            <i class="icon icon-admin">&nbsp</i>
                            <span>Yêu cầu hỗ trợ</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse14" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='SupportRequestsController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('support-requests.index') }}">
                                        <p>Danh sách</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>

                    <?php
                    $hp = \App\Helpers\Auth::has_permission('faqs.index', $user, $permissions);
                    if ($hp) {
                    $active = $ac['controller']=='FaqsController'
                        ? 'active-menu' : '';
                    ?>
                    <li class="item-menu panel {{$active}}">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse18">
                            <i class="icon icon-admin">&nbsp</i>
                            <span>Câu hỏi thường gặp</span>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </a>
                        <div id="collapse18" class="panel-collapse collapse <?=$active?'in':''?>">
                            <ul class="sub-menu" >
                                <?php
                                $sub_active = $ac['controller']=='FaqsController' && $ac['action']=='index' ? 'active' : '';
                                ?>
                                <li class="item-sub {{$sub_active}}">
                                    <a href="{{ route('faqs.index') }}">
                                        <p>Danh sách</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</aside>