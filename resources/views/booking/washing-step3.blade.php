@extends('layouts.master')

@section('content')
<?php
$user = \App\Helpers\Auth::user();
?>
<!--page-container-->
<div class="col-md-">
    <div class="wrap_view view_product create_product">
        <div class="header">
            <h2 class="title"><?=isset($object['id'])?'Cập nhật đơn hàng: '.$object['service_name'].' - ['.$object['code'].']'
                    :'Thêm mới đơn hàng - '.$service['name']?></h2>
            <a href="<?=route('booking.edit', ['id' => $object['id']])?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
        </div>
    </div>

    <section class="clean-house form">
        <form action="" id="frm_step2">
            <input type="hidden" name="id" value="<?=@$object['id']?>">
            <input type="hidden" name="contact_id" value="<?=@$object['contact_id']?>">
            <div class="form-group">
                <label class="label"><span class="icon icon-name">&nbsp;</span> Tên liên hệ</label>
                <input type="text" class="form-control" name="name" id="name" value="<?=@$object['fullname']?>">
                <label for="" class="error">Vui lòng nhập tên</label>
            </div>
            <div class="form-group">
                <label class="label"><span class="icon icon-phone">&nbsp;</span> Số điện thoại</label>
                <input type="text" class="form-control" name="phone" id="phone" value="<?=@$object['phone']?>" placeholder="Vd: 0123.456.7891">
                <label for="" class="error">Vui lòng nhập số điện thoại</label>
            </div>
            <div class="form-group">
                <label class="label"><span class="icon icon-email">&nbsp;</span> Email <span>(để nhận biên nhận)</span></label>
                <input type="text" class="form-control" name="email" id="email" value="<?=@$object['email']?>" placeholder="Vd: nguyenvana@gmail.com">
                <label for="" class="error">Vui lòng nhập email</label>
            </div>
            <div class="form-group">
                <label class="label"><span class="icon icon-note">&nbsp;</span> Ghi chú cho người làm</label>
                <textarea class="form-control" name="customer_note" id="customer_note" cols="30" rows="10"><?=@$object['customer_note']?></textarea>
            </div>
            @if(!empty($payments_method))
            <div class="form-group">
                <label class="label"><span class="icon icon-wallet">&nbsp;</span> Hình thức thanh toán</label>
                <select name="payment_method_id" id="payment_method_id" class="form-control">
                    @foreach($payments_method as $item)
                    <option <?=isset($object) && $item['id']==$object['payment_method_id']?' selected':''?>
                            value="<?=$item['id']?>"><?=$item['name']?></option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="wrap-total-price">
                <div class="total">
                    <p class="total-price">Tổng số tiền dịch vụ: <span class="price pull-right total_amount_text"></span></p>
                    <p class="note"><i>(Bao gồm phí dịch vụ + Dịch vụ thêm + Phụ cấp)</i></p>
                </div>
                <a href="#" class="btn btn-primary btn-next" id="btn_submit">Tiếp theo <i class="fa fa-arrow-right"></i></a>
            </div>
        </form>
    </section>
</div>
@stop

@section('after_styles')
    <style type="text/css">
        label.error{
            display: none;
        }
    </style>
@stop

@section('after_scripts')
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script type="text/javascript">
        var url = '<?=$url?>';
        $(function(){
            var washing_data_step1 = localStorage.getItem('washing_data_step1');

            if(!washing_data_step1){
                window.location.href = url;
                return false;
            }
            
            var washing_data_step3 = localStorage.getItem('washing_data_step3');
            if(washing_data_step3){
                washing_data_step3 = JSON.parse(washing_data_step3);
                var data = [];
                $.each(washing_data_step3,function(k,v){
                    data[v.name] = v.value;
                });
                
                $('#name').val(data.name);
                $('#phone').val(data.phone);
                $('#email').val(data.email);
                $('#customer_note').val(data.customer_note);
                $('#payment_method_id').val(data.payment_method_id);
            }

            washing_data_step1 = JSON.parse(washing_data_step1);
            var total_amount = 0;
            $.each(washing_data_step1,function(k,v){
                if(v.name == 'total_amount'){
                    total_amount = v.value;
                }
            });
            $('.total_amount_text').text(numeral(total_amount).format('0,0')+'VND');

            $(document).on('click','#btn_submit',function(){
                $('#frm_step2').submit();
                return false;
            });
            $(document).on('click','i.back',function(){
                window.location.href = url;
            });

            $('#frm_step2').validate({
                //ignore: "",
                rules:{
                    'name': {
                        required: true
                    },
                    'phone': {
                        required: true
                    },
                    'email': {
                        required: true,
                        email:true,
                    },
                },
                messages:{
                    'name': {
                        required: 'Vui lòng nhập họ tên'
                    },
                    'phone': {
                        required: 'Vui lòng nhập số điện thoại'
                    },
                    'email': {
                        required: 'Vui lòng nhập email',
                        email: 'Email không đúng',
                    },
                },
                submitHandler: function(form) {
                    var data = $(form).serializeArray();
                    washing_data_step3 = $.merge(data, washing_data_step1);
                    var tmp = {
                        "name":"payment_method",
                        "value":$('#payment_method_id').find(":selected").text()
                    };
                    washing_data_step3.push(tmp);
                    
                    localStorage.setItem("washing_data_step3", JSON.stringify(washing_data_step3));

                    window.location.href = url+'?step=4';
                    return false;
                }
            })
        });
    </script>
@stop
