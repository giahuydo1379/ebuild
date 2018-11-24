@extends('layouts.master')

@section('content')

<div class="col-md-">
    <form id="frm_booking" action="/order/store" method="post">
    <div class="wrap_view booking">
        <div class="header">
            <h2 class="title">Tạo đơn hàng mới</h2> 
            <ul class="top-action">
                <li><a href="#"><i class="icon icon-link">&nbsp</i> Web</a></li>
                <li><a href="#"><i class="icon icon-print">&nbsp</i> In</a></li>
                <li><a href="#"><i class="icon icon-edit">&nbsp</i> Sửa</a></li>
                <li><a href="#"><i class="icon icon-save">&nbsp</i> Lưu</a></li>
            </ul>
        </div>
        <div class="first_step" id="first_step">
            <div class="list-serices">
                <div class="title-block">
                    <div class="step active"><b>1</b><span>/3</span></div>
                    <h2 class="title">Chọn dịch vụ <span>(Vui lòng chọn dịch vụ bên dưới)</span></h2>
                </div>
                @foreach($categories as $cate)
                    @if(!empty($packages[$cate['category_id']]))
                        @foreach($packages[$cate['category_id']] as $pack)
                <div class="col-md-6">
                    <div class="radio">
                        <input type="radio" name="package_id" id="package_id_<?=$pack['id']?>" value="<?=$pack['id']?>" <?=@($object['package_id'] == $pack['id']?'checked':'')?> ><label for="package_id_<?=$pack['id']?>"><?=$cate['category']?>, <?=$pack['package_name']?></label>
                    </div>
                </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
            <div class="wrap_btn">
                <a href="javascript:void(0)">Hủy bỏ</a>
                <a href="javascript:void(0)" id="next1" class="btn_next">Tiếp tục</a>
            </div>
        </div>
        <!-- end first step -->
        <div class="second_step" id="second_step">
            <div class="title-block">
                <div class="step"><b>2</b><span>/3</span></div>
                <h2 class="title">Chọn thời gian chăm sóc xe <span>(Vui lòng chọn thời gian bên dưới)</span></h2>
            </div>
            <div class="calendar html_time">
            </div>
            <div class="wrap_btn">
                <a href="javascript:void(0)" id="prev1">Hủy bỏ</a>
                <a href="javascript:void(0)" class="btn_next" id="next2" class="btn_next">Tiếp tục</a>
            </div>
        </div>
        <!--end second step-->
        <div class="third_step choose-other" id="third_step">
            <div class="title-block">
                <div class="step"><b>3</b><span>/3</span></div>
                <h2 class="title">Chọn dịch vụ thêm & Thông tin khách hàng <span>(Vui lòng điền vào thông tin bên dưới)</span></h2>
            </div>
            <div class="content">
                <div class="col-xs-5 left no-padding-left">
                    <h3 class="title">Chọn dịch vụ thêm</h3>
                    @foreach($service_extra as $item)
                    <div class="checkbox">
                        <input id="service_extra<?=$item['product_id']?>" type="checkbox" name="service_extra[]" value="<?=$item['product_id']?>" >
                        <label for="service_extra<?=$item['product_id']?>"><?=$item['product']?> <b>- <?=number_format($item['price'])?>vnđ/ lần </b></label>
                    </div>
                    @endforeach
                </div>
                <div class="col-xs-7 right no-padding-right">
                    <h3 class="title">Thông tin khách hàng</h3>
                    <div class="wp-form">
                        <div class="form-group">
                            <div class="col-md-6 no-padding-left">
                                <label class="lb-form" for="">Họ tên: <span class="red">*</span></label>
                                <input type="text" class="form-control" name="fullname" value="<?=@$object['fullname']?>">
                                <label for="fullname" class="error">Nhập họ tên khách hàng</label>
                            </div>
                            <div class="col-md-6 no-padding-left">
                                <label class="lb-form" for="">Anh/ Chị:</label>
                                <div class="radio">
                                    <input type="radio" name="gender" id="male" value="male"  <?=@$object['gender'] == 'male'?'checked':''?> > <label for="male">Nam</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" name="gender" id="female" value="female" <?=@$object['gender'] == 'female'?'checked':''?> > <label for="female">Nữ</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="lb-form" for="">Điện thoại: <span class="red">*</span></label>
                            <input type="text" class="form-control" name="phone" value="<?=@$object['phone']?>">
                            <label for="phone" class="error">Nhập số điện thoại</label>
                        </div>
                        <div class="form-group">
                            <label class="lb-form" for="">Địa chỉ email: <span class="red">*</span></label>
                            <input type="text" class="form-control" name="email" value="<?=@$object['email']?>">
                            <label for="email" class="error">Nhập địa chỉ email</label>
                        </div>                    
                    </div>
                </div>
            </div>
            <div class="wrap_btn">
                <input type="hidden" name="order_id" value="<?=$object['order_id']??''?>">
                <input type="hidden" name="time" id="time">
                <a href="javascript:void(0)" id="prev2">Hủy bỏ</a>
                <a href="javascript:void(0)" class="btn_next" id="btn_submit"><?=!empty($object)?'Cập nhật đơn hàng':'Tạo đơn hàng'?></a>
            </div>
        </div>
    </div>
    </form>
