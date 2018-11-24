$(document).ready(function() {
    $(".swiper-slide").click(function (e){
        window.location.href = $(this).find('a').attr('href');
    });

    if (typeof _block != 'undefined' && _block) {
        $('.swiper-slide .'+_block).attr('href', 'javascript:void(0)').addClass('active');

        var tmp = $('.tab.swiper-wrapper').width() - $('.'+_block).parent().offset();
        if (tmp<0) {
            $('.tab.swiper-wrapper').css('transform', 'translate3d('+tmp+'px, 0px, 0px)');
        }
    }

    init_clockpicker('.clockpicker');
});
function get_sku_item(name, product_id, sku) {
    return '<a title="'+name+'">' +
        '<input type="hidden" name="content[]" value="'+product_id+'">'+sku+'</a>';
}
function delete_shock(obj, item) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick && $('ul.quick .shock-item').length==1) {
        malert('Không xóa được. Phải có ít nhất một sản phẩm bán giá sốc.');
        return false;
    }

    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if (is_quick) {
            $('.shock-item.'+item).remove();
            $('#form_update').submit();
        }
    });
}
function update_shock(item, obj) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick) {
        update_shock_quick(item);
        return false;
    }

    var ps = _shocks_products[item];
    if (ps) {
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

        $('.BtnSaveProductShock').html('Cập nhật sản phẩm');
    }
}
function update_shock_quick(item) {
    $('.ShowUpdateShock').slideDown();

    var ps = _shocks_products[item];
    if (ps) {
        $("#q_item").val(item);
        $("#q_sku").val(ps.sku);
        $("#q_from_time").val(ps.from_time);
        $("#q_from_date").val(ps.from_date);
        $("#q_to_time").val(ps.to_time);
        $("#q_to_date").val(ps.to_date);
        $("#q_name").val(ps.name);
        $("#q_product_id").val(ps.product_id);
        $("#q_price").val(ps.price);

        $('.ShowUpdateShock .info-sku-shock').html('Sản phẩm <a class="color">' + ps.name + '</a> - giá <a class="color">' + numeral(ps.price).format() + ' VNĐ;</a>');
    }
}
function add_shock_product(item, product_id, name, price, sku, from_time, from_date, to_time, to_date)
{
    if ($('.shock-item.p'+product_id).length > 0 && $('.shock-item.p'+product_id).attr('data-item')!=item) {
        malert('Sản phẩm ['+sku+'] đã được thêm. Bạn vui lòng kiểm tra lại');
        return false;
    }
    var html = '<li class="shock-item '+item+' p'+product_id+'" data-item="'+item+'">\n' +
        '                                                <div class="col-md-11 no-padding">\n' +
        '<input type="hidden" name="content['+item+'][product_id]" value="'+product_id+'">' +
        '<input type="hidden" name="content['+item+'][from_time]" value="'+from_time+'">' +
        '<input type="hidden" name="content['+item+'][from_date]" value="'+from_date+'">' +
        '<input type="hidden" name="content['+item+'][to_time]" value="'+to_time+'">' +
        '<input type="hidden" name="content['+item+'][to_date]" value="'+to_date+'">' +
        '                                                    <p>Sản phẩm <span class="color">'+name+' - '+sku+';</span> ' +
        'Được bán với giá <span class="color">'+numeral(price).format()+' VNĐ;</span> ' +
        'Bắt đầu từ <span class="color">'+from_time+' ngày '+from_date+'</span> ' +
        'đến <span class="color">'+to_time+' ngày '+to_date+'</span></p>\n' +
        '                                                </div>\n' +
        '                                                <div class="col-md-1 text-right haft-padding-right">\n' +
        '                                                    <a class="tooltip" onclick="delete_shock(this, '+item+')">\n' +
        '                                                        <i class="fa fa-times" aria-hidden="true"></i>\n' +
        '                                                        <span class="tooltiptext">Xóa</span>\n' +
        '                                                    </a>\n' +
        '                                                    <a class="tooltip" onclick="update_shock('+item+', this)">\n' +
        '                                                        <i class="fa fa-pencil" aria-hidden="true" ></i>\n' +
        '                                                        <span class="tooltiptext">Cập nhật</span>\n' +
        '                                                    </a>\n' +
        '                                                </div>\n' +
        '                                            </li>';

    if ($('.shock-item.'+item).length > 0) {
        $('.shock-item.'+item).replaceWith(html);
    } else {
        $('ul.list-shocks').append(html);
    }
}

