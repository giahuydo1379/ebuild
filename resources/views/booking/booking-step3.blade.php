@extends('layouts.master')

@section('content')
<div class="col-md-">
    <div class="wrap_view view_product create_product">
        <div class="header">
            <h2 class="title"><?=isset($object['id'])?'Cập nhật đơn hàng: '.$object['service_name'].' - ['.$object['code'].']':'Thêm mới đơn hàng'?></h2>
            <a href="<?=url()->previous();?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
        </div>
    </div>
    <section class="clean-house form">
        <form action="" id="frm_booking">
            <div class="block-infor">
                <label for="" class="title"><span class="icon icon-detail">&nbsp;</span> Chi tiết công việc</label>
                <p><b>Địa chỉ:</b> <span id="address"></span></p>
                
                <div class="show-time" id="date">
                    <p><b>Thời gian làm việc:</b> </p>
                    <div class="line-time line_time">
                    </div>
                </div>
                <p><b>Dọn dẹp nhà gồm:</b> <span id="service_unit"></span></p>
            </div>
            <div class="block-infor">
                <label for="" class="title"><span class="icon icon-contact">&nbsp;</span> Thông tin liên hệ</label>
                <p><b>Tên khách hàng:</b> <span id="name"></span></p>
                <p><b>Điện thoại:</b> <span id="phone"></span></p>
                <p><b>Địa chỉ Email:</b> <span id="email"></span></p>
            </div>
            <div class="block-infor">
                <label for="" class="title"><span class="icon icon-wallet">&nbsp;</span> Thông tin thanh toán</label>
                <p><b>Tổng số tiền: <span class="price" id="total_amount"></span></b></p>
                <p><b>Hình thức thanh toán: </b><span id="payment_method"></span></p>
            </div>
            <button type="button" class="btn btn-primary btn-final" id="btn_submit">Sử dụng dịch vụ</button>
        </form>
    </section>
</div>
@stop

@section('after_styles')
   
@stop

@section('after_scripts')
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script type="text/javascript">
        var url = '<?=$url?>';
        var $key_step1 = '<?=$key_step1?>';
        var $key_step2 = '<?=$key_step2?>';
        $(function(){
 
            var data_step2 = localStorage.getItem($key_step2);

            if(!data_step2){
                window.location.href = url;
                return false;
            }

            data_step2 = JSON.parse(data_step2);            
            var total_amount = 0;
            data = [];
            $.each(data_step2,function(k,v){
                data[v.name] = v.value;
            });
            $('.title_step').html(data.service_unit_name);
            var address = data.address_number + ' ' + data.address_location;
            if(data.end_date){
                date = '<p><b>Thời gian làm việc:</b> </p>';
                date += '<div class="line-time">';
                date += '<p><b>'+data.day_of_week+ '</b> lúc <b>'+data.time+'</b></p>';
                date += '<p>Từ <b>'+data.start_date+'</b> đến <b>'+data.end_date+'</p>';
                date += '</div>';
            }else{
                 date = '<p><b>Thời gian làm việc:</b> Ngày <b>'+data.start_date+'</b> lúc <b>'+data.time+'</b></p>';
            }
            var service_unit    = data.service_unit_description;
            if(service_unit)
                service_unit    += ', ';
            service_unit        += data.service_unit_name;
            var name            = data.name;
            var phone           = data.phone;
            var email           = data.email;
            var total_amount    = data.total_amount;
            var payment_method  = data.payment_method;

            $('#address').html(address);
            $('#date').html(date);
            $('#service_unit').html(service_unit);
            $('#name').html(name);
            $('#phone').html(phone);
            $('#email').html(email);
            $('#payment_method').html(payment_method);
            $('#total_amount').html(numeral(total_amount).format('0,0')+'VND');
            $(document).on('click','#btn_submit',function(){
                ajax_loading(true);
                $.post('/booking',data_step2,function(res){
                    ajax_loading(false);
                    if(res.rs){
                        alert_success(res.msg, function () {
                            localStorage.removeItem($key_step2);
                            localStorage.removeItem($key_step1);

                            window.location.href = '/booking';
                        });
                    }else{
                        alert_fail(res.msg);
                    }
                });
            });
            $(document).on('click','i.back',function(){
                window.location.href = url+'?step=2';
            });
        });
    </script>
@stop
