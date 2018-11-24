@extends('layouts.master')

@section('content')
<div class="col-md-">
    <div class="wrap_view">
        <div class="header">
            <h2 class="title">Xem đơn hàng <span>/#<?=$object['validation_code'] ? $object['validation_code'] : $object['order_id'];?></span></h2> <span class="time">- <?=date('H:i d/m/Y',$object['timestamp'])?></span>
            <ul class="top-action">
                <?php
                $order_sources = \App\Helpers\General::order_sources();
                ?>
                <li><a href="javascript:void(0)"><i class="icon icon-link">&nbsp</i> <span><?=$order_sources[$object['type']]?></span></a></li>
                <li><a target="_blank" href="<?=route('order.print', ['id' => $object['order_id']])?>"><i class="icon icon-print">&nbsp</i> <span>In</span></a></li>
                @if(!$object['lock'])
                <li><a href="<?=route('order.edit',['id' => $object['order_id']])?>"><i class="icon icon-edit">&nbsp</i> <span>Sửa</span></a></li>
                @endif

                <li ><a style="display: <?=!$object['lock']?'block':'none'?>" href="javascript:void(0)" class="btn_save_lock_status" data-id="<?=$object['order_id']?>" ><i class="icon icon-save">&nbsp</i> <span>Lưu</span></a></li>
            </ul>
        </div>

        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#1" data-toggle="tab">Thông tin chung</a>
            </li>
            <li><a href="#2" data-toggle="tab">Lịch sử đơn hàng</a>
            </li>
            <li style="display: none;"><a href="#3" data-toggle="tab">Trạng thái giao hàng</a>
            </li>
        </ul>

        <div class="tab-content ">
            <!-- tab content 1 -->
            <div class="tab-pane active" id="1">
                <div class="top_content">
                    <p> Đơn hàng <b>#<?=$object['validation_code'] ? $object['validation_code'] : $object['order_id'];?></b> được đặt vào lúc <b><?=date('H:i d/m/Y',$object['timestamp'])?></b></p>
                    <p style="display: block;"><b>Trạm giao hàng:</b> <?=@$object['transport_info']['from']?></p>
                    <div class="top_right">
                        <div class="switch_button">
                            <label class="name">Khóa</label>
                            <div class="wrapper">
                              <input type="checkbox" id="checkbox" class="slider-toggle is_lock" <?=$object['lock']==1?'checked':''?>  />
                                <label class="slider-viewport" for="checkbox">
                                <div class="slider">
                                    <div class="slider-button">&nbsp;</div>
                                    <div class="slider-content left"><span>On</span></div>
                                    <div class="slider-content right"><span>Off</span></div>
                                </div>
                                </label>
                            </div>
                        </div>
                        <div class="choose_status">
                            <label>Trạng thái đơn hàng</label>
                            <div class="wrap_select" style="width: auto !important;">
                                <select class="form-control select2" id="status" data-placeholder="Trạng thái đơn hàng" <?=$object['lock']==1?'disabled':''?> >
                                    <option value=""></option>
                                    @foreach($orderStatus as $key => $value)
                                    <option value="<?=$key?>" <?=$object['status']==$key?'selected':''?> ><?=$value?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="main_content">
                    <div class="col-md-7">
                        <div class="box top_detail">
                        	<?php $total_weight = 0; $service = []; ?>
                        	@if(!empty($orderDetail[$object['order_id']]))
                            @foreach($orderDetail[$object['order_id']] as $detail)
                            <?php 
                                if($detail['product_type'] == 'S'){
                                    $service[] = $detail;
                                    continue;
                                }
                            	$extra = unserialize($detail['extra']);
                            	$total_weight += $detail['weight'] * $detail['amount'];
                            ?>
                            <div class="wrap">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-2">
                                            <div class="wrap-img">
                                                <img src="<?=$detail['image']?>" alt="">
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <a target="_blank" href="<?=route('products.edit', ['id' => $detail['product_id']])?>"><?=$detail['product'].' - '.$detail['product_code']?></a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 price-number">
                                            <p><?=number_format($detail['price'])?>₫    X    <?=$detail['amount']?></p>
                                            @if ($detail['discount'] > 0) <p class="discount">- <?=number_format($detail['discount'])?>₫</p> @endif
                                        </div>
                                        <div class="col-md-4">
                                            <p><?=number_format($detail['price'] * $detail['amount'] - $detail['discount'])?> <sup>đ</sup></p>
                                        </div>
                                        <div class="col-md-1">
                                            @if(!$object['lock'])
                                            <a href="<?=route('order.edit',['id' => $object['order_id']])?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div> 
                                    
                            </div>  
                            @if(!empty($extra['product_options_value']))
                            @foreach($extra['product_options_value'] as $product_options_value)
                            
                            <div class="row gift_hight">
                            	<div class="content">
                                    <i class="fa fa-gift" aria-hidden="true"></i>
                                    <p><?=$product_options_value['variant_name']?></p>
                                </div>
                            </div>
                            @endforeach
                            @endif 	                  
                            @endforeach
                            
                            @if(!empty($service))
                            <div class="wrap row">
                                <div class="col-md-12">
                                    <h5>Dịch vụ thêm</h5>
                                    @foreach($service as $item)
                                    <p><?=$item['product']?>: <?=number_format($item['price'])?>đ</p>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @endif

                            <div class="row money">
                                <p>Tổng tiền sản phẩm: <span><?=number_format($object['subtotal_product'])?> <sup>đ</sup></span></p>
                                <p>Tổng tiền dịch vụ: <span><?=number_format($object['subtotal_service'])?> <sup>đ</sup></span></p>
                                <p>Thành tiền: <span><?=number_format($object['subtotal'])?> <sup>đ</sup></span></p>
                                <p>Phụ phí: <span><?=number_format($object['surcharge'])?> <sup>đ</sup></span></p>
                                <p>Phí vận chuyển <?=!empty($object['transport_info']['distance'])?'('.$object['transport_info']['distance'].')':''?>: <span><?=number_format($object['shipping_cost'])?> <sup>đ</sup></span></p>
                                @if($object['voucher_code'])
                                <p>Mã coupon : <span><?=$object['voucher_code']?></span></p>
                                @endif
                                <p>Giảm giá : <span>-<?=number_format($object['discount'])?></span> <sup>đ</sup></p>
                                <p><b>Tổng cộng : <span class="orange"><?=number_format($object['total'])?> <sup>đ</sup></span></b></p>
                                <p>Khối lượng đơn hàng : <span class="orange"><?=$total_weight?>kg</span></p>
                                <!-- <p>Đơn hàng này được tích lũy: <span class="orange">+1990 <span>myXu</span></span></p> -->
                            </div>
                        </div>
                        <div class="box box-note">
                        	<form id="form_note" method="post" action="<?=route('order.update-note')?>">
                            <div class="header_box">
                                <i class="icon-note-detail">&nbsp</i><b class="title"> Ghi chú đơn hàng: </b>
                                @if(!$object['lock'])
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                @endif
                            </div>
                            <ul class="body_box">
                                <li>
                                    <input type="radio" name="defaut" value="giao hang tieu chuan" checked>
                                    <div class="infor">
                                        <p><b>Ghi chú của khách hàng</b></p>
                                        <p><?=$object['notes']?></p>
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" name="defaut1" value="giao hang tieu chuan" checked>
                                    <div class="infor">
                                        <p><b>Ghi chú của nhân viên</b></p>
                                        <p><?=$object['details']?></p>
                                    </div>
                                </li>
                            </ul>
                            <ul class="edit_note">
                                <li>
                                    <label>Ghi chú của khách hàng:</label>
                                    <input type="text" name="notes" value="<?=$object['notes']?>">
                                </li>
                                <li>
                                    <label>Ghi chú của nhân viên:</label>
                                    <input type="text" name="details" value="<?=$object['details']?>">
                                </li>
                                <li>
                                	<input type="hidden" name="order_id" value="<?=$object['order_id']?>">
                                    <div class="wrap_btn">
                                        <span href="javascript:void(0)" class="cancel" style="line-height: 25px;"
                                              data-show=".box-note .body_box,.box-note .fa-pencil" data-hide=".box-note .edit_note">Hủy bỏ</span>
                                        <button type="submit" class="btn-save">Lưu thay đổi</button>
                                    </div>
                                </li>
                            </ul>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5">
                    	<form id="form_adress" method="post" action="<?=route('order.update-address')?>">
                        <div class="box box-address delivery">
                            <div class="header_box">
                                <b class="title"> Địa chỉ giao hàng </b>
                                @if(!$object['lock'])
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="body_box">
                                <p><b>Người nhận:</b> <?=$object['b_firstname']?></p>
                                <p><b>Địa chỉ:</b> <?=$object['b_address']?></p>
                                <p><b>Điện thoại:</b>  <?=$object['b_phone']?></p>
                                <p><b>Email:</b> <?=$object['email']?></p>
                            </div>
                            <div class="edit_address">                            
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Người nhận:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="b_firstname" value="<?=$object['b_firstname']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Địa chỉ:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="wrap_select">
                                            <select class="form-control provinces" onchange="get_districts_by_province(this)"
                                                    data-destination="#form_adress .districts" aria-invalid="false" data-id="<?=$object['province_id']?>" name="province_id">
                                            </select>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </div>
                                        <div class="wrap_select">
                                            <select class="form-control districts" onchange="get_wards_by_district(this)" data-destination="#form_adress .wards" aria-invalid="false" data-id="<?=$object['district_id']?>" name="district_id">
                                            </select>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </div>
                                        <div class="wrap_select">
                                            <select class="form-control wards" name="ward_id" data-id="<?=$object['ward_id']?>">
                                            </select>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </div>
                                        <input type="text" name="b_address" value="<?=$object['b_address']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Điện thoại:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="b_phone" value="<?=$object['b_phone']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Email:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="email" value="<?=$object['email']?>">
                                    </div>
                                </div>
                                <input type="hidden" name="order_id" value="<?=$object['order_id']?>">

                                <div class="wrap_btn">
                                    <span href="javascript:void(0)" data-show=".delivery .body_box,.delivery .fa-pencil" data-hide=".delivery .edit_address"
                                       class="cancel" style="line-height: 25px;">Hủy bỏ</span>
	                                <button type="submit" class="btn-save">Lưu thay đổi</button>
                                </div>
                            </div>
                        </div>
                        </form>
                        <div class="box box-address" style="display: none;">
                            <div class="header_box">
                                <b class="title"> Địa chỉ thanh toán </b>
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </div>
                            <div class="body_box">
                                <p><b>Người nhận:</b> Nguyễn Nhật Toàn</p>
                                <p><b>Địa chỉ:</b> 241, Ấp 9, Xã Tân Lập Đông, Huyện Thới Bình, Tỉnh Cà Mau</p>
                                <p><b>Điện thoại:</b>  01698948506</p>
                                <p><b>Email:</b> nhattoannguyen89@gmail.com</p>
                            </div>
                            <div class="edit_address">
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Người nhận:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" value="Nguyễn Nhật Toàn">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Địa chỉ:</b>
                                    </div>
                                    <div class="col-md-10">
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
                                        <input type="text" value="241, Ấp 9">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Điện thoại:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" value="01698948506">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Email:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" value="nhattoannguyen89@gmail.com">
                                    </div>
                                </div>
                                <button type="button" class="btn-save">Lưu thay đổi</button>
                            </div>
                        </div>
                        <div class="box box_method">
                            <div class="header_box">
                                <i class="icon-method">&nbsp</i>
                                <div class="infor">
                                    <p><b class="title"> Phương thức thanh toán: </b></p>
                                    <p><?=isset($object['payment'])?$object['payment']:'Thanh toán khi giao hàng (COD)'?></p>
                                </div>
                            </div>
                            <div class="body_box">
                                <p><b>Số tiền thanh toán: </b><?=number_format($object['total'])?>đ</p>
                                <?php
                                $vats = [''=>'Không', 0=>'Không',1=>'Chưa xuất VAT',2=>'Đã xuất VAT'];
                                ?>
                                <p><b>Tình trạng xuất VAT: </b> <?=$vats[$object['is_vat']]?></p>
                                <?php
                            if ($company_invoice) {
                                $extra = json_decode($company_invoice['extra'], 1);
                                if ($extra) {
                                ?>
                                    <p><b>Đơn vị: </b> <?=$extra['company_name']?></p>
                                    <p><b>Đĩa chỉ: </b> <?=$extra['company_address']?></p>
                                    <p><b>Mã số thuế: </b> <?=$extra['company_tax_code']?></p>
                                <?php } else {
                                $extra = str_replace(" - ", '</p><p>', $company_invoice['value']);
                                ?>
                                    <p><?=$vats[$object['is_vat']]?></p>
                                <?php }
                            }
                                ?>
                            </div>
                        </div>

                        @if ($object['admin_id'])
                        <div class="box box_method">
                            <div class="header_box">
                                <i class="icon-method">&nbsp</i>
                                <div class="infor">
                                    <p><b class="title">@if($object['type']=='admin') Nhân viên tạo đơn hàng: @else Nhân viên phụ trách đơn hàng: @endif</b></p>
                                </div>
                            </div>
                            <div class="body_box">
                                <p><b>Mã nhân viên: </b><?=@$admin['username']?></p>
                                <p><b>Tên nhân viên: </b><?=@$admin['full_name']?></p>
                            </div>
                        </div>
                        @endif
                        
                        <button type="button" class="btn_save btn_save_lock_status" data-id="<?=$object['order_id']?>" style="display: <?=!$object['lock']?'block':'none'?>" > Lưu đơn hàng</button>
                    </div>
                </div>
            </div>
            <!-- tab content 2 -->
            <div class="tab-pane" id="2">
                <?php
                $status = \App\Helpers\General::get_current_status($object['status']);
                ?>
                <ol class="progress-track">
                  <li class="progress-done">
                    <center>
                      <div class="icon-wrap">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <svg class="icon-state icon-check-mark">
                          <use xlink:href="#icon-check-mark"></use>
                        </svg>

                        <svg class="icon-state icon-down-arrow">

                          <use xlink:href="#icon-down-arrow"></use>
                        </svg>
                      </div>
                      <div class="progress-text">
                        <p>Đặt hàng thành công</p>
                        <p class="time">(<?=date('H:i:s - d/m/Y', $object['timestamp'])?>)</p>
                      </div>
                    </center>
                  </li>

                  <li class="<?=$status>=1?'progress-done':'progress-todo'?>">
                    <center>
                      <div class="icon-wrap">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <svg class="icon-state icon-check-mark">
                          <use xlink:href="#icon-check-mark"></use>
                        </svg>

                        <svg class="icon-state icon-down-arrow">
                          <use xlink:href="#icon-down-arrow"></use>
                        </svg>
                      </div>
                      <div class="progress-text">
                        <p>Xác nhận đơn hàng</p>
                      </div>
                    </center>
                  </li>

                <li class="<?=$status>=2?'progress-done':'progress-todo'?>">
                    <center>
                      <div class="icon-wrap">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <svg class="icon-state icon-check-mark">
                          <use xlink:href="#icon-check-mark"></use>
                        </svg>

                        <svg class="icon-state icon-down-arrow">
                          <use xlink:href="#icon-down-arrow"></use>
                        </svg>
                      </div>
                      <div class="progress-text">
                        <p>Vận chuyển - Giao hàng</p>
                      </div>
                    </center>
                  </li>

                <li class="<?=$status>=3?'progress-done':'progress-todo'?>">
                    <center>
                      <div class="icon-wrap">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <svg class="icon-state icon-check-mark">
                          <use xlink:href="#icon-check-mark"></use>
                        </svg>

                        <svg class="icon-state icon-down-arrow">
                          <use xlink:href="#icon-down-arrow"></use>
                        </svg>
                      </div>
                      <div class="progress-text">
                        <p>Giao hàng thành công</p>
                      </div>
                    </center>
                  </li>

                </ol>
                <!-- table display list -->
                <div class="table_history">
                    <div class="header_table">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="col-md-5">Thời gian</div>
                                <div class="no-padding col-md-7">Nhân viên</div>
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
                    </div>
                    <ul class="body_table">
                @if (isset($audits))
                    @foreach($audits as $item)
                        <li class="row">
                            <div class="col-md-5">
                                <div class="col-md-5"><?=$item['created_at']?></div>
                                <div class="no-padding col-md-7"><?=$item['full_name']?></div>
                            </div>
                            <div class="col-md-7">
                                <?php
                                $tmp = $item['event'].'-'.$item['auditable_type'];
                                ?>
                                <div class="col-md-4"><span><?=isset($event_options[$tmp]) ? $event_options[$tmp] : $item['event']?></span></div>
                                <div class="col-md-8">
                                    <?php
                                        $orderStatus['N'] = 'Đơn hàng mới';
                                        $old_values = json_decode($item['old_values'], 1);
                                        $new_values = json_decode($item['new_values'], 1);
                                        if (isset($new_values['extra'])) {
                                            $extra = unserialize($new_values['extra']);
                                            $new_values['product_name'] = $extra['product']??'';
                                        }
                                    foreach ($new_values as $nk => $nv) {
                                            if (!array_key_exists($nk, $field_name_options)) continue;

                                            if ($nk=='status') {
                                        ?>
                                            <span><?=isset($field_name_options[$nk])?$field_name_options[$nk]:$nk?> :
                                                <?=(isset($old_values[$nk])?$orderStatus[$old_values[$nk]].' -> ':'').$orderStatus[$nv]?>, </span>
                                        <?php } elseif ($nv != @$old_values[$nk]) { ?>
                                        <span><?=isset($field_name_options[$nk])?$field_name_options[$nk]:$nk?> : <?=(isset($old_values[$nk])?$old_values[$nk].' -> ':'').$nv?>, </span>
                                        <?php } ?>
                            <?php   } ?>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
                    </ul>
                </div>
            </div>
            <!-- tab content 3 -->
            <div class="tab-pane" id="3">
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
</style>
@endsection

