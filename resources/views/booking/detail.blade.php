<div class="row">
	<div class="col-md-4">
		<p><b>Khách hàng: </b><?=$customer['name']?></p>
		<p><b>Dịch vụ: </b><?=$service['name']?></p>
		<p><b>Ngày bắt đầu: </b><?=$start_date?></p>
		<p><b>Ngày kết thúc: </b><?=$end_date?></p>
		<p><b>Ghi chú: </b><?=$customer_note?></p>
	</div>
	<div class="col-md-4">
		<p><b>Họ tên: </b><?=$contact['name']?></p>
		<p><b>Điện thoại: </b><?=$contact['phone']?></p>
		<p><b>Emaili: </b><?=$contact['email']?></p>
		<p><b>Địa chỉ: </b><?=$address_number?> <?=$address_location?></p>
	</div>
	<div class="col-md-4">
		@if(!empty($booking_detail))
		@foreach($booking_detail as $item)
		<p><b><?=$item['service_unit']['name']?>: </b>: <?=number_format($item['service_unit_price'])?> x <?=$item['service_unit_quantity']?></p>
		
			@if(!empty($item['service_unit_description']))
		<p><b>Mô tả: </b><?=$item['service_unit_description']?></p>
			@endif

			@if(!empty($service_extra_ids))
				@foreach($service_extra_ids as $se_ex)
		<p><b><?=$service_extra[$se_ex['id']]?>: </b><?=number_format($se_ex['price'])?>vnđ</p>
				@endforeach
			@endif
		@endforeach
		@endif

		@if(!empty($booking_freezer_detail))
		<?php $services_freezer_units = $booking_freezer_detail['services_freezer_units'];?>
		<p><b>Loại máy: </b><?=$services_freezer_units['freezer']['name']?></p>
		<p><b>Công suất: </b><?=$services_freezer_units['freezer_capacity']['name']?>
		</p>
		<p><b>Số lượng: </b><?=$booking_freezer_detail['freezer_number']?></p>
		<p><b>Giá: </b><?=number_format($booking_freezer_detail['price'])?></p>

			@if(!empty($booking_freezer_detail['service_extra_ids']))
				@foreach($booking_freezer_detail['service_extra_ids'] as $se_ex)
		<p><b><?=$booking_freezer_detail['service_extra'][$se_ex['id']]?>: </b><?=number_format($se_ex['price'])?>vnđ</p>
				@endforeach
			@endif

		@endif
		<p><b>Benefit: </b><?=number_format($benefit)?></p>
		<p><b>Tổng tiền: </b><?=number_format($total_amount)?>vnđ</p>
	</div>
</div>