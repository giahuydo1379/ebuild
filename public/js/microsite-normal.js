$(document).ready(function() {
    init_datepicker('.datepicker');
    $('textarea#description').ckeditor();

    $('.btn-start').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn kích hoạt trang này không?', 'Xác nhận kích hoạt trang', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/normal/update-status', {id: id, status: 1}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });
    $('.btn-stop').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn dừng trang này không?', 'Xác nhận dừng trang', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/normal/update-status', {id: id, status: 0}, function () {
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
                class: $("#fp_class").val(),
                ordering: $("#fp_ordering").val(),
                sort: $("#fp_sort").val(),
                product_ids: $('.list-products .color').html(),
                sku: $("#fp_sku").val()
            };

            add_giff_product(item, $("#fp_name").val(), $("#fp_class").val(), $("#fp_ordering").val(), $("#fp_sort").val(), $('.list-products .color').html());
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
        $("#fp_class").val("");
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
        $('.display-product .list-level').html('');
        $('.right-banner-other .wrap_images').html('');
        $('.list-radio input[value=normal]').trigger('click');
        $('.no-banner.add-giff-product').show();

        $('#image_url').val('');
        $('#image_location').val('');

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

        $('.preview-banner').attr('src', item['image_url']+item['image_location']);
        $('#image_url').val(item.image_url);
        $('#image_location').val(item.image_location);

        $('.link.add-giff-product').show();
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
                "image_location": "required",
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
                "image_location": "Chọn hình thể hiện chương trình khuyến mãi",
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
});

$(document).on('click', '.delete-banner-so', function () {
    $(this).parent().remove();
});

function check_submit_sale_off() {
    $('')
}
function get_sku_item(now, name, product_id, sku) {
    return '<a title="'+name+'">' +
        '<input type="hidden" name="floors_products['+now+'][product_ids][]" value="'+product_id+'">'+sku+'</a>';
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
        $("#fp_class").val(fp.class);
        $("#fp_ordering").val(fp.ordering);
        $("#fp_sort").val(fp.sort);
        $("#fp_sku").val(fp.sku);
        $('.list-products .color').html(fp.product_ids);
        $('.list-products').show();
    }
}
function add_giff_product(item, name, class_name, ordering, sort, products) {
    var html = '<li class="giff-item '+item+'">' +
        '<input type="hidden" name="floors_products['+item+'][class]" value="'+class_name+'">' +
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