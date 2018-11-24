@extends('layouts.master')

@section('content')
    <!--page-container-->
    <div class="col-md-">
        <div class="wrap_view view_product create_product">
            <div class="header">
                <h2 class="title"><?=isset($object['id'])?'Cập nhật đơn hàng: '.$object['service_name'].' - ['.$object['code'].']'
                        :'Thêm mới đơn hàng - '.$service['name']?></h2>
                <a href="<?=isset($object['id'])?route('booking.show', ['id' => $object['id']]):'/booking/create'?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
            </div>
        </div>
        <section class="clean-house form">
            <form action="" id="frm_step1">
                @if(!empty($service_unit))
                <div class="form-group">
                    <label for="" class="label"><span class="icon icon-repair">&nbsp;</span>Chọn dịch vụ</label>
                    <select name="service_unit" id="service_unit" class="form-control">
                        <option value="">Chọn dịch vụ</option>
                        @foreach($service_unit as $item)
                        <option data-description="<?=$item['description']?>" data-price="<?=$item['price']?>" value="<?=$item['id']?>"  <?=isset($booking_detail[0]['service_unit_id']) && $booking_detail[0]['service_unit_id']==$item['id']?'selected':''?> ><?=$item['name']?></option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="form-group">
                    <label class="label"><span class="icon icon-location">&nbsp;</span> Địa điểm sử dụng dịch vụ</label>
                    <div class="ip_">
                        <input type="text" class="form-control" name="address_location" id="address_location" value="<?=@$object['address_location']?>">
                        <span class="icon icon-search">&nbsp;</span>
                        <label for="" class="error">Vui lòng chọn địa điểm</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label"><span class="icon icon-no-home">&nbsp;</span> Số nhà / Căn hộ</label>
                    <input type="text" class="form-control" placeholder="Vd: 125" name="address_number" id="address_number" value="<?=@$object['address_number']?>" >
                    <label for="" class="error">Vui lòng chọn địa điểm</label>
                </div>

               
                <div class="form-group">
                    <div class="col-xs-6 no-padding-left">
                        <label class="label"><span class="icon icon-calendar">&nbsp;</span> Chọn ngày</label>
                        <input type="text" class="form-control" id="start_date" name="start_date" value="<?=@$object['start_date']?>" >
                        <label for="" class="error">Vui lòng chọn ngày</label>
                    </div>
                    <div class="col-xs-6 no-padding-right">
                        <label class="label"><span class="icon icon-time">&nbsp;</span> Chọn giờ</label>
                        <input type="text" class="form-control" id="time" name="time" value="<?=@$object['time']?>">
                        <label for="" class="error">Vui lòng chọn giờ</label>
                    </div>
                </div>

                @if(!empty($service['title_description']))
                <div class="form-group">
                    <label class="label"><span class="icon icon-note">&nbsp;</span> <?=$service['title_description']?> </label>
                    <textarea class="form-control" name="customer_description" id="customer_description" cols="30" rows="2" placeholder="Nhập nội dung mô tả"><?=@$object['customer_description']?></textarea>
                </div>
                @endif

                @if(!empty($services_extra))
                <div class="form-group">
                    <label class="label"><span class="icon icon-add">&nbsp;</span> Thêm dịch vụ</label>
                    <div class="wrap-checkbox">
                        @foreach($services_extra as $key => $item)
                        <p class="checkbox">
                            <input type="checkbox" id="services_extra_<?=$item['id']?>" name="services_extra[]" data-price="<?=$item['price']?>" value="<?=$item['id']?>" class="services_extra" <?=isset($service_extra_ids) && in_array($item['id'], $service_extra_ids)?'checked="checked"':''?> >
                            <label for="services_extra_<?=$item['id']?>"><?=$item['name']?> (<?=number_format($item['price'])?>đ)</label>
                        </p>
                        @endforeach
                    </div>
                </div>
                @endif
                @if(!empty($employees_favorite))
                <div class="form-group input-add">
                    <label class="label"><span class="icon icon-person">&nbsp;</span> Chọn người làm ưa thích</label>
                    <div class="ip_">
                        <div class="wrap_select">
                        {!! Form::select("employee_id", ["" =>"Chọn người làm"]+$employees_favorite,"", ['id' => 'employee_id', 'class' => 'form-control']) !!}
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>
                        <button type="button" class="btn btn-primary" id="select_employee">Chọn</button>
                        <a href="javascript:void(0);" class="link" id="select_employee_explain"><i>Nghĩa là gì?</i></a>
                    </div>
                </div>
                @endif
                <div class="input-add">
                    <p>Thêm phụ cấp để kiếm người làm nhanh hơn</p>
                    <div class="ip_">
                        <input type="text" class="form-control fm-number" name="benefit" id="benefit" value="<?=@$object['benefit']?>" >
                        <button class="btn btn-primary btn_benefit">Đồng ý</button>
                    </div>
                </div>

                @if(!empty($service['note']))
                <label for="" class="error warning" style="display: block"><i class="fa fa-exclamation-triangle"></i><?=$service['note']?></label>
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
        var $key_step1 = '<?=$key_step1?>';
        var $key_step2 = '<?=$key_step2?>';
        $(function(){     
            init_date('#end_date');
            init_date('#start_date');
            init_time('#time');
            init_fm_number('.fm-number');

            var data_step1 = localStorage.getItem($key_step1);
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
                $('#benefit').val(data.benefit);
                $('#start_date').val(data.start_date);
                $('#time').val(data.time);
                $('#total_amount').val(data.total_amount);
                $('#service_unit').val(data.service_unit).trigger('change');
                $('#employee_id').val(data.employee_id);
                setEndDate(data.end_date);
            }else{
                setEndDate();
            }
            $('#start_date').on('change', function(e, date) {
                setEndDate();
            });
            function setEndDate(end_date){
                var minDate = $('#start_date').val().split("-").reverse().join("-");
                minDate = new Date(minDate);
                $('#day_of_week').val(day_of_week(minDate));
                minDate.setDate(minDate.getDate() + 30);
                if(end_date){
                    $('#end_date').val(end_date);
                }else{
                    $('#end_date').val(convertFormatDate(minDate));
                }
                
                $('#end_date').bootstrapMaterialDatePicker('setMinDate', minDate);
            }
            
            $('.services_extra').on('click',function(){
                setTotalAmount();
            });

            $('.btn_benefit').on('click',function(){
                setTotalAmount();
                return false;
            });

            $(document).on('change','#service_unit',function(){
                var description = $(this).find(":selected").data('description');                
                $('#service_unit_description').val(description);
                setTotalAmount();
            });

            $('#service_unit').trigger('change');
            //setTotalAmount();
            $(document).on('click','#btn_submit',function(){
                $('#frm_step1').submit();
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
                    'time': {
                        required: true
                    },
                    'service_unit': {
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
                        required: 'Vui lòng chọn ngày'
                    },
                    'time': {
                        required: 'Vui lòng chọn giờ'
                    },
                    'service_unit': {
                        required: 'Vui lòng chọn dịch vụ'
                    },
                },
                submitHandler: function(form) {
                    $('.fm-number').each(function( index ) {
                        $(this).val( numeral($(this).val()).value() );
                    });

                    var data = $(form).serializeArray();
                    var service_unit    = $('#service_unit').find(":selected");
                    var service_unit_description    = service_unit.data('description'); 
                    var service_unit_name           = service_unit.text();
                    tmp = {
                        "name":"service_unit_description",
                        "value":service_unit_description
                    };

                    data.push(tmp);
                    tmp ={
                        "name":"service_unit_name",
                        "value":service_unit_name,
                    };
                    data.push(tmp);

                    localStorage.setItem($key_step1, JSON.stringify(data));
                    
                    window.location.href = url+'?step=2';
                    return false;
                }
            });

            $(document).on('click','#select_employee',function(){
                var employee_id = $('#employee_id').val();
                var start_date  = $('#start_date').val();

                if(!employee_id){
                    notification_fail('Vui lòng chọn người làm ưa thích!');
                    return false;
                }

                if(!start_date){
                    notification_fail('Vui lòng chọn thời gian thực hiện dịch vụ trước!');
                    return false;
                }
                ajax_loading(true);
                $.post('/booking/check-employee-free-time',{employee_id:employee_id,start_date:start_date},function(res){
                    ajax_loading(false);
                    if(!res.rs){
                        notification_fail('<p>Người làm ưa thích bạn chọn hiện đang bận vào giờ này. Vẫn còn rất nhiều nhân viên khác có thể làm bạn hài lòng.</p>',function(){
                            $('#employee_id').val('');
                        });
                    }
                })
            });

            $('#select_employee_explain').on('click',function(){
                notification_success('<p>Người làm ưa thích bạn chọn hiện đang bận vào giờ này. Vẫn còn rất nhiều nhân viên khác có thể làm bạn hài lòng.</p><p>Bạn hãy chọn <b>“KHÔNG CẦN NGƯỜI LÀM ƯA THÍCH”</b> để tiếp tục booking này.</p>');
            })
        });
        function setTotalAmount(){
            var service_unit = parseFloat($('#service_unit').find(":selected").data('price'));

            var services_extra = 0;
            $('input.services_extra').each(function(){
                if($(this).is(':checked')){
                    services_extra += parseFloat($(this).data('price'));
                }
            });

            var benefit = numeral( $('#benefit').val() ).value();

            var total_amount = service_unit + services_extra + benefit;

            $('#total_amount').val(total_amount);
            $('.total_amount_text').text(numeral(total_amount).format()+'VND');
        }
    </script>
@stop
