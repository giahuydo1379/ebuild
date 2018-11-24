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
                <p><b>Vệ sinh máy lạnh gồm:</b> <span id="freezers"></span></p>               
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
        $(function(){
 
            var freezers_data_step2 = localStorage.getItem('freezers_data_step2');

            if(!freezers_data_step2){
                window.location.href = url;
                return false;
            }

            freezers_data_step2 = JSON.parse(freezers_data_step2);            
            var total_amount = 0;
            data = [];
            $.each(freezers_data_step2,function(k,v){
                data[v.name] = v.value;
            });
            var address = data.address_number + ' ' + data.address_location;
            var date    = 'Ngày <b>'+data.start_date+'</b> lúc <b>'+data.time+'</b>';
            var service_unit    = data.service_unit_description+' trong '+data.service_unit_name;
            var freezers        = data.freezers_number+' máy';
            var name            = data.name;
            var phone           = data.phone;
            var email           = data.email;
            var total_amount    = data.total_amount;
            var payment_method  = data.payment_method;

            $('#address').html(address);
            $('#date').html(date);
            $('#freezers').html(freezers);
            $('#name').html(name);
            $('#phone').html(phone);
            $('#email').html(email);
            $('#payment_method').html(payment_method);
            $('#total_amount').html(numeral(total_amount).format('0,0')+'VND');
            $(document).on('click','#btn_submit',function(){
                ajax_loading(true);
                $.post('/booking',freezers_data_step2,function(res){
                    ajax_loading(false);
                    if(res.rs){
                        alert_success(res.msg, function () {
                            localStorage.removeItem('freezers_data_step2');
                            localStorage.removeItem('freezers_data_step1');

                            window.location.href = '<?=route('booking.index')?>';
                        });
                    }else{
                        malert(res.msg);
                    }
                });
            });
            $(document).on('click','i.back',function(){
                window.location.href = url+'?step=2';
            });
        });
    </script>
@stop
