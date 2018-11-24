@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view view_product">
            <div class="header">
                <h2 class="title">Danh sách <?=$title?></h2>
            </div>
            <section class="view_donhang ">
                <div class="header-panel">
                    <h3 class="title ">Tìm kiếm nhanh</h3>
                    <div class="wrap_link">
                        <a href="<?=route($controllerName.'.create')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo <?=$title?> mới</span></a>
                    </div>
                </div>
                <form action="" method="get" class="search_donhang" id="frm-search">
                    <div class="form_search_fast row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tên <?=$title?></label>
                                <input type="text" name="name" value="<?=@$params['name']?>" placeholder="Nhập tên <?=$title?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Danh mục <?=$title?></label>
                                {!! Form::select("parent_id", ['' => ''] + $service_all, @$params['parent_id'],
                                        ['id' => 'parent_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo danh mục']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="search_level" style="display: none">
                        <h3 class="title">Tìm kiếm nâng cao</h3>
                        <div class="wrap_search">
                            <div class="number_product">
                                <label>Số lượng <?=$title?></label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="number_product" class="form-control select2" data-placeholder="Chọn số lượng <?=$title?>">
                                                <option value=""></option>
                                            <?php for($i=0; $i<20; $i++) { ?>
                                                <option value="<?=$i?>"><?=$i?> <?=$title?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>Từ</b>
                                            <input type="text" name="number_from" id="number_from" value="<?=@$params['number_from']?>" placeholder="Nhập số lượng <?=$title?>">
                                            <i>Cái</i>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>Đến</b>
                                            <input type="text" name="number_to" id="number_to" value="<?=@$params['number_to']?>" placeholder="Nhập số lượng <?=$title?>">
                                            <i>Cái</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="price_product">
                                <label>Giá <?=$title?></label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="price_product" class="form-control select2" data-placeholder="Chọn giá bán <?=$title?>">
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
                                            <input class="fm-number" type="text" name="price_from" id="price_from" value="<?=@$params['price_from']?>" placeholder="Nhập giá <?=$title?>">
                                            <i>VNĐ</i>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>Đến</b>
                                            <input class="fm-number" type="text" name="price_to" id="price_to" value="<?=@$params['price_to']?>" placeholder="Nhập giá <?=$title?>">
                                            <i>VNĐ</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="status">
                                <p class="sub_title"><i class="icon-status">&nbsp</i> <span>Trạng thái <?=$title?></span></p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-4"><label><input type="radio" name="status" value="all" <?=@$params['status']=='all'?'checked':''?>> Tất cả</label></div>
                                        <div class="col-md-4"><label><input type="radio" name="status" value="1" <?=@$params['status']==1?'checked':''?>> Kích hoạt</label></div>
                                        <div class="col-md-4"><label><input type="radio" name="status" value="0" <?=@$params['status']==0?'checked':''?>> Không kích hoạt</label></div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="btn_search_level">
                        <i class="icon-search-hover" style="display: none">&nbsp</i>
                        <div class="show_search" style="display: none">
                            <span class="on_search"  >Tìm kiếm nâng cao</span> <i class="fa fa-angle-down" aria-hidden="true"></i>
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
                            <div class="col-md-8">
                                <div class=" col-md-1"><input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check"></div>
                                <div class="center col-md-3">Tên dịch vụ</div>
                                <div class=" col-md-4">Dịch vụ cha</div>
                                <div class=" col-md-4">Hình</div>
                            </div>
                            <div class="col-md-4">
                                <div class=" col-md-4">Vị trí</div>
                                <div class=" col-md-4">Trạng thái</div>
                                <div class=" col-md-4"></div>
                            </div>
                        </div>
                    </div>
                    @if(!empty($objects['data']))
                    <ul class="body_table panel-group" id="accordion5">
                        <div class="switch_bar" >
                            <span>Bạn đã chọn <b class="orange selected-items">00</b> <?=$title?></span>
                            <div class="wrap-choose">
                                <label class="lb-choose">Chọn thao tác</label>
                                <div class="wrap-select" style="width: 59%;">
                                    <select class="form-control" id="choose-act">
                                        <option value="0">Chọn thao tác</option>
                                        <option value="1">Chuyển trạng thái <?=$title?></option>
                                       <!--  <option value="2">Cập nhật số lượng</option>
                                        <option value="3">Hiển thị icon</option> -->
                                    </select>
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="opacity1 option0 option">1</div>
                            <div class="wrap-choose choose-status option1 option">
                                <label class="lb-choose">Chọn trạng thái</label>
                                <div class="wrap-select">
                                    <select class="form-control" id="select-status">
                                        <option value="0">Không kích hoạt</option>
                                        <option value="1">Kích hoạt</option>
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
                                <input type="text" name="product_count" id="product_count" placeholder="Nhập số lượng <?=$title?>">
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
                            <div class="col-md-8">
                                <div class=" col-md-1">
                                    <input type="checkbox" value="<?=$item['id']?>" name="choose" class="checkbox_check">
                                </div>
                                <div class="id col-md-3"><?=$item['name']?></div>
                                <div class="status col-md-4"><?=$item['parent_name']?></div>
                                <div class=" col-md-4"> <img src="<?=$item['image_url'].$item['image_location']?>" style="max-height: 50px"></div>
                            </div>
                            <div class="col-md-4">
                                <div class=" col-md-4"> <?=$item['position']?></div>
                                <div class=" col-md-4">
                                    <div class="wrapper_">
                                        <input type="checkbox" id="checkbox0" class="slider-toggle" <?=$item['status'] == 1?'checked':'' ?> onclick="return false;" onkeydown="return false;"/>
                                        <label class="slider-viewport" for="checkbox0">
                                            <div class="slider">
                                                <div class="slider-button">&nbsp;</div>
                                                <div class="slider-content left"><span>On</span></div>
                                                <div class="slider-content right"><span>Off</span></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?=route($controllerName.'.edit',['id' => $item['id']])?>" class="tooltip" style="top: 2px;">
                                        <i class="icon-edit-pen active" aria-hidden="true"></i>
                                        <i class="icon-edit-pen-hover" title="Chỉnh sửa">&nbsp</i>
                                        <span class="tooltiptext">Cập nhật</span>
                                    </a>

                                    <a href="#" class="tooltip delete-action" data-id="<?=$item['id']?>" >
                                        <i class="icon-delete active" title="Xóa">&nbsp</i>
                                        <i class="icon-delete-hover" title="Xóa">&nbsp</i>
                                        <span class="tooltiptext">Xoá</span>
                                    </a>
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
            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có chắc chắn muốn xóa?", function () {
                    ajax_loading(true);
                    $.post("{{route($controllerName.'.delete')}}", {
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
                return false;
            });
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
                        title = "Bạn muốn kích hoạt các <?=$title?> đã chọn?";
                    } else if (option=='D') {
                        title = "Bạn muốn hủy kích hoạt các <?=$title?> đã chọn?";
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
                        $.post('<?=route($controllerName.'.change-status')?>', data, function (res) {
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
                    malert('Vui lòng nhập số lượng <?=$title?>', 'Thông báo', function () {
                        $('#product_count').focus();
                    });
                    return;
                }

                malert('Bạn muốn cập nhật số lượng của các <?=$title?> được chọn bằng: <strong>'+product_count+'</strong> đúng không?',
                    'Xác nhận cập nhật số lượng <?=$title?>', null, function(){
                        var product_ids = [];
                        $('.checkbox_check:checked').each(function () {
                            product_ids.push($(this).val())
                        });

                        ajax_loading(true);
                        $.post('<?=route($controllerName.'.update-count')?>', {product_count:product_count, product_ids:product_ids}, function (res) {
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
                var url = '<?=route($controllerName.'.change-value-field')?>';
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
                    title = "Bạn muốn hiển thị icon "+data.name+"? Cho các <?=$title?> đã chọn.";
                } else {
                    title = "Bạn không muốn hiển thị icon "+data.name+"? Cho các <?=$title?> đã chọn.";
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