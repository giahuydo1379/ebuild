$(document).ready(function() {
    $('.clockpicker').clockpicker({
        autoclose: true
    });
    init_datepicker('.datepicker');

    $('#type').on('change',function(){
        if($(this).val() == 'product'){
            $('.type_product').show();
            $('.list-products').show();
            $("#fp_sku").rules( "add", {
                required: true,
                messages: {
                    required: "Điền mã SKU sản phẩm khuyến mãi",
                }
            });
        }else{
             $("#fp_sku").rules( "remove");
             $('.type_product').hide();
            $('.list-products').hide();
        }

        if($(this).val() == 'brand'){                    
            $('.type_brand').show();
            $("#brand_id").rules( "add", {
                required: true,
                messages: {
                    required: "Chọn thương hiệu",
                }
            });
        }else{
             $("#brand_id").rules( "remove");
             $('.type_brand').hide();
        }

        if($(this).val() == 'category'){
            $('.type_category').show();
            $("#category_id").rules( "add", {
                required: true,
                messages: {
                    required: "Chọn danh mục sản phẩm",
                }
            });
        }else{
            $("#category_id").rules( "remove");
            $('.type_category').hide();
        }
    });
    $('#fp_sku').on('change', function () {
        $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
            $('.list-products').show();
            var now = $("#fp_item").val();
            var list = [];
            var sku = [];
            $.each(data.products, function (index, item) {
                list.push( get_sku_item(now, item.name, item.product_id, item.sku) );
                sku.push(item.sku);
            });
            $('.list-products .color').html(list.join(', '));
            $('#fp_sku').val(sku.join(','));
        });
    });


    $(document).on('click','#is_percent',function(){
        if($(this).is(':checked')){
            $('#label_discount').html('Phần trăm giảm');
            $("#discount").rules( "add", {
                messages: {
                    required: "Nhập phần trăm muốn giảm",
                }
            });
        }else{
            $('#label_discount').html('Số tiền giảm');
            $("#discount").rules( "add", {
                messages: {
                    required: "Nhập số tiền muốn giảm",
                }
            });
        }
    })

    $('.btn-start').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn kích hoạt mã coupon này không?', 'Xác nhận kích hoạt mã coupon', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/coupons/update-status', {id: id, status: 1}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });
    $('.btn-stop').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn dừng mã coupon này không?', 'Xác nhận dừng mã coupon', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/coupons/update-status', {id: id, status: 0}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });

    $('input.slider-toggle').each(function (key, msg) {
        if ($(this).is(':checked')) {
            $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
        } else {
            $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
        }
    });



    $('.delete-action').on('click', function () {
        var obj = this;
        confirm_delete("Bạn có muốn xóa, banner này không?", function () {
            $.post('', {
                id: $(obj).attr('data-id'),
                _token: '{!! csrf_token() !!}'
            }, function(data){
                alert_success(data.msg, function () {
                    if(data.rs == 1)
                    {
                        location.reload();
                    }
                });
            });
        });
    });

    $('.LoadProduct .cancel').unbind('click');
    $('.LoadProduct .cancel').on('click', function (e) {
        e.preventDefault();

        $('.display-product').slideDown();

        $('.add-giff-product').hide();

        if ($('.giff-item').length > 0) {
            $('.LoadProduct .TitleCreate').hide();
            $('.LoadProduct .TitleDisplay').show();

            $('.link.add-giff-product').show();
        } else {
            $('.LoadProduct .TitleCreate').show();
            $('.LoadProduct .TitleDisplay').hide();

            $('.no-banner.add-giff-product').show();
        }
        $('.LoadProduct .CreateLoad').slideUp();

        $('.list-products .color').html("");

        return false;
    });


    $('.add-action').on('click', function () {
        $(this).hide();
        $('.banner-display').slideUp();
        $('.banner-update').slideDown();

        $('.TitleCreate').show();
        $('.TitleDisplay').hide();
        $('.TitleUpdate').hide();
        
        $('.link.add-giff-product').hide();
    });

    $('.add-action-none').on('click', function () {
        $(this).parent().slideUp();
        $('.banner-update').slideDown();
        $('.link.add-giff-product').hide();
    });

    $('.banner-update .main-cancel').on('click', function (e) {
        e.preventDefault();

        if ($('#is_reload').val() == '1') {
            location.reload();
            return false;
        }
        $('#is_next').val(0);
        $('#form_update')[0].reset();
        $('#id').val(0);
        $('#type').trigger('change');
        $('#fp_sku').trigger('change');
        $('#brand_id').trigger('change');
        $('#is_percent').prop('checked',true).trigger('click');
        $('#is_random').trigger('click');
        $('#form_update .not_update').show();
        remove_rule_code();
        $("#total_coupons").rules( "add", {
            required: true,
            messages: {
                required: "Nhập số lượng mã muốn tạo",
            }
        });

        $('.add-action-none').parent().slideDown();
        $('.banner-display').slideDown();

        if ($('.add-action-none').length > 0) {
            $('.TitleCreate').show();
            $('.TitleDisplay').hide();
            $('.TitleUpdate').hide();
        } else {
            $('.TitleCreate').hide();
            $('.TitleDisplay').show();
            $('.TitleUpdate').hide();

            $('.add-action').show();
        }

        $('.banner-update').slideUp();
    });

    $('.update-action').on('click', function () {
        var item = _objects[$(this).attr('data-id')];

        $('.add-action').click();
        $('.TitleCreate').hide();
        $('.TitleDisplay').hide();
        $('.TitleUpdate').show();
        $('.list-radio input[value='+item.layout+']').trigger('click');

        var obj_form = '#form_update';

        $('label.error').hide();
        $(obj_form + ' #is_next').val(0);
        $(obj_form + ' #id').val(item.id);
        $(obj_form + ' #name').val(item.name);
        $(obj_form + ' #alias').val(item.alias);
        $(obj_form + ' #from_time').val(item.from_time);
        $(obj_form + ' #from_date').val(item.from_date);
        $(obj_form + ' #to_time').val(item.to_time);
        $(obj_form + ' #to_date').val(item.to_date);
        $(obj_form + ' #discount').val(item.discount);
        $(obj_form + ' #times').val(item.times);
        $(obj_form + ' #min_amount').val(item.min_amount);
        $(obj_form + ' #type').val(item.type).trigger('change');

        $(obj_form + ' .not_update').hide();
        remove_rule_code();
        $("#total_coupons").rules( "remove" );

        if (item.status) {
            $(obj_form + ' #status').attr('checked', 'checked');
        } else {
            $(obj_form + ' #status').removeAttr('checked');
        }

        if (item.is_percent) {
            $(obj_form + ' #is_percent').prop('checked',false).trigger('click');
        } else {
            $(obj_form + ' #is_percent').prop('checked',true).trigger('click');
        }

        if(item.type == 'product'){
            $.post(_base_url+'/coupons/get-product-code',{id:item.id},function(res){
                $('#fp_sku').val(res.data).trigger('change');
            });
        } else if(item.type == 'brand'){
            $.post(_base_url+'/coupons/get-brand',{id:item.id},function(res){
                if(res.rs == 1){
                    $('#brand_id').select2('val', res.data);
                }
            });
        } else if(item.type == 'category'){
            $.post(_base_url+'/coupons/get-brand',{id:item.id},function(res){
                if(res.rs == 1){
                    $('#category_id').select2('val', res.data);
                }
            });
        }

        $('.fm-number').each(function( index ) {
            $(this).val( numeral($(this).val()).format() );
        });
    });
    $('.list-radio input[name=layout]').on('click', function () {
        $('.list-radio .radio').removeClass('active');
        $(this).closest('div').addClass('active');
    });
    $('form.frm_update').each(function () {
        init_form_update('#'+$(this).attr('id'));
    });

    $(document).on('click','input[name=is_random]',function(){
        if($(this).val() == 'not_random'){
            $('#code').show();
            $('#total_coupons').val(1).prop('disabled',true);
            $('label#total_coupons-error').hide();
            add_rule_code();
        }else{
            $('#code').hide();
            $('#total_coupons').val('').prop('disabled',false);
            remove_rule_code();
            $('label#code-error').hide();
        }
    })

    function init_form_update(obj_form) {
        $(obj_form).validate({
            ignore: ".ignore",
            rules: {
                name: "required",
                // from_date: "required",
                // from_time: "required",
                // to_date: "required",
                // to_time: "required",
                discount: "required",
                min_amount: "required",
                times: "required",
                min_amount: "required",
                total_coupons: "required",
            },
            messages: {
                name: "Vui lòng nhập tên chương trình quà tặng",
                // from_date: "Chọn ngày bắt đầu",
                // from_time: "Chọn giờ bắt đầu",
                // to_date: "Chọn ngày kết thúc",
                // to_time: "Chọn giờ kết thúc",
                discount: "Nhập số tiền muốn giảm",
                min_amount: "Nhập giá trị tối thiểu đơn hàng sẽ được giảm",
                times: "Nhập số lượt sử dụng",
                min_amount: "Nhập giá trị tối thiểu đơn hàng sẽ được giảm",
                total_coupons: "Nhập số lượng mã muốn tạo",
            },
            submitHandler: function(form) {
                submitHandler(form);

                return false;
            }
        });
    }

    function submitHandler(form) {
        $('.fm-number').each(function( index ) {
            $(this).val( numeral($(this).val()).value() );
        });

        // do other things for a valid form
        ajax_loading(true);
        var data = $(form).serializeArray();
        var url = $(form).attr('action');
        $.post(url, data).done(function(data){
            ajax_loading(false);
            if(data.rs == 1)
            {
                $('#is_reload').val(1);
                $(form)[0].reset();
                alert_success(data.msg, function () {  
                    if(data.download)
                        window.open(_base_url+'/coupons/download', '_blank');

                    location.reload();
                });
            } else {
                alert_success(data.msg);
                if (data.errors) {
                    $.each(data.errors, function (key, msg) {
                        $('#'+key+'-error').html(msg).show();
                    });
                }
            }
        });
    }

    function add_rule_code(){
        $("#code").rules( "add", {
            required: true,
            minlength:8,
            maxlength:10,
            remote: _base_url+'/coupons/check-coupon',
            messages: {
                required: "Nhập mã coupon",
                minLenght:"Ít nhất 8 ký tự",
                maxLenght:"Tối đa 10 ký tự",
                remote:"Mã coupon đã tồn tại"
            }
        });
    }

    function remove_rule_code(){
        $("#code").rules( "remove" );
    }

    function get_sku_item(now, name, product_id, sku) {
        return '<a title="'+name+'">' +
        '<input type="hidden" name="object[]" value="'+product_id+'">'+sku+'</a>';
    }
});