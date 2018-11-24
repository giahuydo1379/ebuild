@extends('layouts.master')

@section('content')
    <style>
        .list_product .row_gift {
            border-bottom: 1px solid #e1e1e1;
        }
        .list_product .row_gift:last-child {
            border-bottom: none;
        }
    </style>
<div class="col-md-">
    <div class="wrap_view">
        <div class="header">
            @if(!empty($object))
            <h2 class="title">Chỉnh sửa đơn hàng <span>/#<?=$object['validation_code']?></span></h2> <span class="time">- <?=date('H:i d/m/Y',$object['timestamp'])?></span>
            @endif
            <ul class="top-action">
                <li><a href="javascript:void(0)"><i class="icon icon-link">&nbsp</i> <span>Web</span></a></li>
            @if(isset($object))
                <li><a target="_blank" href="<?=route('order.print', ['id' => $object['order_id']])?>"><i class="icon icon-print">&nbsp</i> <span>In</span></a></li>
            @endif
                <li><a href="javascript:void(0)" id="save_order_top"><i class="icon icon-save">&nbsp</i> <span>Lưu</span></a></li>
            </ul>
        </div>
        <div class="first_step" id="first_step">
            <div class="title-list-product">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="col-md-5"></div>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="box top_detail">
                <div class="wrap list_product">
                <?php
                    $total_weight = 0;
                    $list_cart = [];
                ?>
                @if(isset($object) && !empty($orderDetail[$object['order_id']]))
                @foreach($orderDetail[$object['order_id']] as $detail)
                <?php 
                    if($detail['product_type'] == 'S'){
                        $detail['name'] = $detail['product'];
                        $list_service[$detail['product_id']] = $detail;
                        continue;
                    }
                    $extra = unserialize($detail['extra']);
                    $total_weight += $detail['weight'];

                    $list_cart[$detail['product_id']] = $detail;
                    $list_cart[$detail['product_id']]['promotion'] = '';
                ?>

                    <div class="row item_product item_product_<?=$detail['product_id']?>" data-id="<?=$detail['product_id']?>"
                         data-price="<?=$detail['price']?>">
                        <div class="col-md-6">
                            <div class="col-md-1">
                                <div class="wrap-img">
                                    <img src="<?=$detail['image']?>" alt="">
                                </div>
                            </div>
                            <div class="col-md-11">
                                <a target="_blank" href="<?=route('products.edit', ['id' => $detail['product_id']])?>"><?=$detail['product'].' - '.$detail['product_code']?></a>
                                <span class="number-in-store">Còn <?=$detail['number_in_store']?> sản phẩm</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-5 price-number">
                                <p><?=number_format($detail['price'])?>₫ <span>X</span></p>
                                <div class="wrap-input">
                                    <input type="number" class="product_amount" name="amount" value="<?=$detail['amount']?>">
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-md-3 div-discount">
                                <input type="text" class="frm-number" name="discount" value="<?=number_format($detail['discount'])?>">
                            </div>
                            <div class="col-md-3">
                                <p class="total"><?=number_format($detail['price'] * $detail['amount'] - $detail['discount'])?> <sup>đ</sup></p>
                            </div>
                            <div class="col-md-1 remove-product">
                                <i class="icon-trash"></i>
                                <i class="icon-trash-hover"></i>
                            </div>
                        </div>

                    </div>
                    <div class="row_gift gift_<?=$detail['product_id']?>">
                    @if(!empty($extra['product_options_value']))
                    <?php
                        foreach($extra['product_options_value'] as $product_options_value){
                            $list_cart[$detail['product_id']]['promotion'] .= '<div class="row gift"><p><label for=""><i class="fa fa-gift" aria-hidden="true"></i> </label>'.$product_options_value['variant_name'].'</p></div>';
                        }
                        echo $list_cart[$detail['product_id']]['promotion'];
                    ?>
                    @endif
                    </div>

                @endforeach
                @endif
                </div>
                <!-- <div class="row gift_hight">
                    <div class="content">
                        <i class="fa fa-gift" aria-hidden="true"></i>
                        <b>Tặng <span>voucher mua hàng</span> trị giá <span>100.000đ</span> cho đơn hàng trị giá từ <span>500.000đ</span> trở lên</b>
                    </div>
                </div> -->
                <div class="row money">
                    <div class="col-md-6">
                        <input type="text" class="search_product" placeholder="Tìm, nhập tên sản phẩm hoặc mã SKU" id="search" data-action="<?=route('order.search-product')?>">
                        <i class="icon_search_add">&nbsp</i>
                        <div class="content collapse in search_result_content">
                            <div class="scrollbar">
                                <ul class="list_result_search">     
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">                       
                        <p>Tổng Tiền sản phẩm: <span class="subtotal_product price"> <?=@number_format($object['subtotal_product'])?> <sup>đ</sup></span></p>
                    </div>
                   
                </div>
            </div>
            <div class="wrap_btn">
                <a href="<?=route('order.index')?>">Hủy bỏ</a>
                <a href="javascript:void(0)" id="next1" type="button" class="btn_next">Tiếp tục</a>
            </div>
        </div>
        <!-- end first step -->
        <form id="form_step2">
        <div class="second_step" id="second_step">            
            <div class="row">                
                <div class="col-md-6 ">
                    <div class="header_step">
                        <h3>Dịch vụ thêm</h3>
                    </div>
                    <div class="list_service">
                        Không có dịch vụ thêm
                    </div>
                    
                </div>
                <div class="col-md-6">
                    <div class="header_step">
                        <h3>Thông tin thanh toán</h3>
                    </div>
                    <div>
                        <input type="hidden" class="subtotal_product" value="<?=@number_format($object['subtotal_service'])?>">
                        <p>Tổng tiền sản phẩm: <span class="subtotal_product price"> <?=@number_format($object['subtotal_product'])?> <sup>đ</sup></span></p>
                        <p>Tổng tiền dịch vụ: <span class="price subtotal_service"> <?=@number_format($object['subtotal_service'])?> <sup>đ</sup></span></p>
                        <p>Thành tiền: <span class="total_sum price"> <?=@number_format($object['subtotal'])?> <sup>đ</sup></span></p>
                        <p>Phụ phí: <span class="surcharge price"> <?=@number_format($object['surcharge'])?><sup>đ</sup></span></p>
                        <input type="hidden" id="shipping_cost" name="shipping_cost" value="<?=@number_format($object['shipping_cost'])?>">
                        <input type="hidden" id="subtotal" value="<?=@number_format($object['subtotal'])?>">
                        <p style="display: none"><b>Tổng cộng : <span class="orange total_pay price"> <?=@number_format($object['total'])?><sup>đ</sup></span></b></p>    
                    </div>       
                </div>
                
            </div>
        </div>
        <div class="second_step" id="three_step">            
            <div class="header_step">
                <i class="icon-ship-edit">&nbsp</i>
                <h3>Thông tin giao hàng</h3>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div style="display: none;" class="box">
                        <div class="header-box">
                            <p>Địa chỉ liên hệ và thanh toán</p>
                        </div>
                        <div class="body-box">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>Người nhận:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" value="Nguyễn Nhật Toàn">
                                </div>
                                <div class="col-md-2">
                                    <div class="wrap_select">
                                        <select class="form-control">
                                            <option>Nam</option>
                                            <option>Nữ</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>Điện thoại:</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" value="01698948506">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>Email:</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" value="nhattoannguyen89@gmail.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>Địa chỉ:</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="col-md-6">
                                        <div class="wrap_select">
                                            <select class="form-control">
                                                <option>Cà Mau</option>
                                                <option>Kiên Giang</option>
                                                <option>An Giang</option>
                                                <option>TP Hồ Chí Minh</option>
                                                <option>Hà Nội</option>
                                            </select>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="wrap_select">
                                            <select class="form-control">
                                                <option>Huyện Bình Thới</option>
                                                <option>Đang đặt hàng</option>
                                                <option>Hủy</option>
                                                <option>Kiểm tra đơn hàng</option>
                                                <option>Chờ đặt hàng</option>
                                            </select>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-10">
                                    <div class="col-md-6">
                                        <div class="wrap_select">
                                            <select class="form-control">
                                                <option>Xã Tân Lập Đông</option>
                                                <option>Đang đặt hàng</option>
                                                <option>Hủy</option>
                                                <option>Kiểm tra đơn hàng</option>
                                                <option>Chờ đặt hàng</option>
                                            </select>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" value="241, Ấp 9">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="header-box">
                            <p>Địa chỉ giao hàng</p>
                        </div>
                        <div class="body-box">
                            {{--<p>--}}
                                {{--<input type="checkbox" id="test9" data-toggle="collapse" data-target="#info" class="" aria-expanded="false"/>--}}
                                {{--<label for="test9">Địa chỉ thanh toán là địa chỉ giao hàng</label>--}}
                            {{--</p>--}}
                            <div class="similar collapse in" id="info" aria-expanded="false">
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <label>Người nhận:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input name="b_firstname" type="text" value="<?=@$object['b_firstname']?>">
                                        <label class="error" id="b_firstname-error"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <label>Điện thoại:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="b_phone" value="<?=@$object['b_phone']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <label>Email:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="email" value="<?=@$object['email']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <label>Địa chỉ:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="col-md-6">
                                            <select id="c_province_id" class="form-control provinces" onchange="get_districts_by_province(this, true)"
                                                    data-destination="select.districts" aria-invalid="false" data-id="<?=@$object['province_id']?>" name="province_id">
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="wrap_select">
                                                <select id="c_district_id" class="form-control districts" onchange="get_wards_by_district(this)" data-destination="select.wards" aria-invalid="false" data-id="<?=@$object['district_id']?>" name="district_id">
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-10">
                                        <div class="col-md-6">
                                            <div class="wrap_select">
                                                <select id="c_ward_id" class="form-control wards" name="ward_id" data-id="<?=@$object['ward_id']?>">
                                                </select>
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input id="c_address" type="text" name="b_address" value="<?=@$object['b_address']?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box" style="display: none">
                        <div class="header-box">
                            <p>Phương thức thanh toán</p>
                        </div>
                        <div class="body-box">
                            <p>
                                <input type="radio" name="choose" id="test1" />
                                <label for="test1">Thanh toán bằng tiền mặt khi giao hàng (COD)</label>
                            </p>
                            <p>
                                <input type="radio" name="choose" id="test2" />
                                <label for="test2">Thanh toán bằng thẻ ATM có đăng ký Internet Banking</label>
                            </p>
                            <p>
                                <input type="radio" name="choose" id="test3" />
                                <label for="test3">Thanh toán bằng thẻ tín dụng VISA/ Master Card</label>
                            </p>
                            <p>
                                <input type="radio" name="choose" id="test4" />
                                <label for="test4">Thanh toán bằng ví điện tử VTC Pay</label>
                            </p>
                            <p>
                                <input type="radio" name="choose" id="test5"/>
                                <label for="test5">Thanh toán trả góp lãi xuất 0% - Sacombank</label>
                            </p>
                        </div>
                    </div>
                    <div class="box" style="display: none">
                        <div class="header-box">
                            <p>Phương thức vận chuyển</p>
                        </div>
                        <div class="body-box">
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label>Phương thức vận chuyển:</label>
                                </div>
                                <div class="col-md-7">
                                    <div class="wrap_select">
                                        <select class="form-control">
                                            <option>Giao hàng nhanh</option>
                                            <option>Giao hàng chậm</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label>Đơn vị vận chuyển:</label>
                                </div>
                                <div class="col-md-7">
                                    <div class="wrap_select">
                                        <select class="form-control">
                                            <option>Speedlink</option>
                                            <option>abc</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label>Cước vận chuyển:</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" value="61.000">
                                </div>
                                <div class="col-md-2">
                                    <div class="wrap_select">
                                        <select class="form-control">
                                            <option>VNĐ</option>
                                            <option>USD</option>
                                            <option>EURO</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box" style="display: none">
                        <div class="header-box">
                            <p>Áp dụng mã Coupon giám giá</p>
                        </div>
                        <div class="body-box">
                            <div class="col-md-4">
                                <label >Nhập mã coupon:</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" value="MUASAM1402">
                            </div>
                            <div class="col-md-2">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="box order_note">
                        <div class="header-box">
                            <p>Giảm giá đơn hàng</p>
                        </div>
                        <div class="body_box" style="padding-top: 10px;">
                            <div class="form-group">
                                <div class="infor col-md-12">
                                    <label>Nhập số tiền muốn giảm giá (VNĐ):</label>
                                    <input type="hidden" id="order-discount" name="discount" value="<?=@$object['discount']?>">
                                    <input class="form-control frm-number order-discount" data-target="#order-discount" type="text" value="<?=@$object['discount']?>">
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="box order_note">
                        <div class="header-box">
                            <p>Ghi chú đơn hàng</p>
                        </div>
                        <div class="body_box" style="padding-top: 10px;">
                            <div class="form-group">
                                <div class="infor col-md-12">
                                    <label>Ghi chú của khách hàng:</label>
                                    <input class="form-control notes" type="text" name="notes" value="<?=@$object['notes']?>">
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                            <div class="form-group">
                                <div class="infor col-md-12">
                                    <label>Ghi chú của nhân viên:</label>
                                    <input class="form-control details" type="text" name="details" value="<?=@$object['details']?>">
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="header-box">
                            <p>Dịch vụ khác</p>
                        </div>
                        <div class="body-box">
                            <p>
                                <input type="hidden" name="is_vat" value="0">
                                <input type="checkbox" id="is_vat" name="is_vat" value="1"
                                       data-toggle="collapse" data-target="#VAT-info" class="collapsed" aria-expanded="false">
                                <label for="is_vat">Xuất hóa đơn VAT cho đơn hàng</label>
                            </p>
                            <div class="collapse infor" id="VAT-info" aria-expanded="false">
                                <div class="form-group">
                                    <input value="<?=@$company_invoice['company_name']?>" name="company_name" id="company_name" type="text" class="form-control" placeholder="Tên công ty">
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 no-padding-left">
                                        <input value="<?=@$company_invoice['company_address']?>" name="company_address" id="company_address" type="text" class="form-control" placeholder="Địa chỉ công ty">
                                    </div>
                                    <div class="col-md-6 no-padding-right">
                                        <input value="<?=@$company_invoice['company_tax_code']?>" name="company_tax_code" id="company_tax_code" type="text" class="form-control" placeholder="Mã số thuế">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrap_btn">
                <input type="hidden" name="transport_info[warehouse]" id="transport_info_warehouse">
                <input type="hidden" id="transport_info_from" name="transport_info[from]" value="">
                <input type="hidden" id="transport_info_to" name="transport_info[to]">
                <input type="hidden" id="transport_info_asap" name="transport_info[asap]" value="<?=\Carbon\Carbon::now()->addDays(2)->format('d/m/Y')?>">
                <input type="hidden" id="transport_info_distance" name="transport_info[distance]">
                <input type="hidden" id="transport_info_time" name="transport_info[time]">
                <input type="hidden" name="id" value="<?=@$object['order_id']?>">
                <a href="javascript:void(0)" id="prev1">Hủy bỏ</a>
                <button type="submit" class="btn_next" disabled>Tiếp tục</button>
            </div>

        </div>
        </form>
        <div id="map" style="display: none"></div>
        <!-- end second step -->
        <div class="third_step modal fade" id="Modal1" role="dialog">
            <div class="modal-dialog modal_detail">
                <div class="modal-content ">
                    <div class="content">
                        <div class="header">
                            <i class="icon-tomtat">&nbsp</i>
                            <h2>Tóm tắt đơn hàng</h2>
                        </div>
                        <div class="row">
                            <div class="main_content">
                                <div class="col-md-7">
                                    <div class="box top_detail">
                                        <div class="row money">
                                            <p>Tổng tiền sản phẩm: <span class="subtotal_product"> <sup>đ</sup></span></p>
                                            <p>Tổng tiền dịch vụ: <span class="subtotal_service"> <sup>đ</sup></span></p>
                                            <p>Phụ phí: <span class="surcharge"> <sup>đ</sup></span></p>
                                            <p>Phí vận chuyển: <span class="shipping_cost price"> <sup>đ</sup></span></p>
                                            <p>Thành tiền: <span class="total_sum"> <sup>đ</sup></span></p>
                                            <p>Giảm: <span class="discount price"> <sup>đ</sup></span></p>
                                            <p><b>Tổng cộng : <span class="orange total_pay"> <sup>đ</sup></span></b></p>
                                            <!-- <p>Khối lượng đơn hàng : <span class="orange total_weight">100gr</span></p> -->
                                        </div>
                                    </div>
                                    
                                    <div class="box box-note order_note">
                                        <div class="header_box">
                                            <i class="icon-note-detail">&nbsp</i><b class="title"> Ghi chú đơn hàng: </b>
                                        </div>
                                        <ul class="body_box">
                                            <li>
                                                <input type="radio" name="defaut" value="giao hang tieu chuan" checked="">
                                                <div class="infor">
                                                    <p><b>Ghi chú của khách hàng</b></p>
                                                    <p class="show-notes"><?=@$object['notes']?></p>
                                                </div>
                                            </li>
                                            <li>
                                                <input type="radio" name="defaut1" value="giao hang tieu chuan" checked="">
                                                <div class="infor">
                                                    <p><b>Ghi chú của nhân viên</b></p>
                                                    <p class="show-details"><?=@$object['details']?></p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="box box_coupon" style="display: none;">
                                        <label>Áp dụng mã Coupon giám giá</label>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="box box-address shipping_address">
                                        <div class="header_box">
                                            <b class="title"> Địa chỉ giao hàng </b>
                                        </div>
                                        <div class="body_box">

                                        </div>
                                    </div>
                                    <div class="box box-address" style="display: none;">
                                        <div class="header_box">
                                            <b class="title"> Địa chỉ thanh toán </b>
                                        </div>
                                        <div class="body_box">
                                            <p><b>Người nhận:</b> Nguyễn Nhật Toàn</p>
                                            <p><b>Địa chỉ:</b> 241, Ấp 9, Xã Tân Lập Đông, Huyện Thới Bình, Tỉnh Cà Mau</p>
                                            <p><b>Điện thoại:</b>  01698948506</p>
                                            <p><b>Email:</b> nhattoannguyen89@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="box box-ship" style="display: none;">
                                        <div class="header_box">
                                            <i class="icon-shipper">&nbsp</i><b class="title"> Thông tin vận chuyển: </b>
                                        </div>
                                        <div class="body_box">
                                            <p><b>Phương thức vận chuyển: </b> Giao hàng nhanh</p>
                                            <p><b>Đơn vị vận chuyển: </b> Speedlink</p>
                                            <p><b>Cước vận chuyển: </b> <span class="orange"> 89,000 VNĐ</span></p>
                                            <p><b>Mã vận chuyển: </b><span class="orange"> ZZD34T57</span></p>
                                            <p><b>Ngày giao hàng: </b><span> 15/02/2017</span></p>
                                            <p><b>Ngày khách nhận hàng: </b><span> 15/02/2017</span></p>
                                        </div>
                                    </div>
                                    <div class="box box_method">
                                        <div class="header_box">
                                            <i class="icon-method">&nbsp</i>
                                            <div class="infor">
                                                <p><b class="title"> Phương thức thanh toán: </b></p>
                                                <p>Thanh toán khi giao hàng (COD)</p>
                                            </div>
                                        </div>
                                        <div class="body_box" >
                                            <p><b>Số tiền thanh toán: </b><span class="total_pay"></span> </p>
                                            {{--<p><b>Tình trạng xuất VAT: </b> Chưa xuất VAT</p>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap_btn">
                                    <a href="<?=route('order.index')?>" type="button"  data-dismiss="modal">Hủy bỏ</a>
                                    <a href="javascript:void(0)" type="button" class="btn_next" id="save_order">Hoàn tất đơn hàng</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end step 3 -->
        <div class="last_step modal fade" id="Modal12" role="dialog">
            <div class="modal-dialog modal_success">
                <div class="modal-content ">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="content">
                        <i class="icon_success">&nbsp</i>
                        <h2 class="msg">Đơn hàng của bạn đã cập nhật thành công</h2>
                        <p>Bạn có thể xem <a class="detail" href="#">Chi tiết đơn hàng</a> hoặc <a href="#" class="list">Danh sách đơn hàng</a></p>
                        <button style="width: 250px;" onclick="location.href='<?=route('order.create')?>';return false;" type="button" class="btn_next btn-save">Tiếp tục tạo mới đơn hàng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_styles')
<style type="text/css">
	label.error{
		display: none;
	}
    #Modal12,#alert_success,#modal_alert{
        z-index: 100000000;
    }
    .first_step .top_detail .wrap .row {
        border-bottom: 1px solid #e1e1e1;
    }
</style>
@endsection
<?php
$ver_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')
    
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
    <script src="/js/cart_map.js?v={{$ver_js}}" type="text/javascript"></script>
    <script src="{{ asset('js/order.js?v='.$ver_js) }}"></script>
    <script type="text/javascript">        
    	var $list_cart      = <?=!empty($list_cart)?json_encode($list_cart):'{}'?>;
        var $list_service   = <?=!empty($list_service)?json_encode($list_service):'{}'?>;
        $(document).ready(function() {
            init_fm_number('.frm-number');

            $('#is_vat').on('click', function () {
                if ($(this).is(':checked')) {
                    $("#company_name").rules("add", {
                        required: true
                    });
                    $("#company_address").rules("add", {
                        required: true
                    });
                    $("#company_tax_code").rules("add", {
                        required: true
                    });
                } else {
                    $("#company_name").rules("remove");
                    $("#company_address").rules("remove");
                    $("#company_tax_code").rules("remove");
                }
            });
            @if (isset($object['is_vat']) && $object['is_vat'])
            $('#is_vat').trigger('click');
            @endif
        });
    </script>
@endsection