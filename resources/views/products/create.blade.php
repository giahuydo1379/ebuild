@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view view_product create_product">
            <div class="header">
                @if(!empty($data) && $event == 'edit')
                <h2 class="title">Chi tiết sản phẩm <span>/ < <?=@$data['product_code']?> > < <?=@$data['product']?> ></span></h2>               
                @else
                <h2 class="title">Tạo mới sản phẩm</h2>
                @endif
                <a href="<?=route('products.index')?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
                @if(!empty($data) && $event == 'edit')               
                <a href="<?=route('products.create',['parent_id' => $data['product_id']])?>" style="margin-right: 5px;">  Sao chép sản phẩm</a>
                @endif
            </div>
            @if(!empty($data) && $event == 'edit')
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#1" data-toggle="tab">Thông tin chung</a>
                </li>
                <li><a href="#2" data-toggle="tab">Lịch sử sản phẩm</a>
                </li>
                <li><a href="#3" data-toggle="tab">S.E.O sản phẩm</a>
                </li>
                <li><a href="#4" data-toggle="tab">Sản phẩm bán kèm</a>
                </li>
            </ul>
            @endif
            <div class="body tab-content">


                <div class="content_product tab-pane active" id="1">
                    <form class="frm_product" action="<?=route('products.store')?>" method="post">
                    <div class="part_create">
                        <div class="header-panel">
                            <h3 class="title_supplier">Hình ảnh sản phẩm</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p><b>Hình chính</b></p>
                                <div class="image-upload">
                                    <label for="file-input1">
                                        <img class="preview-image-main"
                                             src="<?=!empty($data['image_location'])?$data['image_url'].$data['image_location']:'/html/assets/images/img_upload.png'?>" alt=""/>

                                        <div class="wrap-bg" style="display: none;">
                                            <img class="display btn-loadfile browse-image" src="/html/assets/images/icon-camera.png" data-target="image_location">
                                        </div>
                                    </label>
                                    <input type="hidden" name="image_url" id="image_url" value="<?=@$data['image_url']?>">
                                    <input type="hidden" value="<?=@$data['image_location']?>" name="image_location"
                                           id="image_location" data-preview=".preview-image-main" data-url="#image_url">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <p><b>Hình phụ</b></p>
                                <div class="wrap_images image-upload">
                                    <label>
                                        <img src="/html/assets/images/img_upload.png" alt="">
                                        <div class="wrap-bg" style="display: none;">
                                            <img class="display btn-loadfile browse-image-list" src="/html/assets/images/icon-camera.png" data-target="#list" alt="your image">
                                        </div>
                                    </label>

                                    <output id="list" data-index="<?=@(count($data['images']['details']))?>">
                                        @if(!empty($data['images']['details']))
                                            @foreach($data['images']['details'] as $item)
                                                <span>
                                                <img src="<?=$item['image_url'].$item['file_name']?>" alt="">
                                                <input type="hidden" name="image_details[<?=$item['id']?>]" value="<?=$item['file_name']?>">
                                            </span>
                                            @endforeach
                                        @endif
                                    </output>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <b>Lưu ý:</b>
                                    <ul>
                                        <li>- Hình có dạng: <b class="orange">.png</b>; <b class="orange">.jpeg</b>; <b class="orange">.jpg</b></li>
                                        <li>- Kích thước màn hình: <b class="orange">1024px x 1024px</b></li>
                                        <li>- Dung lượng hình: <b class="orange">350Kb</b></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li>- Số hình chính: <b class="orange">01 hình</b></li>
                                        <li>- Số hình phụ: Tối đa <b class="orange">5 hình</b></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </div>
                    <div class="part_information">
                        <div class="part_information">
                            <h3 class="title_supplier">Thông tin sản phẩm</h3>
                            <div class="col-md-6">
                                <ul>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Tên sản phẩm</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="product" value="<?=@$data['product']?>" placeholder="Nhập tên sản phẩm" value="Bộ 03 Hộp Thủy Tinh Và Túi Giữ Nhiệt Hàn Qu...">
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Tên Alias</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="" placeholder="" value="<?=@(!empty($data['alias'])?$data['alias']:str_slug($data['product'],'-'))?>" disabled>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>SKU ({{config('app.name')}})</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="product_code" id="product_code" placeholder="Nhập mã SKU. Vd: AD12DAR8914" value="<?=@$data['product_code']?>">
                                            <label class="error" id="product_code-error" style="display: none"></label>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Thương hiệu:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control select2" id="brand_id" name="brand_id" data-placeholder="Chọn thương hiệu">
                                                <option value=""></option>
                                                @foreach($list_brands as $item)
                                                    <option value="<?=$item['brand_id']?>" <?=@($item['brand_id'] == $data['brand_id']?'selected':'')?> ><?=$item['name']?></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Trạng thái</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="wrapper">
                                                <input type="hidden" name="status" value="D"/>
                                                <input type="checkbox" id="status" name="status" value="A" class="slider-toggle" <?=@($item['status']=='A'?'checked':'')?> />
                                                <label class="slider-viewport" for="status">
                                                    <div class="slider">
                                                        <div class="slider-button">&nbsp;</div>
                                                        <div class="slider-content left"><span>On</span></div>
                                                        <div class="slider-content right"><span>Off</span></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Danh mục chính:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control select2" name="category_id" id="category_id" data-placeholder="Chọn danh mục">
                                                <option value=""></option>
                                                @foreach($list_categories as $item)
                                                    <option <?=@($item['category_id'] == $data['category_id']?'selected':'') ?> value="<?=$item['category_id']?>" ><?=$item['category']?></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Danh mục phụ:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control select2" multiple name="sub_category[]" id="sub_category" data-placeholder="Chọn danh mục phụ">
                                                @foreach($list_categories as $item)
                                                    <option <?=@(in_array($item['category_id'], $data['sub_category'])?'selected':'') ?> value="<?=$item['category_id']?>" ><?=$item['category']?></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Số lượng sản phẩm:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="amount" placeholder="Nhập số lượng sản phẩm" value="<?=@$data['amount']?>">
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Khối lượng sản phẩm:</label>
                                        </div>
                                        <div class="col-md-7" style="padding-right: 5px">
                                            <input type="text" name="weight" placeholder="Nhập khối lượng sản phẩm" value="<?=@$data['weight']?>"> 
                                        </div>
                                        <p style="font-size: 16px">Kg</p>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Đơn vị tính:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="wrap_select">
                                            <select class="form-control" name="unit_id">
                                                <option value="">chọn đơn vị</option>
                                                @if(!empty($list_units))
                                                @foreach($list_units as $key => $item)
                                                    <option value="<?=$item['id']?>"<?php if(!empty($data['unit_id']) && ($item['id'] == $data['unit_id'])) echo 'selected'; ?> ><?=$item['name']?></option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Giá thị trường:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="list_price" name="list_price" class="fm-number"
                                                   placeholder="Nhập giá sản phẩm trên thị trường" value="<?=@$data['list_price']?>">
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Giá bán ({{config('app.name')}}):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="price" name="price" class="fm-number"
                                                   placeholder="Nhập giá bán trên {{config('app.name')}}" value="<?=@$data['price']?>">
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <span><b>Giá bán ({{config('app.name')}})</b> <br> áp dụng từ ngày:</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="time">
                                                <div class="input-group date">
                                                    <input class="form-control datepicker" name="date_start_sell" size="20" type="text"
                                                           value="<?=@$data['date_start_sell']?>" placeholder="Thời gian : Ngày-Tháng-Năm ">
                                                    <span class="fa fa-calendar"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <span><b>Sản phẩm cha:</b></span>
                                        </div>
                                        <div class="col-md-8">
                                            {!! Form::select("parent_id", ['' => ''] + $parent_options, @$data['parent_id'],
                                            ['id' => 'parent_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn sản phẩm cha']) !!}
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="part_information">
                        <h3 class="title_supplier">Nhà cung cấp và địa điểm bán</h3>
                        <div class="col-md-6">
                            <ul>
                                <li class="row">
                                    <div class="col-md-4">
                                        <label>Nhà cung cấp</label>
                                    </div>
                                    <div class="col-md-8">
                                        {!! Form::select("supplier_id", ['' => ''] + $suppliers, @$data['supplier_id'],
                                                ['class' => 'form-control select2', "id" => "supplier_id", "data-placeholder"=>"Chọn nhà cung cấp"]) !!}
                                    </div>
                                </li>
                                
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul>
                                <li class="row">
                                    <div class="col-md-4">
                                        <label>Kho hàng</label>
                                    </div>
                                    <div class="col-md-8">
                                        {!! Form::select("warehouse_id", [], null,
                                                ['class' => 'form-control select2', 'data-id' => @$data['warehouse_id'],
                                                "id" => "warehouse_id", "data-placeholder"=>"Chọn kho hàng"]) !!}
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                             <button type="button" id="add_location" class="btn_edit" style="width: unset; margin-bottom:20px">Thêm địa điểm</button>
                        </div>
                        <div class="col-md-12" id="show-address-list"> 
                            <?php
                                $products_places_items = [];
                            ?>                           
                            @if(!empty($products_places))
                                @foreach($products_places as $key => $place)
                                <?php 
                                    $products_places_item = '#pp_'.$key;
                                    $products_places_items[] = $products_places_item;
                                ?>
                                <div class="row item_products_places" id="pp_<?=$key?>">
                                    <div class="col-md-3" style="padding:0 20px 0 0;">
                                        <select class="provinces select2" data-id="{{@$place['province_id']}}" name="products_places[<?=$key?>][province_id]" class="form-control select2" data-placeholder="Chọn Tỉnh / Thành phố" onchange="get_districts_by_province(this)" data-destination="<?=$products_places_item?> .districts">
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" style="padding:0 20px 0 0;">
                                        <select data-id="{{@$place['district_id']}}" name="products_places[<?=$key?>][district_id]" class="form-control districts"
                                                onchange="get_wards_by_district(this)" data-destination="<?=$products_places_item?> .wards">
                                            <option value="">Chọn Quận / Huyện</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3" style="padding:0 20px 0 0;">
                                        <select data-id="{{@$place['ward_id']}}" name="products_places[<?=$key?>][ward_id]" class="form-control wards">
                                            <option value="">Chọn Phường / Xã</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3"><a href="javascript:void(0)" onclick="$('<?=$products_places_item?>').remove()"><span class="glyphicon glyphicon-remove"></span></a></div>
                                </div>
                                @endforeach
                            @endif
                            {{-- <table width="100%">
                                <tr>
                                    <th class="text-center" width="30%">Tỉnh/ Thành phố</th>
                                    <th class="text-center" width="30%">Quận/ Huyện</th>
                                    <th class="text-center" width="30%">Phường/ Xã</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="padding:0 40px;">
                                            <select  data-id="{{@$data['province_id']}}" name="province_id" class="form-control select2 provinces" data-placeholder="Chọn Tỉnh / Thành phố"
                                                onchange="get_districts_by_province(this)" data-destination=".frm_product .districts">
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                        </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="padding:0 40px;">
                                            <select data-id="{{@$data['district_id']}}" name="district_id" class="form-control districts"
                                                    onchange="get_wards_by_district(this)" data-destination=".frm_product .wards">
                                                <option value="">Chọn Quận / Huyện</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>Xóa</td>
                                </tr>
                            </table> --}}
                        </div>
                    </div>
                    <div class="part_property">
                        <h3 class="title_supplier">Thuộc tính sản phẩm</h3>
                        <div class="col-md-6">
                            <div class="top_box row">
                                <div class="col-md-3 no-padding">
                                    <b>Chọn thuộc tính: </b>
                                </div>
                                <div class="col-md-9">
                                    {!! Form::select("select_feature_id", ['' => '']+($features['options']??[]), null,
                                        ['id' => 'select_feature_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo thuộc tính']) !!}
                                </div>
                            </div>
                            <div class="bottom_box row">
                                <div class="content collapse in">
                                    <div class="scrollbar scrollbar_features"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="subtitle">Các thuộc tính đã chọn</p>
                            <div class="property_choosed selected_variants">
                            <?php foreach ($features_values as $fi => $fvalues) {
                                if (isset($features['options'][$fi])) {
                                ?>
                                <div class="wrap_property selected_variant <?=$fi?>">
                                    <p>
                                        <b class="title"><?=$features['options'][$fi]?></b>
                                        <input id="is_show_frontend_<?=$fi?>" class="custom" type="checkbox" name="variant_ids[<?=$fi?>][is_show_frontend]" value="1" <?=$fvalues[0]['is_show_frontend'] == 1?'checked':''?> >
                                        <label for="is_show_frontend_<?=$fi?>">Hiển thị trang chi tiết sản phẩm</label>
                                    </p>
                                    
                                <?php foreach ($fvalues as $fv) { ?>
                                    <span><input type="hidden" name="variant_ids[<?=$fi?>][data][]" value="<?=$fv['variant_id']?>"><?=$fv['variant']?>
                                        <i class="fa fa-times" aria-hidden="true" data-id="<?=$fv['variant_id']?>"></i></span>
                                <?php } ?>
                                </div>
                            <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="part_pro_vanhanh">
                        <h3 class="title_supplier">Thuộc tính vận hành</h3>
                        <div class="content">
                            <b>Nội dung</b>
                            <div class="wrap-pro row">
                                <div class="col-md-3">
                                    <input type="hidden" name="has_gift" value="0">
                                    <input class="custom" type="checkbox" name="has_gift" id="has_gift" value="1" <?=@$data['has_gift']?'checked':''?>>
                                    <label for="has_gift">Hiển thị icon quà tặng</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="is_new" value="0">
                                    <input class="custom" type="checkbox" name="is_new" id="is_new" value="1" <?=@$data['is_new']?'checked':''?>>
                                    <label for="is_new">Hàng mới</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="is_good_price" value="0">
                                    <input class="custom" type="checkbox" name="is_good_price" id="is_good_price" value="1" <?=@$data['is_good_price']?'checked':''?>>
                                    <label for="is_good_price">Giá tốt</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input_information">
                        <div class="row">
                            <div class="col-md-12" style="padding: 0px;">
                                <h3 class="title_supplier">Giới thiệu sản phẩm</h3>
                                <div class="style-textarea">
                                    <textarea name="full_description" id="full_description" class="ckeditor"
                                              placeholder="Nhập thông tin giới thiệu cho sản phẩm ...."><?=str_replace('src="uploads', 'src="/uploads', @$data['full_description'])?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 25px;">
                            <div class="col-md-6">
                                <h3 class="title_supplier">Mô tả ngắn sản phẩm</h3>
                                <div class="style-textarea">
                                    <textarea name="short_description" id="short_description"
                                              class="ckeditor" placeholder="Nhập thông tin giới thiệu cho sản phẩm ...."><?=@$data['short_description']?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="title_supplier">Thông số sản phẩm</h3>
                                <div class="right_corner" style="right: 0px;">
                                    <span>SKU hiển thị: </span>
                                    <span class="code"><?=isset($data['product_code']) ? $data['product_code'] : '&nbsp;'?></span>
                                </div>
                                <div class="style-textarea">
                                    <textarea name="specifications" id="specifications"
                                              class="ckeditor" placeholder="Nhập thông số sản phẩm ...."><?=@$data['specifications']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="product_id" value="<?=@$product_id?>">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button type="submit" class="btn_edit col-md-2 col-md-offset-10" name="form" value="product">Lưu sản phẩm</button>
                    <!-- end -->
                    </form>
                </div>

                <!-- end tab 1 -->
                <div class="tab-pane" id="2">
                    <div class="wrap-table">
                        <div class="table-status">
                            <div class="header_table">
                                <div class="col-md-5">
                                    <div class="col-md-8">
                                        <span>Thời gian</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>User</span>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="col-md-4">
                                        <span>Thao tác</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>Chi tiết</span>
                                    </div>
                                </div>
                            </div>
                            <ul>
                        @if(isset($audits))
                            @foreach($audits as $item)
                                <li class="row">
                                    <div class="col-md-5">
                                        <div class="col-md-8"><span><?=$item['created_at']?></span></div>
                                        <div class="col-md-4"><span><?=$item['full_name']?></span></div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="col-md-4"><span><?=$event_options[$item['event']]?></span></div>
                                        <div class="col-md-8 {{$item['event']}}">
                                        @if ($item['event']!='created')
                                            <?php
                                            $old_values = json_decode($item['old_values'], 1);
                                            $new_values = json_decode($item['new_values'], 1);
                                            foreach ($new_values as $nk => $nv) {
                                                if (!array_key_exists($nk, $field_name_options)
                                                || $nv==@$old_values[$nk]) continue;
                                                ?>
                                            <span><?=isset($field_name_options[$nk])?$field_name_options[$nk]:$nk?> : <?=(isset($old_values[$nk])?$old_values[$nk].' -> ':'').$nv?>, </span>
                                            <?php } ?>
                                        @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end tab 2 -->

                <div class="tab-pane" id="3">
                    <form class="frm_seo" action="<?=route('products.update_seo')?>" method="post">
                    <div class="content_seo col-md-7 col-md-offset-2">
                        <ul>
                            <li class="row">
                                <div class="col-md-3">
                                    <label>Tên sản phẩm:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="product" placeholder="Tên sản phẩm" value="<?=@$data['product']?>">
                                </div>
                            </li>
                            <li class="row">
                                <div class="col-md-3">
                                    <label>Tiêu đề:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="page_title" placeholder="Nhập tiêu đề" value="<?=@$data['page_title']?>">
                                </div>
                            </li>
                            <li class="row">
                                <div class="col-md-3">
                                    <label>Mô tả:</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea placeholder="Mô tả sản phẩm" name="meta_description"><?=@$data['meta_description']?></textarea>
                                </div>
                            </li>
                            <li class="row">
                                <div class="col-md-3">
                                    <label>Từ khóa</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="meta_keywords" placeholder="Nhập từ khóa S.E.O cho sản phẩm" value="<?=@$data['meta_keywords']?>">
                                </div>
                            </li>
                        </ul>
                        <div class="col-md-3 col-md-offset-3">
                            <input type="hidden" name="product_id" value="<?=@$data['product_id']?>">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="submit" class="btn-save" name="form" value="seo">Lưu</button>
                        </div>
                    </div>
                    </form>
                </div>
                <!-- end tab 3 -->
                <div class="tab-pane" id="4">
                    <div class="content-add-product money">
                        <div class="search">
                            <div class="ip_">
                                <input type="text" placeholder="Tìm, nhập tên sản phảm hoặc mã SKU" class="search search_product">
                            </div>
                            <div class="content collapse in search_result_content">
                                <div class="scrollbar">
                                    <ul class="list_result_search"></ul>
                                </div>
                            </div>
                            <button class="btn btn-primary btn_add_product_sold">Thêm sản phẩm</button>
                        </div>

                        <ul class="list-product list_product">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    <style>
        label.error{
            border: none !important;
        }
        .view_product .content_product .part_create .col-md-9 #list span img {
            width: 140px;
        }
    </style>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>

    <script type="text/javascript">
        var product_id = '';
        var $products_places_items = <?=json_encode($products_places_items)?>;
        @if(isset($product_id))
            product_id = <?=$product_id?>;
        @endif
        $(function(){
            init_datepicker('.datepicker');
            init_select2('.select2');
            init_fm_number('.fm-number');
            $('.fm-number').each(function( index ) {
                $(this).val( numeral($(this).val()).format() );
            });

            $('textarea.ckeditor').ckeditor();

            $('#product_code').on('change', function () {
                $('.input_information .code').html($(this).val());
            });

            $('.multiselect').multiselect();

            $('.cancel').on('click',function(){
                $(this).closest('form')[0].reset();
            });
            $.each($products_places_items,function(k,v){
                get_provinces(v+' .provinces');    
            });

            $('#supplier_id').on('change', function () {
                var html = '<option value="" ></option>';
                if (!$(this).val()) {
                    $('#warehouse_id').html(html).trigger('change');
                    return;
                }

                ajax_loading(true);
                $.get('<?=route('supplier.get-warehouses')?>', {supplier_id: $(this).val()}, function (res) {
                    ajax_loading(false);
                    if (res.options) {
                        $.each(res.options, function (key, item) {
                            html += '<option value="' + key + '" >' + item + '</option>';
                        });
                    }

                    $('#warehouse_id').html(html).val($('#warehouse_id').attr('data-id')).trigger('change');
                });
            });
            $('#supplier_id').trigger('change');

            $('#category_id12').on('change', function () {
                ajax_loading(true);
                $.get('<?=route('features.get-feature-options')?>', {category_id: $(this).val()}, function (res) {
                    ajax_loading(false);
                    var html = '<option value="" ></option>';
                    if (res.options) {
                        $.each(res.options, function (key, item) {
                            html += '<option value="' + key + '" >' + item + '</option>';
                        });
                    }

                    $('#select_feature_id').html(html).trigger('change');
                });
            });

            @if (isset($data['category_id']))
            $('#category_id').trigger('change');
            @endif

            $('#select_feature_id').on('change', function () {
                if (!$(this).val()) {
                    $('.scrollbar_features').replaceWith('<div class="scrollbar scrollbar_features"></div>');
                    return false;
                }
                ajax_loading(true);
                $.get('<?=route('features.get-feature-variants')?>', {feature_id: $(this).val()}, function (res) {
                    ajax_loading(false);
                    var html = '';
                    var checked = '';
                    $.each(res.variants, function( index, item ) {
                        checked = $('.selected_variant input[value="'+item.id+'"]').length > 0 ? ' checked="checked"' : '';

                        html += '<div class="col-md-6">\n' +
                            '       <p>\n' +
                            '           <input class="custom select_variant" type="checkbox" id="variant_'+item.id+'" ' +
                            ' data-feature="'+item.feature_id+'" value="'+item.id+'"'+checked+'/>\n' +
                            '           <label for="variant_'+item.id+'">'+item.variant+'</label>\n' +
                            '       </p>\n' +
                            '   </div>';
                    });

                    $('.scrollbar_features').replaceWith('<div class="scrollbar scrollbar_features">'+html+'</div>');

                    init_scrollbar('.scrollbar_features');
                });
            });

            $(document).on('click','.select_variant',function(){
                var feature_id = $(this).attr('data-feature');
                if ($('.select_variant:checked').length < 1) {
                    $('.selected_variant.'+feature_id).remove();
                    return;
                }
                var html = '<div class="wrap_property selected_variant '+feature_id+'">\n' +
                    '<p><b class="title">'+$('#select_feature_id option:selected').text()+'</b>';
                    html += '<input class="custom" id="is_show_frontend_'+feature_id+'" type="checkbox" name="variant_ids['+feature_id+'][is_show_frontend]"> <label for="is_show_frontend_'+feature_id+'">Hiện thị trang chi tiết sản phẩm</label></p>';
                $( '.select_variant:checked' ).each(function( index ) {
                    html += '<span><input type="hidden" name="variant_ids['+feature_id+'][data][]" value="'+$(this).val()+'">'
                        +$(this).parent().find('label').html()
                        +' <i class="fa fa-times" aria-hidden="true" data-id="'+$(this).val()+'"></i></span>\n';
                });
                html += '</div>';

                if ($('.selected_variant.'+feature_id).length > 0) {
                    $('.selected_variant.'+feature_id).replaceWith(html);
                } else {
                    $('.selected_variants').append(html);
                }
            });

            $(document).on('click','.selected_variant .fa-times',function(){
                var tmp = $(this).attr('data-id');
                $('#variant_'+tmp).click();

                tmp = $(this).closest('div');
                $(this).closest('span').remove();
                if (tmp.find('span').length < 1) {
                    tmp.remove();
                }
            });

            $(document).on('click','#list span',function(){
               $(this).remove();
            });

            $('.frm_product').validate({
                ignore: ".ignore",
                rules: {
                    image_location: "required",
                    product: "required",
                    product_code: "required",
                    category_id: "required",
                    amount: "required",
                    price: "required",
                },
                messages: {
                    image_location: "Chọn hình sản phẩm",
                    product: "Nhập tên sản phẩm",
                    product_code: "Nhập SKU sản phẩm",
                    category_id: "Chọn danh mục",
                    amount: "Nhập số lượng sản phẩm",
                    price: "Nhập giá bán",
                },
                submitHandler: function(form) {
                    $('.fm-number').each(function( index ) {
                        $(this).val( numeral($(this).val()).value() );
                    });
                    var submit = true;
                    if($('.item_products_places').length > 0){
                        $('.item_products_places select').each(function(){
                            if($(this).hasClass('provinces') || $(this).hasClass('districts')) {
                                if(!$(this).val())
                                    submit = false;
                            }
                        });
                    }
                    if(!submit){
                        malert('Vui lòng chọn đầy đủ thông tin: Tỉnh / Thành phố, Quận / Huyện Cho địa điểm bán');
                        return false;
                    }
                    //form.submit(); return true;
                    // do other things for a valid form
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = '<?=route('products.index')?>';
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

            $('.frm_seo').validate({
                ignore: ".ignore",
                rules: {
                    product: "required",
                },
                messages: {
                    product: "Nhập tên sản phẩm",

                },
                submitHandler: function(form) {
                    // do other things for a valid form
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = data.redirect;
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

            if(product_id){
                ajax_loading(true);
                $.post('/products/get-list-products-sold',{product_id:product_id},function(res){
                    ajax_loading(false);
                    if(res.result){
                        $.each(res.result,function(k,v){
                            $('.list_product').append(get_html_add_product(v));
                        })
                    }
                })    
            }
            

            var _interval_search_product = null;
            var searchRequest = null;
            $(document).on('keyup','.search_product',function(){
                var kw = $(this).val();
                if(kw.length < 3) return false;

                if (_interval_search_product) clearInterval(_interval_search_product);

                _interval_search_product = setInterval(function() {
                    clearInterval(_interval_search_product);

                    var url = '/products/search-product';
                    if (searchRequest != null)
                        searchRequest.abort();

                    var pids = [];
                    $( ".item_product" ).each(function( index ) {
                        pids.push($(this).attr('data-id'));
                    });

                    searchRequest = $.post(url, {kw: kw, except_pids: pids}, function (res) {
                        if (res.rs == 1) {
                            $('.list_result_search').html(get_html_result_product_search(res.data)).show();
                            $(".search_result_content").show();
                        } else {
                            $('.list_result_search').html('').show();
                            $(".search_result_content").hide();
                        }
                    }, 'json');
                }, 500);
            });
            $('.btn_add_product_sold').on('click',function(){
                $('input.search_product').focus();
            });
            $(document).on('click','.select_product',function(){
                $(".search_result_content").hide();
                var pid     = $(this).data('id');                
                ajax_loading(true);
                $.post('/products/store-products-sold', {product_sold_id: pid, product_id: product_id}, function (res) {
                    ajax_loading(false);

                    if (res.rs) {
                        alert_success(res.msg, function () {
                            var product = res.product;

                            if($('.item_product_'+pid).length > 0);
                            $('.list_product').append(get_html_add_product(product));
                            $('.search_product').val('');
                        });
                        
                    } else {
                        malert(res.msg);
                    }
                },'json');
            });
            $(document).on('click','.delete_action',function () {
                var product_sold_id = $(this).data('id');
                confirm_delete("Bạn có muốn xóa sản phẩm này không?", function () {
                    $.post('/products/delete-products-sold', {product_sold_id: product_sold_id, product_id: product_id}, function(res){
                        if(res.rs){
                            alert_success(res.msg, function () {
                                $('.item_product_'+product_sold_id).remove();
                            });    
                        }else{
                            malert(res.msg);
                        }
                        
                    });
                });
            });

            $(document).on('click','#add_location',function () {
                // var province = $( "#provinces" ).val();
                // var district = $( "#districts" ).val();
                // var ward = $( "#wards" ).val();
                // $('#show-address-list').append(
                //     get_html_add_address_place(data)
                // );
                set_html_add_address_place()
            });

            function set_html_add_address_place() {
                var item = $.now();
                var id = '#item_'+item;
                html = '<div class="row item_products_places" id="item_'+item+'">';
                html += '<div class="col-md-3" style="padding:0 20px 0 0;">';
                html += '<select name="products_places['+item+'][province_id]" class="form-control select2 provinces" data-placeholder="Chọn Tỉnh / Thành phố" onchange="get_districts_by_province(this)" data-destination="'+id+' .districts">';
                html += '<option value="">Chọn Tỉnh / Thành phố</option>';
                html += '</select>';
                html += '</div>';
                html += '<div class="col-md-3" style="padding:0 20px 0 0;">';
                html += '<select data-id="" name="products_places['+item+'][district_id]" class="form-control districts" onchange="get_wards_by_district(this)" data-destination="'+id+' .wards">';
                html += '<option value="">Chọn Quận / Huyện</option>';
                html += '</select>';
                html += '</div>';
                html += '<div class="col-md-3" style="padding:0 20px 0 0;">';
                html += '<select data-id="" name="products_places['+item+'][ward_id]" class="form-control wards">';
                html += '<option value="">Chọn Phường / Xã</option>';
                html += '</select>';
                html += '</div>';
                html += '<div class="col-md-3"><a href="javascript:void(0)" onclick="$(\''+id+'\').remove()"><span class="glyphicon glyphicon-remove"></span></a></div>';
                html += '</div>';
                $('#show-address-list').prepend(html);
                get_provinces(id+' .provinces');
                return html;
            }
            

            function get_html_add_product(data){
                html = '<li class="item_product_'+data.product_id+'">';
                html += '    <div class="wrap-img item_product " data-id="'+data.product_id+'">';
                html += '        <img src="'+data.image+'" alt="">';
                html += '    </div>';
                html += '    <div class="information">';
                html += '        <p>'+data.name+'</p>';
                html += '        <p class="price">';
                html += '            <span class="sale-price">'+numeral(data.price).format()+'đ </span>';
                html += '            <span class="real-price">'+numeral(data.list_price).format()+'đ</span>';
                html += '        </p>';
                html += '    </div>';
                html += '    <a href="#" class="tooltip pull-right delete_action" data-id="'+data.product_id+'" >';
                html += '        <i class="icon-delete" title="Xóa">&nbsp;</i>';
                html += '        <span class="tooltiptext">Xoá</span>';
                html += '    </a>';
                html += '</li>';

                return html;
            }

            function get_html_result_product_search(data){
                var html = '';
                $.each(data,function(k,v){
                    html += '<li class="row select_product select_pid_'+v.product_id+'" data-id="'+v.product_id+'" >';
                    html +=         '<div class="col-md-1">';
                    html +=             '<div class="wrap-img">';
                    html +=                 '<img src="'+v.image+'" alt="">';
                    html +=             '</div>';
                    html +=         '</div>';
                    html +=         '<div class="col-md-8">';
                    html +=             '<span>'+v.product+'</span>';
                    html +=         '</div>';
                    html +=         '<div class="col-md-3">';
                    html +=             '<b class="orange price">'+numeral(v.price).format('0,0')+' <sup>đ</sup></b>';
                    html +=         '</div>';
                    html +=     '</li>';
                });            
                return html;
            }

            $( 'li.row div.updated' ).each(function( index ) {
                console.log($(this).html().trim());
                if (!$(this).html().trim()) {
                    $(this).closest('li').remove();
                }
            });
        })
    </script>
@endsection