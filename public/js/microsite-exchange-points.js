$(document).ready(function() {
    init_datepicker('.datepicker');
    $('textarea.editor').ckeditor();

    $('.btn-start').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn kích hoạt trang tích điểm đổi quà này không?', 'Xác nhận kích hoạt trang tích điểm đổi quà', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/exchange-points/update-status', {id: id, status: 1}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });
    $('.btn-stop').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn dừng trang tích điểm đổi quà này không?', 'Xác nhận dừng trang tích điểm đổi quà', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/exchange-points/update-status', {id: id, status: 0}, function () {
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

    $('.fp_sku').on('change', function () {
        var point = $(this).attr('data-point');

        $.get(_base_url+'/promotion/get-list-products', {sku: $(this).val()}, function (data) {
            var list = [];
            var sku = [];
            $.each(data.products, function (index, item) {
                list.push( get_sku_item(point, item.name, item.product_id, item.sku) );
                sku.push(item.sku);
            });
            $('.giff_'+point).html(list.join(', '));
            $('#fp_sku_'+point).val(sku.join(','));
        });
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

        if (item.gifts_products) {
            var product_ids = [];
            if (item.gifts_products['product_ids_20']) {
                product_ids = $.merge(product_ids, item.gifts_products['product_ids_20']);
            }
            if (item.gifts_products['product_ids_40']) {
                product_ids = $.merge(product_ids, item.gifts_products['product_ids_40']);
            }
            if (item.gifts_products['product_ids_60']) {
                product_ids = $.merge(product_ids, item.gifts_products['product_ids_60']);
            }
            if (item.gifts_products['product_ids_100']) {
                product_ids = $.merge(product_ids, item.gifts_products['product_ids_100']);
            }

            $.post(_base_url + '/promotion/get-list-products-by-ids', {
                ids: product_ids,
            }, function (data) {
                var products = data.products;
                var product_ids = '', sku = '';
                if (item.gifts_products['product_ids_20']) {
                    product_ids = [];
                    sku = [];
                    $.each(item.gifts_products['product_ids_20'], function (index, pid) {
                        if (products[pid]) {
                            product_ids.push(get_sku_item('20', products[pid].name, products[pid].product_id, products[pid].sku));
                            sku.push(products[pid].sku);
                        }
                    });

                    $('.giff_20').html( product_ids.join(', ') );
                    $('#fp_sku_20').val(sku.join(','));
                }
                if (item.gifts_products['product_ids_40']) {
                    product_ids = [];
                    sku = [];
                    $.each(item.gifts_products['product_ids_40'], function (index, pid) {
                        if (products[pid]) {
                            product_ids.push(get_sku_item('40', products[pid].name, products[pid].product_id, products[pid].sku));
                            sku.push(products[pid].sku);
                        }
                    });

                    $('.giff_40').html( product_ids.join(', ') );
                    $('#fp_sku_40').val(sku.join(','));
                }
                if (item.gifts_products['product_ids_60']) {
                    product_ids = [];
                    sku = [];
                    $.each(item.gifts_products['product_ids_60'], function (index, pid) {
                        if (products[pid]) {
                            product_ids.push(get_sku_item('60', products[pid].name, products[pid].product_id, products[pid].sku));
                            sku.push(products[pid].sku);
                        }
                    });

                    $('.giff_60').html( product_ids.join(', ') );
                    $('#fp_sku_60').val(sku.join(','));
                }
                if (item.gifts_products['product_ids_100']) {
                    product_ids = [];
                    sku = [];
                    $.each(item.gifts_products['product_ids_100'], function (index, pid) {
                        if (products[pid]) {
                            product_ids.push(get_sku_item('100', products[pid].name, products[pid].product_id, products[pid].sku));
                            sku.push(products[pid].sku);
                        }
                    });

                    $('.giff_100').html( product_ids.join(', ') );
                    $('#fp_sku_100').val(sku.join(','));
                }
            });

            var tmp = item.gifts_products['banner'];
            if (tmp) {
                $('.preview-banner-giff').attr('src', tmp['image_url']+tmp['image_location']);
                $('#image_url_giff').val(tmp['image_url']);
                $('#image_location_giff').val(tmp['image_location']);
            }
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
        $(obj_form + ' #rule').val(item.rule);
        $(obj_form + ' #interest').val(item.interest);
        $(obj_form + ' #guide').val(item.guide);
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
                "rule": "required",
                "interest": "required",
                "guide": "required",
                "gifts_products[banner][image_location]": "required",
                "fp_sku_20": "required",
                "fp_sku_40": "required",
                "fp_sku_60": "required",
                "fp_sku_100": "required",
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
                "rule": "Nhập nội dung đăng ký và quy định",
                "interest": "Nhập nội dung điều kiện và quyền lợi",
                "guide": "Nhập nội dung hướng dẫn đổi quà",
                "gifts_products[banner][image_location]": "Chọn ảnh quà tặng",
                "fp_sku_20": "Điền mã SKU sản phẩm quà tặng",
                "fp_sku_40": "Điền mã SKU sản phẩm quà tặng",
                "fp_sku_60": "Điền mã SKU sản phẩm quà tặng",
                "fp_sku_100": "Điền mã SKU sản phẩm quà tặng",
            },
            submitHandler: function(form) {
                if ($('.giff_20').html() == '') {
                    malert('Không tìm thấy sản phẩm của sku tích lũy 20,000,000 VNĐ', null, function () {
                        $('#fp_sku_20').focus();
                    });
                    return false;
                }
                if ($('.giff_40').html() == '') {
                    malert('Không tìm thấy sản phẩm của sku tích lũy 40,000,000 VNĐ', null, function () {
                        $('#fp_sku_40').focus();
                    });
                    return false;
                }
                if ($('.giff_40').html() == '') {
                    malert('Không tìm thấy sản phẩm của sku tích lũy 40,000,000 VNĐ', null, function () {
                        $('#fp_sku_40').focus();
                    });
                    return false;
                }
                if ($('.giff_100').html() == '') {
                    malert('Không tìm thấy sản phẩm của sku tích lũy 100,000,000 VNĐ', null, function () {
                        $('#fp_sku_100').focus();
                    });
                    return false;
                }
                if ($('.right-banner-other .wrap_images').html() == '') {
                    malert('Vui lòng thêm một vài banner khuyến mãi', null, function () {
                        $('.add-banner-other').trigger('click');
                    });
                    return false;
                }

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

function get_sku_item(point, name, product_id, sku) {
    return '<a title="'+name+'">' +
        '<input type="hidden" name="gifts_products[product_ids_'+point+'][]" value="'+product_id+'">'+sku+'</a>';
}

function add_banner_other(location, url, link, index) {
    var html = '<div class="col-md-6">' +
        '<img src="'+url+location+'" alt="Banner other" />' +
        '<input type="hidden" value="'+location+'" name="banners_other['+index+'][location]"/>' +
        '<input type="hidden" value="'+url+'" name="banners_other['+index+'][url]"/>' +
        '<input type="hidden" value="'+link+'" name="banners_other['+index+'][link]"/>' +
        '</div>';

    $('.right-banner-other .wrap_images').append(html);
}