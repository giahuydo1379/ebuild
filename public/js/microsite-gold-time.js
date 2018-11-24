$(document).ready(function() {
    init_datepicker('.datepicker');
    $('textarea#description').ckeditor();

    $(document).on('click', '.delete-banner-so', function () {
        $(this).parent().remove();
    });

    $('.btn-start').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn kích hoạt trang giờ vàng giá sốc này không?', 'Xác nhận kích hoạt trang giờ vàng giá sốc', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/gold-time/update-status', {id: id, status: 1}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });
    $('.btn-stop').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn dừng trang giờ vàng giá sốc này không?', 'Xác nhận dừng trang giờ vàng giá sốc', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/gold-time/update-status', {id: id, status: 0}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });

    $('.add-banner-other').on('click', function () {
        var bo_url = $('#bo_url').val();
        var bo_location = $('#bo_location').val();
        var bo_link = $('#bo_link').val();

        if (bo_location=='' || bo_link=='') {
            $('.banner-other.error').html('Chọn ảnh và nhập link cho banner').show();
            $('#bo_link').focus();

            return false;
        }
        $('.banner-other.error').hide();

        add_banner_other(bo_location, bo_url, bo_link, $.now());

        $('#bo_url').val('');
        $('#bo_location').val('');
        $('#bo_link').val('');
        $('.preview-banner-other').attr('src', '/html/assets/images/img_upload.png');
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
    $('.BtnSaveProductGiff').on('click', function () {
        $("#fp_name" ).rules( "add", {
            required: true,
            messages: {
                required: "Điền tên tầng sản phẩm",
            }
        });
        $("#fp_ordering" ).rules( "add", {
            required: true,
            messages: {
                required: "Chọn thứ tự hiển thị",
            }
        });
        $("#fp_sort" ).rules( "add", {
            required: true,
            messages: {
                required: "Chọn hiển thị sản phẩm theo",
            }
        });
        $("#fp_sku").rules( "add", {
            required: true,
            messages: {
                required: "Điền mã SKU sản phẩm khuyến mãi",
            }
        });

        var flag1 = $("#fp_name").valid();
        var flag2 = $("#fp_ordering").valid();
        var flag3 = $("#fp_sort").valid();
        var flag4 = $("#fp_sku").valid();

        if ( flag1 && flag2 && flag3 && flag4 ) {
            var item = $('#fp_item').val();

            _floors_products[item] = {
                name: $("#fp_name").val(),
                ordering: $("#fp_ordering").val(),
                sort: $("#fp_sort").val(),
                product_ids: $('.list-products .color').html(),
                sku: $("#fp_sku").val()
            };

            add_giff_product(item, $("#fp_name").val(), $("#fp_ordering").val(), $("#fp_sort").val(), $('.list-products .color').html());
            $('.list-products .color').html('');

            if ($('#is_next_giff').val()=='1') {
                $('.add-giff-product').click();
            } else {
                $('.no-banner.add-giff-product').hide();
                $('.CreateLoad .cancel-giff').click();
            }
        }

        $("#fp_name").rules( "remove" );
        $("#fp_ordering").rules( "remove");
        $("#fp_sort").rules("remove");
        $("#fp_sku").rules("remove");

        return false;
    });

    $('.add-giff-product').on('click', function () {
        $(this).hide();
        $('.display-product').slideUp();
        $('.LoadProduct .CreateLoad').slideDown();

        $('.LoadProduct .TitleCreate').show();
        $('.LoadProduct .TitleDisplay').hide();

        $('#is_next_giff').val(0);
        $("#fp_item").val($.now());
        $("#fp_name").val("");
        $("#fp_ordering").val("");
        $("#fp_sort").val("");
        $("#fp_sku").val("");

        $('.list-products').hide();
        $('.list-products .color').html("");
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
        $('.link.add-giff-product').hide();
        $('.link.add-shock-product').hide();
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
        CKEDITOR.instances.description.setData( '' );
        $('.preview-banner-desktop').attr('src', '');
        $('#image_url_desktop').val('');
        $('#image_location_desktop').val('');
        $('.preview-banner-tablet').attr('src', '');
        $('#image_url_tablet').val('');
        $('#image_location_tablet').val('');
        $('.preview-banner-mobile').attr('src', '');
        $('#image_url_mobile').val('');
        $('#image_location_mobile').val('');

        $('.right-banner-other .wrap_images').html('');
        $('.list-radio input[value=normal]').trigger('click');

        $('.display-product .list-level').html('');
        $('.no-banner.add-giff-product').show();

        $('.display-product .list-shocks').html('');
        $('.no-banner.add-shock-product').show();

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

        $('.banner-update').slideUp();
    });

    $('.update-action').on('click', function () {
        var item = _objects[$(this).attr('data-id')];

        $('.add-action').click();
        $('.list-radio input[value='+item.layout+']').trigger('click');

        var obj_form = '#form_update';

        // floors_products
        _floors_products = [];
        _shocks_products = [];

        if (item.floors_products || item.products) {
            var product_ids = [];
            if (item.floors_products) {
                $('.link.add-giff-product').show();
                $('.no-banner.add-giff-product').hide();
                $('.LoadProduct .display-product').show();

                $.each(item.floors_products, function (index, other) {
                    if (other.product_ids) {
                        product_ids = $.merge(product_ids, other.product_ids);
                    }
                });
            }
            if (item.products) {
                $('.link.add-shock-product').show();
                $('.no-banner.add-shock-product').hide();
                $('.LoadProductShock .display-product').show();

                $.each(item.products, function (index, other) {
                    if (other.product_id) {
                        product_ids.push(other.product_id);
                    }
                });
            }

            $.post(_base_url + '/promotion/get-list-products-by-ids', {
                ids: product_ids,
            }, function (data) {
                var products = data.products;
                var product_ids = '', sku = '';
                if (item.floors_products) {
                    $.each(item.floors_products, function (index, other) {
                        if (other) {
                            product_ids = '';
                            sku = '';
                            if (other.product_ids) {
                                product_ids = [];
                                sku = [];
                                other.product_ids = $.unique(other.product_ids);
                                $.each(other.product_ids, function (i, pid) {
                                    if(products[pid]){
                                        product_ids.push(get_sku_item(index, products[pid].name, products[pid].product_id, products[pid].sku));
                                        sku.push(products[pid].sku);    
                                    }
                                });
                                product_ids = product_ids.join(', ');
                                sku = sku.join(',');
                            }
                            _floors_products[index] = {
                                name: other.name,
                                ordering: other.ordering,
                                sort: other.sort,
                                product_ids: product_ids,
                                sku: sku
                            };
                            add_giff_product(index, other.name, other.ordering, other.sort, product_ids);
                        }
                    });
                }

                if (item.products) {
                    $.each(item.products, function (index, other) {
                        if (other) {
                            var sp = products[other.product_id];
                            if(sp){
                                    _shocks_products[index] = {
                                    name: sp.name,
                                    product_id: sp.product_id,
                                    price: sp.price,
                                    sku: sp.sku,
                                    from_time: other.from_time,
                                    from_date: other.from_date,
                                    to_time: other.to_time,
                                    to_date: other.to_date
                                };
                                add_shock_product(index, sp.product_id, sp.name, sp.price, sp.sku, other.from_time, other.from_date,
                                    other.to_time, other.to_date);
                            }
                        }
                    });
                }
            });
        }

        $('label.error').hide();
        $(obj_form + ' #is_next').val(0);
        $(obj_form + ' #id').val(item.id);
        $(obj_form + ' #name').val(item.name);
        $(obj_form + ' #alias').val(item.alias);
        $(obj_form + ' #from_time').val(item.from_time);
        $(obj_form + ' #from_date').val(item.from_date);
        $(obj_form + ' #to_time').val(item.to_time);
        $(obj_form + ' #to_date').val(item.to_date);
        $(obj_form + ' #description').val(item.description);
        if (item.status) {
            $(obj_form + ' #status').attr('checked', 'checked');
        } else {
            $(obj_form + ' #status').removeAttr('checked');
        }

        if (item.banner) {
            var tmp = item.banner['desktop'];
            if (tmp) {
                $('.preview-banner-desktop').attr('src', tmp['image_url']+tmp['image_location']);
                $('#image_url_desktop').val(tmp['image_url']);
                $('#image_location_desktop').val(tmp['image_location']);
            }
            tmp = item.banner['tablet'];
            if (tmp) {
                $('.preview-banner-tablet').attr('src', tmp['image_url']+tmp['image_location']);
                $('#image_url_tablet').val(tmp['image_url']);
                $('#image_location_tablet').val(tmp['image_location']);
            }
            tmp = item.banner['mobile'];
            if (tmp) {
                $('.preview-banner-mobile').attr('src', tmp['image_url']+tmp['image_location']);
                $('#image_url_mobile').val(tmp['image_url']);
                $('#image_location_mobile').val(tmp['image_location']);
            }
        }

        // banners_other
        if (item.banners_other) {
            $.each(item.banners_other, function (index, other) {
                add_banner_other(other.location, other.url, other.link, index);
            });
        }
    });

    /**
     * start shock product
     */
    $('.add-shock-product').on('click', function () {
        $(this).hide();
        $('.LoadProductShock .display-product').slideUp();
        $('.LoadProductShock .CreateLoad').slideDown();

        $('.LoadProductShock .TitleCreate').show();
        $('.LoadProductShock .TitleDisplay').hide();

        $('#is_next_shock').val(0);
        $("#p_item").val($.now());
        $("#p_sku").val("");
        $("#p_from_time").val("");
        $("#p_from_date").val("");
        $("#p_to_time").val("");
        $("#p_to_date").val("");
        $("#p_product_id").val("");

        $('.info-sku-shock').html("");
    });

    $('.LoadProductShock .cancel').unbind('click');
    $('.LoadProductShock .cancel').on('click', function (e) {
        e.preventDefault();

        $('.LoadProductShock .display-product').slideDown();

        $('.add-shock-product').hide();

        if ($('.shock-item').length > 0) {
            $('.LoadProductShock .TitleCreate').hide();
            $('.LoadProductShock .TitleDisplay').show();

            $('.link.add-shock-product').show();
        } else {
            $('.LoadProductShock .TitleCreate').show();
            $('.LoadProductShock .TitleDisplay').hide();

            $('.no-banner.add-shock-product').show();
        }
        $('.LoadProductShock .CreateLoad').slideUp();

        $('.info-sku-shock').html("");

        return false;
    });
    $('#p_sku').on('change', function () {
        $('#p_product_id').val('');
        $('.info-sku-shock').html('');
        if ($(this).val()=='') {
            return false;
        }

        $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
            var tmp = data.products[0];
            if (tmp) {
                $('#p_product_id').val(tmp.product_id);
                $('#p_name').val(tmp.name);
                $('#p_price').val(tmp.price);
                $('#p_sku').val(tmp.sku);
                $('.info-sku-shock').html('Sản phẩm <a class="color">' + tmp.name + '</a> - giá <a class="color">' + numeral(tmp.price).format() + ' VNĐ;</a>');
            } else {
                $('.info-sku-shock').html('<a class="color">Không tìm thấy sản phẩm</a>');
            }
        });
    });
    $('.BtnSaveProductShock').on('click', function () {
        $("#p_sku" ).rules( "add", {
            required: true,
            messages: {
                required: "Điền sku sản phẩm bán giá sốc",
            }
        });
        $("#p_product_id" ).rules( "add", {
            required: true,
            messages: {
                required: "Không tìm thấy sản phẩm với sku trên",
            }
        });
        $("#p_from_time" ).rules( "add", {
            required: true,
            messages: {
                required: "Chọn giờ bắt đầu",
            }
        });
        $("#p_from_date" ).rules( "add", {
            required: true,
            messages: {
                required: "Chọn ngày bắt đầu",
            }
        });
        $("#p_to_time").rules( "add", {
            required: true,
            messages: {
                required: "Chọn giờ kết thúc",
            }
        });
        $("#p_to_date").rules( "add", {
            required: true,
            messages: {
                required: "Chọn ngày kết thúc",
            }
        });

        var flag1 = $("#p_sku").valid() && $("#p_product_id").valid();
        var flag2 = $("#p_from_time").valid();
        var flag3 = $("#p_from_date").valid();
        var flag4 = $("#p_to_time").valid();
        var flag5 = $("#p_to_date").valid();

        if ( flag1 && flag2 && flag3 && flag4 && flag5 ) {
            var item = $('#p_item').val();

            _shocks_products[item] = {
                from_time: $("#p_from_time").val(),
                from_date: $("#p_from_date").val(),
                to_time: $("#p_to_time").val(),
                to_date: $("#p_to_date").val(),
                sku: $("#p_sku").val(),
                product_id: $("#p_product_id").val(),
                price: $("#p_price").val(),
                name: $("#p_name").val()
            };

            add_shock_product(item, $("#p_product_id").val(), $("#p_name").val(), $("#p_price").val(), $("#p_sku").val(),
                $("#p_from_time").val(), $("#p_from_date").val(), $("#p_to_time").val(), $("#p_to_date").val());

            if ($('#is_next_shock').val()=='1') {
                $('.add-shock-product').click();
            } else {
                $('.no-banner.add-shock-product').hide();
                $('.CreateLoad .cancel-shock').click();
            }
        }

        $("#p_sku").rules( "remove" );
        $("#p_product_id").rules( "remove");
        $("#p_from_time").rules("remove");
        $("#p_from_date").rules("remove");
        $("#p_to_time").rules("remove");
        $("#p_to_date").rules("remove");

        return false;
    });
    /**
     * end shock product
     */
    $('.list-radio input[name=layout]').on('click', function () {
        $('.list-radio .radio').removeClass('active');
        $(this).closest('div').addClass('active');
    });

    $('form.frm_update').each(function () {
        init_form_update('#'+$(this).attr('id'));
    });

    function init_form_update(obj_form) {
        $(obj_form).validate({
            ignore: ".ignore",
            rules: {
                name: "required",
                from_date: "required",
                from_time: "required",
                to_date: "required",
                to_time: "required",
                "banner[desktop][image_location]": "required",
                "banner[tablet][image_location]": "required",
                "banner[mobile][image_location]": "required",
                "description": "required",
            },
            messages: {
                name: "Vui lòng nhập tên chương trình quà tặng",
                from_date: "Chọn ngày bắt đầu",
                from_time: "Chọn giờ bắt đầu",
                to_date: "Chọn ngày kết thúc",
                to_time: "Chọn giờ kết thúc",
                "banner[desktop][image_location]": "Chọn banner desktop",
                "banner[tablet][image_location]": "Chọn banner tablet",
                "banner[mobile][image_location]": "Chọn banner mobile",
                "description": "Nhập nội dung giới thiệu chương trình khuyến mãi",
            },
            submitHandler: function(form) {
                if ($('.shock-item').length == 0) {
                    malert('Vui lòng thêm sản phẩm bán giá sốc', null, function () {
                        $('.add-shock-product').click();
                        $('#p_name').focus();
                    });
                    return false;
                }

                if ($('.giff-item').length == 0) {
                    malert('Vui lòng thêm sản phẩm khuyến mãi', null, function () {
                        $('.add-giff-product').click();
                        $('#fp_name').focus();
                    });
                    return false;
                }

                // if ($('.right-banner-other .wrap_images').html() == '') {
                //     malert('Vui lòng thêm một vài banner khuyến mãi', null, function () {
                //         $('.add-banner-other').trigger('click');
                //     });
                //     return false;
                // }

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
});

function get_sku_item(now, name, product_id, sku) {
    return '<a title="'+name+'">' +
        '<input type="hidden" name="floors_products['+now+'][product_ids][]" value="'+product_id+'">'+sku+'</a>';
}
function delete_shock(obj) {
    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if ($('.shock-item').length == 0) {
            $('.no-banner.add-shock-product').show();
            $('.link.add-shock-product').hide();

            $('.LoadProductShock .TitleCreate').show();
            $('.LoadProductShock .TitleDisplay').hide();
        }
    });
}
function update_shock(item) {
    $('.link.add-shock-product').trigger('click');
    var ps = _shocks_products[item];

    if (ps) {
        $('#is_next_shock').val(0);
        $("#p_item").val(item);
        $("#p_sku").val(ps.sku);
        $("#p_from_time").val(ps.from_time);
        $("#p_from_date").val(ps.from_date);
        $("#p_to_time").val(ps.to_time);
        $("#p_to_date").val(ps.to_date);
        $("#p_name").val(ps.name);
        $("#p_product_id").val(ps.product_id);
        $("#p_price").val(ps.price);

        $('.info-sku-shock').html('Sản phẩm <a class="color">' + ps.name + '</a> - giá <a class="color">' + numeral(ps.price).format() + ' VNĐ;</a>');
    }
}

