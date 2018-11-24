@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title"><?=$title?></h2>
            </div>
            <div class="view_detail_brand create_brand" >
                <div class="header-panel">
                    <h3 class="title ">Thông tin chi tiết nhà kho</h3>
                    <div class="wrap_link">
                        <a href="<?=route('warehouse.index')?>"><i class="fa fa-reply" aria-hidden="true"></i><span>Quay lại</span></a>
                    </div>
                </div>
                <form action="<?=route('warehouse.store')?>" id="frm_update" class="frm_update" method="post">

                    <div class="row">
                        <div class="col-md-6">
                            <label>Tên nhà kho</label>
                            <input type="text" name="name" class="form-control name" placeholder="Nhập tên hệ thống" value="<?=@$object['name']?>">
                        </div>
                        <div class="col-md-3">
                            <label>Số điên thoại</label>
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="<?=@$object['phone']?>">
                        </div>
                        <div class="col-md-3">
                            <label>Phí vận chuyển trên 1km</label>
                            <input type="text" id="shipping_cost" name="shipping_cost" class="fm-number" placeholder="Nhập phí vận chuyển" value="<?=@$object['shipping_cost']?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Nhà cung cấp</label>
                            {!! Form::select("supplier_id", ['' => 'Chọn nhà cung cấp'] + $supplier, @$object['supplier_id'], ['id' => 'supplier_id', 'class' => 'form-control select2']) !!}
                        </div>
                        <div class="col-md-6">
                            <label>Danh mục sản phẩm</label>
                            <select class="form-control select2" name="category_id[]" multiple id="category_id" data-placeholder="Chọn danh mục">
                                <option value=""></option>
                                @foreach($category_options as $key => $item)
                                    <option <?=@(in_array($key, $category_ids)?'selected':'') ?> value="<?=$key ?>" ><?=$item?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Tỉnh / Thành phố</label>
                            <div class="wrap_select">
                                <select name="province_id" class="form-control provinces" data-id="<?=@$object['province_id']?>" onchange="get_districts_by_province(this)" data-destination="#frm_update .districts">
                                    <option value="">Chọn Tỉnh / Thành phố</option>
                                </select>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Quận / Huyện</label>
                            <div class="wrap_select">
                                <select name="district_id" class="form-control districts" data-id="<?=@$object['district_id']?>" onchange="get_wards_by_district(this)" data-destination="#frm_update .wards">
                                    <option value="">Chọn Quận / Huyện</option>
                                </select>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Phường / Xã</label>
                            <div class="wrap_select">
                                <select name="ward_id" data-id="<?=@$object['ward_id']?>" class="form-control wards">
                                    <option value="">Chọn Phường / Xã</option>
                                </select>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="<?=@$object['address']?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Kinh độ</label>
                            <input type="text" name="longitude" class="form-control" placeholder="Kinh độ" value="<?=@$object['longitude']?>">
                        </div>
                        <div class="col-md-6">
                            <label>Vĩ độ</label>
                            <input type="text" name="latitude" class="form-control" placeholder="Vĩ độ" value="<?=@$object['latitude']?>">
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-6">
                            <div class="box box_descript">
                                <div class="header_box">
                                    <b class="title"> Mô tả ngắn </b>
                                </div>
                                <div class="body_box">
                                    <div class="edit edit_vietnam">
                                        <textarea class="short_description" name="short_description"><?=@$object['short_description']?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box_descript">
                                <div class="header_box">
                                    <b class="title"> Mô tả đầy đủ </b>
                                </div>
                                <div class="body_box">
                                    <div class="edit edit_vietnam">
                                        <textarea class="full_description" name="full_description"><?=@$object['full_description']?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--add địa điểm giao hàng--}}
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12" >
                            <label>Địa điểm giao hàng</label><button id="add_location" class="btn-save" style="margin-left: 20px; float:none;">Thêm</button>
                            <div id="show-address-list">
                                <?php $warehouses_places_items = [] ?>
                                @if(!empty($warehouses_places))
                                    @foreach ($warehouses_places as $key => $place)
                                    <?php 
                                        $warehouses_places_item = '#pp_'.$key;
                                        $warehouses_places_items[] = $warehouses_places_item;
                                    ?>
                                    <div class="wrap_select row item_warehouse_places" id="pp_<?=$key?>" style="margin-bottom:15px;">
                                        <div class="col-md-3">
                                            <select name="warehouses_places[<?=$key?>][province_id]" class="form-control select2 warehouse_place_provinces" data-id="<?=@$place['province_id']?>" onchange="get_districts_by_province(this)" data-destination="<?=$warehouses_places_item?> .warehouse_place_districts">
                                                <option value="">Chọn Tỉnh / Thành phố</option>
                                            </select>
                                            {{-- <i class="fa fa-angle-down" aria-hidden="true"></i> --}}
                                        </div>
                                        <div class="col-md-3">
                                            <select name="warehouses_places[<?=$key?>][district_id]" class="form-control warehouse_place_districts" data-id="<?=@$place['district_id']?>" onchange="get_wards_by_district(this)" data-destination="<?=$warehouses_places_item?> .warehouse_place_wards">
                                                <option value="">Chọn Quận / Huyện</option>
                                            </select>
                                            {{-- <i class="fa fa-angle-down" aria-hidden="true"></i> --}}
                                        </div>
                                        <div class="col-md-3">
                                            <select name="warehouses_places[<?=$key?>][ward_id]" data-id="<?=@$place['ward_id']?>" class="form-control warehouse_place_wards">
                                                <option value="">Chọn Phường / Xã</option>
                                            </select>
                                            {{-- <i class="fa fa-angle-down" aria-hidden="true"></i> --}}
                                        </div>
                                        <div class="col-md-3" style="cursor:pointer;padding:10px;" ><a href="javascript:void(0)" onclick="$('<?=$warehouses_places_item?>').remove()"><span class="glyphicon glyphicon-remove"></span></a></div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-6">
                            <label style="float: left">Trạng thái:</label>
                            <div class="wrapper">
                                <input value="0" type="hidden" name="status" <?=@($object['status'] == 0? 'checked':'')?> />
                                <input type="checkbox" value="1" id="status" name="status" class="slider-toggle" <?=@($object['status'] == 1 || !isset($object)? 'checked':'')?> />
                                <label class="slider-viewport" for="status">
                                    <div class="slider">
                                        <div class="slider-button">&nbsp;</div>
                                        <div class="slider-content left"><span>On</span></div>
                                        <div class="slider-content right"><span>Off</span></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="wrap_btn">
                            <input type="hidden" name="id" value="<?=@$object['id']?>">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <a href="javascript:void(0)" class="cancel">Hủy bỏ</a>
                            <button type="submit" class="btn-save">Lưu</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection

@section('after_styles')
    <style>
        .frm_update .row {
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        var $warehouses_places_items = <?=json_encode($warehouses_places_items)?>;
        var group_categories = <?=json_encode($list_categories['parent'])?>;

        $(function(){
            $('#category_id').on('change', function(){
                var selected = $(this).val();
                if (selected) {
                    $.each(selected, function (k, cid) {
                        if (group_categories[cid] != undefined) {
                            $.each(group_categories[cid], function (i, new_cid) {
                                selected.push(new_cid);
                            });
                        }
                    });
                }
                $('#category_id').select2("val", selected || []);
            });

            init_select2('.select2');
            get_provinces('#frm_update .provinces');
            init_fm_number('.fm-number');
            $('.fm-number').each(function( index ) {
                $(this).val( numeral($(this).val()).format() );
            });
            
            $.each($warehouses_places_items,function(k,v){
                get_provinces(v+' .warehouse_place_provinces');    
            });

            $('.cancel').on('click',function(){
                $(this).closest('form')[0].reset();
                init_fm_number('.fm-number');
                $('.fm-number').each(function( index ) {
                    $(this).val( numeral($(this).val()).format() );
                });
                get_provinces('#frm_update .provinces');
                $.each($warehouses_places_items,function(k,v){
                get_provinces(v+' .warehouse_place_provinces');    
                });
            });

            $('.frm_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    phone:{
                        required:true,
                        minlength: 10,
                        maxlength: 11,
                    },
                    'category_id': 'required',
                    'province_id': 'required',
                    'district_id': 'required',
                    'ward_id': 'required',
                    'address': 'required',
                    // 'shipping_cost': 'required',
                },
                messages: {
                    name: "Nhập tên thương hiệu",
                    'phone': {
                        required: 'Nhập số điện thoại',
                        minlength: "Số điện thoại tối thiểu 10 số",
                        maxlength: "Số điện thoại tối đa 11 số",
                    },
                    'category_id': 'Chọn danh mục sản phẩm',
                    'province_id': 'Chọn Tỉnh / Thành phố',
                    'district_id': 'Chọn Quận / Huyện',
                    'ward_id': 'Chọn Phường / Xã',
                    'address': 'Nhập địa chỉ',
                    // 'shipping_cost': 'Nhập phí vận chuyển',
                },
                submitHandler: function(form) {
                    // do other things for a valid form
                    $('.fm-number').each(function( index ) {
                        $(this).val( numeral($(this).val()).value() );
                    });
                    var submit = true;
                    if($('.item_warehouse_places').length > 0){
                        $('.item_warehouse_places select').each(function(){
                            if($(this).hasClass('warehouse_place_provinces') || $(this).hasClass('warehouse_place_districts')) {
                                if (!$(this).val())
                                    submit = false;
                            }
                        });
                    }
                    if(!submit){
                        malert('Vui lòng chọn đầy đủ thông tin: Tỉnh / Thành phố, Quận / Huyện Cho địa điểm bán');
                        return false
                    }

                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = '<?=route("warehouse.index")?>';
                            });
                        } else {
                            alert_success(data.msg);
                            if (data.errors) {
                                $.each(data.errors, function (key, msg) {
                                    $('#'+key+'-error').html(msg).show();
                                });
                            }
                        }
                    });

                    return false;
                }
            });

            $(document).on('click','#add_location',function (e) {
                e.preventDefault();
                set_html_add_address_place();
            });

            function set_html_add_address_place() {
                var item = $.now();
                var id = "#item_" + item;
                html = '<div class="wrap_select row item_warehouse_places" id="item_' + item + '" style="margin-bottom:15px;" >';
                html += '<div class="col-md-3">';
                html += '<select name="warehouses_places[' + item + '][province_id]" class="form-control warehouse_place_provinces" data-id="" onchange="get_districts_by_province(this)" data-destination="' + id + ' .warehouse_place_districts">';
                html += '<option value="">Chọn Tỉnh / Thành phố</option>';
                html += '</select>';
                html += '<i style="right:20px" class="fa fa-angle-down" aria-hidden="true"></i>';
                html += '</div>';
                html += '<div class="col-md-3">';
                html += '<select name="warehouses_places[' + item + '][district_id]" class="form-control warehouse_place_districts" data-id="" onchange="get_wards_by_district(this)" data-destination="' + id + ' .warehouse_place_wards">';
                html += '<option value="">Chọn Quận / Huyện</option>';
                html += '</select>';
                html += '<i style="right:20px" class="fa fa-angle-down" aria-hidden="true"></i>';
                html += '</div>';
                html += '<div class="col-md-3">';
                html += '<select name="warehouses_places[' + item + '][ward_id]" data-id="" class="form-control warehouse_place_wards">';
                html += '<option value="">Chọn Phường / Xã</option>';
                html += '</select>';
                html += '<i style="right:20px" class="fa fa-angle-down" aria-hidden="true"></i>';
                html += '</div>';
                html += '<div class="col-md-3" style="cursor:pointer;padding:10px;"><a href="javascript:void(0)" onclick="$(\'' + id + '\').remove()"><span class="glyphicon glyphicon-remove"></span></a></div>';
                html += '</div>';
                $('#show-address-list').prepend(html);
                get_provinces(id+' .warehouse_place_provinces');

                return html;
            }
        })
    </script>
@endsection