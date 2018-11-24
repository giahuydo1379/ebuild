@extends('layouts.master')

@section('content')
<div class="col-md-">
    <div class="wrap_view">
        <div class="header">
            <h2 class="title">Xem đơn hàng <span>/#<?=$object['code'] ? $object['code'] : $object['id'];?></span></h2> <span class="time">- <?=date('H:i d/m/Y',strtotime($object['created_at']))?></span>
            <ul class="top-action">
                <?php
                $order_sources = \App\Helpers\General::order_sources();
                ?>
                <li><a href="javascript:void(0)"><i class="icon icon-link">&nbsp</i> <span><?=$order_sources[$object['source']]?></span></a></li>
                <li><a target="_blank" href="<?=route($controllerName.'.print', ['id' => $object['id']])?>"><i class="icon icon-print">&nbsp</i> <span>In</span></a></li>
                @if(!$object['is_lock'])
                <li><a href="<?=route($controllerName.'.edit',['id' => $object['id']])?>"><i class="icon icon-edit">&nbsp</i> <span>Sửa</span></a></li>
                @endif
                <li><a href="javascript:void(0)" class="btn_save_lock_status" data-id="<?=$object['id']?>" ><i class="icon icon-save">&nbsp</i> <span>Lưu</span></a></li>
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
                    <div class="col-md-6">
                        <p> Đơn hàng <b>#<?=$object['code'] ? $object['code'] : $object['id'];?></b> được đặt vào lúc <b><?=date('H:i d/m/Y',strtotime($object['created_at']))?></b>
                        </p>
                        <br>
                        <p>Khách hàng sử dụng dịch vụ lúc: <b><?=date('H:i d/m/Y',strtotime($object['start_date']))?></b></p>
                    </div>
                    <div class="col-md-6">
                        <div class="top_right">
                            <div class="choose_status">
                                <label>Trạng thái đơn hàng</label>
                                <div class="wrap_select">
                                    <select class="form-control select2" id="status" data-placeholder="Trạng thái đơn hàng" <?=$object['is_lock']==1?'disabled':''?> >
                                        <option value=""></option>
                                        @foreach($booking_status as $key => $value)
                                        <option value="<?=$key?>" <?=$object['booking_status_id']==$key?'selected':''?> ><?=$value?></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main_content">
                    <div class="col-md-7">
                        <div class="box top_detail"> 
                            <div class="header_box">
                                <b class="title">Dịch vụ khách hàng sử dụng: <?=$object['service_name']?></b>
                                @if(!$object['is_lock'])
                                <a href="<?=route($controllerName.'.edit',['id' => $object['id']])?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                @endif
                            </div>
                            <div class="body_box">
                                @if(!empty($booking_detail))
                                @foreach($booking_detail as $item)
                                <div class="row">
                                    <div class="col-md-6">
                                    <?=$item['service_unit_name']?>
                                    </div>
                                    <div class="col-md-3 detail-price">
                                        <p><?=number_format($item['service_unit_price'])?><sup>đ</sup> x <?=$item['service_unit_quantity']?></p>
                                    </div>
                                    <div class="col-md-3 detail-price">
                                        <p><?=number_format($item['service_unit_price'] * $item['service_unit_quantity'])?><sup>đ</sup></p>
                                    </div>
                                </div>
                                @endforeach
                                @endif

                                @if(!empty($service_extra_ids))
                                    @foreach($service_extra_ids as $item)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?=$services_extra[$item['id']]?>
                                            </div>
                                            <div class="col-md-3 detail-price">
                                                <p><?=number_format($item['price'])?><sup>đ</sup></p>
                                            </div>
                                            <div class="col-md-3 detail-price">
                                                <p><?=number_format($item['price'])?><sup>đ</sup></p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if($object['benefit'])
                                    <div class="row">
                                        <div class="col-md-6">
                                            Phụ cấp để kiếm người làm nhanh hơn
                                        </div>
                                        <div class="col-md-3 detail-price">
                                            <p><?=number_format($object['benefit'])?><sup>đ</sup></p>
                                        </div>
                                        <div class="col-md-3 detail-price">
                                            <p><?=number_format($object['benefit'])?><sup>đ</sup></p>
                                        </div>
                                    </div>
                                @endif

                                <div class="row money">
                                    <!-- <p>Phí vận chuyển và thu tiền tận nơi : <span>+61,000</span> <sup>đ</sup></p>-->
                                    @if($object['voucher_code'])
                                    <p>Mã coupon : <span><?=$object['voucher_code']?></span></p>
                                    @endif
                                    <p><b>Tổng cộng : <span class="orange"><?=number_format($object['total_amount'])?> <sup>đ</sup></span></b></p>
                                    <!-- <p>Đơn hàng này được tích lũy: <span class="orange">+1990 <span>myXu</span></span></p> -->
                                </div>
                            </div>
                        </div>
                        <div class="box box-note">
                        	<form id="form_note" method="post" action="<?=route($controllerName.'.update-note')?>">
                            <div class="header_box">
                                <i class="icon-note-detail">&nbsp</i><b class="title"> Ghi chú đơn hàng: </b>
                                @if(!$object['is_lock'])
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                @endif
                            </div>
                            <ul class="body_box">
                                <li>
                                    <input type="radio" name="defaut" value="giao hang tieu chuan" checked>
                                    <div class="infor">
                                        <p><b>Ghi chú của khách hàng</b></p>
                                        <p><?=$object['customer_note']?></p>
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" name="defaut1" value="giao hang tieu chuan" checked>
                                    <div class="infor">
                                        <p><b>Ghi chú của nhân viên</b></p>
                                        <p><?=$object['notes']?></p>
                                    </div>
                                </li>
                            </ul>
                            <ul class="edit_note">                                
                                <li>
                                    <label>Ghi chú của khách hàng:</label>
                                    <input type="text" name="customer_note" value="<?=$object['customer_note']?>">
                                </li>
                                <li>
                                    <label>Ghi chú của nhân viên:</label>
                                    <input type="text" name="notes" value="<?=$object['notes']?>">
                                </li>
                                <li>
                                	<input type="hidden" name="id" value="<?=$object['id']?>">
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
                    	<form id="form_adress" method="post" action="<?=route($controllerName.'.update-address')?>">
                        <div class="box box-address delivery">
                            <div class="header_box">
                                <strong class="title"> Thông tin khách hàng </strong>
                                @if(!$object['is_lock'])
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="body_box">
                                <p><b>Tên:</b> <?=$object['fullname']?></p>
                                <?php /* <p><b>Địa chỉ:</b> <?=$object['address']?></p> */?>
                                <p><b>Điện thoại:</b>  <?=$object['phone']?></p>
                                <p><b>Email:</b> <?=$object['email']?></p>
                            </div>
                            <div class="edit_address">
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Tên:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" style="width:100%" name="fullname" value="<?=$object['fullname']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <b>Điện thoại:</b>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" name="phone" value="<?=$object['phone']?>" style="width:100%">
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
                                <input type="hidden" name="id" value="<?=$object['id']?>">

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
                        @if ($object['admin_id'])
                        <div class="box box_method">
                            <div class="header_box">
                                <i class="icon-method">&nbsp</i>
                                <div class="infor">
                                    <p><b class="title">@if($object['source']=='admin') Nhân viên tạo đơn hàng: @else Nhân viên phụ trách đơn hàng: @endif</b></p>
                                </div>
                            </div>
                            <div class="body_box">
                                <p><b>Mã nhân viên: </b><?=@$admin['username']?></p>
                                <p><b>Tên nhân viên: </b><?=@$admin['fullname']?></p>
                            </div>
                        </div>
                        @endif
                        <div class="box box_method">
                            <div class="header_box">
                                <i class="icon-method">&nbsp</i>
                                <div class="infor">
                                    <p><b class="title"> Phương thức thanh toán: </b></p>
                                    <p><?=isset($object['payment'])?$object['payment']:'Thanh toán khi giao hàng (COD)'?></p>
                                </div>
                            </div>
                            <div class="body_box">
                                <p><b>Số tiền thanh toán: </b><?=number_format($object['total_amount'])?>đ</p>
                            </div>
                        </div>
                        <button type="button" class="btn_save btn_save_lock_status" data-id="<?=$object['id']?>"> Lưu đơn hàng</button>
                    </div>
                </div>
            </div>
            <!-- tab content 2 -->
            <div class="tab-pane" id="2">
                <?php
                $status = \App\Helpers\General::get_current_status($object['booking_status_id']);
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
                        <p class="time">(<?=date('H:i:s - d/m/Y', strtotime($object['created_at']))?>)</p>
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
                                <div class="no-padding col-md-7"><?=$item['fullname']?></div>
                            </div>
                            <div class="col-md-7">
                                <?php
                                $tmp = $item['event'].'-'.$item['auditable_type'];
                                ?>
                                <div class="col-md-4"><span><?=isset($event_options[$tmp]) ? $event_options[$tmp] : $item['event']?></span></div>
                                <div class="col-md-8">
                                    <?php
                                        $booking_status['N'] = 'Đơn hàng mới';
                                        $old_values = json_decode($item['old_values'], 1);
                                        $new_values = json_decode($item['new_values'], 1);
                                        if (isset($new_values['extra'])) {
                                            $extra = unserialize($new_values['extra']);
                                            $new_values['product_name'] = $extra['product'];
                                        }
                                    foreach ($new_values as $nk => $nv) {
                                            if (in_array($nk, $fillable_no_auditable)) continue;

                                            if ($nk=='status') {
                                        ?>
                                            <span><?=isset($field_name_options[$nk])?$field_name_options[$nk]:$nk?> :
                                                <?=(isset($old_values[$nk])?$booking_status[$old_values[$nk]].' -> ':'').$booking_status[$nv]?>, </span>
                                        <?php } else { ?>
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
    .top_detail .row{
        padding: 10px;
    }
    .top_detail .detail-price{
        
        text-align: right;
    }
    .tab-content .tab-pane .main_content {
        display: block;
    }
    .tab-content .tab-pane .main_content .top_detail .money{
        padding-right: 15px !important;
    }
</style>
@endsection

@section('after_scripts')
    <script type="text/javascript">
    	
        $(function(){
            localStorage.clear();

        	init_datepicker('.datepicker');
        	init_select2('.select2');
        	get_provinces('.provinces');
        	$(document).on('click','#form_adress .btn-save',function(){
        		
        		$('#form_adress').validate({
	                ignore: ".ignore",
	                rules: {
	                    fullname: "required",
	                    phone: "required",
	                    province_id:'required',
	                    district_id:'required',
	                    ward_id:'required',
	                    address:'required',
	                    email:{
	                    	'required':true,
	                    	'email':true
	                	}
	                },
	                messages: {
	                    fullname: "Nhập họ tên",
	                    phone: "Nhập số điện thoại",
	                    province_id:'Chọn tỉnh thành / phố',
	                    district_id:'Chọn quận huyện',
	                    ward_id:'Chọn phường xã',
	                    address:'Nhập địa chỉ',
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
                var id = $(this).data('id');
                var data = {lock:lock,status:status,id:id};
                var url = '/booking/change-lock-status';
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