@section('after_scripts')
    <script type="text/javascript">
    	
        $(function(){
        	init_datepicker('.datepicker');
        	init_select2('.select2');
        	get_provinces('.provinces');
            @if($object['lock'] === 1)
            $('.is_lock').on('change',function(){
                $('.btn_save_lock_status').toggle();
            });
            @endif

        	$(document).on('click','#form_adress .btn-save',function(){
        		
        		$('#form_adress').validate({
	                ignore: ".ignore",
	                rules: {
	                    b_firstname: "required",
	                    b_phone: "required",
	                    province_id:'required',
	                    district_id:'required',
	                    ward_id:'required',
	                    b_address:'required',
	                    email:{
	                    	'required':true,
	                    	'email':true
	                	}
	                },
	                messages: {
	                    b_firstname: "Nhập họ tên",
	                    b_phone: "Nhập số điện thoại",
	                    province_id:'Chọn tỉnh thành / phố',
	                    district_id:'Chọn quận huyện',
	                    ward_id:'Chọn phường xã',
	                    b_address:'Nhập địa chỉ',
	                    email:{
	                    	'required':'Nhập email',
	                    	'email':'Email không đúng'
	                	}
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
	                                location.reload();
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
	                }
	            });
        	});
        	
        	$(document).on('click','#form_note .btn-save',function(){
        		
        		$('#form_note').validate({
	                ignore: ".ignore",
	                rules: {
	                },
	                messages: {
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
	                                location.reload();
	                            });
	                        } else {
	                            malert(data.msg);
	                            if (data.errors) {
	                                $.each(data.errors, function (key, msg) {
	                                    $('#'+key+'-error').html(msg).show();
	                                });
	                            }
	                        }
	                    });
	                }
	            });
        	});

            $(document).on('click','.btn_save_lock_status',function(){
                var lock = 0;
                if($('.is_lock').is(":checked"))
                    lock = 1;
                var status = $('#status').val();
                var order_id = $(this).data('id');
                var data = {lock:lock,status:status,order_id:order_id};
                var url = '/order/change-lock-status';
                ajax_loading(true);
                $.post(url, data).done(function(data){
                    ajax_loading(false);
                    if(data.rs == 1)
                    {
                        alert_success(data.msg, function () {
                            location.reload();
                        });
                    } else {
                        malert(data.msg);
                        if (data.errors) {
                            $.each(data.errors, function (key, msg) {
                                $('#'+key+'-error').html(msg).show();
                            });
                        }
                    }
                });
            })
        	
        });
    </script>
@endsection