function delete_brand_hot(obj, item) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick && $('ul.quick .shock-item').length==1) {
        malert('Không xóa được. Phải có ít nhất một tab sản phẩm.');
        return false;
    }

    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if (is_quick) {
            $('.shock-item.'+item).remove();
            $('#form_update').submit();
        }
    });
}
function update_brand(item, obj) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick) {
        update_brand_quick(item);
        return false;
    }
    var ps = _tab_brand[item];
    if (ps) {

        $("#tab_product_name").val(ps.name);
        $("#tab_product_link").val(ps.link);
        $("#p_item").val(item);

        $('.BtnSaveProductShock').html('Cập nhật sản phẩm');
    }

}
function update_brand_quick(item) {

    $('.ShowUpdateShock').slideDown();

    var ps = _tab_brand[item];
    if (ps) {

        $("#q_tab_name").val(ps.name);
        $("#q_tab_link").val(ps.link);
        $("#q_item").val(item);
    }
}
function add_band_tab(item, name, link)
{

    if ($('.shock-item.p'+item).length > 0 && $('.shock-item.p'+item).attr('data-item')!=item) {
        malert('Tab sản phẩm đã được thêm. Bạn vui lòng kiểm tra lại');
        return false;
    }

    var html = '<li class="shock-item '+item+' col-md-12" data-item="'+item+'">\n' +
        '<div class="col-md-11 no-padding">\n' +
        '<input type="hidden" name="content['+item+'][name]" value="'+name+'">' +
        '<input type="hidden" name="content['+item+'][link]" value="'+link+'">' +
        '<p>Tên tab: <span class="color">'+name+';</span> ' +
        'Liên kết: <span class="color">'+link+'</span> </p> </div>' +
        ' <div class="col-md-1 text-right haft-padding-right">\n' +
        '                                                    <a class="tooltip" onclick="delete_brand_hot(this, '+item+')">\n' +
        '                                                        <i class="fa fa-times" aria-hidden="true"></i>\n' +
        '                                                        <span class="tooltiptext">Xóa</span>\n' +
        '                                                    </a>\n' +
        '                                                    <a class="tooltip" onclick="update_brand('+item+', this)">\n' +
        '                                                        <i class="fa fa-pencil" aria-hidden="true" ></i>\n' +
        '                                                        <span class="tooltiptext">Cập nhật</span>\n' +
        '                                                    </a>\n' +
        '                                                </div>\n' +
        '                                            </li>';

    if ($('.shock-item.'+item).length > 0) {
        $('.shock-item.'+item).replaceWith(html);
    } else {
        $('ul.list-shocks').append(html);
    }
}

/**
 *
 */
