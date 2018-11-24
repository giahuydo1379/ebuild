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
                    <label class="label"><span class="icon icon-calendar">&nbsp;</span> Chọn ngày</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="<?=@$object['start_date']?>">
                    <label id="start_date-error" for="start_date" class="error"></label>
                </div>
                <div class="col-xs-6 no-padding-right">
                    <label class="label"><span class="icon icon-time">&nbsp;</span> Chọn giờ</label>
                    <input type="text" class="form-control" id="time" name="time" value="<?=@$object['time']?>">
                    <label id="time-error" for="time" class="error"></label>
                </div>
            </div>
            @if(!empty($freezers))
            <div class="form-group">
                <label class="label"><span class="icon icon-fridge">&nbsp;</span> Loại máy lạnh</label>
                <select name="freezers" id="freezers" class="form-control">
                    @foreach($freezers as $item)
                    <option <?=isset($booking_detail[0]['freezer_id']) && $booking_detail[0]['freezer_id']==$item['id']?'selected':''?>
                             value="<?=$item['id']?>"><?=$item['name']?></option>
                    @endforeach
                </select>
                <label for="" class="error">Chọn loại máy lạnh</label>
            </div>
            @endif
            <div class="form-group use-service">
                @if(!empty($freezers_capacity))
                <div class="wrap-control">
                    <label class="label"><span class="icon icon-perform">&nbsp;</span> Công suất</label>
                    <select name="freezers_capacity" id="freezers_capacity" class="form-control">
                        @foreach($freezers_capacity as $item)
                        <option <?=isset($booking_detail[0]['freezer_capacity_id']) && $booking_detail[0]['freezer_capacity_id']==$item['id']?'selected':''?>
                                value="<?=$item['id']?>"><?=$item['name']?></option>
                        @endforeach
                    </select>
                </div>
                @endif
                <span class="equal washing">Tương đương</span>
                <div class="wrap-control">
                    <label class="label"><span class="icon icon-no-fridge">&nbsp;</span> Số lượng</label>
                    <select name="freezers_number" id="freezers_number" data-price="0" class="form-control">
                    </select>
                </div>
            </div>
            @if(!empty($services_extra))
            <div class="form-group">
                <label class="label"><span class="icon icon-add">&nbsp;</span> Thêm dịch vụ</label>
                <div class="wrap-checkbox">
                    @foreach($services_extra as $key => $item)
                    <p class="checkbox">
                        <input <?=isset($service_extra_ids) && in_array($item['id'], $service_extra_ids)?'checked="checked"':''?>
                                type="checkbox" id="services_extra_<?=$item['id']?>" name="services_extra[]" data-price="<?=$item['price']?>" value="<?=$item['id']?>" class="services_extra">
                        <label for="services_extra_<?=$item['id']?>"><?=$item['name']?> (<?=number_format($item['price'])?>đ)</label>
                    </p>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="input-add">
                <p>Thêm phụ cấp để kiếm người làm nhanh hơn</p>
                <div class="ip_">
                    <input type="text" class="form-control fm-number" name="benefit" id="benefit" value="<?=@$object['benefit']?>">
                    <button class="btn btn-primary btn_benefit">Đồng ý</button>
                </div>
            </div>
            <div class="wrap-total-price">
                <div class="total">      
                    <input type="hidden" name="total_amount" id="total_amount" class="">
                    <p class="total-price">Tổng số tiền dịch vụ: <span class="price pull-right total_amount_text"></span></p>
                    <p class="note"><i>(Bao gồm phí dịch vụ + Dịch vụ thêm + Phụ cấp)</i></p>
                </div>
                <input type="hidden" name="service_id" value="<?=$service_id?>">
                <a href="#" class="btn btn-primary btn-next" id="btn_submit">Tiếp theo <i class="fa fa-arrow-right"></i></a>
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
        var freezers_number_max = <?=json_encode($freezers_number_max)?>;
        $(function(){
            setFreezerzNum();

            $('#freezers,#freezers_capacity').on('change',function(){
                setFreezerzNum();
                setTotalAmount();
            });

            $(document).on('change','#freezers_number',function(){
                ajax_loading(true);
                $.post('/booking/get-price-freezers',{
                    freezer_id:$('#freezers').val(),
                    freezer_capacity_id:$('#freezers_capacity').val(),
                    freezer_number:$('#freezers_number').val(),
                },function(res){
                    ajax_loading(false);
                    $('#freezers_number').data('price',res.price);
                    setTotalAmount();
                });
            });

            $('.services_extra').on('click',function(){
                setTotalAmount();
            });

            $('.btn_benefit').on('click',function(){
                setTotalAmount();
                return false;
            });

            var freezers_data_step1 = localStorage.getItem('freezers_data_step1');
            if(freezers_data_step1){
                freezers_data_step1 = JSON.parse(freezers_data_step1);
                console.log(freezers_data_step1);
                var data = [];
                var services_extra = [];
                $.each(freezers_data_step1,function(k,v){
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
                $('#freezers').val(data.freezers);
                $('#freezers_capacity').val(data.freezers_capacity).trigger('change');
                $('#freezers_number').val(data.freezers_number).trigger('change');
                $('#total_amount').val(data.total_amount);
            }

            setTotalAmount();

            init_date('#start_date');
            init_time('#time');
            init_fm_number('.fm-number');

            $('#services_extra').on('change',function(){
                setTotalAmount();
                return false;
            });
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
                    'freezers_number':{
                        required:true
                    }
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
                    'freezers_number':{
                        required:'Vui lòng chọn số lượng'
                    }
                },
                submitHandler: function(form) {
                    $('.fm-number').each(function( index ) {
                        $(this).val( numeral($(this).val()).value() );
                    });

                    var data = $(form).serializeArray();

                    localStorage.setItem("freezers_data_step1", JSON.stringify(data));
                    
                    window.location.href = url+'?step=2';
                    return false;
                }
            });

            @if (isset($object['total_amount']))
            $('#total_amount').val('<?=$object['total_amount']?>');
            $('.total_amount_text').text(numeral('<?=$object['total_amount']?>').format()+'VND');
            @endif
        });
        function setTotalAmount(){
            var freezers_price = $('#freezers_number').data('price');           
            var services_extra = 0;
            $('input.services_extra').each(function(){
                if($(this).is(':checked')){
                    services_extra += parseFloat($(this).data('price'));
                }
            });

            var benefit = numeral( $('#benefit').val() ).value();

            var total_amount = freezers_price + services_extra + benefit;
            $('#total_amount').val(total_amount);
            $('.total_amount_text').text(numeral(total_amount).format('0,0')+'VND');
        }
        function setFreezerzNum(){
            $('#freezers_number').data('price',0);
            $('#freezers_number').html('');
            var freezer_id            = $('#freezers').val();
            var freezers_capacity   = $('#freezers_capacity').val();
            var freezers_number = 0;
            $.each(freezers_number_max,function(k,v){
                if(v.freezer_id == freezer_id && v.freezer_capacity_id == freezers_capacity){
                    freezers_number = v.freezer_number;
                    return false;
                }
            });
            if(!freezers_number)
                return false;

            var html = '<option value="">Chọn số lượng</option>';
            for(i=1; i <= freezers_number;i++){
                html += '<option value="'+i+'">'+i+' máy</option>';
            }
            $('#freezers_number').html(html);

            @if (isset($booking_detail[0]['freezer_number']))
            $('#freezers_number').val('<?=$booking_detail[0]['freezer_number']?>').trigger('change');
            @endif
        }
    </script>
@stop