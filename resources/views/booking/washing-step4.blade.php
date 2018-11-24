@extends('layouts.master')

@section('content')
<!--page-container-->
<div class="col-md-">
    <div class="wrap_view view_product create_product">
        <div class="header">
            <h2 class="title"><?=isset($object['id'])?'Cập nhật đơn hàng: '.$object['service_name'].' - ['.$object['code'].']'
                    :'Thêm mới đơn hàng - '.$service['name']?></h2>
            <a href="<?=url()->previous();?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
        </div>
    </div>

    <section class="clean-house form">
        <form action="" id="frm_booking">
            <div class="block-infor">
                <label for="" class="title"><span class="icon icon-detail">&nbsp;</span> Chi tiết công việc</label>
                <p><b>Địa chỉ:</b> <span id="address"></span></p>
                <p><b>Thời gian làm việc:</b> <span id="date"></span></p>
                <p><b>Giặt ủi:</b> <span id="washing"></span></p>
                <p><b>Khác:</b> <span id="services_extra"></span></p>
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

@section('after_style')
   
@stop

@section('after_scripts')
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script type="text/javascript">
        var url = '<?=$url?>';
        $(function(){
 
            var washing_data_step3 = localStorage.getItem('washing_data_step3');

            if(!washing_data_step3){
                window.location.href = url;
                return false;
            }

            washing_data_step3 = JSON.parse(washing_data_step3);            
            console.log(washing_data_step3);
            var total_amount = 0;
            data = [];
            $.each(washing_data_step3,function(k,v){
                data[v.name] = v.value;
            });
            var address = data.address_number + ' ' + data.address_location;
            var date    = 'Từ ngày <b>'+data.start_date+'</b> lúc <b>'+data.start_time+'</b> đến ngày <b>'+data.end_date+'</b> lúc <b>'+data.end_time+'</b>';
            var services_extra    = data.services_extra_text;
            var name            = data.name;
            var phone           = data.phone;
            var email           = data.email;
            var total_amount    = data.total_amount;
            var payment_method  = data.payment_method;

            $('#address').html(address);
            $('#date').html(date);
            $('#services_extra').html(services_extra);
            $('#name').html(name);
            $('#phone').html(phone);
            $('#email').html(email);
            $('#payment_method').html(payment_method);
            $('#total_amount').html(numeral(total_amount).format('0,0')+'VND');

            $(document).on('click','#btn_submit',function(){
                ajax_loading(true);
                $.post('/booking',washing_data_step3,function(res){
                    ajax_loading(false);
                    if(res.rs){
                        alert_success(res.msg, function () {
                            localStorage.removeItem('washing_data_step3');
                            localStorage.removeItem('washing_data_step2');
                            localStorage.removeItem('washing_data_step1');

                            window.location.href = '<?=route('booking.index')?>';
                        });
                    }else{
                        malert(res.msg);
                    }
                });
            });
            $(document).on('click','i.back',function(){
                window.location.href = url+'?step=3';
            });
        });
    </script>
@stop