function get_sku_tab_product(name, product_id, sku) {
    return '<a title="'+name+'">' +
        '<input type="hidden" class="product_id_tmp"  name="products[]" value="'+product_id+'">'+sku+'</a>';
}
function add_tab_product(item, name, ordering, products,sku) {
    var html = '<li class="row tab-item '+item+'">\n' +
        '<input type="hidden" name="tabs['+item+'][name]" value="'+name+'">' +
        '<input type="hidden" name="tabs['+item+'][ordering]" value="'+ordering+'">'+
        '<input type="hidden" value="'+sku+'">';
        $.each(products,function(k,v){
            html += '<input type="hidden" name="tabs['+item+'][products][]" value="'+v+'">';
        });

        html += '    <div class="col-md-11 haft-padding-left">\n' +
        '    Sản phẩm <b class="color">'+name+'</b>, Hiển thị <b class="color">Vị trí '+ordering+'</b>, Sản phẩm <b class="color">'+sku+'</b>\n' +
        '    </div>\n' +
        '    <div class="col-md-1 no-padding text-right">\n' +
        '    <a class="tooltip delete" onclick="delete_tab_product(this, '+item+')">\n' +
        '    <i class="fa fa-times" aria-hidden="true"></i>\n' +
        '    <span class="tooltiptext">Xóa</span>\n' +
        '    </a>\n' +
        '    <a class="tooltip" onclick="update_tab_product('+item+', this)">\n' +
        '    <i class="fa fa-pencil" aria-hidden="true"></i>\n' +
        '    <span class="tooltiptext">Cập nhật</span>\n' +
        '</a>\n' +
        '</div>\n' +
        '</li>';

    if ($('.tab-item.'+item).length > 0) {
        $('.tab-item.'+item).replaceWith(html);
    } else {
        $('.list-tabs').append(html);
    }
}
function delete_tab_product(obj, item) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick && $('ul.quick .tab-item').length==1) {
        malert('Không xóa được. Phải có ít nhất một tab sản phẩm.');
        return false;
    }

    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if (is_quick) {
            $('.tab-item.'+item).remove();
            $('#form_update').submit();
        }
    });
}
function update_tab_product(item, obj) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick) {
        update_tab_product_quick(item);
        return false;
    }

    var ps = _tabs[item];
    if (ps) {
        $("#tab_item").val(item);
        $("#tab_name").val(ps.name);
        $("#tab_ordering").val(ps.ordering);
        $("#tab_sku").val(ps.sku).trigger('change');

        $('.BtnSaveTabProduct').html('Cập nhật Tab');
    }
}
function update_tab_product_quick(item) {
    $(".banner-promotion").slideUp();
    $('.ShowUpdateBanner').slideDown();

    var ps = _tabs[item];
    if (ps) {
        $("#qtab_item").val(item);
        $("#qtab_name").val(ps.name);
        $("#qtab_ordering").val(ps.ordering);
        $("#qtab_sku").val(ps.sku).trigger('change');
    }
    
}
/**
 *
 */

