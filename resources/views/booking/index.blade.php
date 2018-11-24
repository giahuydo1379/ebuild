@extends('layouts.master')

@section('content')
	<style>
		.wrap_view section.table .table_donhang .body_table .view_fast .top_detail .wrap .gift i {
			font-size: 22px;
			color: #ff4000;
			float: left;
			margin-right: 10px;
			position: relative;
			top: 10px;
		}
		.wrap_view section.table .table_donhang .body_table .view_fast .top_detail .wrap .gift p {
			overflow: hidden;
			display: -webkit-box;
			-webkit-line-clamp: 1;
			-webkit-box-orient: vertical;
			text-overflow: ellipsis;
			max-height: 2.125em;
			word-wrap: break-word;
		}
	</style>
   <div class="col-md-">
	    <div class="wrap_view">
	        <div class="header">
	            <h2 class="title">Xem đơn hàng</h2>
	        </div>
	        <section class="view_donhang">
	            <div class="header-panel">
	                <h3 class="title ">Tìm kiếm nhanh</h3>
	                <div class="wrap_link">
	                    <a href="<?=route($controllerName.'.create-no-params')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo đơn hàng mới</span></a>
	                </div>
	            </div>
	            <form id="form_search" class="search_donhang" action="<?=route($controllerName.'.index')?>">
	                <div class="form_search_fast row">
	                    <div class="col-md-3">
	                        <div class="form-group">
	                            <label>Thời gian</label>
	                            <div class="wrap_select">
	                                <select class="form-control" id="select_time">
	                                	<option value="">Chọn thời gian</option>
	                                    <option value="to_day">Hôm nay</option>
	                                    <option value="this_week">Trong tuần</option>
	                                    <option value="this_month">Trong tháng</option>
	                                    <option value="this_year">Trong năm</option>
	                                    <option value="last_year">Năm trước</option>
	                                </select>
	                                <i class="fa fa-angle-down" aria-hidden="true"></i>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-3">
	                        <div class="form-group">
	                            <label>Chọn từ ngày</label>
	                            <div class="time">
	                                <div class="input-group date">     
	                                    <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="<?=@$params['from_date']?>" placeholder="Chọn ngày đặt hàng">
	                                    <span class="fa fa-calendar"></span>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-3">
	                        <div class="form-group">
	                            <label>Đến ngày</label>
	                            <div class="time">
	                                <div class="input-group date" >     
	                                    <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="<?=@$params['to_date']?>" placeholder="Chọn ngày đặt hàng">
	                                    <span class="fa fa-calendar"></span>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-md-3">
							<div class="form-group">
								<label>Nhân viên xử lý đơn hàng</label>
							{!! Form::select("admin_id", ['' => ''] + $admin_users, @$params['admin_id'],
                                        ['id' => 'admin_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn nhân viên']) !!}
							</div>
						</div>
	                </div>
					<div class="form_search_fast row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Mã đơn hàng</label>
								<input type="text" class="ip_text" name="code" value="<?=@$params['code']?>" placeholder="Nhập mã đơn hàng">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Người liên hệ</label>
								<input type="text" class="ip_text" name="fullname" value="<?=@$params['fullname']?>" placeholder="Nhập tên người nhận hàng">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Địa chỉ email</label>
								<input type="email" class="ip_text" name="email" value="<?=@$params['email']?>" placeholder="Nhập email người nhận hàng">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Số điện thoại</label>
								<input type="text" class="ip_text" name="phone" value="<?=@$params['phone']?>" placeholder="Nhập số điện thoại">
							</div>
						</div>
					</div>
	                <div class="search_level">
	                    <h3 class="title">Tìm kiếm nâng cao</h3>
	                    <div class="wrap_search">
	                        <div class="status">
	                            <p class="sub_title"><i class="icon-status">&nbsp</i> <span>Trạng thái đơn hàng</span></p>
	                            <div class="row">	                            	
	                                <div class="col-md-3 order_status_1">
	                                </div>
	                                <div class="col-md-3 order_status_2">
	                                </div>
	                                <div class="col-md-3 order_status_3">
	                                </div>
	                                <div class="col-md-3 order_status_4">
	                                </div>
	                            </div>
	                        </div>
							<div class="sources">
								<p class="sub_title"><i class="icon-payment">&nbsp</i> <span>Nguồn</span></p>
								<div class="row">
                                    <?php
                                    $order_sources = \App\Helpers\General::order_sources();
                                    ?>
									<div class="col-md-11">
									@foreach($order_sources as $key => $item)
										<label class="radio-inline">
											<input <?=(!isset($params['type']) && !$key ) || (@$params['type']==$key) ? 'checked="checked"' : ''?> type="radio" value="<?=$key?>" name="type"><?=$key?$item:'Tất cả'?>
										</label>
									@endforeach
									</div>
								</div>
							</div>
	                        <div class="payment" style="display: none;">
	                            <p class="sub_title"><i class="icon-payment">&nbsp</i> <span>Thanh toán</span></p>
	                            <div class="row">
	                                <div class="col-md-3 payment_1">
	                                </div>
	                                <div class="col-md-3 payment_2">
	                                </div>
	                                <div class="col-md-3 payment_3">
	                                </div>
	                                <div class="col-md-3 payment_4">
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
	        <section class="table">
	            <div class="table_donhang">
	                <div class="header_table">
	                    <div class="row">
	                        <div class="col-md-6">
	                            <div class=" col-md-1"><input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check"></div>
	                            <div class="center col-md-3">ID</div>
	                            <div class=" col-md-4">Trạng thái</div>
	                            <div class=" col-md-4">Người liên hệ</div>
	                        </div>
	                        <div class="col-md-6">
	                            <div class="center col-md-3">Thời gian</div>
	                            <div class=" col-md-3">Tổng cộng</div>
	                            <div class=" col-md-2 no-padding">Đơn hàng từ</div>
								<div class=" col-md-2 no-padding">Nhân viên</div>
	                            <div class=" col-md-2"></div>
	                        </div>
	                    </div>
	                </div>
	                @if(!empty($objects['data']))
	                <ul class="body_table panel-group" id="accordion3">
	                    <div class="switch_bar" >
	                        <div class="wrap-switch " >
	                            <div class="select">
	                                <select class="form-control" id="order_status">
	                                    <option value="">Chuyển trạng thái đơn hàng</option>
	                                    @foreach($booking_status as $item)
	                                    <option value="<?=$item['booking_status_id']?>"><?=$item['booking_status_name']?></option>
	                                    @endforeach
	                                </select>
	                                <i class="fa fa-angle-down" aria-hidden="true"></i>
	                            </div>
	                            
	                            <label class="switch change_status_order">
	                              <input type="checkbox" >
	                              <div class="slider round"><i class="icon-check">&nbsp</i></div>
	                            </label>
	                        </div>
	                    </div>
	                    @foreach($objects['data'] as $item)
	                    <li class="row panel">
	                        <div class="col-md-6">
	                            <div class=" col-md-1">
	                            	<input type="checkbox" name="choose" value="<?=$item['id']?>"  class="checkbox_check">
	                            </div>
	                            <div class="id col-md-3">
	                            	<a href="<?=route($controllerName.'.show',['id' => $item['id']])?>">#<?=$item['code'] ? $item['code'] : $item['id'];?></a>
	                            </div>
	                            <div class="status col-md-4">
									@if (isset($booking_status[$item['booking_status_id']]))
									<span class="label label-info"><?=$booking_status[$item['booking_status_id']]['booking_status_name']?></span>
									@else
									<span class="label label-warning">Chưa hoàn thành</span>
									@endif

	                            	@if($item['is_lock'] == 1)
	                            	<i class="icon-lock" title="Đơn hàng đã bị khóa">&nbsp</i>
	                            	@endif
	                            </div>
	                            <div class=" col-md-4"><?=$item['fullname']?></div>
	                        </div>
	                        <div class="col-md-6">
	                            <div class=" col-md-3"><?=date('d/m/Y H:i:s', strtotime($item['created_at']))?></div>
	                            <div class="total col-md-3"><?=number_format($item['total_amount'])?> <sup>₫</sup></div>
	                            <div class=" col-md-2"><?=$order_sources[$item['source']]?></div>
								<div class=" col-md-2"><?=$item['admin_fullname']?></div>
	                            <div class=" col-md-2">
	                                <i class="icon-view-fast" title="Xem nhanh" data-toggle="collapse" data-parent="#accordion3" href="#collapsea<?=$item['id']?>">&nbsp</i>
	                                <i class="icon-view-fast-hover" title="Xem nhanh" style="display: none;" data-toggle="collapse" data-parent="#accordion3" href="#collapsea<?=$item['id']?>">&nbsp</i>
	                                <a href="<?=route($controllerName.'.show',['id' => $item['id']])?>">
	                                    <i class="icon-view-detail" title="Xem chi tiết">$nbsp</i><i class="icon-view-detail-hover" title="Xem chi tiết" style="display: none;">$nbsp</i>
	                                </a>
	                            </div>
	                        </div>
	                        <div class="view_fast panel-collapse collapse" id="collapsea<?=$item['id']?>">
	                            <div class="col-md-8">
	                            	<div class="top_detail">
	                                	<div class="row">
		                                    <div class="col-md-6">
		                                    <?=$item['service_name']?>
		                                    </div> 
		                                    <div class="col-md-3 detail-price">
		                                        <p><?=number_format($item['total_amount'])?><sup>đ</sup></p>
		                                    </div>
		                                </div>
		                                @if(!empty($orders_detail[$item['id']]['product']))
		                                @foreach($orders_detail[$item['id']]['product'] as $detail)
		                                <div class="row">
		                                    <div class="col-md-6">
		                                    <?=$detail['product']?>
		                                    </div>
		                                    <div class="col-md-3 detail-price">
		                                        <p><?=number_format($detail['price'])?><sup>đ</sup></p>
		                                    </div>
		                                    <div class="col-md-3 detail-price">
		                                        <p><?=number_format($detail['price'])?><sup>đ</sup></p>
		                                    </div>
		                                </div>
		                                @endforeach
		                                @endif
	                                    <div class="row money">
	                                        <!-- <p>Phí vận chuyển và thu tiền tận nơi : <span>+0</span> <sup>đ</sup></p>
	                                        <p>Giảm giá Coupon : <span>-0</span> <sup>đ</sup></p> -->
	                                        <p><b>Tổng cộng : <span class="origane"><?=number_format($item['total_amount'])?></span> <sup>đ</sup></b></p>
	                                        {{--<p>Khối lượng đơn hàng : <span class="orange">100gr</span></p>--}}
	                                        {{--<p>Đơn hàng này được tích lũy: <span class="origane">+1990 <span>myXu</span></span></p>--}}
	                                    </div>
	                                    <div class="row">
	                                        <i class="icon-method">&nbsp</i>
	                                        <p><b class="title">Phương thúc thanh toán: </b> <span><?=$item['payment']??'Tiền mặt'?></span></p>
	                                    </div>
	                                </div>
	                                <div class="bottom_detail" style="min-height: 60px;">
	                                    <i class="icon-note">&nbsp</i>
	                                    <b class="title"> Ghi chú đơn hàng: </b>
	                                    <p><?=$item['customer_note']?></p>
	                                </div>

	                                @if($item['is_lock'] == 1)
	                                <a href="#" class="lock">
	                                	<i class="icon-lock-hover">&nbsp</i> Đơn hàng này đã bị khóa
	                                </a>
	                                @endif
	                            </div>
	                            <div class="col-md-4">
	                                <div class="address">
	                                    <div class="address_send">
	                                        <p class="title">Địa điểm sử dụng dịch vụ</p>
	                                        <div class="body">
												<div class="row">
													<div class="col-md-3"><b>Địa chỉ: </b></div>
													<div class="col-md-9"><span><?=$item['address_location']?></span></div>
												</div>
												<div class="row">
													<div class="col-md-3"><b>Số nhà: </b></div>
													<div class="col-md-9"><span><?=$item['address_number']?></span></div>
												</div>
											</div>
											<p class="title">Người liên hệ</p>
											<div class="body">
	                                            <div class="row">
	                                                <div class="col-md-3"><b>Tên liên hệ: </b></div>
	                                                <div class="col-md-9">
	                                                	<span><?=$item['fullname']?></span>
	                                                </div>
	                                            </div>
	                                            <div class="row">
	                                                <div class="col-md-3"><b>Điện thoại: </b></div>
	                                                <div class="col-md-9"><span><?=$item['phone']?></span></div>
	                                            </div>
	                                            <div class="row">
	                                                <div class="col-md-3"><b>Email: </b></div>
	                                                <div class="col-md-9"><span><?=$item['email']?></span></div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                                <button type="button" data-href="<?=route($controllerName.'.edit',['id' => $item['id']])?>"
											class="btn_edit @if($item['is_lock'] == 1) btn_lock_edit @endif">Sửa đơn hàng</button>
	                            </div>
	                        </div>
	                    </li>
	                    @endforeach
	                </ul>
	                <p class="total_donhang">Tổng đơn hàng: <span class="number"><?=$objects['total']?></span></p>
	                <div class="total_bill">
	                    <p>Tổng doanh thu: <span><?=number_format($revenue)?></span> <sup>đ</sup></p>
	                    <p>Đã thu: <span><?=number_format($paymented)?></span> <sup>đ</sup></p>
	                </div>
	                @endif
	            </div>
	            @include($controllerName.'.paginator')
	        </section>
	    </div>
	</div>
