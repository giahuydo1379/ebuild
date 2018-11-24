<!DOCTYPE html>
<html lang="vi">
<head>	
	<meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Chi tiết đơn hàng</title>
	<style>	
	h1 {
		margin: 0;
	}
	table {
		border-collapse: collapse;
		border-spacing: 0;
		background-color: transparent;
		width: 100%;
		max-width: 100%;
		margin-bottom: 20px;
	}
	.table-information .table {
		border: 1px solid #f8ba05;
	}
	.table-information .table thead th {
		background: #f8ba05;
		padding: 10px;
		color: #000;
	}
	.table-information .table tbody td {
		padding: 10px;
		color: #000;
		font-weight: 500;
	}
	.table > thead > tr > th {
		vertical-align: bottom;
		border-bottom: 2px solid #ddd;
	}
	.text-left {
		text-align: left;
	}
	.text-center {
		text-align: center;
	}
	.table-striped > tbody > tr:nth-of-type(odd) {
		background-color: #f9f9f9;
	}
	.information {
		padding: 0 20px;
		border: 1px solid #f8ba05;
		margin-bottom: 10px;
	}
	.information .header {
		font-size: 16px;
		text-transform: uppercase;
		color: #000;
		font-weight: 700;
		line-height: 40px;
		border-bottom: 1px solid #f8ba05;
	}
	.information .body {
		padding: 15px 0;
		line-height: 28px;
	}
	label {
		display: inline-block;
		max-width: 100%;
		margin-bottom: 5px;
		font-weight: bold;
	}
	.information .info label {
		float: left;
	}
	.information .info .person {
		float: left;
		margin-left: 10px;
	}
	.information .body .person span {
		display: block;
	}
	.row:after {
		clear: both;
	}
	.row:before, .row:after  {
		content: " ";
		display: table;
	}
	footer .bottom-footer {
		text-align: center;
		background: #f8ba05;
		padding: 10px 0px;
		display: inline-block;
		width: 100%;
		color: #000;
	}
	footer .bottom-footer a {
		text-decoration: none;
	}
	.left {
		float: left;
	}
	.right {
		float: right;
	}
	.red {
	    color: #ff0000;
	}
	.yellow {
		color: #f8ba05;
	}
		.table-information p {
			line-height: 10px;
		}
	</style>
</head>
<body style="font-family: 'DejaVu Sans';width: 100% !important;;
    margin-left: auto;
    margin-right: auto;font-size: 14px;">
<?php
$settings = \App\Helpers\General::get_settings();
?>
<header>
    <div class="container" style="border-bottom: 4px solid #000000;">
        <div class="left">
            <h1 class="logo"><a href="/"><img style="height: 100px" src="<?=url(@$settings['image_logo_header']['value'])?>" alt="logo"></a></h1>
        </div>
        <div class="right" style="
    text-align: center;
    padding: 27px 50px;">
            <strong>Hotline<br> <b class="red"><?=$settings['hotline']['value'] ?? ''?></b></strong>
		</div>
		<div style="clear:both;"></div>
    </div>
</header>
<div style="clear:both;"></div>
	<section class="container booking-success">
		<div class="table-information text-center">
			<div class="text text-left">
				<p>Cảm ơn bạn đã sử dụng dịch vụ của <span class="yellow"><b>{{ config('app.name', '') }}</b></span>. Mã đơn hàng của bạn là <b class="yellow"><?=$order['code']?></b></p>
				<P>Bạn sử dụng dịch vụ lúc: <span class="yellow"><?=\App\Helpers\General::output_date($order['start_date'], true, 'H:i d-m-Y')?></span></P>
				<P>Sử dụng: <?=$order['service_name']?>, Các dịch vụ bạn đã đặt bao gồm:</P>
	        </div>

	        <div class="table-responsive">
	            <table class="table table-striped text-left">
	                <thead>
	                <tr>
	                    <th>Mã dịch vụ</th>
	                    <th>Tên dịch vụ</th>
	                    <th>Phí dịch vụ</th>
	                    <th class="text-center">Tổng chi phí</th>
	                </tr>
	                </thead>
	                <tbody>
	                @if(!empty($order_detail))
	                @foreach($order_detail as $key => $item)
	                    <tr>
	                        <td><?=$item['service_unit_id']?></td>
	                        <td><?=$item['service_unit_name']?></td>
	                        <td><?=number_format($item['service_unit_price'])?><sup>đ</sup> x <?=$item['service_unit_quantity']?></td>
	                        <td class="text-center"><?=number_format($item['service_unit_price'] * $item['service_unit_quantity'])?><sup>đ</sup></td>
	                    </tr>
	                @endforeach
	                @endif

					@if(!empty($service_extra_ids))
						@foreach($service_extra_ids as $item)
							<tr>
								<td>EX<?=$item['id']?></td>
								<td><?=$services_extra[$item['id']]?></td>
								<td><?=number_format($item['price'])?><sup>đ</sup></td>
								<td class="text-center"><?=number_format($item['price'])?><sup>đ</sup></td>
							</tr>
						@endforeach
					@endif

					@if($order['benefit'])
						<tr>
							<td colspan="2">Phụ cấp để kiếm người làm nhanh hơn</td>
							<td><?=number_format($order['benefit'])?><sup>đ</sup></td>
							<td class="text-center"><?=number_format($order['benefit'])?><sup>đ</sup></td>
						</tr>
					@endif

	                    <tr>
	                        <td colspan="3">
	                            <p>Tổng tiền dịch vụ</p>
	                            {{--<p>Mã giảm giá: <span class="red">MDA123HJA</span></p>--}}
	                        </td>
	                        <td class="text-center">
	                            <p class="red"><?=number_format($order['subtotal'])?>đ</p>
	                            {{--<p> <span class="red">40.000đ</span></p>--}}
	                        </td>
	                    </tr>
	                    <tr>
	                        <td colspan="3">
	                            TỔNG GIÁ TRỊ DỊCH VỤ
	                        </td>
	                        <td class="text-center">
	                            <p class="red"><?=number_format($order['total_amount'])?>đ</p>
	                        </td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>

	        <div class="information text-left">
	            <div class="header">Thông tin khách hàng</div>
	            <div class="body">
	                <div class="row info">
	                    <label for="">Thông tin khách hàng: </label>
	                    <div class="person">
	                        <span>Họ tên khách hàng: <?=$order['fullname']?></span>
	                        <span>Điện thoại: <?=$order['phone']?> -   Email: <?=$order['email']?></span>
	                    </div>
	                </div>
	                <div class="row info">
	                	<label for="">Hình thức thanh toán:</label>
	                	<div class="person">
	                       <span>Tiền mặt</span>
	                    </div>
	                </div>
	                 <!-- <div class="row info">
	                	<label for="">Thời gian sử dụng dịch vụ:</label>
	                	<div class="person">
	                       <span><?=@$order['duration']?> phút</span>
	                    </div>
	                </div> -->
	            </div>
	        </div>
		</div>
	</section>
    <!--footer-->
	<footer>
		<div class="bottom-footer"><?=@$settings['copyright']['value']?></div>
	</footer>
</body>
</html>