function delete_banner_only(obj, item) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick && $('ul.quick .shock-item').length==1) {
        malert('Không xóa được. Phải có ít nhất một sản phẩm bán giá sốc.');
        return false;
    }

    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if (is_quick) {
            $('.shock-item.'+item).remove();
            $('#form_update').submit();
        }
    });
}
function update_banner_only(item, obj) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick) {
        update_banner_only_quick(item);
        return false;
    }

    var ps = _banners_only[item];
    if (ps) {
        $("#b_item").val(item);
        $("#b_name").val(ps.name);
        $("#b_link").val(ps.link);
        $("#b_url").val(ps.url);
        $("#b_location").val(ps.location);
        if (ps.status=='1') {
            $('#b_status').attr('checked', 'checked');
        } else {
            $('#b_status').removeAttr('checked');
        }

        $('.preview-b-banner').attr("src", ps.url+ps.location);

        $('.BtnSaveBanner').html('Cập nhật banner');
    }
}
function update_banner_only_quick(item) {
    $(".banner-promotion").slideUp();
    $('.ShowUpdateBanner').slideDown();

    var ps = _banners_only[item];
    if (ps) {
        $("#qb_item").val(item);
        $("#qb_name").val(ps.name);
        $("#qb_link").val(ps.link);
        $("#qb_url").val(ps.url);
        $("#qb_location").val(ps.location);
        if (ps.status=='1') {
            $('#qb_status').attr('checked', 'checked');
        } else {
            $('#qb_status').removeAttr('checked');
        }

        $('.preview-qb-banner').attr("src", ps.url+ps.location);
    }
}
function add_banner_only(item, name, url, location, link, status) {
    var html = '<li class="banner-item '+item+' row">\n' +
        '<input type="hidden" name="content['+item+'][name]" value="'+name+'">' +
        '<input type="hidden" name="content['+item+'][link]" value="'+link+'">' +
        '<input type="hidden" name="content['+item+'][url]" value="'+url+'">' +
        '<input type="hidden" name="content['+item+'][location]" value="'+location+'">' +
        '<input type="hidden" name="content['+item+'][status]" value="'+status+'">' +
        '           <div class="col-md-6">\n' +
        '               <div class="col-md-2">\n' +
        '                   <input type="checkbox" name="choose" class="checkbox_check">\n' +
        '               </div>\n' +
        '               <div class="col-md-5 no-padding content">\n' +
        '                   <span>'+name+'</span>\n' +
        '               </div>\n' +
        '               <div class="col-md-5 wrap-banner">\n' +
        '                   <img src="'+url+location+'" alt="Chỉ có tại Thiên Hòa">\n' +
        '               </div>\n' +
        '           </div>\n' +
        '           <div class="col-md-6 no-padding">\n' +
        '               <div class="col-md-5 content no-padding">\n' +
        '                   <span>'+link+'</span>\n' +
        '               </div>\n' +
        '               <div class="col-md-5">\n' +
        '                   <div class="wrapper tooltip">\n' +
        '                       <input type="checkbox" id="status-banner-'+item+'" '+(status==1?'checked':'')+' class="slider-toggle" onclick="return false;"/>\n' +
        '                       <label class="slider-viewport" for="status-banner-'+item+'">\n' +
        '                           <div class="slider">\n' +
        '                               <div class="slider-button">&nbsp;</div>\n' +
        '                               <div class="slider-content left"><span>On</span></div>\n' +
        '                               <div class="slider-content right"><span>Off</span></div>\n' +
        '                           </div>\n' +
        '                       </label>\n' +
        '                       <span class="tooltiptext">Chưa kích hoạt</span>\n' +
        '                   </div>\n' +
        '               </div>\n' +
        '               <div class="col-md-2">\n' +
        '                   <a class="tooltip" onclick="update_banner_only('+item+', this)">\n' +
        '                       <i class="icon-edit-pen active UpdateAction1" aria-hidden="true"></i>\n' +
        '                       <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>\n' +
        '                       <span class="tooltiptext">Cập nhật</span>\n' +
        '                   </a>\n' +
        '                   <a class="tooltip" onclick="delete_banner_only(this, '+item+')">\n' +
        '                       <i class="icon-delete active DeleteAction" aria-hidden="true"></i>\n' +
        '                       <i class="icon-delete-hover DeleteHover" title="Xóa">&nbsp</i>\n' +
        '                       <span class="tooltiptext">Xóa</span>\n' +
        '                   </a>\n' +
        '               </div>\n' +
        '           </div>\n' +
        '       </li>';

    if ($('.banner-item.'+item).length > 0) {
        $('.banner-item.'+item).replaceWith(html);
    } else {
        $('.list-banners').append(html);
    }
}

function get_sku_item_brand_popular(name, product_id, sku) {
    return '<a title="'+name+'">' +
        '<input type="hidden" class="product_ids" name="product_ids[]" data-name="'+name+'" data-sku="'+sku+'" value="'+product_id+'">'+sku+'</a>';
}