@endsection

@section('after_styles')
	<!-- iCheck -->
	<link href="/assets/plugins/iCheck/skins/flat/green.css" rel="stylesheet">
<style type="text/css">
	label.error{
		display: none !important;
	}
	.top_detail .row{
        padding: 10px;
    }
</style>
@endsection

@section('after_scripts')
	<!-- iCheck -->
	<script src="/assets/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
    	var $booking_status 	= <?=json_encode($booking_status)?>;
    	var $status 		= <?=!empty($params['status'])?json_encode($params['status']):'{}'?>;
    	var $paymentDescription 	= <?=json_encode($paymentDescription)?>;
    	var $payment 		= <?=!empty($params['payment_id'])?json_encode($params['payment_id']):'{}'?>;
    	
        $(function(){
            localStorage.clear();

            init_icheck('input.flat');
        	init_datepicker('.datepicker');
        	init_select2('.select2');
        	set_data_order_status();
        	set_data_payment();
        	get_provinces('.provinces');

        	$('.btn_edit').on('click', function () {
				if ($(this).hasClass('btn_lock_edit')) return false;
				location.href = $(this).attr('data-href');
            });
        	$(document).on('change','.change_status_order input',function(){
        		if (!$(this).prop('checked'))
        			return;
        		var flag = true;
        		var status = $('#order_status').val();
        		if(status == ''){
        			malert('Chọn trạng thái đơn hàng','Thông báo',function () {
                        if (flag) $('.change_status_order input').prop('checked', false).change()
                    });
        			return;
        		}
                var data = {status: status};
                var title = "Bạn có chắc chắn muốn chuyển trạng thái đơn hàng?";
                malert(title, 'Xác nhận chuyển trạng thái', function () {
                        if (flag) $('.change_status_order input').prop('checked', false).change()
                    }, function(){
                        flag = false;
                        var ids = [];
                        $('.checkbox_check:checked').each(function () {
                            ids.push($(this).val())
                        });
                        data.ids = ids;
                        
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
        	})
        	
        });
        function set_data_order_status(){
    		var countClass = 1;
    		var html = '';
    		$.each($booking_status,function(k,v){  
    			html = '<input type="checkbox" name="status[]" value="'+v.booking_status_id+'" ';
    			if($.inArray( v.status, $status ) >= 0)
    				html += ' checked ';
    			html += ' >'+v.booking_status_name+'<br>';

    			$('.order_status_'+countClass).append(html);
    			countClass++;
    			if(countClass > 4)
    				countClass = 1;
    		});
    	}
    	function set_data_payment(){
    		var countClass = 1;
    		var html = '';
    		$.each($paymentDescription,function(k,v){
    			
    			var payment_id = v.payment_id.toString();
    			html = '<input type="checkbox" name="payment_id[]" value="'+payment_id+'" ';
    			if($.inArray( payment_id, $payment ) >= 0){
    				console.log(payment_id);
    				html += ' checked ';
    			}
    			html += ' >'+v.payment+'<br>';

    			$('.payment_'+countClass).append(html);
    			countClass++;
    			if(countClass > 4)
    				countClass = 1;
    		});
    	}
    </script>
@endsection