</div>

@stop

@section('after_styles')
<style type="text/css">
	label.error{
		display: none;
	}
    #Modal12,#alert_success,#modal_alert{
        z-index: 100000000;
    }
    .first_step .top_detail .wrap .row {
        border-bottom: 1px solid #e1e1e1;
    }
</style>
@endsection
<?php
$ver_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')

    
<script type="text/javascript">
    var $order  = <?=!empty($object)?json_encode($object):'{}'?>;
    var $orderDetail  = <?=!empty($orderDetail)?json_encode($orderDetail):'{}'?>;
    
    $(function(){        
        if($orderDetail.product){
            $.each($orderDetail.product,function(k,v){
                $('#service_extra'+v.product_id).trigger('click');
            });
        }
        $('#frm_booking').validate({
            rules:{
                fullname : "required",
                phone : "required",
                email : {
                    required :true,
                    email:true
                }
            },
            messages:{
                fullname: 'Vui lòng nhập họ tên',
                phone: 'Vui lòng nhập số điện thoại',
                email: {
                    required: "Vui lòng nhập email",
                    email:"Email không đúng"
                }
            },
            submitHandler: function(form) {
                var data = $(form).serializeArray();
                var url = $(form).attr('action');
                ajax_loading(true);
                $.post(url,data,function(res){
                    ajax_loading(false);               
                    if(res.rs == 1){
                        alert_success(res.msg,function(){
                            @if (isset($object))
                                location.href = '/order/show/'+res.order_id;
                            @else
                                location.href = '/order/'+res.order_id;
                            @endif
                        });
                    }
                });
                return false;
            }
        });
        $('#btn_submit').on('click',function(){
            if($('input[name="package_id"]:checked').val()==undefined){
                alert_success('Vui lòng chọn dịch vụ');
                return false;
            }

            if(!$('#time').val()){
                alert_success('Vui lòng chọn thời gian');
                return false;
            }

            $('#frm_booking').submit();
            return false;
        });

        function active_time(){
            if(!$order)
                return false;
            $('.list-date .btn_time').each(function(){
                if($order.time == $(this).data('time')){                    
                    $(this).trigger('click');
                }
            });
        }
        function set_list_time(page){
            var package_id = $('input[name="package_id"]:checked').val();
            var order_id = '';
            if($order)
                order_id = $order.order_id;

            $.post('/order/get-list-time',{page:page,package_id:package_id,order_id:order_id},function(res){
                if(res.data){
                    $('.html_time').html(res.data);
                    active_time();
                }
            });
        }
        $(document).on('click','.time_next, .time_prev',function(){
            set_list_time($(this).data('page'));
        });

        $(document).on('click','#next1',function(){
            if($('input[name="package_id"]:checked').val()==undefined) {
                malert('Vui lòng chọn dịch vụ')
                return false;
            }

            var page = 1;
            if($order){
                page = $order.current_page;
            }
            console.log(page);
            set_list_time(page);

            $("#second_step .step").addClass("active");
            $(".first_step .step").removeClass("active");
            $("#third_step .step").removeClass("active");
            $('html, body').animate({
                scrollTop: $("#second_step").offset().top - 20
            }, 700);

            $(".first_step").addClass("select");
            $(".second_step").removeClass("select");
            $(".second_step").css("opacity", "1");
            $(".first_step").css("opacity", "0.5");
            return false;
        });

        $(document).on('click','.btn_time',function(){
            $('.title_month').html('Tháng '+$(this).data('month'));
            $('#time').val($(this).data('time'));
            $(".list-time .btn-available").removeClass("active");
            $(this).addClass("active");
            $(this).closest('.list-date').find('.item').removeClass("active");
            $(this).closest('.item').addClass('active');
            return false;
        });

        $(document).on('click','#next2',function(){
            console.log($('#time').val());
            if(!$('#time').val()) {
                malert('Vui lòng chọn thời gian');
                return false;
            }

            $("#third_step .step").addClass("active");
            $("#second_step .step").removeClass("active");
            $(".first_step .step").removeClass("active");
            $('html, body').animate({
                scrollTop: $("#third_step").offset().top - 20
            }, 700);

            
            $(".third_step").css("opacity", "1");
            $(".second_step").css("opacity", "0.5");
            return false;
        });
        $(".second_step #prev1").click(function() {
            $(".first_step").removeClass("select");
            $(".second_step").addClass("select");
            $(".first_step").css("opacity", "1");
            $(".second_step").css("opacity", "0.5");
        });
        $(".third_step #prev2").click(function() {
            $("#second_step .step").addClass("active");
            $(".first_step .step").removeClass("active");
            $("#third_step .step").removeClass("active");
            $('html, body').animate({
                scrollTop: $("#second_step").offset().top - 120
            }, 700);
            $(".second_step").css("opacity", "1");
            $(".third_step").css("opacity", "0.5");
        });
    });
</script>
@stop