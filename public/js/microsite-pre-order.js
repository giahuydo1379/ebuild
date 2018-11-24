$(document).ready(function() {
    init_datepicker('.datepicker');
    $('textarea#description').ckeditor();
    $('textarea#technical_specifications').ckeditor();

    $(document).on('click', '.delete-banner-so', function () {
        $(this).parent().remove();
    });

    $('.btn-start').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn kích hoạt trang này không?', 'Xác nhận kích hoạt', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/pre-order/update-status', {id: id, status: 1}, function () {
                ajax_loading(false);
                location.reload();
            });
        });
    });
    $('.btn-stop').on('click', function () {
        var id = $(this).attr('data-id');
        malert('Bạn có muốn dừng trang này không?', 'Xác nhận dừng', null, function () {
            ajax_loading(true);

            $.post(_base_url+'/microsite/pre-order/update-status', {id: id, status: 0}, function () {
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
        frm_reset();

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
console.log(item);
        $('.add-action').click();

        var obj_form = '#form_update';

        // floors_products
        _products = [];
        _banner_content = [];


        if (item.products) {
            var product_ids = [];
            $('.display-product').show();

            $.each(item.products, function (index, other) {
                if (other.product_id) {
                    product_ids.push(other.product_id);
                }
            });
            

            $.post(_base_url + '/promotion/get-list-products-by-ids', {
                ids: product_ids,
            }, function (data) {
                var products = data.products;
                var product_ids = '', sku = '';
                
                if (item.products) {
                    $.each(item.products, function (index, other) {
                        if (other) {
                            var sp = products[other.product_id];
                            if(sp){
                                    _products[index] = {
                                    name: sp.name,
                                    product_id: sp.product_id,
                                    price: sp.price,
                                    sku: sp.sku,
                                };
                                add_shock_product(index, sp.product_id, sp.name, sp.price, sp.sku);
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
        $(obj_form + ' #technical_specifications').val(item.technical_specifications);
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
        if (item.banner_content) {
            $.each(item.banner_content, function (index, other) {
                _banner_content[index] ={
                    name:other.name,
                    image_location:other.image_location,
                    image_url:other.url,
                }
                add_banner_content(index,other.name,other.image_location,other.url);
            });
        }

        if (item.logo) {
            var tmp = item.logo;
            $('.preview-logo').attr('src', tmp['url']+tmp['location']);
            $('#logo_url').val(tmp['url']);
            $('#logo').val(tmp['location']);
        }
    });

    /**
     * start shock product
     */
    $('.add-banner-content').on('click', function () {
        $(this).hide();

        $('#is_next_banner').val(0);
        $("#b_item").val($.now());
        $("#banner_content_name").val("");
        $("#banner_content_image_location").val("").trigger('change');
        $('.LoadProduct img.preview-banner-content').attr('src','');
        $('.LoadProduct').show();
        $('.LoadProduct .no-banner').hide();
        $('.LoadProduct .CreateLoad').slideDown();
    });

    $('.LoadProduct .cancel-banner-content').unbind('click');
    $('.LoadProduct .cancel-banner-content').on('click', function (e) {
        e.preventDefault();

        $('.add-banner-content').hide();

        if ($('.banner_content_item').length > 0) {
            $('.LoadProduct').hide();
            $('.banner_content').show();
            $('.add-banner-content').show();
        } else {
            $('.LoadProduct').show();
            $('.LoadProduct .no-banner').show();
            $('.LoadProduct .CreateLoad').hide();
        }


        return false;
    });
    $('#p_sku').on('change', function () {
        $('#p_product_id').val('');
        $('.info-sku-product').html('');
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
                $('.info-sku-product').html('Sản phẩm <a class="color">' + tmp.name + '</a> - giá <a class="color">' + numeral(tmp.price).format() + ' VNĐ;</a>');
            } else {
                $('.info-sku-product').html('<a class="color">Không tìm thấy sản phẩm</a>');
            }
        });
    });
    $('.BtnSaveProduct').on('click', function () {

        $("#p_sku" ).rules( "add", {
            required: true,
            messages: {
                required: "Điền sku sản phẩm",
            }
        });
        $("#p_product_id" ).rules( "add", {
            required: true,
            messages: {
                required: "Không tìm thấy sản phẩm với sku trên",
            }
        });

        var flag = $("#p_sku").valid() && $("#p_product_id").valid();

        if ( flag ) {
            var item = $('#p_item').val();
            if(!item || item == 0)
                item = $.now();

            console.log(item);
            _products[item] = {
                sku: $("#p_sku").val(),
                product_id: $("#p_product_id").val(),
                price: $("#p_price").val(),
                name: $("#p_name").val()
            };

            add_shock_product(item, $("#p_product_id").val(), $("#p_name").val(), $("#p_price").val(), $("#p_sku").val());
        }

        $("#p_sku").val('');
        $("#p_product_id").val('');
        $("#p_name").val('');
        $("#p_price").val('');
        $('.info-sku-product').html('');

        $("#p_sku").rules( "remove" );
        $("#p_product_id").rules( "remove");

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

    $('.btnSaveBannerContent').on('click', function () {

        $("#banner_content_image_location" ).rules( "add", {
            required: true,
            messages: {
                required: "Chọn banner",
            }
        });
        $("#banner_content_name").rules( "add", {
            required: true,
            messages: {
                required: "Nhập tên tab nội dung",
            }
        });

        var flag = $("#banner_content_image_location").valid() && $("#banner_content_name").valid();

        if ( flag ) {
            var item = $('#b_item').val();
            if(!item)
                item = $.now();

            _banner_content[item] = {
                name: $("#banner_content_name").val(),
                image_location: $("#banner_content_image_location").val(),
                image_url: $("#banner_content_image_url").val(),
            };

            add_banner_content(item, $("#banner_content_name").val(), $("#banner_content_image_location").val(),$("#banner_content_image_url").val());

            if ($('#is_next_banner').val()=='1') {
                $('.add-banner-content').click();
            } else {
                //$('.no-banner.add-shock-product').hide();
                $('.CreateLoad .cancel-banner-content').click();
            }
        }

        $("#banner_content_name").rules( "remove" );
        $("#banner_content_image_location").rules( "remove");

        return false;
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
                'logo[location]':'required'
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
                "description": "Nhập nội dung giới thiệu chương trình",
                'logo[location]': "Chọn logo",
            },
            submitHandler: function(form) {
                if ($('.shock-item').length == 0) {
                    malert('Vui lòng thêm sản phẩm', null, function () {
                        $('#p_sku').focus();
                    });
                    return false;
                }

                if ($('.banner_content_item ').length == 0) {
                    malert('Vui lòng thêm tab nội dung', null, function () {                        
                        $('.add-banner-content').click();
                        $('#banner_content_name').focus();
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
        console.log(data);
        var url = $(form).attr('action');
        $.post(url, data).done(function(data){
            if(data.rs == 1)
            {
                alert_success(data.msg, function () {
                    if ($('#is_next').val()=='1') {
                        $('.add-action').click();

                        $('#is_reload').val(1);
                        frm_reset();
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

function delete_shock(obj) {
    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if ($('.shock-item').length == 0)
           $('.display-product').hide();
    });
}
function update_shock(item) {    
    var ps = _products[item];

    if (ps) {
        $("#p_item").val(item);
        $("#p_sku").val(ps.sku);
        $("#p_product_id").val(ps.product_id);
        $("#p_name").val(ps.name);
        $("#p_price").val(ps.price);

        $('.info-sku-product').html('Sản phẩm <a class="color">' + ps.name + '</a> - giá <a class="color">' + numeral(ps.price).format() + ' VNĐ;</a>');
    }
}

function add_shock_product(item, product_id, name, price, sku)
{
    var html = '<li class="shock-item '+item+'">\n' +
        '                                                <div class="col-md-11 no-padding">\n' +
        '<input type="hidden" name="products['+item+'][product_id]" value="'+product_id+'">' +
        '                                                    <p>Sản phẩm <span class="color">'+name+' - '+sku+';</span> ' +
        'Được bán với giá <span class="color">'+price+' VNĐ;</span> ' +'</p>\n' +
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

    if ($('.shock-item').length > 0)
        $('.display-product').show();
}

function add_banner_content(item,name,image_location,image_url)
{

    var html = '<tr class="banner_content_item banner_content_item_'+item+'"> ' +
        '<td> <input type="hidden" name="banner_content['+item+'][name]" value="'+name+'">'+name+'</td>' +
        '<td> <input type="hidden" name="banner_content['+item+'][image_location]" value="'+image_location+'"><input type="hidden" name="banner_content['+item+'][image_url]" value="'+image_url+'"><img src="'+image_url+image_location+'"></td>';
    html += '<td><a class="tooltip" onclick="delete_banner_content(this)">';
    html += '    <i class="fa fa-times" aria-hidden="true"></i>';
    html += '        <span class="tooltiptext">Xóa</span>';
    html += '        </a>';
    html += '        <a class="tooltip" onclick="update_banner_content('+item+')">';
    html += '        <i class="fa fa-pencil" aria-hidden="true"></i>';
    html += '        <span class="tooltiptext">Cập nhật</span>';
    html += '    </a></td></tr>';

    if ($('.banner_content_item_'+item).length > 0) {
        $('.banner_content_item_'+item).replaceWith(html);
    } else {
        $('.banner_content table').append(html);
    }
    $('.banner_content').show();
}

function delete_banner_content(obj) {
    confirm_delete(null, function () {
        $(obj).closest('tr').remove();
    });
}

function update_banner_content(item) {

    var bc = _banner_content[item];

    if (bc) {
        $('.add-banner-content').trigger('click');
        $('#is_next_banner').val(0);
        $("#b_item").val(item);
        $("#banner_content_name").val(bc.name);
        $("#banner_content_image_location").val(bc.image_location);
        $("#banner_content_image_url").val(bc.image_url);
        $('.LoadProduct img.preview-banner-content').attr('src',bc.image_location);
    }
}

function frm_reset(){
    $('#is_next').val(0);
    $('#form_update')[0].reset();
    CKEDITOR.instances.description.setData( '' );
    CKEDITOR.instances.technical_specifications.setData( '' );

    $('.preview-banner-desktop').attr('src', '');
    $('#image_url_desktop').val('');
    $('#image_location_desktop').val('');
    $('.preview-banner-tablet').attr('src', '');
    $('#image_url_tablet').val('');
    $('#image_location_tablet').val('');
    $('.preview-banner-mobile').attr('src', '');
    $('#image_url_mobile').val('');
    $('#image_location_mobile').val('');

    $('.preview-logo').attr('src', '');
    $('#logo_url').val('');
    $('#logo').val('');

    $('#banner_content_image_location').val('');
    $('#banner_content_image_url').val('');
    $('.preview-banner-content').attr('src', '');

    $('.banner_content_item').remove();
    $('.banner_content').hide();
    $('.LoadProduct').show();

    $('.display-product .list-shocks').html('');
     $('.display-product').hide();
    $('.no-banner.add-shock-product').show();
}
