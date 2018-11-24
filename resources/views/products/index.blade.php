@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view view_product">
            <div class="header">
                <h2 class="title">Danh sách sản phẩm</h2>
            </div>
            <section class="view_donhang ">
                <div class="header-panel">
                    <h3 class="title ">Tìm kiếm nhanh</h3>
                    <div class="wrap_link">
                        <a href="<?=route('products.create')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo sản phẩm mới</span></a>
                    </div>
                </div>
                <form action="" method="get" class="search_donhang" id="frm-search">
                    <div class="form_search_fast row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>SKU Sản phẩm</label>
                                <input type="text" name="sku" value="<?=@$params['sku']?>" placeholder="Nhập mã sản phẩm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tên sản phẩm</label>
                                <input type="text" name="name" value="<?=@$params['name']?>" placeholder="Nhập tên sản phẩm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Thương hiệu</label>
                                {!! Form::select("brand_id", ['' => ''] + $brand_options, @$params['brand_id'],
                                        ['id' => 'brand_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo thương hiệu']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Danh mục sản phẩm</label>
                                {!! Form::select("category_id", ['' => ''] + $category_options, @$params['category_id'],
                                        ['id' => 'category_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo danh mục']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nhà cung cấp</label>
                                {!! Form::select("supplier_id", ['' => ''] + $supplier_options, @$params['supplier_id'],
                                        ['id' => 'supplier_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo nhà cung cấp']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="search_level">
                        <h3 class="title">Tìm kiếm nâng cao</h3>
                        <div class="wrap_search">
                            <div class="number_product">
                                <label>Số lượng sản phẩm</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="number_product" class="form-control select2" data-placeholder="Chọn số lượng sản phẩm">
                                                <option value=""></option>
                                            <?php for($i=0; $i<20; $i++) { ?>
                                                <option value="<?=$i?>"><?=$i?> Sản phẩm</option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>Từ</b>
                                            <input type="text" name="number_from" id="number_from" value="<?=@$params['number_from']?>" placeholder="Nhập số lượng sản phẩm">
                                            <i>Cái</i>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>Đến</b>
                                            <input type="text" name="number_to" id="number_to" value="<?=@$params['number_to']?>" placeholder="Nhập số lượng sản phẩm">
                                            <i>Cái</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="price_product">
                                <label>Giá bán sản phẩm</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="price_product" class="form-control select2" data-placeholder="Chọn giá bán sản phẩm">
                                                <option value=""></option>
                                            <?php
                                                $tmp = 1000000;
                                                for($i=0; $i<10; $i++) { ?>
                                                <option value="<?=$i*$tmp?>" data-from="<?=$i*$tmp?>" data-to="<?=($i+1)*$tmp?>">
                                                    <?=number_format($i*$tmp)?> - <?=number_format(($i+1)*$tmp)?> VNĐ</option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>Từ</b>
                                            <input class="fm-number" type="text" name="price_from" id="price_from" value="<?=@$params['price_from']?>" placeholder="Nhập giá sản phẩm">
                                            <i>VNĐ</i>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>Đến</b>
                                            <input class="fm-number" type="text" name="price_to" id="price_to" value="<?=@$params['price_to']?>" placeholder="Nhập giá sản phẩm">
                                            <i>VNĐ</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="status">
                                <p class="sub_title"><i class="icon-status">&nbsp</i> <span>Trạng thái sản phẩm</span></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-4"><label><input type="radio" name="status" value="all" <?=@$params['status']=='all'?'checked':''?>> Tất cả</label></div>
                                        <div class="col-md-4"><label><input type="radio" name="status" value="A" <?=@$params['status']=='A'?'checked':''?>> Kích hoạt</label></div>
                                        <div class="col-md-4"><label><input type="radio" name="status" value="D" <?=@$params['status']=='D'?'checked':''?>> Không kích hoạt</label></div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="btn_search_level">
                        <i class="icon-search-hover">&nbsp</i>
                        <div class="show_search" >
                            <span class="on_search">Tìm kiếm nâng cao</span> <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>
                        <div class="hide_search" style="display: none;">
                            <span class="off_search" >Tắt tìm kiếm nâng cao</span>
                            <i class="fa fa-angle-up" aria-hidden="true"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn_search" value="">Tìm kiếm</button>
                </form>
            </section>
            <section class="table ">
                <div class="table_donhang">
                    <div class="header_table">
                        <div class="row">
                            <div class="col-md-7">
                                <div class=" col-md-1"><input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check"></div>
                                <div class="center col-md-3">SKU</div>
                                <div class=" col-md-3">Tên sản phẩm</div>
                                <div class=" col-md-2">Danh mục chính</div>
                                <div class=" col-md-3">Danh mục phụ</div>
                            </div>
                            <div class="col-md-5">
                                <div class="center col-md-2">Thương hiệu</div>
                                <div class=" col-md-3">Giá thị trường</div>
                                <div class=" col-md-3">Giá bán</div>
                                <div class=" col-md-2">Trạng thái</div>
                                <div class=" col-md-2"></div>
                            </div>
                        </div>
                    </div>
                    @if(!empty($objects['data']))
                    <ul class="body_table panel-group" id="accordion5">
                        <div class="switch_bar" >
                            <span>Bạn đã chọn <b class="orange selected-items">00</b> sản phẩm</span>
                            <div class="wrap-choose">
                                <label class="lb-choose">Chọn thao tác</label>
                                <div class="wrap-select" style="width: 59%;">
                                    <select class="form-control" id="choose-act">
                                        <option value="0">Chọn thao tác</option>
                                        <option value="1">Chuyển trạng thái sản phẩm</option>
                                        <option value="2">Cập nhật số lượng</option>
                                        <option value="3">Hiển thị icon</option>
                                    </select>
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="opacity1 option0 option">1</div>
                            <div class="wrap-choose choose-status option1 option">
                                <label class="lb-choose">Chọn trạng thái</label>
                                <div class="wrap-select">
                                    <select class="form-control" id="select-status">
                                        <option value="D">Không kích hoạt</option>
                                        <option value="A">Kích hoạt</option>
                                    </select>
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="wrap-switch option1 option">
                                <label class="switch" style="width: 106px;">
                                    <input type="checkbox" id="switch-icon">
                                    <div class="slider round"><i class="icon-check">&nbsp</i></div>
                                </label>
                            </div>
                            <div class="wrap-choose option2 option">
                                <label>Nhập số lượng</label>
                                <input type="text" name="product_count" id="product_count" placeholder="Nhập số lượng sản phẩm">
                            </div>
                            <button type="button" class="btn_update option2 option" value="">Cập nhật</button>
                            <div class="wrap-choose option3 option">
                                <label class="lb-choose">Chọn icon</label>
                                <div class="wrap-select">
                                    <select class="form-control" id="icon-option">
                                        <option value="1">Hiện tặng quà</option>
                                        <option value="0">Không hiện tặng quà</option>
                                        <option value="3">Hiện hàng mới</option>
                                        <option value="4">Không hiện hàng mới</option>
                                        <option value="5">Hiện đổi cũ lấy mới</option>
                                        <option value="6">Không hiện đổi cũ lấy mới</option>
                                        <option value="7">Hiện trả góp 0%</option>
                                        <option value="8">Không hiện trả góp 0%</option>
                                        <option value="9">Hiện giá tốt</option>
                                        <option value="10">Không hiện giá tốt</option>
                                    </select>
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            <button type="button" class="btn_update option3 option" value="">Cập nhật</button>
                        </div>

                        @foreach($objects['data'] as $item)
                        <li class="row panel">
                            <div class="col-md-7">
                                <div class=" col-md-1">
                                    <input type="checkbox" value="<?=$item['product_id']?>" name="choose" class="checkbox_check">
                                </div>
                                <div class="id col-md-3"><?=$item['product_code']?></div>
                                <div class="status col-md-3"><?=$item['product']?></div>
                                <div class="col-md-2"><?=$item['category']?></div>
                                <div class="col-md-3">
                                    <?php
                                        $subc = [];
                                    if (isset($sub_category[$item['product_id']])) {
                                        foreach ($sub_category[$item['product_id']] as $sub) {
                                            if (isset($category_options[$sub])) $subc[] = trim($category_options[$sub], '|-');
                                        }
                                    }
                                    echo implode(", ", $subc);
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class=" col-md-2"> <?=$item['brand_name']?></div>
                                <div class=" col-md-3"> <span class="orange"><?=number_format($item['list_price'])?>đ</span></div>
                                <div class=" col-md-3"> <span class="orange"><?=number_format($item['price'])?>đ</span></div>
                                <div class=" col-md-2">
                                    <div class="wrapper_">
                                        <input type="checkbox" id="checkbox0" class="slider-toggle" <?=$item['status'] == 'A'?'checked':'' ?> onclick="return false;" onkeydown="return false;"/>
                                        <label class="slider-viewport" for="checkbox0">
                                            <div class="slider">
                                                <div class="slider-button">&nbsp;</div>
                                                <div class="slider-content left"><span>On</span></div>
                                                <div class="slider-content right"><span>Off</span></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a target="_blank" href="<?=\App\Helpers\General::get_link_product_preview($item)?>"><i class="icon-xemtruoc" title="Xem trước">&nbsp</i></a>
                                    <a target="_blank" href="<?=\App\Helpers\General::get_link_product_preview($item)?>"><i class="icon-xemtruoc-hover" title="Xem trước">&nbsp</i></a>
                                    <i class="icon-view-fast" title="Xem nhanh" data-toggle="collapse" data-parent="#accordion5" href="#collapsea<?=$item['product_id']?>">&nbsp</i>
                                    <i class="icon-view-fast-hover" title="Xem nhanh" style="display: none;" data-toggle="collapse" data-parent="#accordion5" href="#collapsea<?=$item['product_id']?>">&nbsp</i>
                                    <a href="<?=route('products.edit',['id' => $item['product_id']])?>"><i class="icon-view-detail" title="Xem chi tiết">&nbsp</i></a>
                                    <i class="icon-view-detail-hover" title="Xem chi tiết" style="display: none;">&nbsp</i>
                                </div>
                            </div>
                            <div class="view_fast panel-collapse collapse" id="collapsea<?=$item['product_id']?>">
                                <div class="wrap_pannel">
                                    <p class="title_viewfast">THông tin sản phẩm</p>
                                    <div class="col-md-7">
                                        <div class="box">
                                            <ul>
                                                <li class="row"><b>Tên sản phẩm: </b> <span><?=$item['product']?></span></li>
                                                <li class="row"><b>SKU: </b> <span><?=$item['product_code']?></span></li>
                                                <li class="row"><b>Danh mục: </b> <span><?=$item['category']?></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="box">
                                            <ul>
                                                <li class="row"><b>Thương hiệu: </b> <span></span></li>
                                                <li class="row"><b>Số lượng sản phẩm:  </b> <span><?=$item['amount']?> cái</span></li>
                                                <li class="row"><b>Khối lượng sản phẩm:  </b> <span><?=$item['weight']?>gr</span></li>
                                                <li class="row"><b>Giá thị trường:  </b> <span><?=number_format($item['list_price'])?> VNĐ</span></li>
                                                <li class="row"><b>Giá bán:  </b> <span><?=number_format($item['price'])?> VNĐ</span></li>
                                                <?php $percent_discount = $item['list_price'] > 0 ? round( 100 - ($item['price'] * 100 / $item['list_price']) ) : 0; ?>
                                                <li class="row"><b>% Giảm giá </b> <span>(giá TH/giá thị trường):</span> <span><?=$percent_discount?>%</span></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6 col-md-offset-6">
                                            <span class="cancel">Hủy bỏ</span>
                                            <button type="button" class="btn_edit">Sửa sản phẩm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                @include('includes.paginator')
            </section>
        </div>
    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(function(){
            $('#choose-action').on('change', function () {
                var option = $(this).val();
                $('.switch_bar .option').hide();
                $('.switch_bar .option'+option).show();
            });
            $('#choose-action').trigger('change');

            $('.checkbox_check').on('click', function(){
                $('.selected-items').html($('.checkbox_check:checked').length);
            });
            $('#switch-icon').change(function() {
                if ($(this).prop('checked')) {
                    var option = $('#select-status').val();
                    var data = {status: option};
                    var title = "";
                    var flag = true;
                    if (option=='A') {
                        title = "Bạn muốn kích hoạt các sản phẩm đã chọn?";
                    } else if (option=='D') {
                        title = "Bạn muốn hủy kích hoạt các sản phẩm đã chọn?";
                    }

                    malert(title, 'Xác nhận chuyển trạng thái', function () {
                        if (flag) $('#switch-icon').prop('checked', false).change()
                    }, function(){
                        flag = false;
                        var product_ids = [];
                        $('.checkbox_check:checked').each(function () {
                            product_ids.push($(this).val())
                        });
                        data.product_ids = product_ids;
                        ajax_loading(true);
                        $.post('<?=route('products.change-status')?>', data, function (res) {
                            ajax_loading(false);
                            if(res.rs == 1)
                            {
                                alert_success(res.msg, function () {
                                    location.reload();
                                });
                            } else {
                                alert_success(res.msg);
                                if (res.errors) {
                                    $.each(res.errors, function (key, msg) {
                                        $('#'+key+'-error').html(msg).show();
                                    });
                                }
                            }
                        });
                    });
                }
            })
            $('.btn_update.option2').on('click', function () {
                var product_count = $('#product_count').val();
                if (product_count=='' || product_count<0) {
                    malert('Vui lòng nhập số lượng sản phẩm', 'Thông báo', function () {
                        $('#product_count').focus();
                    });
                    return;
                }

                malert('Bạn muốn cập nhật số lượng của các sản phẩm được chọn bằng: <strong>'+product_count+'</strong> đúng không?',
                    'Xác nhận cập nhật số lượng sản phẩm', null, function(){
                        var product_ids = [];
                        $('.checkbox_check:checked').each(function () {
                            product_ids.push($(this).val())
                        });

                        ajax_loading(true);
                        $.post('<?=route('products.update-count')?>', {product_count:product_count, product_ids:product_ids}, function (res) {
                            ajax_loading(false);
                            if(res.rs == 1)
                            {
                                alert_success(res.msg, function () {
                                    location.reload();
                                });
                            } else {
                                alert_success(res.msg);
                                if (res.errors) {
                                    $.each(res.errors, function (key, msg) {
                                        $('#'+key+'-error').html(msg).show();
                                    });
                                }
                            }
                        });
                    });
            });
            $('.btn_update.option3').on('click', function () {
                var option = parseInt( $('#icon-option').val() );
                var data = {field: 'has_gift', value: option};
                var title = "";
                var url = '<?=route('products.change-value-field')?>';
                switch(option) {
                    case 1:
                    case 0: {
                        data.name = "quà tặng";
                        break;
                    }
                    case 3:{
                        data = {field: 'is_new', value: 1, name: 'hàng mới'};
                        break;
                    }
                    case 4:{
                        data = {field: 'is_new', value: 0, name: 'hàng mới'};
                        break;
                    }
                    case 5:{
                        data = {field: 'is_exchange', value: 1, name: 'hàng đổi cũ lấy mới'};
                        break;
                    }
                    case 6:{
                        data = {field: 'is_exchange', value: 0, name: 'hàng đổi cũ lấy mới'};
                        break;
                    }
                    case 7:{
                        data = {field: 'is_installment', value: 1, name: 'trả góp 0%'};
                        break;
                    }
                    case 8:{
                        data = {field: 'is_installment', value: 0, name: 'trả góp 0%'};
                        break;
                    }
                    case 9:{
                        data = {field: 'is_good_price', value: 1, name: 'giá tốt'};
                        break;
                    }
                    case 10:{
                        data = {field: 'is_good_price', value: 0, name: 'giá tốt'};
                        break;
                    }
                }
                if (data.value) {
                    title = "Bạn muốn hiển thị icon "+data.name+"? Cho các sản phẩm đã chọn.";
                } else {
                    title = "Bạn không muốn hiển thị icon "+data.name+"? Cho các sản phẩm đã chọn.";
                }

                malert(title, 'Xác nhận chọn icon', null, function(){
                    var product_ids = [];
                    $('.checkbox_check:checked').each(function () {
                        product_ids.push($(this).val());
                    });
                    data.product_ids = product_ids;
                    ajax_loading(true);
                    $.post(url, data, function (res) {
                        ajax_loading(false);
                        if(res.rs == 1)
                        {
                            alert_success(res.msg, function () {
                                location.reload();
                            });
                        } else {
                            alert_success(res.msg);
                            if (res.errors) {
                                $.each(res.errors, function (key, msg) {
                                    $('#'+key+'-error').html(msg).show();
                                });
                            }
                        }
                    });
                });
            });
            init_select2('.select2');
            init_fm_number('.fm-number');
            $('.fm-number').each(function( index ) {
                $(this).val( numeral($(this).val()).format() );
            });

            $('#number_product').on('change', function () {
                $('#number_from').val($(this).val());
                $('#number_to').val($(this).val());
            });
            $('#price_product').on('change', function () {
                var option = $('#price_product option:selected');
                $('#price_from').val(numeral(option.attr('data-from')).format());
                $('#price_to').val(numeral(option.attr('data-to')).format());
            });
            $('#frm-search').on('submit', function () {
                $('.fm-number').each(function( index ) {
                    $(this).val( numeral($(this).val()).value() );
                });
            });
        });
    </script>
@endsection