function delete_giff(obj) {
    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if ($('.giff-item').length == 0) {
            $('.no-banner.add-giff-product').show();
            $('.link.add-giff-product').hide();

            $('.LoadProduct .TitleCreate').show();
            $('.LoadProduct .TitleDisplay').hide();
        }
    });
}
function update_giff(item) {
    $('.link.add-giff-product').trigger('click');
    var fp = _floors_products[item];

    if (fp) {
        $('#is_next_giff').val(0);
        $("#fp_item").val(item);
        $("#fp_name").val(fp.name);
        $("#fp_ordering").val(fp.ordering);
        $("#fp_sort").val(fp.sort);
        $("#fp_sku").val(fp.sku);
        $('.list-products .color').html(fp.product_ids);
        $('.list-products').show();
    }
}
function add_shock_product(item, product_id, name, price, sku, from_time, from_date, to_time, to_date)
{
    var html = '<li class="shock-item '+item+'">\n' +
        '                                                <div class="col-md-11 no-padding">\n' +
        '<input type="hidden" name="products['+item+'][product_id]" value="'+product_id+'">' +
        '<input type="hidden" name="products['+item+'][from_time]" value="'+from_time+'">' +
        '<input type="hidden" name="products['+item+'][from_date]" value="'+from_date+'">' +
        '<input type="hidden" name="products['+item+'][to_time]" value="'+to_time+'">' +
        '<input type="hidden" name="products['+item+'][to_date]" value="'+to_date+'">' +
        '                                                    <p>Sản phẩm <span class="color">'+name+' - '+sku+';</span> ' +
        'Được bán với giá <span class="color">'+price+' VNĐ;</span> ' +
        'Bắt đầu từ <span class="color">'+from_time+' ngày '+from_date+'</span> ' +
        'đến <span class="color">'+to_time+' ngày '+to_date+'</span></p>\n' +
        '                                                </div>\n' +
        '                                                <div class="col-md-1 text-right haft-padding-right">\n' +
        '                                                    <a class="tooltip" onclick="delete_shock(this)">\n' +
        '                                                        <i class="fa fa-times" aria-hidden="true"></i>\n' +
        '                                                        <span class="tooltiptext">Xóa</span>\n' +
        '                                                    </a>\n' +
        '                                                    <a class="tooltip" onclick="update_shock('+item+')">\n' +
        '                                                        <i class="fa fa-pencil" aria-hidden="true" ></i>\n' +
        '                                                        <span class="tooltiptext">Cập nhật</span>\n' +
        '                                                    </a>\n' +
        '                                                </div>\n' +
        '                                            </li>';

    if ($('.shock-item.'+item).length > 0) {
        $('.shock-item.'+item).replaceWith(html);
    } else {
        $('.display-product .list-shocks').append(html);
    }
}
function add_giff_product(item, name, ordering, sort, products) {
    var html = '<li class="giff-item '+item+'">' +
        '<div class="col-md-6 no-padding">' +
        '<ul class="list-infor">' +
        '<li><b>Tên tầng sản phẩm: </b><input type="hidden" name="floors_products['+item+'][name]" value="'+name+'">'+name+'</li>' +
        '<li><b>Thứ tự hiển thị: </b>'+$("#fp_ordering option[value="+ordering+"]").text()+'</li><input type="hidden" name="floors_products['+item+'][ordering]" value="'+ordering+'">' +
        '<li><b>Hiển thị sản phẩm theo: </b>'+$("#fp_sort option[value="+sort+"]").text()+'</li><input type="hidden" name="floors_products['+item+'][sort]" value="'+sort+'">' +
        '</ul>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<label for="">Danh sách sản phẩm khuyến mãi: </label>' +
        '<div class="color">' +
        products +
        '</div>' +
        '</div>' +
        '<div class="action text-right">' +
        '<span class="cancel" onclick="delete_giff(this)">Xóa</span>' +
        '<button type="button" onclick="update_giff('+item+')" class="btn btn_primary">Cập nhật</button>' +
        '</div>' +
        '</li>';

    if ($('.giff-item.'+item).length > 0) {
        $('.giff-item.'+item).replaceWith(html);
    } else {
        $('.display-product .list-level').append(html);
    }
}
function add_banner_other(location, url, link, index) {
    var html = '<div class="col-md-6">' +
        '<span class="delete-banner-so" aria-hidden="true">×</span>' +
        '<img src="'+url+location+'" alt="Banner other" />' +
        '<input type="hidden" value="'+location+'" name="banners_other['+index+'][location]"/>' +
        '<input type="hidden" value="'+url+'" name="banners_other['+index+'][url]"/>' +
        '<input type="hidden" value="'+link+'" name="banners_other['+index+'][link]"/>' +
        '</div>';

    $('.right-banner-other .wrap_images').append(html);
}