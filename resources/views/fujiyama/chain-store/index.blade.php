@extends('layouts.master')

@section('content')
<div class="col-md-">
    <section class="section section-banner cs-banner">
        <h3 class="title-section">hệ thống siêu thị</h3>
        <div class="panel box-panel">
            <?php
            $cs_banner = !empty($chain_store_banner) ? $chain_store_banner['value'] : '';
            $cs_link = !empty($chain_store_banner) ? $chain_store_banner['field'] : '';
            ?>
            <div class="top">
                <h3 class="title">Banner</h3>
                @if($cs_banner)
                <a class="pull-right update-action update-banner">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    <span>Cập nhật</span>
                </a>
                @endif
            </div>

            @if($cs_banner)
                <div class="banner banner-display">
                    <img id="banner-display" src="{{ $cs_banner }}" alt="">
                </div>
            @else
                <div class="no-banner">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-banner"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
            @endif
            <div class="banner-update">
                <form id="form_banner_update" action="{{ url("/{$controllerName}/chain-store-setting-update") }}">
                    {{ csrf_field() }}
                    <div class="col-md-9">
                        <img id="preview-file-upload" src="{{ $cs_banner }}" alt="">
                    </div>
                    <div class="col-md-3">
                        <ul class="wrap_btn">
                            <li>
                                <a href="#" class="btn-loadfile browse-image" data-target="image_url">
                                    <i class="icon-browser">&nbsp;</i>
                                    <span>Browse ...</span>
                                </a>
                            </li>
                        </ul>
                        <div class="show_file">
                            <span class="file_name"></span>
                        </div>
                        <span class="size-note">Kích thước 900 x 300 px</span>
                        <input type="hidden" name="value" id="image_url" data-preview="#preview-file-upload" class="form-control" value="{{ $cs_banner }}">
                        <label>Link banner</label>
                        <input type="text" name="field" class="form-control" placeholder="Nhập link banner" value="{{ $cs_link }}">
                        <div class="action pull-right">
                            <span id="btn-cancel-update-banner" class="cancel">Hủy bỏ</span>
                            <input type="hidden" name="key" value="fujiyama_chain_store_banner">
                            <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="section section-infor">
        <div class="panel box-panel">
            <div class="top">
                <h3 class="title">Thông tin siêu thị</h3>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-left cs-opening">
                        <?php
                        $opening = isset($chain_stores['opening']) && $chain_stores['opening'] ? $chain_stores['opening'] : [];
                        ?>
                        <div class="header-box">
                            <h4>Siêu thị sắp khai trương</h4>
                            @if ($opening)
                            <a class="pull-right add-action add-opening">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                <span>Thêm mới</span>
                            </a>
                            @endif
                        </div>
                        <div class="body-box display-list" @if (!$opening) style="display: none;" @endif>
                            <div class="scrollbar">
                                <ul class="list-super">
                                @foreach($opening as $item)
                                    <li>
                                        <strong>{{$item['name']}}</strong>
                                        <p>Địa chỉ: {{$item['name']}}, {{$item['ward_name']}}, {{$item['district_name']}}, {{$item['province_name']}} </p>
                                        <div class="pull-right">
                                            <a class="tooltip action-delete" data-id="{{$item['id']}}">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                <span class="tooltiptext">Xóa</span>
                                            </a>
                                            <a class="tooltip action-update-opening" data-id="{{$item['id']}}">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                <span class="tooltiptext">Cập nhật</span>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                                </ul>
                            </div>
                        </div>
                        @if (!$opening)
                        <div class="no-banner add-opening-none">
                            <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                            <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                        </div>
                        @endif
                        <div class="body-box update-list">
                            <form id="form_cs_opening" action="{{ route("chain-store.add") }}">
                                <div class="row form-group">
                                    <label>Tên siêu thị</label>
                                    <input type="text" name="name" class="form-control name" placeholder="Nhập tên siêu thị">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tỉnh/ Thành phố</label>
                                            <div class="wrap_select">
                                                <select name="province_id" class="form-control provinces" onchange="get_districts_by_province(this)" data-destination="#form_cs_opening .districts">
                                                    <option value="">Chọn Tỉnh / Thành phố</option>
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quận/ Huyện</label>
                                            <div class="wrap_select">
                                                <select name="district_id" class="form-control districts" onchange="get_wards_by_district(this)" data-destination="#form_cs_opening .wards">
                                                    <option value="">Chọn Quận / Huyện</option>
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phường/ Xã</label>
                                            <div class="wrap_select">
                                                <select name="ward_id" class="form-control wards">
                                                    <option value="">Chọn Phường / Xã</option>
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <input type="text" name="address" class="form-control address" placeholder="Nhập địa chỉ">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Mã nhúng bản đồ</label>
                                    <input type="text" name="embed_map" class="form-control embed_map" placeholder="Nhập mã nhúng bản đồ">
                                </div>
                                <div class="row action text-right">
                                    <span class="cancel">Hủy bỏ</span>
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <input type="hidden" name="status" value="opening">
                                    <input type="hidden" name="id" id="opening_id" value="">
                                    <input type="hidden" name="brand_id" value="<?=env('FUJIYAMA_BRAND_ID')?>">
                                    <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-right cs-active">
                        <?php
                        $active = isset($chain_stores['active']) && $chain_stores['active'] ? $chain_stores['active'] : [];

                        $group_active = [];
                        foreach ($active as $item) {
                            if (isset($group_active[$item['province_id']])) {
                                $group_active[$item['province_id']]['list'][] = $item;
                            } else {
                                $group_active[$item['province_id']] = [
                                    'list' => [$item],
                                    'name' => $item['province_name'],
                                ];
                            }
                        }
                        ?>
                        <div class="header-box">
                            <h4>Siêu thị hiện có/ Đang hoạt động</h4>
                            @if ($active)
                            <a class="pull-right add-action add-active">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                <span>Thêm mới</span>
                            </a>
                            @endif
                        </div>
                        <div class="body-box display-list" @if (!$active) style="display: none;" @endif>
                            <div class="scrollbar">
                                @foreach($group_active as $item_active)
                                <div class="city">
                                    <h4 class="title-city"><i class="fa fa-map-marker" aria-hidden="true"></i> <span>{{$item_active['name']}}</span></h4>
                                    <ul class="list-super">
                                    @foreach($item_active['list'] as $item)
                                        <li>
                                            <strong>{{$item['name']}}</strong>
                                            <p>Địa chỉ: {{$item['name']}}, {{$item['ward_name']}}, {{$item['district_name']}}, {{$item['province_name']}} </p>
                                            <div class="pull-right">
                                                <a class="tooltip action-delete" data-id="{{$item['id']}}">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Xóa</span>
                                                </a>
                                                <a class="tooltip action-update-active" data-id="{{$item['id']}}">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Cập nhật</span>
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="body-box update-list">
                            <form id="form_cs_active" action="{{ route("chain-store.add") }}">
                                <div class="row">
                                    <label>Tên siêu thị</label>
                                    <input type="text" name="name" class="form-control name" placeholder="Nhập tên siêu thị">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tỉnh/ Thành phố</label>
                                            <div class="wrap_select">
                                                <select name="province_id" class="form-control provinces" onchange="get_districts_by_province(this)" data-destination="#form_cs_active .districts">
                                                    <option value="">Chọn Tỉnh / Thành phố</option>
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quận/ Huyện</label>
                                            <div class="wrap_select">
                                                <select name="district_id" class="form-control districts" onchange="get_wards_by_district(this)" data-destination="#form_cs_active .wards">
                                                    <option value="">Chọn Quận / Huyện</option>
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phường/ Xã</label>
                                            <div class="wrap_select">
                                                <select class="form-control wards" name="ward_id">
                                                    <option value="">Chọn Phường / Xã</option>
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Địa chỉ</label>
                                            <input type="text" name="address" class="form-control address" placeholder="Nhập địa chỉ">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label>Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control phone" placeholder="Nhập số điện thoại liên hệ">
                                </div>
                                <div class="row">
                                    <label>Thời gian mở cửa</label>
                                    <input type="text" name="opening_time" class="form-control opening_time" placeholder="Nhập thời gian mở cửa">
                                </div>
                                <div class="row">
                                    <label>Mã nhúng bản đồ</label>
                                    <input type="text" name="embed_map" class="form-control embed_map" placeholder="Nhập mã nhúng bản đồ">
                                </div>
                                <div class="row action text-right">
                                    <span class="cancel">Hủy bỏ</span>
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <input type="hidden" name="status" value="active">
                                    <input type="hidden" name="id" id="active_id" value="">
                                    <input type="hidden" name="brand_id" value="<?=env('FUJIYAMA_BRAND_ID')?>">
                                    <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                                </div>
                            </form>
                        </div>
                        @if (!$active)
                        <div class="no-banner add-active-none">
                            <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                            <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row intro-supper cs-introduction">
                <div class="top">
                    <?php
                    $value = !empty($chain_store_introduction['value']) ? $chain_store_introduction['value'] : '';
                    ?>
                    <h3 class="title">Giới thiệu hệ thống siêu thị</h3>
                    @if($value)
                        <a id="chain_store_introduction_action" class="pull-right update-action update-introduction">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Cập nhật</span>
                        </a>
                    @endif
                </div>
                @if($value)
                    <div class="content content-display">
                        {!! $value !!}
                    </div>
                @else
                    <div class="no-banner">
                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                        <button class="btn add-introduction"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                    </div>
                @endif
                <div class="update-content">
                    <form id="form_introduction_update" action="{{ url("/{$controllerName}/chain-store-setting-update") }}">
                        {{ csrf_field() }}
                        <textarea class="value" id="value" name="value" placeholder="Nhập thông tin giới thiệu về hệ thống siêu thị" rows="5" cols="20">{{ $value }}</textarea>
                        <input type="hidden" name="key" value="fujiyama_chain_store_introduction">
                        <input type="hidden" name="field" value="">
                        <div class="row action text-right">
                            <span class="cancel">Hủy bỏ</span>
                            <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript" src="/js/chain-store.js"></script>
    <script type="text/javascript">
        var _opening = {!! json_encode($opening) !!};
        var _active = {!! json_encode($active) !!};
        $(document).ready(function() {
            $('.action-delete').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, siêu thị này!", function () {
                    ajax_loading(true);

                    $.post('{{route('chain-store.delete')}}', {
                        id: $(obj).attr('data-id'),
                        _token: '{!! csrf_token() !!}'
                    }, function(data){
                        ajax_loading(false);

                        alert_success(data.msg, function () {
                            if(data.rs == 1)
                            {
                                location.reload();
                            }
                        });
                    });
                });
            });
        });
    </script>
@endsection