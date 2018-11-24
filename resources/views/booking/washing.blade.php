@extends('layouts.master')

@section('content')
<!--page-container-->
<div class="col-md-">
    <div class="wrap_view view_product create_product">
        <div class="header">
            <h2 class="title"><?=isset($object['id'])?'Cập nhật đơn hàng: '.$object['service_name'].' - ['.$object['code'].']'
                    :'Thêm mới đơn hàng - '.$service['name']?></h2>
            <a href="<?=route('booking.show', ['id' => $object['id']])?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
        </div>
    </div>

    <section class="clean-house form">
        <form action="" id="frm_step1">
            <div class="form-group">
                <label class="label"><span class="icon icon-location">&nbsp;</span> Địa điểm sử dụng dịch vụ</label>
                <div class="ip_">
                    <input type="text" class="form-control" name="address_location" id="address_location" value="<?=@$object['address_location']?>">
                    <span class="icon icon-search">&nbsp;</span>
                    <label id="address_location-error" for="address_location" class="error"></label>
                </div>
            </div>
            <div class="form-group">
                <label class="label"><span class="icon icon-no-home">&nbsp;</span> Số nhà / Căn hộ</label>
                <input type="text" class="form-control" placeholder="Vd: 125" name="address_number" id="address_number"
                       value="<?=@$object['address_number']?>">
                <label id="address_number-error" for="address_number" class="error"></label>
            </div>
            <div class="form-group">
                <div class="col-xs-6 no-padding-left">
                    <label class="label"><span class="icon icon-calendar">&nbsp;</span> Ngày nhận đồ</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="<?=@$object['start_date']?>">
                    <label id="start_date-error" for="start_date" class="error"></label>
                </div>
                <div class="col-xs-6 no-padding-right">
                    <label class="label"><span class="icon icon-time">&nbsp;</span> Chọn giờ</label>
                    <input type="text" class="form-control" id="start_time" name="start_time" value="<?=@$object['time']?>">
                    <label id="start_time-error" for="start_time" class="error"></label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-6 no-padding-left">
                    <label class="label"><span class="icon icon-calendar">&nbsp;</span> Ngày trả đồ</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" value="<?=@$object['end_date']?>">
                    <label id="end_date-error" for="end_date" class="error"></label>
                </div>
                <div class="col-xs-6 no-padding-right">
                    <label class="label"><span class="icon icon-time">&nbsp;</span> Chọn giờ</label>
                    <input type="text" class="form-control" id="end_time" name="end_time" value="<?=@$object['end_time']?>">
                    <label id="end_time-error" for="end_time" class="error"></label>
                </div>
            </div>
            <?php
                if (isset($booking_detail[$washing_water_id])) {
                    $value = $booking_detail[$washing_water_id]['service_unit_quantity'];
                    unset($booking_detail[$washing_water_id]);
                } else {
                    $value = '';
                }
            ?>
            <div class="form-group">
                <label class="label"><span class="icon icon-wash-water">&nbsp;</span> Giặt nước <span>(Tính theo Kg - <?=number_format($washing_water_price)?>đ/kg)</span></label>
                <input value="<?=$value?>" type="number" class="form-control" data-price="<?=$washing_water_price?>" placeholder="Tối thiểu 3kg" id="washing_water" name="services_units[<?=$washing_water_id?>]">
                <label for="" class="error">Bạn vui lòng nhập lại khối lượng quần áo (tối thiểu 3kg)</label>
            </div>
            <div class="form-group">
                <label class="label"><span class="icon icon-wash-kho">&nbsp;</span> Giặt khô <span>(Tính theo loại quần áo)</span>
                    <a href="<?=$url?>?step=2" class="link pull-right">Thêm <i class="fa fa-plus-circle"></i></a></label>
                <ul class="list-clothes list_clothes" style="display: none;"></ul>
            </div>
            @if(!empty($services_extra))
                <?php
                if (isset($booking_detail[$services_extra['id']])) {
                    $value = $booking_detail[$services_extra['id']]['service_unit_description'];
                    unset($booking_detail[$services_extra['id']]);
                } else {
                    $value = '';
                }
                ?>
            <div class="form-group">
                <label class="label"><span class="icon icon-other">&nbsp;</span> Khác <span>(+<?=number_format($services_extra['price'])?>vnđ)</span></label>
                <textarea name="services_extra[<?=$services_extra['id']?>]" id="services_extra" data-price="<?=$services_extra['price']?>" cols="30" rows="10" class="form-control" placeholder="Nhập yêu cầu/ mô tả đồ muốn gặt"><?=@$value?></textarea>
                <label for="" class="error">Nhập yêu cầu, mô tả</label>
            </div>
            @endif
            <div class="wrap-total-price">
                <div class="total">
                    <input type="hidden" name="total_amount" id="total_amount" class="">
                    <p class="total-price">Tổng số tiền dịch vụ: <span class="price pull-right total_amount_text"></span></p>
                    <p class="note"><i>(Bao gồm phí dịch vụ + Dịch vụ thêm + Phụ cấp)</i></p>
                </div>
                <input type="hidden" name="service_id" value="<?=$service_id?>">
                <a href="javascript:void(0)" class="btn btn-primary btn-next" id="btn_submit">Tiếp theo <i class="fa fa-arrow-right"></i></a>
            </div>
        </form>
    </section>
