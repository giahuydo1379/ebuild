//step1
$(function(){        
    var data_step1 = localStorage.getItem('data_step1');
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
    }

    init_date('#start_date');
    init_time('#time');
    init_fm_number('.fm-number');

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

            localStorage.setItem("data_step1", JSON.stringify(data));
            
            window.location.href = url+'?step=2';
            return false;
        }
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