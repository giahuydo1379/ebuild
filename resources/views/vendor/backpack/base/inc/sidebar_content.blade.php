<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa fa-gears"></i> <span>Dịch vụ</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="<?=route('crud.service.index')?>"><i class="fa fa-gears"></i> <span>Dịch vụ</span></a></li>
        <li><a href="<?=route('crud.services-extra.index')?>"><i class="fa fa-calendar-plus-o"></i> <span>Dịch vụ cộng thêm</span></a></li>
        <li><a href="<?=route('crud.freezer.index')?>"><i class="fa fa-list-ol"></i> <span>Loại máy lạnh</span></a></li>
        <li><a href="<?=route('crud.freezer-capacity.index')?>"><i class="fa fa-list-ol"></i> <span>Công suất máy lạnh</span></a></li>
        <li><a href="<?=route('crud.services-units.index')?>"><i class="fa fa-list-ol"></i> <span>Đơn vị dịch vụ</span></a></li>
        <li><a href="<?=route('crud.services-freezer-units.index')?>"><i class="fa fa-list-ol"></i> <span>Giá dịch vụ vệ sinh máy lạnh</span></a></li>
        <li><a href="<?=route('crud.booking.index')?>"><i class="fa fa-list-ol"></i> <span>Booking</span></a></li>
    </ul>
</li>
<li class="treeview">
    <a href="#"><i class="fa fa-newspaper-o"></i> <span>Tin tức</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('article') }}"><i class="fa fa-newspaper-o"></i> <span>Bài viết</span></a></li>
        <li><a href="{{ backpack_url('category') }}"><i class="fa fa-list"></i> <span>Danh mục</span></a></li>
        <li><a href="{{ backpack_url('tag') }}"><i class="fa fa-tag"></i> <span>Thẻ</span></a></li>
    </ul>
</li>
<li class="treeview">
    <a href="#"><i class="fa fa-cog"></i> <span>Cài đặt</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{backpack_url('page') }}"><i class="fa fa-file-o"></i> <span>Trang tĩnh</span></a></li>
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/menu-item') }}"><i class="fa fa-list"></i> <span>Menu</span></a></li>
        <li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>Quản lý tập tin</span></a></li>
        <li><a href="{{  backpack_url('language') }}"><i class="fa fa-flag-o"></i> <span>Ngôn ngữ</span></a></li>
        <li><a href="{{ backpack_url( 'language/texts') }}"><i class="fa fa-language"></i> <span>Tập tin ngôn ngũ</span></a></li>
        <li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/backup') }}'><i class='fa fa-hdd-o'></i> <span>Sao lưu</span></a></li>
        <li><a href="{{ backpack_url('log') }}"><i class="fa fa-terminal"></i> <span>Logs</span></a></li>
        <li><a href='{{ url(config('backpack.base.route_prefix', 'admin') . '/setting') }}'><i class='fa fa-cog'></i> <span>Cài đặt</span></a></li>
    </ul>
</li>