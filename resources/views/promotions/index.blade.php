@extends('layouts.master')

@section('content')
    <style>
        .delete-product {
            font-size: 24px;
            position: absolute;
            font-weight: bold;
            padding-left: 8px;
            cursor: pointer;
        }
        .Radio.Radio4 {
            margin-bottom: 10px;
        }
        .col-md-6.item-giff {
            margin-bottom: 8px;
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
                        <a type="button" class="tablinks active">Quà tặng cho sản phẩm</a>
                        <a href="{{route('promotions.order')}}" class="tablinks">Quà tặng cho đơn hàng</a>
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
                                    <li class="row panel">
                                        <form class="row-filter FilterRow text-center">
                                            <div class="col-md-7"></div>
                                            <div class="col-md-5">
                                                <div class="col-md-4 no-padding">
                                                    <div class="wrap_select">
                                                        {!! Form::select("status-filter", \App\Helpers\General::get_status_options(), @$params['status-filter'], ['id' => 'status_filter', 'class' => 'form-control']) !!}
                                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-left submit">
                                                    <button type="submit" value="" class="btn btn_primary">Lọc</button>
                                                </div>
                                            </div>
                                        </form>
                                    </li>
                                    @foreach($objects['data'] as $index => $item)
                                        <?php
                                        $item['from_date'] = \App\Helpers\General::output_date($item['from_date'], true);
                                        $item['to_date'] = \App\Helpers\General::output_date($item['to_date'], true);
                                        $item['apply_objects'] = json_decode($item['apply_objects'], 1);
                                        $item['gift_products'] = json_decode($item['gift_products'], 1);
                                        if ($item['package_id']==7 || $item['package_id']==8) {
                                            if (isset($item['apply_objects']) && is_array($item['apply_objects'])) {
                                                $brand_ids = array_merge($brand_ids, array_keys($item['apply_objects']));
                                            }
                                        } elseif ($item['package_id']==6) {
                                            if (isset($item['apply_objects'][0]) && is_array($item['apply_objects'][0])) {
                                                $product_ids = array_merge($product_ids, array_keys($item['apply_objects'][0]));
                                            }

                                            if (isset($item['apply_objects'][1]) && is_array($item['apply_objects'][1])) {
                                                $product_ids = array_merge($product_ids, array_keys($item['apply_objects'][1]));
                                            }
                                        } else {
                                            if (isset($item['apply_objects']) && is_array($item['apply_objects'])) {
                                                $product_ids = array_merge($product_ids, array_keys($item['apply_objects']));
                                            }
                                        }

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
                                                @if ($item['status'])
                                                    <button class="btn btn-stop" data-id="{{$item['id']}}">Dừng</button>
                                                @else
                                                    <button class="btn btn-start" data-id="{{$item['id']}}">Kích hoạt</button>
                                                @endif
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <a class="tooltip" data-toggle="collapse" data-parent="#accordion3" href="#collapsea{{$item['id']}}">
                                                    <i class="fa fa-eye" aria-hidden="true" ></i>
                                                    <span class="tooltiptext">Xem nhanh</span>
                                                </a>
                                                <a class="tooltip update-action" data-id="{{$item['id']}}">
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
                                                    <li><b>Loại khuyến mãi: </b> <span>{{@$packages[$item['package_id']]['name']}}</span></li>
                                                    <li><b>Thời gian áp dụng: </b><span>{{$item['from_date']}} ~ {{$item['to_date']}}</span></li>
                                                    <li><b>Sản phẩm khuyến mãi: </b><span class="color list-pro"></span></li>
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
                                    @foreach($packages as $id => $item)
                                        <li>
                                            <div class="radio <?=$id==1?'active':'';?>">
                                                <input <?=$id==1?'checked':'';?> id="radio{{$id}}" value="{{$id}}" type="radio" class="package" name="list-promotion">
                                                <label for="radio{{$id}}">{{$item['name']}}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-7 right-block">
                                    <div class="display-radio DisplaRadio">
                                        <div class="Radio Radio1">
                                            <form method="post" id="form_update1" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="1">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm A <span>*</span></label>
                                                    <textarea name="apply_objects" id="apply_objects" type="text" rows="2" class="form-control apply_objects" placeholder="Điền mã SKU sản phẩm"></textarea>
                                                    <span class="note">(Mỗi SKU cách nhau bởi dấu phẩy, tối đa 30 SKU một chương trình)</span>
                                                    <label id="apply_objects-error" class="error" for="apply_objects" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa">
                                                    {{--<li class="col-md-6">0259LH12387 - 20 sản phẩm</li>--}}
                                                </ul>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- end radio1 -->
                                        <div class="Radio Radio2" style="display: none;">
                                            <form method="post" id="form_update2" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="2">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm A <span>*</span></label>
                                                    <textarea name="apply_objects" id="apply_objects" type="text" rows="2" class="form-control apply_objects" placeholder="Điền mã SKU sản phẩm"></textarea>
                                                    <span class="note">(Mỗi SKU cách nhau bởi dấu phẩy, tối đa 30 SKU một chương trình)</span>
                                                    <label id="apply_objects-error" class="error" for="apply_objects" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa">
                                                    {{--<li class="col-md-6">0259LH12387 - 20 sản phẩm</li>--}}
                                                </ul>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm B <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku" id="gift_products_sku" type="text" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku-error" class="error" for="gift_products_sku" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm khuyến mãi</label>
                                                <ul class="list-pro pb">
                                                    {{--<li class="col-md-6">05812OK01258 - 200 sản phẩm</li>--}}
                                                </ul>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- end radio 2 -->
                                        <div class="Radio Radio3" style="display: none;">
                                            <form method="post" id="form_update3" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="3">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm A <span>*</span></label>
                                                    <textarea name="apply_objects" id="apply_objects" type="text" rows="2" class="form-control apply_objects" placeholder="Điền mã SKU sản phẩm"></textarea>
                                                    <span class="note">(Mỗi SKU cách nhau bởi dấu phẩy, tối đa 30 SKU một chương trình)</span>
                                                    <label id="apply_objects-error" class="error" for="apply_objects" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa"></ul>
                                            </div>
                                        <div class="list-giff">
                                            <div class="form-group item-giff 0">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm B <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku_b" id="gift_products_sku_b" type="text" data-po="0" class="form-control gift_products_sku_po" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku_b-error" class="error" for="gift_products_sku_b" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm B <span>*</span></label>
                                                    <input name="gift_products_number_b" id="gift_products_number_b" type="text" data-po="0" class="form-control gift_products_number_po" placeholder="Điền số lượng sản phẩm">
                                                    <label id="gift_products_number_b-error" class="error" for="gift_products_number_b" style="display: none;"></label>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm khuyến mãi</label>
                                                <ul class="list-pro pb"></ul>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- end radio3 -->
                                        <div class="Radio Radio4" style="display: none;">
                                            <form method="post" id="form_update4" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="4">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm A <span>*</span></label>
                                                    <textarea name="apply_objects" id="apply_objects" type="text" rows="2" class="form-control apply_objects" placeholder="Điền mã SKU sản phẩm"></textarea>
                                                    <span class="note">(Mỗi SKU cách nhau bởi dấu phẩy, tối đa 30 SKU một chương trình)</span>
                                                    <label id="apply_objects-error" class="error" for="apply_objects" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa"></ul>
                                            </div>

                                            <div class="form-group list-giff">
                                                <div class="col-md-6 item-giff 0">
                                                    <label for="">Nhập SKU sản phẩm B <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku" id="gift_products_sku" type="text" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku-error" class="error" for="gift_products_sku" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6 item-giff 1">
                                                    <label for="">Nhập SKU sản phẩm C <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku_c" id="gift_products_sku_c" type="text" data-po="1" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku_c-error" class="error" for="gift_products_sku_c" style="display: none;"></label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="">Sản phẩm khuyến mãi</label>
                                                <ul class="list-pro pb"></ul>
                                            </div>
                                            <div class="add-gift-only">
                                                <a href="javascript:void(0)" class="pull-right link" style=""><i class="fa fa-plus-circle" aria-hidden="true"></i> <span>Thêm quà tặng</span></a>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- end radio4 -->
                                        <div class="Radio Radio5" style="display: none;">
                                            <form method="post" id="form_update5" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="5">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm A <span>*</span></label>
                                                    <textarea name="apply_objects" id="apply_objects" type="text" rows="2" class="form-control apply_objects" placeholder="Điền mã SKU sản phẩm"></textarea>
                                                    <span class="note">(Mỗi SKU cách nhau bởi dấu phẩy, tối đa 30 SKU một chương trình)</span>
                                                    <label id="apply_objects-error" class="error" for="apply_objects" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa"></ul>
                                            </div>
                                        <div class="list-giff">
                                            <div class="form-group item-giff 0">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm B <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku_b" id="gift_products_sku_b" type="text" data-po="0" class="form-control gift_products_sku_po" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku_b-error" class="error" for="gift_products_sku_b" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm B <span>*</span></label>
                                                    <input name="gift_products_number_b" id="gift_products_number_b" type="text" data-po="0" class="form-control gift_products_number_po" placeholder="Điền số lượng sản phẩm">
                                                    <label id="gift_products_number_b-error" class="error" for="gift_products_number_b" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group item-giff 1">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm C <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku_c" id="gift_products_sku_c" type="text" data-po="1" class="form-control gift_products_sku_po" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku_c-error" class="error" for="gift_products_sku_c" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm C <span>*</span></label>
                                                    <input name="gift_products_number_c" id="gift_products_number_c" type="text" data-po="1" class="form-control gift_products_number_po" placeholder="Điền số lượng sản phẩm">
                                                    <label id="gift_products_number_c-error" class="error" for="gift_products_number_c" style="display: none;"></label>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm khuyến mãi</label>
                                                <ul class="list-pro pb"></ul>
                                            </div>
                                            <div class="add-gift">
                                                <a href="javascript:void(0)" class="pull-right link" style=""><i class="fa fa-plus-circle" aria-hidden="true"></i> <span>Thêm quà tặng</span></a>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- end radio 5 -->
                                        <div class="Radio Radio6" style="display: none;">
                                            <form method="post" id="form_update6" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="6">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm A <span>*</span></label>
                                                    <textarea name="apply_objects" id="apply_objects" type="text" rows="2" class="form-control apply_objects" placeholder="Điền mã SKU sản phẩm"></textarea>
                                                    <span class="note">(Mỗi SKU cách nhau bởi dấu phẩy, tối đa 30 SKU một chương trình)</span>
                                                    <label id="apply_objects-error" class="error" for="apply_objects" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm B <span>*</span></label>
                                                    <textarea name="apply_objects_b" id="apply_objects_b" type="text" rows="2" class="form-control apply_objects_b" placeholder="Điền mã SKU sản phẩm"></textarea>
                                                    <label id="apply_objects_b-error" class="error" for="apply_objects_b" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm B <span>*</span></label>
                                                    <input name="apply_number_b" id="apply_number_b" type="text" class="form-control apply_number_b" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number_b-error" class="error" for="apply_number_b" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa"></ul>
                                                <ul class="list-pro pab"></ul>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm B <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku" id="gift_products_sku" type="text" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku-error" class="error" for="gift_products_sku" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm khuyến mãi</label>
                                                <ul class="list-pro pb"></ul>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- end radio6 -->
                                        <div class="Radio Radio7" style="display: none;">
                                            <form method="post" id="form_update7" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="7">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập thương hiệu A <span>*</span></label>
                                                    <textarea name="apply_brands" id="apply_brands" type="text" rows="2" class="form-control apply_brands" placeholder="Điền ID thương hiệu"></textarea>
                                                    <span class="note">(Mỗi ID cách nhau bởi dấu phẩy)</span>
                                                    <label id="apply_brands-error" class="error" for="apply_brands" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_brands_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Thương hiệu áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa"></ul>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập SKU sản phẩm B <span class="note">(Quà tặng)</span><span>*</span></label>
                                                    <input name="gift_products_sku" id="gift_products_sku" type="text" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">
                                                    <label id="gift_products_sku-error" class="error" for="gift_products_sku" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Sản phẩm khuyến mãi</label>
                                                <ul class="list-pro pb"></ul>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- end radio7 -->
                                        <div class="Radio Radio8" style="display: none;">
                                            <form method="post" id="form_update8" class="frm_update" action="{{ route("promotions.add") }}">
                                                <input type="hidden" name="id" id="id" value="0">
                                                <input type="hidden" name="package_id" value="8">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <div class="form-group">
                                                <label for="">Tên chương trình tặng quà <span>*</span></label>
                                                <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
                                                <label id="name-error" class="error" for="name" style="display: none;"></label>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Thời gian áp dụng <span>*</span></label>
                                                    <div class="time">
                                                        <div class="input-group date">
                                                            <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label >&nbsp</label>
                                                    <div class="time to-time">
                                                        <div class="input-group date">
                                                            <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                                                            <span class="fa fa-calendar"></span>
                                                        </div>
                                                    </div>
                                                    <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label for="">Nhập thương hiệu A <span>*</span></label>
                                                    <textarea name="apply_brands" id="apply_brands" type="text" rows="2" class="form-control apply_brands" placeholder="Điền ID thương hiệu"></textarea>
                                                    <span class="note">(Mỗi ID cách nhau bởi dấu phẩy)</span>
                                                    <label id="apply_brands-error" class="error" for="apply_brands" style="display: none;"></label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="">Nhập số lượng sản phẩm A <span>*</span></label>
                                                    <input name="apply_number" id="apply_number" type="text" class="form-control apply_brands_number" placeholder="Điền số lượng sản phẩm">
                                                    <label id="apply_number-error" class="error" for="apply_number" style="display: none;"></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Thương hiệu áp dụng khuyến mãi</label>
                                                <ul class="list-pro pa"></ul>
                                            </div>

                                                <div class="form-group list-giff">
                                                    <div class="col-md-6 item-giff 0">
                                                        <label for="">Nhập SKU sản phẩm B <span class="note">(Quà tặng)</span><span>*</span></label>
                                                        <input name="gift_products_sku" id="gift_products_sku" type="text" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">
                                                        <label id="gift_products_sku-error" class="error" for="gift_products_sku" style="display: none;"></label>
                                                    </div>
                                                    <div class="col-md-6 item-giff 1">
                                                        <label for="">Nhập SKU sản phẩm C <span class="note">(Quà tặng)</span><span>*</span></label>
                                                        <input name="gift_products_sku_c" id="gift_products_sku_c" type="text" data-po="1" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">
                                                        <label id="gift_products_sku_c-error" class="error" for="gift_products_sku_c" style="display: none;"></label>
                                                    </div>
                                                </div>

                                            <div class="form-group">
                                                <label for="">Sản phẩm khuyến mãi</label>
                                                <ul class="list-pro pb"></ul>
                                            </div>
                                            <div class="add-gift">
                                                <a href="#" class="pull-right link" style=""><i class="fa fa-plus-circle" aria-hidden="true"></i> <span>Thêm quà tặng</span></a>
                                            </div>
                                            </form>
                                        </div>
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

<?php
$version_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')
    <script type="text/javascript" src="/js/promotions-product.js?v=<?=$version_js?>"></script>

    <script type="text/javascript">
        var _objects = {!! @json_encode($_objects) !!};
        var _product_ids = {!! @json_encode($product_ids) !!};
        var _brand_ids = {!! @json_encode($brand_ids) !!};
        var _products = [];
        var _brands = [];
        $(document).ready(function() {
            ajax_loading(true);
            console.log(_objects);
            $.post(_base_url + '/promotion/get-list-products-by-ids', {
                ids: _product_ids,
                brand_ids: _brand_ids
            }, function (data) {
                ajax_loading(false);
                _products = data.products;
                _brands = data.brands;
                console.log(data);
                var html, tmp;
                $.each(_objects, function (id, item) {
                    html = [];
                    if (item.package_id == 7 || item.package_id == 8) {
                        console.log(item.apply_objects);
                        $.each(item.apply_objects, function (bid, num) {
                            tmp = _brands[bid];
                            if (tmp) {
                                html.push('<a target="_blank" href="'+get_link_brand(tmp.name, tmp.id)+'" title="' + tmp.name + '">' + tmp.name + ' - ' + num + '</a>');
                            }
                        });
                    } else if (item.package_id == 6) {
                        $.each(item.apply_objects, function (i, items) {
                            $.each(items, function (pid, num) {
                                tmp = _products[pid];
                                if (tmp) {
                                    html.push('<a target="_blank" href="'+get_link_product(tmp.name, tmp.product_id)+'" title="' + tmp.name + '">' + tmp.sku + ' - ' + num + '</a>');
                                }
                            });
                        });
                    } else {
                        $.each(item.apply_objects, function (pid, num) {
                            tmp = _products[pid];
                            if (tmp)
                                html.push('<a target="_blank" href="'+get_link_product(tmp.name, tmp.product_id)+'" title="' + tmp.name + '">' + tmp.sku + ' - ' + num + '</a>');
                        });
                    }
                    $('.ViewFast.p' + id + ' .list-pro').html(html.join('; '));

                    if (item.package_id == 4 || item.package_id == 2 || item.package_id == 7 || item.package_id == 8) {
                        html = [];
                        if (item.gift_products) {
                            $.each(item.gift_products, function (index, pid) {
                                tmp = _products[pid];
                                if (tmp)
                                    html.push('<a target="_blank" href="' + get_link_product(tmp.name, tmp.product_id) + '" title="' + tmp.name + '">' + tmp.sku + '</a>');
                            });
                        }
                        $('.ViewFast.p' + id + ' .list-giff').html(html.join('; '));
                        $('.ViewFast.p' + id + ' .list-giff').closest('li').show();
                    }
                });
            });
        });
    </script>
@endsection