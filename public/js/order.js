$(function(){
    $(document).on('change', '.item_product input[name="discount"]', function () {
        var parent = $(this).closest('div.item_product');
        var tmp = numeral( parent.attr('data-price') ).value() * parent.find('input.product_amount').val() - numeral( $(this).val() ).value();
        parent.find('.total').html(numeral(tmp).format() + '<sup>đ</sup>');

        parent.find('input.product_amount').trigger('change');
    });
    $('.order-discount').on('change', function () {
        var total = numeral( $('#subtotal').val() ).value() - numeral( $(this).val() ).value();
        $('#second_step .total_pay.price').html(numeral(total).format()+'<sup>đ</sup>');
    });
	$('.order_note .notes').on('change', function () {
        $('.show-notes').html($(this).val());
    });
    $('.order_note .details').on('change', function () {
        $('.show-details').html($(this).val());
    });
    init_datepicker('.datepicker');
	init_select2('.select2');
	get_provinces('.provinces');
    var searchRequest = null;

    var _interval_search_product = null;
    $(document).on('keyup','.search_product',function(){
        var kw = $(this).val();
        if(kw.length < 3) return false;

        if (_interval_search_product) clearInterval(_interval_search_product);

        _interval_search_product = setInterval(function() {
            clearInterval(_interval_search_product);

            var url = '/order/search-product';
            if (searchRequest != null)
                searchRequest.abort();

            var pids = [];
            $( ".item_product" ).each(function( index ) {
                pids.push($(this).attr('data-id'));
            });

            searchRequest = $.post(url, {kw: kw, except_pids: pids}, function (res) {
                if (res.rs == 1) {
                    $('.list_result_search').html(get_html_result_product_search(res.data)).show();
                    $(".search_result_content").show();
                } else {
                    $('.list_result_search').html('').show();
                    $(".search_result_content").hide();
                }
            }, 'json');
        }, 500);
    });
    var changeAmountRequest = null;
    $(document).on('change','.product_amount', function (e) {
        var item = $(this).closest('div.item_product');
        var pid = item.data('id');
        var discount = numeral( item.find('input[name="discount"]').val() ).value();

        var amount = numeral( $(this).val() ).value();
        if (amount < 1) {
            amount = 1;
            $(this).val(1);
        }

        if (changeAmountRequest != null) 
            changeAmountRequest.abort();
        ajax_loading(true);
        changeAmountRequest = $.post('/order/add-product', {product_id: pid, amount: amount, discount: discount}, function (res) {
            ajax_loading(false);
            if (res.status) {
                $list_cart[pid].amount = amount;
                amount = numeral( amount * res.data.price - discount);
                item.find('.total').html(amount.format()+'<sup>đ</sup>');
                set_promotion_total_price(res.data);
            } else {
                malert(res.msg);
            }
        });
    });

    $(document).on('click','.select_product',function(){
        var pid     = $(this).data('id');
        var amount  = 1;
        ajax_loading(true);
        $.post('/order/add-product', {product_id: pid, amount: amount}, function (res) {
            ajax_loading(false);

            if (res.status) {
                console.log(res);
                var product = res.data.product;                    
                $list_cart[pid] = {
                    amount: amount,                        
                    price: product.price,
                    image: product.image,
                    product: product.product_name,
                    weight: product.weight,
                    product_id: pid,
                    promotion:''
                };

                if($('.item_product_'+pid).length > 0) return false;
                
                $('.list_product').append(get_html_add_product(product));

                set_promotion_total_price(res.data);

                $('.select_pid_'+pid).remove();
                init_fm_number('.item_product_'+pid+' .frm-number');
            } else {
                malert(res.msg);
            }
        },'json');
    });

    $(document).on('change','.product_services',function(){
        var product_services_ids = [];
        $('.product_services').each(function(){
            if($(this).is(':checked')){
                product_services_ids.push($(this).val());
                $list_service[$(this).val()] = {
                    name: $(this).data('name'),                        
                    price: $(this).data('price'),
                };
            }else{
                delete $list_service[$(this).val()];
            }
        });
        if(product_services_ids.length < 1){
            product_services_ids = [0];
        }
        ajax_loading(true);
        $.post(_base_url + '/order/add-product', {product_services_ids: product_services_ids}, function (res) {
            ajax_loading(false);
            if (res.status) { 
               var data = res.data;
                set_total_price(data);

            }
        });

    });

    $(document).on('click','.remove-product i', function (e) {
        var product_id = $(this).closest('div.item_product').data('id');
        ajax_loading(true);
        $.post('/order/remove-product', {product_id: product_id}, function (res) {
            ajax_loading(false); 
            delete $list_cart[product_id];
            $('.item_product_'+product_id).remove();
            $('.gift_'+product_id).remove();
            set_promotion_total_price(res);
        },'json');
    });

    $(".first_step .btn_next").click(function() {
        if($('.list_product .item_product').length < 1){                    
            malert('Chưa có sản phẩm');
            return false;
        }
        $(".first_step").addClass("select");
        $(".second_step").removeClass("select");
        $(".second_step").css("opacity", "1");
        $(".first_step").css("opacity", "0.5");
        $('#form_step2 button.btn_next').prop("disabled", false);
        ajax_loading(true);
        $.get('/order/get-service',{},function(res){
            ajax_loading(false);
            $('.list_service').html('Không có dịch vụ thêm');
            if(res.data){
                setService(res.data,res.service_ids);
            }
        });
    });
    $(".second_step #prev1").click(function() {
        $(".first_step").removeClass("select");
        $(".second_step").addClass("select");
        $(".first_step").css("opacity", "1");
        $(".second_step").css("opacity", "0.5");
        $('#form_step2')[0].reset();
        get_provinces('.provinces');
        $('#form_step2 label.error').remove();
    });

    jQuery.validator.addMethod("rgphone", function (value, element) {
        return this.optional(element) || /^(056|058|059|032|033|034|035|036|037|038|039|070|076|077|078|079|081|082|083|084|085|098|095|097|096|0169|0168|0167|0166|0165|0164|0163|0162|090|093|0122|0126|0128|0121|0120|091|094|0123|0124|0125|0127|0129|092|0188|0186|099|0199|086|088|089|087)[0-9]{7}$/.test(value);
    }, "Số điện thoại không đúng định dạng");

    $('#form_step2').validate({
        ignore: ".ignore",
        rules: {
            b_firstname: "required",
            b_phone: {
                required: true,
                minlength: 10,
                maxlength: 11,
                number: true,
                rgphone: true
            },
            province_id:'required',
            district_id:'required',
            ward_id:'required',
            b_address:'required',
            email:{
                'required':true,
                'email':true
            }
        },
        messages: {
            b_firstname: "Nhập họ tên",
            b_phone: {
                required: "Nhập số điện thoại",
                minlength: "Số điện thoại tối thiểu 10 số",
                maxlength: "Số điện thoại tối đa 11 số",
                number: "Vui lòng nhập đúng định dạng"
            },
            province_id:'Chọn tỉnh thành / phố',
            district_id:'Chọn quận huyện',
            ward_id:'Chọn phường xã',
            b_address:'Nhập địa chỉ',
            email:{
                'required':'Nhập email',
                'email':'Email không đúng'
            },
            company_name: "Nhập tên công ty",
            company_address: "Nhập địa chỉ",
            company_tax_code: "Nhập mã số thuế",
        },
        submitHandler: function(form) {
            resetTransportInfo();
            var ward_id     = $('#c_ward_id').val();
            var district_id = $('#c_district_id').val();
            warehouse       = false;
            ajax_loading(true);
            $.get('/order/get-warehouse',{ward_id:ward_id,district_id:district_id},function(res){            
                ajax_loading(false);
                if(res.rs){
                    warehouse = res.data;
                    $('#transport_info_warehouse').val(warehouse.id);
                    callGeoB = true;
                    geocodeA(platform);
                }
            });

            $('.third_step .top_detail .list_product_step3').remove();
            $('.third_step .top_detail .list_service_step3').remove();
            $('.third_step .top_detail').prepend(get_html_service_step3($list_service));
            $('.third_step .top_detail').prepend(get_html_product_step3($list_cart));            
            $('.third_step .subtotal_product').html($('#second_step .subtotal_product.price').html());
            $('.third_step .subtotal_service').html($('#second_step .subtotal_service.price').html());
            $('.third_step .shipping_cost').html(numeral($('#shipping_cost').val()).format()+'<sup>đ</sup>');            
            $('.third_step .surcharge').html($('#second_step .surcharge.price').html());
            $('.third_step .total_sum').html($('#second_step .total_sum').html());
            $('.third_step .total_pay').html($('#second_step .total_pay').html());
            $('.third_step .discount').html(numeral($('#order-discount').val()).format('0,0')+'<sup>đ</sup>');
            //$('.third_step .total_weight').html($('.first_step .total_weight').html());

            var dataFrm = $(form).serializeArray();
            var data = {};
            $.each(dataFrm,function(i, v) {
                data[v.name] = v.value;
            });

            var adress = [
                data.b_address,
                $('#form_step2 select.wards option:selected:selected').text(),
                $('#form_step2 select.districts option:selected').text(),
                $('#form_step2 select.provinces option:selected').text(),
            ];

            var html_shipping_address   = '<p><b>Người nhận: </b>'+data.b_firstname+'</p>';
            html_shipping_address       += '<p><b>Địa chỉ: </b>'+adress.toString()+'</p>';
            html_shipping_address       += '<p><b>Điện thoại: </b>'+data.b_phone+'</p>';
            html_shipping_address       += '<p><b>Email: </b>'+data.email+'</p>';

            $('.shipping_address .body_box').html(html_shipping_address);

            $('#Modal1').modal('show');

            return false;
        }
    });

    $(document).on('click','#save_order,#save_order_top',function(){
        var data = $('#form_step2').serializeArray();
        ajax_loading(true);
        $.post('/order/store',data,function(res){
            ajax_loading(false);
            if(res.rs == 1)
            {
                $('#Modal1').modal('hide');

                $('#Modal12 .msg').html(res.msg);
                $('#Modal12 .detail').attr('href',res.detail);
                $('#Modal12 .list').attr('href',res.list);
                $('#Modal12').modal('show');
            } else {
                malert(res.msg);
                if (res.errors) {
                    $.each(res.errors, function (key, msg) {
                        console.log(msg);
                        $('#'+key+'-error').html(msg).show();
                    });
                }
            }
        });
    });
    $('#Modal12').on('hidden.bs.modal', function () {
        location.reload();
    })

    function get_html_result_product_search(data){                
        var html = '';
        $.each(data,function(k,v){
            html += '<li class="row select_product select_pid_'+v.product_id+'" data-id="'+v.product_id+'" >';
            html +=         '<div class="col-md-1">';
            html +=             '<div class="wrap-img">';
            html +=                 '<img src="'+v.image+'" alt="">';
            html +=             '</div>';
            html +=         '</div>';
            html +=         '<div class="col-md-8">';
            html +=             '<span>'+v.product+'</span>';
            html +=         '</div>';
            html +=         '<div class="col-md-3">';
            html +=             '<b class="orange price">'+numeral(v.price).format('0,0')+' <sup>đ</sup></b>';
            html +=         '</div>';
            html +=     '</li>';
        });            
        return html;
    }
    function get_html_add_product(data){                
        html = '<div class="row item_product item_product_'+data.product_id+'" data-id="'+data.product_id+'" data-price="'+data.price+'">';
        html += '        <div class="col-md-6">';
        html += '            <div class="col-md-1">';
        html += '                <div class="wrap-img">';
        html += '                    <img src="'+data.image+'" alt=""> ';
        html += '                </div>';
        html += '            </div>';
        html += '            <div class="col-md-11">';
        html += '                <a href="#">'+data.product_name+'</a>';
        html += '                <span class="number-in-store">Còn '+data.amount+' sản phẩm</span>';
        html += '            </div>';
        html += '        </div>';
        html += '        <div class="col-md-6">';
        html += '            <div class="col-md-5 price-number">';
        html += '                <p>'+numeral(data.price).format()+'₫ <span>X</span></p>';
        html += '                <div class="wrap-input">';
        html += '                    <input type="number" class="product_amount" name="amount" value="1">';
        html += '                    <i class="fa fa-caret-down" aria-hidden="true"></i>';
        html += '                    <i class="fa fa-caret-up" aria-hidden="true"></i>';
        html += '                </div>';
        html += '            </div>';
        html += '            <div class="col-md-3 div-discount">';
        html += '            <input type="text" class="frm-number" name="discount" value="0">';
        html += '            </div>';
        html += '            <div class="col-md-3">';
        html += '                <p class="total">'+numeral(data.price).format()+' <sup>đ</sup></p>';
        html += '            </div>';
        html += '            <div class="col-md-1 remove-product">';
        html += '                <i class="icon-trash"></i>';
        html += '                <i class="icon-trash-hover"></i>';
        html += '            </div>';
        html += '        </div>';
        html += '    </div>';

        // html += '            <div class="col-md-3">';
        // html += '                <p>'+data.weight+'gr</p>';
        // html += '            </div>';

        return html;
    }
	function set_promotion_total_price(data){
        set_total_price(data);
        //$('#second_step .total_weight').html(numeral(data.weight).format()+'<sup>đ</sup>');
        if(data.promotions){                    
            $('.list_product .row_gift').remove();
            $.each(data.promotions, function (pid, item) {
                if(item != ''){
                    $list_cart[pid].promotion = item;
                    $('.item_product_'+pid+' ').after('<div class="row_gift gift_'+pid+'">'+item+'</div>');
                }
                
            });
        }
        
    }
    function get_html_product_step3(data){
	    var html = '';
	    $.each(data,function(k,v){
            html += '<div class="wrap list_product_step3">';
            html += '    <div class="row">';
            html += '       <div class="col-md-7">';
            html += '           <div class="col-md-2">';
            html += '               <div class="wrap-img">';
            html += '                   <img src="'+v.image+'" alt="">';
            html += '               </div>';
            html += '           </div>';
            html += '           <div class="col-md-10">';
            html += '               <a href="#">'+v.product+'</a>';
            html += '           </div>';
            html += '       </div>';
            html += '       <div class="col-md-5">';
            html += '           <div class="col-md-4">';
            html += '               <p>'+numeral(v.price).format('0,0')+'    X    '+v.amount+'</p>';
            html += '           </div>';
            html += '           <div class="col-md-4">';
            html += '               <p>'+v.weight+'gr</p>';
            html += '           </div>';
            html += '           <div class="col-md-4">';
            html += '               <p>'+numeral(v.price * v.amount).format('0,0')+' <sup>đ</sup></p>';
            html += '           </div>';
            html += '       </div>';
            html += '    </div>';
            html += v.promotion;
            html += '</div>';
        });
	    return html;
    }

    function get_html_service_step3(data){        
        if($.isEmptyObject(data))
            return;
        var html = '<div class="wrap col-md-12 list_service_step3">';
        html +='        <p><b>Dịch vụ thêm</b></p>';
        html +='        <div class="">';
        $.each(data,function(k,v){
            html += '<p>'+v.name+': '+numeral(v.price).format('0,0')+'<sup>đ</sup></p>';
        });
        html +=     '</div>';
        html += '</div>';
        return html;
    }

    function setService(data,selected){
        var selected = selected || [];
        var html = checked = '';
        $.each(data,function(){
            checked = '';
            if($.inArray( this.product_id, selected ) >= 0)
                checked = 'checked';

            html += '<div class="checkbox">';
            html += '   <input class="product_services" id="check'+this.product_id+'" type="checkbox" name="product_id[]" value="'+this.product_id+'" '+checked+' data-name="'+this.name+'" data-price="'+this.price+'">';
            html += '   <label for="check'+this.product_id+'">'+this.name+'  <span class="color price">( +'+numeral(this.price).format('0,0')+' <sup>đ</sup> )</span></label>';
            html += '</div>';
        });

        if(html != ''){
            $('.list_service').html(html);
        }else{
            $('.list_service').html('Không có dịch vụ thêm');
        }
    }
});
function set_total_price(data){
    $('.subtotal_product').html(numeral(data.total_product).format()+'<sup>đ</sup>');
    $('.subtotal_service').html(numeral(data.total_service).format()+'<sup>đ</sup>');
    $('.surcharge').html(numeral(data.surcharge).format()+'<sup>đ</sup>');        
    $('#subtotal').val(data.total);
    $('#shipping_cost').val(data.shipping_cost);
    $('.third_step .shipping_cost').html(numeral($('#shipping_cost').val()).format()+'<sup>đ</sup>');
    $('#second_step .total_sum.price').html(numeral(data.total).format()+'<sup>đ</sup>');
    var total = numeral(data.total).value() - numeral($('#order-discount').val()).value();
    $('#second_step .total_pay.price').html(numeral(total).format()+'<sup>đ</sup>');
}