</div>
@stop
@section('after_styles')
    <link href="/assets/plugins/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <style type="text/css">
        label.error{
            display: none;
        }
    </style>
@stop
@section('after_scripts')
    <script type="text/javascript" src="/assets/plugins/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js"></script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script type="text/javascript">
        var url = '<?=$url?>';
        $(function(){        
            var data_step1 = localStorage.getItem('washing_data_step1');
            if(data_step1){
                data_step1 = JSON.parse(data_step1);
                var data = [];
                var services_extra = [];
                $.each(data_step1,function(k,v){
                    data[v.name] = v.value;
                    if(v.name == 'services_extra[]'){
                        services_extra.push(v.value);
                    }
                });
                
                $('.services_extra').each(function(){                    
                    if(jQuery.inArray($(this).val(),services_extra) != -1)
                        $(this).trigger('click');
                });

                $('#address_location').val(data.address_location);
                $('#address_number').val(data.address_number);                
                $('#start_date').val(data.start_date);
                $('#start_time').val(data.start_time);
                $('#end_date').val(data.end_date);
                $('#end_time').val(data.end_time);
                $('#total_amount').val(data.total_amount);

                $('#services_extra').val(data.services_extra_text);
                $('#washing_water').val(data.washing_water);
            }
            var data_step2 = localStorage.getItem("washing_data_step2");

            <?php
                $tmp = [];
                if (isset($booking_detail) && count($booking_detail) > 0) {
                    foreach($booking_detail as $item) {
                        $tmp[] = [
                            "services_units_id" => $item['service_unit_id'],
                            "name" => $item['service_unit_name'],
                            "amount" => $item['service_unit_quantity'],
                            "price" => $item['service_unit_price']
                        ];
                    }
                ?>
            if (!data_step2) {
                data_step2 = '<?=json_encode($tmp)?>';
                localStorage.setItem("washing_data_step2", data_step2);
            }
            <?php } ?>

            if(data_step2){
                data_step2 = JSON.parse(data_step2);                
                setHtmlListClothes(data_step2);
            }
            setTotalAmount();
            init_date('#start_date');
            init_date('#end_date');
            init_time('#start_time');
            init_time('#end_time');

            $('#services_extra').on('change',function(){
                setTotalAmount();
                return false;
            });

            $(document).on('change','#washing_water',function(){
                if($(this).val()){
                    if($(this).val() < 0){
                        $(this).val('');
                    }else if($(this).val() < 3){
                        $(this).val(3);
                    }
                }  
                setTotalAmount();
                return false;
            });

            $('#service_unit').trigger('change');
            //setTotalAmount();
            $(document).on('click','#btn_submit',function(){
                if(!$('#washing_water').val() && $('.item_clothes').length < 1){
                    malert('Bạn phải sử dựng ít nhất 1 dịch vụ');
                    return false;
                }
                $('#frm_step1').submit();
                return false;
            });

            $(document).on('click','.remove_clothes',function(){   
                var element = $(this).closest('li.item_clothes');                
                var services_units_id = [];
                if(data_step2){
                    var tmp = [];
                    $(data_step2).each(function(k,v){
                        if(v.services_units_id != element.data('id')){
                            tmp.push(v);
                        }
                    });
                    localStorage.setItem("washing_data_step2", JSON.stringify(tmp));
                }
                element.remove();
                setTotalAmount();
                return false;
            });

            $(document).on('click','i.back',function(){                
                window.location.href = '/services';
            });

            $('#frm_step1').validate({
                //ignore: "",
                rules:{
                    'address_location': {
                        required: true
                    },
                    'address_number': {
                        required: true
                    },
                    'start_date': {
                        required: true
                    },
                    'start_time': {
                        required: true
                    },
                    'end_date': {
                        required: true
                    },
                    'end_time': {
                        required: true
                    },
                },
                messages:{
                    'address_location': {
                        required: 'Vui lòng nhập địa điểm sử dụng'
                    },
                    'address_number': {
                        required: 'Vui lòng nhập số nhà / căn hộ'
                    },
                    'start_date': {
                        required: 'Vui lòng chọn ngày nhận đồ'
                    },
                    'start_time': {
                        required: 'Vui lòng chọn giờ'
                    },
                    'end_date': {
                        required: 'Vui lòng chọn ngày trả đồ'
                    },
                    'end_time': {
                        required: 'Vui lòng chọn giờ'
                    },
                },
                submitHandler: function(form) {
                    var data = $(form).serializeArray();

                    var tmp = {
                        "name":"services_extra_text",
                        "value":$('#services_extra').val(),
                    }
                    data.push(tmp);
                    tmp = {
                        "name":"washing_water",
                        "value":$('#washing_water').val(),
                    }
                    data.push(tmp);
                    localStorage.setItem("washing_data_step1", JSON.stringify(data));
                    
                    window.location.href = url+'?step=3';
                    return false;
                }
            });
        });
        function setTotalAmount(){
            var washing_dry_price = 0;
            var washing_dry = localStorage.getItem("washing_data_step2");
            if(washing_dry){
                washing_dry = JSON.parse(washing_dry);

                $.each(washing_dry,function(k,v){
                    washing_dry_price += v.amount * v.price;
                });
            }
            var washing_water_price = 0;
            if($('#washing_water').val() > 0){
                washing_water_price = parseFloat($('#washing_water').data('price')) * parseFloat($('#washing_water').val());
            }            
            var services_extra = 0;
            if($('#services_extra').val()){
                services_extra = $('#services_extra').data('price');
            }

            var total_amount = washing_dry_price + washing_water_price + services_extra;

            $('#total_amount').val(total_amount);
            $('.total_amount_text').text(numeral(total_amount).format('0,0')+'VND');
        }
        function setHtmlListClothes(washing_dry){
            var html = '';
            $.each(washing_dry,function(k,v){
                html += '<li class="item_clothes" data-id="'+v.services_units_id+'">';
                html += '    <div class="col-xs-5 no-padding">'+v.name+'</div>';
                html += '    <div class="col-xs-2 no-padding color text-center">x '+v.amount+'</div>';
                html += '    <div class="col-xs-5 no-padding text-center">'+numeral(v.amount * v.price).format('0,0')+'VNĐ <i class="fa fa-times-circle remove_clothes"></i></div>';
                html += '<input type="hidden" name="services_units['+v.services_units_id+']" value="'+v.amount+'" >';
                html += '</li>';
            });
            $('.list_clothes').html(html).show();
        }
    </script>
@stop