$(document).on('click', '.delete-product', function () {
    $(this).closest('li').remove();
});

$(document).ready(function() {
    init_datepicker('.datepicker');

    $('#brand_id').on('change', function () {
        $('display-brand').html( $(this).find('option:selected').text() );
    });
    $('.btn-start').on('click', function () {
        var promotion_id = $(this).attr('data-id');
        malert('Bạn có muốn kích hoạt chường trình này không?', 'Xác nhận kích hoạt chương trình', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/promotion/start', {id: promotion_id}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });
    $('.btn-stop').on('click', function () {
        var promotion_id = $(this).attr('data-id');
        malert('Bạn có muốn dừng chường trình này không?', 'Xác nhận dừng chương trình', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/promotion/stop', {id: promotion_id}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });

    $('.add-gift-only a').on('click', function () {
        var frm = $(this).closest('form');
        var index = $(frm).find('.item-giff').length;
        var html = '<div class="col-md-6 item-giff '+index+'">\n' +
'                                                    <label for="">Nhập SKU sản phẩm B'+index+' <span class="note">(Quà tặng)</span><span>*</span></label>\n' +
'                                                    <input name="gift_products_sku_b" id="gift_products_sku_b" type="text" data-po="'+index+'" class="form-control gift_products_sku" placeholder="Điền mã SKU sản phẩm">\n' +
'                                                </div>';
        $(frm).find('.list-giff').append(html);

        init_gift_products_sku();
    });

    $('.add-gift a').on('click', function () {
        var frm = $(this).closest('form');
        var index = $(frm).find('.item-giff').length;
        var html = '<div class="form-group item-giff '+index+'">\n' +
'                                                <div class="col-md-6">\n' +
'                                                    <label for="">Nhập SKU sản phẩm B'+index+' <span class="note">(Quà tặng)</span><span>*</span></label>\n' +
'                                                    <input name="gift_products_sku_b" id="gift_products_sku_b" type="text" data-po="'+index+'" class="form-control gift_products_sku_po" placeholder="Điền mã SKU sản phẩm">\n' +
'                                                </div>\n' +
'                                                <div class="col-md-6">\n' +
'                                                    <label for="">Nhập số lượng sản phẩm B'+index+' <span>*</span></label>\n' +
'                                                    <input name="gift_products_number_b" id="gift_products_number_b" type="text" data-po="'+index+'" class="form-control gift_products_number_po" placeholder="Điền số lượng sản phẩm">\n' +
'                                                </div>\n' +
'                                            </div>';
        $(frm).find('.list-giff').append(html);

        init_gift_products_sku_po();
    });

    function init_gift_order_sku() {
        $('.gift_order_sku').unbind('change');
        $('.gift_order_sku').on('change', function () {
            var frm = $(this).closest('form');

            frm.find('#gift_products').val('');
            frm.find('display-sku').html('A');

            if ($(this).val()=='') {
                return;
            }

            $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
                var item = data.products[0];
                if (item) {
                    frm.find('#gift_products').val(item.product_id);
                    frm.find('display-sku').html('<a title="'+item.name+'">'+item.sku+'</a>');
                }
            });
        });
    }
    init_gift_order_sku();

    function init_gift_products_sku() {
        $('.gift_products_sku').unbind('change');
        $('.gift_products_sku').on('change', function () {
            if ($(this).val()=='') return;

            get_giff_product($(this).closest('form'), $(this).val(), $(this).attr('data-po'));
        });
    }
    init_gift_products_sku();
    /**
     * ----------------
     */
    function init_gift_products_sku_po() {
        $('.gift_products_sku_po').unbind('change');
        $('.gift_products_sku_po').on('change', function () {
            if ($(this).val()=='') return;
            var po = $(this).attr('data-po');
            var number = parseInt( $(this).closest('form').find('.item-giff.'+po+' .gift_products_number_po').val() );
            if (number) {
                get_gift_product_number($(this).closest('form'), $(this).val(), number, po);
            }
        });

        $('.gift_products_number_po').unbind('change');
        $('.gift_products_number_po').on('change', function () {
            var number = parseInt( $(this).val() );
            if (!number) return;
            var po = $(this).attr('data-po');
            var sku = $(this).closest('form').find('.item-giff.'+po+' .gift_products_sku_po').val();
            if (sku != '') {
                get_gift_product_number($(this).closest('form'), sku, number, po);
            }
        });
    }
    init_gift_products_sku_po();
    /**
     * -----------------
     */
    $('.apply_objects_b').on('change', function () {
        if ($(this).val()=='') return;

        var number = parseInt( $(this).closest('form').find('.apply_number_b').val() );
        if (number) {
            get_list_products($(this).closest('form'), $(this).val(), number, 1);
        }
    });
    $('.apply_number_b').on('change', function () {
        var number = parseInt( $(this).val() );
        if (!number) return;

        var sku = $(this).closest('form').find('.apply_objects_b').val();
        if (sku != '') {
            get_list_products($(this).closest('form'), sku, number, 1);
        }
    });

    $('.apply_brands').on('change', function () {
        if ($(this).val()=='') return;

        var number = parseInt( $(this).closest('form').find('.apply_brands_number').val() );
        if (number) {
            get_list_brands($(this).closest('form'), $(this).val(), number);
        }
    });
    $('.apply_brands_number').on('change', function () {
        var number = parseInt( $(this).val() );
        if (!number) return;

        var sku = $(this).closest('form').find('.apply_brands').val();
        if (sku != '') {
            get_list_brands($(this).closest('form'), sku, number);
        }
    });

    $('.apply_objects').on('change', function () {
        if ($(this).val()=='') return;

        var number = parseInt( $(this).closest('form').find('.apply_number').val() );
        if (number) {
            get_list_products($(this).closest('form'), $(this).val(), number);
        }
    });
    $('.apply_number').on('change', function () {
        var number = parseInt( $(this).val() );
        if (!number) return;

        var sku = $(this).closest('form').find('.apply_objects').val();
        if (sku != '') {
            get_list_products($(this).closest('form'), sku, number);
        }
    });
    function add_rule_sku_b(obj_form) {
        $(obj_form + " #apply_objects_b" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập sku áp dụng khuyến mãi",
            }
        });
        $(obj_form + " #apply_number_b" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập số lượt áp dụng",
            }
        });
    }
    function remove_rule_sku_b(obj_form) {
        $(obj_form + " #apply_objects" ).rules( "remove" );
        $(obj_form + " #apply_number" ).rules( "remove" );
    }

    function add_rule_sku(obj_form) {
        $(obj_form + " #apply_objects" ).attr('name', 'apply_objects');
        $(obj_form + " #apply_objects" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập sku áp dụng khuyến mãi",
            }
        });
        $(obj_form + " #apply_number" ).attr('name', 'apply_number');
        $(obj_form + " #apply_number" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập số lượt áp dụng",
            }
        });
    }
    function remove_rule_sku(obj_form) {
        $(obj_form + " #apply_objects" ).rules( "remove" );
        $(obj_form + " #apply_number" ).rules( "remove" );

        $(obj_form + " #apply_objects" ).removeAttr('name');
        $(obj_form + " #apply_number" ).removeAttr('name');
    }

    function check_input_sku(obj_form) {
        console.log(obj_form + ' .list-pro input');
        var tmp = $(obj_form + ' .list-pro input').length;
        if (tmp > 0) {
            remove_rule_sku(obj_form);
        } else {
            add_rule_sku(obj_form);
        }
    }
    function get_gift_product_number(obj_form, sku, number, po) {
        var list = $(obj_form).find('.list-pro.pb');

        $.get(_base_url+'/promotion/get-list-products', {sku: sku}, function (data) {
            var item = data.products[0];
            if (item) {
                list.find('.giff-'+po).remove();
                list.append('<li class="col-md-6 giff-'+po+'" title="'+item.name+'">' +
                    '<input type="hidden" name="gift_products['+item.product_id+']" value="'+number+'">' +
                    item.sku+' - '+number+' sản phẩm <span class="delete-product" title="Xóa">×</span></li>');
            }
        });
    }
    function get_giff_product(obj_form, sku, po) {
        if (typeof po == "undefined") po = 0;
        var list = $(obj_form).find('.list-pro.pb');

        $.get(_base_url+'/promotion/get-list-products', {sku: sku}, function (data) {
            var item = data.products[0];
            if (item) {
                list.find('.giff-'+po).remove();
                list.append('<li class="col-md-6 giff-'+po+'" title="'+item.name+'">' +
                    '<input type="hidden" name="gift_products[]" value="'+item.product_id+'">' +
                    item.sku+' <span class="delete-product" title="Xóa">×</span></li>');
            }
        });
    }
    function get_list_brands(obj_form, ids, number, i) {
        if (typeof i == "undefined") i = 0;

        var list = $(obj_form).find('.list-pro.pa');
        if (i != 0) {
            list = $(obj_form).find('.list-pro.pab');
        }

        var package = $('.package:checked').val();

        $.post(_base_url+'/promotion/get-list-brands', {ids: ids}, function (data) {
            $.each(data.objects, function (index, item) {
                list.find('.p'+item.id).remove();
                if (package=="6") {
                    list.append('<li class="col-md-6 p'+item.id+'" title="'+item.name+'"><input type="hidden" name="apply_objects['+i+']['+item.id+']" value="'+number+'">' +
                        item.name+' - '+number+' sản phẩm <span class="delete-product" title="Xóa">×</span></li>');
                } else {
                    list.append('<li class="col-md-6 p'+item.product_id+'" title="'+item.name+'"><input type="hidden" name="apply_objects['+item.id+']" value="'+number+'">' +
                        item.name+' - '+number+' sản phẩm <span class="delete-product" title="Xóa">×</span></li>');
                }
            });
        });
    }
    function get_list_products(obj_form, sku, number, i) {
        if (typeof i == "undefined") i = 0;

        var list = $(obj_form).find('.list-pro.pa');
        if (i != 0) {
            list = $(obj_form).find('.list-pro.pab');
        }

        var package = $('.package:checked').val();

        $.get(_base_url+'/promotion/get-list-products', {sku: sku}, function (data) {
            $.each(data.products, function (index, item) {
                list.find('.p'+item.product_id).remove();
                if (package=="6") {
                    list.append('<li class="col-md-6 p'+item.product_id+'" title="'+item.name+'"><input type="hidden" name="apply_objects['+i+']['+item.product_id+']" value="'+number+'">' +
                        item.sku+' - '+number+' sản phẩm <span class="delete-product" title="Xóa">×</span></li>');
                } else {
                    list.append('<li class="col-md-6 p'+item.product_id+'" title="'+item.name+'"><input type="hidden" name="apply_objects['+item.product_id+']" value="'+number+'">' +
                        item.sku+' - '+number+' sản phẩm <span class="delete-product" title="Xóa">×</span></li>');
                }
            });
        });
    }
    $('.BtnSave').on('click', function () {
        $('#is_next').val(0);
        var package = $('.package:checked').val();

        _log('submit form ' + package);

        check_input_sku_apply(package);

        $('#form_update'+package).submit();
    });
    function check_input_sku_p5(obj_form) {
        var tmp = $(obj_form + ' .list-pro.pa input').length;
        if (tmp > 0) {
            remove_rule_sku(obj_form);
        } else {
            add_rule_sku(obj_form);
        }

        tmp = $(obj_form + ' .list-pro.pb .giff-0').length;
        if (tmp > 0) {
            remove_rule_giff_sku_b(obj_form);
        } else {
            add_rule_giff_sku_b(obj_form);
        }

        tmp = $(obj_form + ' .list-pro.pb .giff-1').length;
        if (tmp > 0) {
            remove_rule_giff_sku_c(obj_form);
        } else {
            add_rule_giff_sku_c(obj_form);
        }
    }
    function check_input_sku_p3(obj_form) {
        var tmp = $(obj_form + ' .list-pro.pa input').length;
        if (tmp > 0) {
            remove_rule_sku(obj_form);
        } else {
            add_rule_sku(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb input').length;
        if (tmp > 0) {
            remove_rule_giff_sku_b(obj_form);
        } else {
            add_rule_giff_sku_b(obj_form);
        }
    }
    function check_input_sku_apply(package) {
        _log("check_input_sku_apply");
        switch (package) {
            case "1":
                check_input_sku('#form_update'+package);
                break;
            case "2":
                check_input_sku_p2('#form_update'+package);
                break;
            case "3":
                check_input_sku_p3('#form_update'+package);
                break;
            case "4":
                check_input_sku_p4('#form_update'+package);
                break;
            case "5":
                check_input_sku_p5('#form_update'+package);
                break;
            case "6":
                check_input_sku_p6('#form_update'+package);
                break;
            case "7":
                check_input_sku_p7('#form_update'+package);
                break;
            case "8":
                check_input_sku_p8('#form_update'+package);
                break;
            case "11":
                $('#form_update'+package+' #apply_objects').rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập giá trị đơn hàng được áp dụng",
                    }
                });
                break;
            case "12":
                $('#form_update'+package+' #apply_objects' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập số lượng sản phẩm để đơn hàng được áp dụng",
                    }
                });
                break;
            case "13":
                $('#form_update'+package+' #apply_objects' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập số lượng sản phẩm để đơn hàng được áp dụng",
                    }
                });
                $('#form_update'+package+' #gift_order_sku' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập sku khuyến mãi",
                    }
                });
                break;
            case "14":
                $('#form_update'+package+' #apply_objects' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập giá trị đơn hàng được áp dụng",
                    }
                });
                $('#form_update'+package+' #gift_order_sku' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập sku khuyến mãi",
                    }
                });
                break;
            case "15":
                $('#form_update'+package+' #apply_objects' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập giá trị đơn hàng được áp dụng",
                    }
                });
                $('#form_update'+package+' #gift_products' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Điền số tiền giảm cho đơn hàng",
                    }
                });
                break;
            case "16":
                $('#form_update'+package+' #apply_objects' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập giá trị đơn hàng được áp dụng",
                    }
                });
                $('#form_update'+package+' #brand_id' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Nhập ID thương hiệu phải mua",
                    }
                });
                $('#form_update'+package+' #gift_products' ).rules( "add", {
                    required: true,
                    messages: {
                        required: "Điền số tiền giảm cho đơn hàng",
                    }
                });
                break;
        }
    }
    $('.BtnSaveNext').on('click', function () {
        $('#is_next').val(1);
        var package = $('.package:checked').val();

        check_input_sku_apply(package);

        $('#form_update'+package).submit();
    });

    $('input.slider-toggle').each(function (key, msg) {
        if ($(this).is(':checked')) {
            $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
        } else {
            $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
        }
    });

    $('.switch_status').on('click', function () {
        var ids = [];
        $('.item_check:checked').each(function (key, msg) {
            ids.push($(this).val());
        });
        $.post('', {
            ids: ids,
            status: $('#status_action').val(),
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

    $('.add-action').on('click', function () {
        $(this).hide();
        $('.banner-display').slideUp();
        $('.banner-update').slideDown();

        $('.TitleCreate').show();
        $('.TitleDisplay').hide();
        $('.BackAction').show();
    });
    $('.add-action-none').on('click', function () {
        $(this).parent().slideUp();
        $('.banner-update').slideDown();
        $('.BackAction').show();
    });
    $('.banner-update .cancel').on('click', function (e) {
        e.preventDefault();

        if ($('#is_reload').val() == '1') {
            location.reload();
            return false;
        }
        $('#is_next').val(0);
//                $('#form_update')[0].reset();

        $('.add-action-none').parent().slideDown();
        $('.banner-display').slideDown();

        if ($('.add-action-none').length > 0) {
            $('.TitleCreate').show();
            $('.TitleDisplay').hide();
        } else {
            $('.TitleCreate').hide();
            $('.TitleDisplay').show();
            $('.add-action').show();
        }
        $('.BackAction').hide();
        $('.banner-update').slideUp();
    });

    $('.update-action-order').on('click', function () {
        var item = _objects[$(this).attr('data-id')];
        var package_id = item.package_id;

        var obj_form = '#form_update'+package_id;

        $('label.error').hide();
        $(obj_form+' #is_next').val(0);
        $(obj_form+' #id').val(item.id);
        $(obj_form+' #name').val(item.name);
        $(obj_form+' #from_date').val(item.from_date);
        $(obj_form+' #to_date').val(item.to_date);

        var tmp;
        switch (package_id) {
            case 13:
            case 14:
            {
                tmp = _products[item.gift_products[0]];
                if (tmp) {
                    $(obj_form + ' #gift_products').val(tmp.product_id);
                    $(obj_form + ' #gift_order_sku').val(tmp.sku);
                    $(obj_form + ' display-sku').html('<a title="'+tmp.name+'">'+tmp.sku+'</a>');
                }
            }
            case 15:
                {
                    tmp = numeral(item.apply_objects);
                    $(obj_form+' .fm-number').val(tmp.format());
                    $(obj_form+' display-price').html(tmp.format());
                    $(obj_form+' #apply_objects').val(tmp.value());

                    tmp = numeral(item.gift_products);
                    $(obj_form+' .fm-number.discount').val(tmp.format());
                    $(obj_form+' display-discount').html(tmp.format());
                    $(obj_form+' #gift_products').val(tmp.value());
                }
                break;
            case 16:
                {
                    var apply_objects = $.parseJSON(item.apply_objects);
                    $(obj_form+' #brand_id').val(apply_objects['brand_id']).trigger('change');

                    tmp = numeral(apply_objects['min-amount-order']);
                    $(obj_form+' .fm-number').val(tmp.format());
                    $(obj_form+' display-price').html(tmp.format());
                    $(obj_form+' #apply_objects').val(tmp.value());

                    tmp = numeral(item.gift_products);
                    $(obj_form+' .fm-number.discount').val(tmp.format());
                    $(obj_form+' display-discount').html(tmp.format());
                    $(obj_form+' #gift_products').val(tmp.value());
                }
                break;
            case 11:
            case 12:
            case 13:
            case 14:
                {
                    tmp = numeral(item.apply_objects);
                    $(obj_form+' .fm-number').val(tmp.format());
                    $(obj_form+' display-price').html(tmp.format());
                    $(obj_form+' #apply_objects').val(tmp.value());
                }
                break;
        }

        $('.add-action').click();
        $('#radio'+package_id).trigger('click');
        $('.list-radio label[for=radio'+package_id+']').trigger('click');
    });

    $('.update-action').on('click', function () {
        var item = _objects[$(this).attr('data-id')];
        var package_id = item.package_id;

        var obj_form = '#form_update'+package_id;

        $('label.error').hide();
        $(obj_form+' #is_next').val(0);
        $(obj_form+' #id').val(item.id);
        $(obj_form+' #name').val(item.name);
        $(obj_form+' #from_date').val(item.from_date);
        $(obj_form+' #to_date').val(item.to_date);
        if (item.status) {
            $(obj_form+' #status').attr('checked', 'checked');
        } else {
            $(obj_form+' #status').removeAttr('checked');
        }

        var list = $(obj_form+' .list-pro');
        switch (package_id) {
            case 1:
                list = $(obj_form+' .list-pro');
                break;
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
                list = $(obj_form+' .list-pro.pa');
                break;
        }
        list.html('');
        var tmp;

        if (item.package_id == 7 || item.package_id == 8) {

            $.each(item.apply_objects, function (id, num) {
                tmp = _brands[id];
                if (tmp) {
                    list.append('<li class="col-md-6 p' + tmp.id + '" title="' + tmp.name + '"><input type="hidden" name="apply_objects[' + tmp.id + ']" value="' + num + '">' +
                        '<a target="_blank" href="'+get_link_brand(tmp.name, tmp.id)+'">' +
                        tmp.name + ' - ' + num + ' sản phẩm</a> <span class="delete-product" title="Xóa">×</span></li>');
                }
            });

        } else if (item.package_id == 6) {
            $.each(item.apply_objects, function (i, items) {
                if (i==1) {
                    list = $(obj_form+' .list-pro.pab');
                    list.html('');
                }
                $.each(items, function (id, num) {
                    tmp = _products[id];
                    if (tmp) {
                        list.append('<li class="col-md-6 p' + tmp.product_id + '" title="' + tmp.name + '"><input type="hidden" name="apply_objects[' + i + '][' + tmp.product_id + ']" value="' + num + '">' +
                            '<a target="_blank" href="' + get_link_product(tmp.name, tmp.product_id) + '">' +
                            tmp.sku + ' - ' + num + ' sản phẩm</a> <span class="delete-product" title="Xóa">×</span></li>');
                    }
                });
            });
        } else {
            $.each(item.apply_objects, function (id, num) {
                tmp = _products[id];
                if (tmp) {
                    list.append('<li class="col-md-6 p' + tmp.product_id + '" title="' + tmp.name + '"><input type="hidden" name="apply_objects[' + tmp.product_id + ']" value="' + num + '">' +
                        '<a target="_blank" href="' + get_link_product(tmp.name, tmp.product_id) + '">' +
                        tmp.sku + ' - ' + num + ' sản phẩm</a> <span class="delete-product" title="Xóa">×</span></li>');
                }
            });
        }

        switch (package_id) {
            case 2:
            case 6:
            case 7:
                list = $(obj_form+' .list-pro.pb');
                list.html('');
                console.log(item);
                tmp = _products[item.gift_products[0]];
                if (tmp) {
                    list.append('<li class="col-md-6 giff-0" title="' + tmp.name + '"><input type="hidden" name="gift_products[]" value="' + tmp.product_id + '">' +
                        '<a target="_blank" href="' + get_link_product(tmp.name, tmp.product_id) + '">' +
                        tmp.sku + '</a> <span class="delete-product" title="Xóa">×</span></li>');
                }
                break;
            case 3:
                list = $(obj_form+' .list-pro.pb');
                list.html('');
                var i = 0;
                $.each(item.gift_products, function (id, num) {
                    tmp = _products[id];
                    list.append('<li class="col-md-6 giff-'+(i++)+'" title="'+tmp.name+'"><input type="hidden" name="apply_objects['+tmp.product_id+']" value="'+num+'">' +
                        '<a target="_blank" href="'+get_link_product(tmp.name, tmp.product_id)+'">' +
                        tmp.sku+' - '+num+' sản phẩm</a> <span class="delete-product" title="Xóa">×</span></li>');
                });
                break;
            case 4:
            case 8:
                list = $(obj_form+' .list-pro.pb');
                list.html('');
                $.each(item.gift_products, function (i, id) {
                    tmp = _products[id];
                    if (tmp) {
                        list.append('<li class="col-md-6 giff-' + i + '" title="' + tmp.name + '"><input type="hidden" name="apply_objects[]" value="' + tmp.product_id + '">' +
                            '<a target="_blank" href="'+get_link_product(tmp.name, tmp.product_id)+'">' +
                            tmp.sku + '</a> <span class="delete-product" title="Xóa">×</span></li>');
                    }
                });
                break;
        }

        $('.add-action').click();
        $('#radio'+package_id).trigger('click');
        $('.list-radio label[for=radio'+package_id+']').trigger('click');
    });

    $('form.frm_update').each(function () {
        _log('#'+$(this).attr('id'));
        init_form_update('#'+$(this).attr('id'));
    });

    function init_form_update(obj_form) {
        $(obj_form).validate({
            ignore: ".ignore",
            rules: {
                name: "required",
                from_date: "required",
                to_date: "required",
            },
            messages: {
                name: "Vui lòng nhập tên chương trình quà tặng",
                from_date: "Chọn ngày bắt đầu",
                to_date: "Chọn ngày kết thúc"
            },
            submitHandler: function(form) {
                submitHandler(form);

                return false;
            }
        });
    }

    function submitHandler(form) {
        // do other things for a valid form
        var data = $(form).serializeArray();
        var url = $(form).attr('action');
        $.post(url, data).done(function(data){
            if(data.rs == 1)
            {
                alert_success(data.msg, function () {
                    if ($('#is_next').val()=='1') {
                        $('.add-action').click();

                        $('#is_reload').val(1);
                        $('#is_next').val(0);
                        $(form)[0].reset();
                        $(form).find('.list-pro').html('');
                    } else {
                        location.reload();
                    }
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
    function check_input_sku_p6(obj_form) {
        var tmp = $(obj_form + ' .list-pro.pa input').length;
        if (tmp > 0) {
            remove_rule_sku(obj_form);
        } else {
            add_rule_sku(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pab input').length;
        if (tmp > 0) {
            remove_rule_sku_b(obj_form);
        } else {
            add_rule_sku_b(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb input').length;
        if (tmp > 0) {
            remove_rule_giff_sku(obj_form);
        } else {
            add_rule_giff_sku(obj_form);
        }
    }
    function check_input_sku_p7(obj_form) {
        var tmp = $(obj_form + ' .list-pro.pa input').length;
        if (tmp > 0) {
            remove_rule_brand(obj_form);
        } else {
            add_rule_brand(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb input').length;
        if (tmp > 0) {
            remove_rule_giff_sku(obj_form);
        } else {
            add_rule_giff_sku(obj_form);
        }
    }
    function check_input_sku_p8(obj_form) {
        var tmp = $(obj_form + ' .list-pro.pa input').length;
        if (tmp > 0) {
            remove_rule_brand(obj_form);
        } else {
            add_rule_brand(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb .giff-0').length;
        if (tmp > 0) {
            remove_rule_giff_sku(obj_form);
        } else {
            add_rule_giff_sku(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb .giff-1').length;
        if (tmp > 0) {
            remove_rule_giff_sku_c(obj_form);
        } else {
            add_rule_giff_sku_c(obj_form);
        }
    }
    function check_input_sku_p2(obj_form) {
        var tmp = $(obj_form + ' .list-pro.pa input').length;
        if (tmp > 0) {
            remove_rule_sku(obj_form);
        } else {
            add_rule_sku(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb input').length;
        if (tmp > 0) {
            remove_rule_giff_sku(obj_form);
        } else {
            add_rule_giff_sku(obj_form);
        }
    }
    function add_rule_giff_sku(obj_form) {
        $(obj_form + " #gift_products_sku" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập sku quà tặng",
            }
        });
    }
    function remove_rule_giff_sku(obj_form) {
        $(obj_form + " #gift_products_sku" ).rules( "remove" );
    }
    function add_rule_giff_sku_c(obj_form) {
        $(obj_form + " #gift_products_sku_c" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập sku quà tặng",
            }
        });
    }
    function remove_rule_giff_sku_c(obj_form) {
        $(obj_form + " #gift_products_sku_c" ).rules( "remove" );
    }
    function add_rule_giff_sku_b(obj_form) {
        $(obj_form + " #gift_products_sku_b" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập sku quà tặng",
            }
        });
        $(obj_form + " #gift_products_number_b" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập số lượng",
            }
        });
    }
    function remove_rule_giff_sku_b(obj_form) {
        $(obj_form + " #gift_products_sku_b" ).rules( "remove" );
        $(obj_form + " #gift_products_number_b" ).rules( "remove" );
    }
    function add_rule_giff_sku_c(obj_form) {
        $(obj_form + " #gift_products_sku_c" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập sku quà tặng",
            }
        });
        $(obj_form + " #gift_products_number_c" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập số lượng",
            }
        });
    }
    function remove_rule_giff_sku_c(obj_form) {
        $(obj_form + " #gift_products_sku_c" ).rules( "remove" );
        $(obj_form + " #gift_products_number_c" ).rules( "remove" );
    }
    function add_rule_brand(obj_form) {
        $(obj_form + " #apply_brands" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập brands áp dụng khuyến mãi",
            }
        });
        $(obj_form + " #apply_number" ).rules( "add", {
            required: true,
            messages: {
                required: "Nhập số lượt áp dụng",
            }
        });
    }
    function remove_rule_brand(obj_form) {
        $(obj_form + " #apply_brands" ).rules( "remove" );
        $(obj_form + " #apply_number" ).rules( "remove" );
    }
    function check_input_sku_p4(obj_form) {
        var tmp = $(obj_form + ' .list-pro.pa input').length;
        if (tmp > 0) {
            remove_rule_sku(obj_form);
        } else {
            add_rule_sku(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb .giff-0').length;
        if (tmp > 0) {
            remove_rule_giff_sku(obj_form);
        } else {
            add_rule_giff_sku(obj_form);
        }

        var tmp = $(obj_form + ' .list-pro.pb .giff-1').length;
        if (tmp > 0) {
            remove_rule_giff_sku_c(obj_form);
        } else {
            add_rule_giff_sku_c(obj_form);
        }
    }
});