function add_band_popular(item, product_ids)
{

    var product_name = [];
    var html = '<li class="shock-item '+item+' col-md-12" data-item="'+item+'">\n' +
        '<div class="col-md-11 no-padding">\n' +
        '<input type="hidden" name="content['+item+'][name]" value="'+_tab_brand[item].name+'">' +
        '<input type="hidden" name="content['+item+'][link]" value="'+_tab_brand[item].link+'">' +
        '<input type="hidden" name="content['+item+'][location]" value="'+_tab_brand[item].location+'">' +
        '<input type="hidden" name="content['+item+'][status]" value="'+_tab_brand[item].status+'">' +
        '<input type="hidden" name="content['+item+'][ordering]" value="'+_tab_brand[item].ordering+'">';

    $.each(product_ids,function(k,v){
        html += '<input type="hidden" name="products['+item+'][]" value="'+v+'">';
        product_name.push(_list_product_name[v]);
    });
    product_name.join(',');


    html +='<p>Tên tab: <span class="color">'+_tab_brand[item].name+';</span> ' +
        'Liên kết: <span class="color">'+product_name+'</span> </p> </div>' +
        ' <div class="col-md-1 text-right haft-padding-right">\n' +
        '                                                    <a class="tooltip" onclick="delete_brand_popular(this, '+item+')">\n' +
        '                                                        <i class="fa fa-times" aria-hidden="true"></i>\n' +
        '                                                        <span class="tooltiptext">Xóa</span>\n' +
        '                                                    </a>\n' +
        '                                                    <a class="tooltip" onclick="update_brand_popular('+item+', this)">\n' +
        '                                                        <i class="fa fa-pencil" aria-hidden="true" ></i>\n' +
        '                                                        <span class="tooltiptext">Cập nhật</span>\n' +
        '                                                    </a>\n' +
        '                                                </div>\n' +
        '                                            </li>';

    if ($('.shock-item.'+item).length > 0) {
        $('.shock-item.'+item).replaceWith(html);
    } else {
        $('ul.list-shocks').append(html);
    }
}

function update_brand_popular(item){

    $('#b_item').val(item);
    $('#b_name').val(_tab_brand[item].name);
    $('#b_ordering').val(_tab_brand[item].ordering);
    $('#b_link').val(_tab_brand[item].link);
    if(_tab_brand[item].status == 1){
        $('#b_status').prop('checked',true);
    }else{
        $('#b_status').removeAttr( "checked" );
    }
    $('.preview-b-banner').attr("src", _tab_brand[item].url+_tab_brand[item].location);
    $('#b_location').val(_tab_brand[item].location);
    var list_sku = [];
    $.each(_tab_brand[item].product_ids,function(k,v){
        list_sku.push(_list_product_sku[v]);
    });
    list_sku.join(',');
    $('#fp_sku').val(list_sku).text(list_sku).trigger('change'); 
    $('.BtnSaveProductShock').html('<i class="fa fa-plus-circle" aria-hidden="true"></i> Cập nhật logo & Sản phẩm yêu thích');   
}
function delete_brand_popular(obj, item) {
    var is_quick = $(obj).closest('ul').hasClass('quick');
    if (is_quick && $('ul.quick .logo-item').length==1) {
        malert('Không xóa được. Phải có ít nhất một logo và sản phẩm yêu thích.');
        return false;
    }

    confirm_delete(null, function () {
        $(obj).closest('li').remove();
        if (is_quick) {
            $('.shock-item.'+item).remove();
            $('#form_update').submit();
        }
    });
}
$(function(){
    $('.btn_update_quick').on('click',function(){
        
        var item = $(this).data('id');

        $('#q_item').val(item);
        $('#q_name').val(_tab_brand[item].name);
        $('#q_ordering').val(_tab_brand[item].ordering);
        $('#q_link').val(_tab_brand[item].link);
        if(_tab_brand[item].status == 1){
            $('#q_status').prop('checked',true);
        }else{
            $('#q_status').removeAttr( "checked" );
        }
        $('.preview-qb-banner').attr("src", _tab_brand[item].url+_tab_brand[item].location);
        $('#q_location').val(_tab_brand[item].location);
        var list_sku = [];
        $.each(_tab_brand[item].product_ids,function(k,v){
            list_sku.push(_list_product_sku[v]);
        });
        list_sku.join(',');
        $('#qp_sku').val(list_sku).text(list_sku).trigger('change');
        
    })
})