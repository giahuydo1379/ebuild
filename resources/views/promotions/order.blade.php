@extends('layouts.master')

@section('content')
    <style>
        fm-number, display-name-brand {
            font-weight: bold;
            color: #00adef;
        }
    </style>
    <div class="col-md-">
        <section class="section section-promotion">
            <h3 class="title-section">Khuyến mãi quà tặng</h3>
            <div class="panel box-panel">
                <div class="top">
                    <h3 class="title TitleCreate">Thêm mới chương trình khuyến mãi</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật chương trình khuyến mãi</h3>
                    <h3 class="title TitleDisplay" style="display: none;">Danh sách khuyến mãi</h3>
                </div>
                <div class="promotion" >
                    <div class="tab">
                        <a href="{{route('promotions.index')}}" class="tablinks">Quà tặng cho sản phẩm</a>
                        <a type="button" class="tablinks active">Quà tặng cho đơn hàng</a>
                        <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                    </div>

                    <div id="home" class="tabcontent active" style="display: block;">
                        <?php
                        $_objects = [];
                        $product_ids = [];
                        $brand_ids = [];
                        ?>
                        @if ($objects['total'])
                            <div class="banner-promotion banner-display">
                                <div class="table-display">
                                    <div class="header_table">
                                        <div class="col-md-7">
                                            <div class="col-md-4 ">
                                                <span>Tên chương trình</span>
                                            </div>
                                            <div class="col-md-4">
                                                <span>Loại khuyến mãi</span>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <span>Thời gian áp dụng</span>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="col-md-4 no-padding">
                                                <span>Trạng thái</span>
                                            </div>
                                            <div class="col-md-4 no-padding text-center">
                                                <span>Dừng chương trình</span>
                                            </div>
                                            <div class="col-md-4 text-center">
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="promotion-gift panel-group" id="accordion3">
                                        @foreach($objects['data'] as $index => $item)
                                            <?php
                                            $item['from_date'] = \App\Helpers\General::output_date($item['from_date'], true);
                                            $item['to_date'] = \App\Helpers\General::output_date($item['to_date'], true);
                                            //$item['apply_objects'] = json_decode($item['apply_objects'], 1);
                                            $item['gift_products'] = json_decode($item['gift_products'], 1);

                                            if ($item['gift_products'] && is_array($item['gift_products'])) {
                                                $product_ids = array_merge($product_ids, $item['gift_products']);
                                            }

                                            $_objects[$item['id']] = $item;
                                            ?>
                                            <li class="row panel">
                                                <div class="col-md-7">
                                                    <div class="col-md-4 content">
                                                        <span>{{$item['name']}}</span>
                                                    </div>
                                                    <div class="col-md-4 content">
                                                        <span title="{{@$packages[$item['package_id']]['name']}}">{{$item['package_id'].'. '.@$packages[$item['package_id']]['name']}}</span>
                                                    </div>
                                                    <div class="col-md-4 text-center date-time">
                                                        <span>{{$item['from_date']}} <br> {{$item['to_date']}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="col-md-4">
                                                        <div class="wrapper tooltip">
                                                            <input type="checkbox" id="status-{{$index}}" class="slider-toggle" @if ($item['status']) checked @endif/>
                                                            <label class="slider-viewport" for="status-{{$index}}" onclick="return false;">
                                                                <div class="slider">
                                                                    <div class="slider-button">&nbsp;</div>
                                                                    <div class="slider-content left"><span>On</span></div>
                                                                    <div class="slider-content right"><span>Off</span></div>
                                                                </div>
                                                            </label>
                                                            <span class="tooltiptext">Chưa kích hoạt</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <button class="btn btn-stop">Dừng</button>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <a class="tooltip" data-toggle="collapse" data-parent="#accordion3" href="#collapsea{{$item['id']}}">
                                                            <i class="fa fa-eye" aria-hidden="true" ></i>
                                                            <span class="tooltiptext">Xem nhanh</span>
                                                        </a>
                                                        <a class="tooltip update-action-order" data-id="{{$item['id']}}">
                                                            <i class="icon-edit-pen active UpdateAction" aria-hidden="true" ></i>
                                                            <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                                            <span class="tooltiptext">Cập nhật</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="view_fast panel-collapse collapse" id="collapsea{{$item['id']}}">
                                                    <div class="wrap-view ViewFast p{{$item['id']}}">
                                                        <ul class="view-list">
                                                            <li><b>Tên chương trình: </b> <span class="color">{{$item['name']}}</span></li>
                                                            <li><b>Loại khuyến mãi: </b> <span>
                                                                    <?php
                                                                        if ($item['package_id']==15) {
                                                                            $tmp = str_replace('{X}', '<fm-number>'.$item['gift_products'].'</fm-number>', $packages[$item['package_id']]['name']);
                                                                            $tmp = str_replace('{Y}', '<fm-number>'.$item['apply_objects'].'</fm-number>', $tmp);
                                                                        } elseif ($item['package_id']==16) {
                                                                            $apply_objects = json_decode($item['apply_objects'], 1);
                                                                            $tmp = str_replace('{X}', '<fm-number>'.$item['gift_products'].'</fm-number>', $packages[$item['package_id']]['name']);
                                                                            $tmp = str_replace('{Z}', '<fm-number>'.$apply_objects['min-amount-order'].'</fm-number>', $tmp);
                                                                            $tmp = str_replace('{Y}', '<display-name-brand data-id="'.$apply_objects['brand_id'].'">'.$apply_objects['brand_id'].'</display-name-brand>', $tmp);
                                                                        } else {
                                                                            $tmp = str_replace('{X}', '<fm-number>'.$item['apply_objects'].'</fm-number>', $packages[$item['package_id']]['name']);
                                                                        }
                                                                    ?>{!! $tmp !!}</span></li>
                                                            <li><b>Thời gian áp dụng: </b><span>{{$item['from_date']}} ~ {{$item['to_date']}}</span></li>
                                                            <li style="display: none;"><b>Sản phẩm khuyến mãi: </b><span class="color list-pro"></span></li>
                                                            <li style="display: none;"><b>Sản phẩm quà tặng: </b> <span class="color list-giff"></span></li>
                                                            <li>
                                                                <b>Trạng thái: </b>
                                                                <div class="wrapper">
                                                                    <input type="checkbox" id="vstatus-{{$index}}" class="slider-toggle" @if ($item['status']) checked @endif/>
                                                                    <label class="slider-viewport" for="vstatus-{{$index}}" onclick="return false;">
                                                                        <div class="slider">
                                                                            <div class="slider-button">&nbsp;</div>
                                                                            <div class="slider-content left"><span>On</span></div>
                                                                            <div class="slider-content right"><span>Off</span></div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <div class="action text-right">
                                                            <span class="cancel Cancel">Hủy bỏ</span>
                                                            <button type="button" value="" class="btn btn_primary BtnView">Xem chi tiết</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @include('includes.paginator')
                                </div>
                            </div>
                        @else
                            <div class="no-banner">
                                <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                                <button class="btn add-action-none"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                            </div>
                        @endif

                        <div class="create-promotion banner-update">
                            <div class="row">
                                <div class="col-md-5 left-block">
                                    <ul class="list-radio">
                                        <?php
                                        $curr = null;
                                        ?>
                                    @foreach($packages as $id => $item)
                                        <li>
                                            <div class="radio <?=$curr?'':'active'?>">
                                                <input id="radio<?=$id?>" <?=$curr?'':'checked'?> value="{{$id}}" type="radio" class="package show-hide"
                                                       data-show=".Radio<?=$id?>" data-hide=".Radio" name="list-promotion">
                                                <label for="radio<?=$id?>">{{$item['name']}}</label>
                                            </div>
                                        </li>
                                                <?php
                                                if (!$curr) $curr = $id;
                                                ?>
                                    @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-7 right-block">
                                    <div class="display-radio DisplaRadio">
                                        @include('promotions.order.p11')
                                        <!-- end radio1 -->
                                        @include('promotions.order.p12')
                                        <!-- end radio 2 -->
                                        @include('promotions.order.p13')
                                        <!-- end radio3 -->
                                        @include('promotions.order.p14')
                                        <!-- end radio4 -->
                                        @include('promotions.order.p15')
                                        <!-- end radio 5 -->
                                        @include('promotions.order.p16')
                                        <!-- end radio 6 -->
                                    </div>
                                    <div class="action text-right">
                                        <input type="hidden" id="is_reload" value="0">
                                        <input type="hidden" id="is_next" value="0">
                                        <input type="hidden" name="id" id="id" value="0">
                                        <span class="cancel Cancel">Hủy bỏ</span>
                                        <button type="button" value="" class="btn btn_primary BtnSave">Lưu & Kích hoạt</button>
                                        <button type="button" value="" class="btn btn_primary BtnSaveNext">Lưu, Kích hoạt & Tạo mới</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript" src="/js/promotions-product.js"></script>

    <script type="text/javascript">
        var _objects = {!! @json_encode($_objects) !!};
        var _product_ids = {!! @json_encode($product_ids) !!};
        var _products = [];
        var _brands = [];

        $(document).ready(function() {
            $('#radio<?=$curr?>').trigger('click');

            ajax_loading(true);
            $.post(_base_url + '/promotion/get-list-products-by-ids', {
                ids: _product_ids
            }, function (data) {
                ajax_loading(false);
                _products = data.products;
                var html, tmp;
                $.each(_objects, function (id, item) {
                    html = [];

                    if (item.package_id == 13 || item.package_id == 14) {
                        html = [];
                        $.each(item.gift_products, function (index, pid) {
                            tmp = _products[pid];
                            html.push('<a title="' + tmp.name + '">' + tmp.sku + '</a>');
                        });
                        $('.ViewFast.p' + id + ' .list-giff').html(html.join('; '));
                        $('.ViewFast.p' + id + ' .list-giff').closest('li').show();
                    }
                });
            });

            init_select2('#brand_id');
            get_brands('#brand_id', 'check_show_brand');
        });
        function check_show_brand() {
            if ($('display-name-brand').length > 0) {
                $('display-name-brand').each(function( index ) {
                    $(this).html( _brands[$(this).attr('data-id')] );
                });
            }
        }
    </script>
@endsection