@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view view_product create_product">
            <div class="header">
                @if(!empty($data))
                <h2 class="title">Chi tiết <?=$title?> </h2>
                @else
                <h2 class="title">Tạo mới <?=$title?></h2>
                @endif
                <a href="<?=route($controllerName.'.index')?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
            </div>
            @if(!empty($data))
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#1" data-toggle="tab">Thông tin chung</a>
                </li>
            </ul>
            @endif
            <div class="body tab-content">
                <div class="content_product tab-pane active" id="1">
                    <form class="frm_product" action="<?=route($controllerName.'.store')?>" method="post">
                    <div class="part_information">
                        <div class="part_information">
                            <h3 class="title_supplier">Thông tin <?=$title?></h3>
                            <div class="col-md-6">
                                <ul>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Loại máy lạnh</label>
                                        </div>
                                        <div class="col-md-8">
                                            {!! Form::select("freezer_id", ['' => ''] + $freezers, @$data['freezer_id'],
                                        ['id' => 'freezer_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo loại máy lạnh']) !!}
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Công suất</label>
                                        </div>
                                        <div class="col-md-8">
                                            {!! Form::select("freezer_capacity_id", ['' => ''] + $freezer_capacity, @$data['freezer_capacity_id'],
                                        ['id' => 'freezer_capacity_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo danh mục']) !!}
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Trạng thái</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="wrapper">
                                                <input type="hidden" name="status" value="0"/>
                                                <input type="checkbox" id="status" name="status" value="1" class="slider-toggle" <?=@($data['status']=='1'?'checked':'')?> />
                                                <label class="slider-viewport" for="status">
                                                    <div class="slider">
                                                        <div class="slider-button">&nbsp;</div>
                                                        <div class="slider-content left"><span>On</span></div>
                                                        <div class="slider-content right"><span>Off</span></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Số lượng máy:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="freezer_number" name="freezer_number" class="fm-number" placeholder="Nhập số lượng máy" value="<?=@$data['freezer_number']?>">
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Giá:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="price" name="price" class="fm-number" placeholder="Nhập giá" value="<?=@$data['price']?>">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="part_pro_vanhanh" style="display: none;">
                        <h3 class="title_supplier">Thuộc tính vận hành</h3>
                        <div class="content">
                            <b>Nội dung</b>
                            <div class="wrap-pro row">
                                <div class="col-md-3">
                                    <input type="hidden" name="has_gift" value="0">
                                    <input class="custom" type="checkbox" name="has_gift" id="has_gift" value="1" <?=@$data['has_gift']?'checked':''?>>
                                    <label for="has_gift">Hiển thị icon quà tặng</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?=@$data['id']?>">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button type="submit" class="btn_edit col-md-2 col-md-offset-10" name="form" value="product">Lưu <?=$title?></button>
                    <!-- end -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    <style>
        label.error{
            border: none !important;
        }
    </style>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>

    <script type="text/javascript">
        var id = '';
        @if(isset($id))
            id = <?=$id?>;
        @endif
        $(function(){
            init_datepicker('.datepicker');
            init_select2('.select2');
            init_fm_number('.fm-number');
            $('.fm-number').each(function( index ) {
                $(this).val( numeral($(this).val()).format() );
            });

            $('textarea.ckeditor').ckeditor();

            $('#product_code').on('change', function () {
                $('.input_information .code').html($(this).val());
            });

            $('.multiselect').multiselect();

            $('.cancel').on('click',function(){
                $(this).closest('form')[0].reset();
            });

            $(document).on('click','#list span',function(){
               $(this).remove();
            });

            $('.frm_product').validate({
                ignore: ".ignore",
                rules: {
                    freezer_id: "required",
                    freezer_capacity_id: "required",
                    freezer_number: "required",
                    price: "required",
                },
                messages: {
                    freezer_id: "Chọn loại máy lạnh",
                    freezer_capacity_id: "Chọn công suất",
                    freezer_number: "Nhập số lượng",
                    price: "Nhập giá",
                },
                submitHandler: function(form) {
                    $('.fm-number').each(function( index ) {
                        $(this).val( numeral($(this).val()).value() );
                    });

                    //form.submit(); return true;
                    // do other things for a valid form
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = '<?=route($controllerName.'.index')?>';
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

                    return false;
                }
            });

            $('.frm_seo').validate({
                ignore: ".ignore",
                rules: {
                    product: "required",
                },
                messages: {
                    product: "Nhập tên <?=$title?>",

                },
                submitHandler: function(form) {
                    // do other things for a valid form
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = data.redirect;
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

                    return false;
                }
            });

//            if(product_id){
//                $.post('/products/get-list-products-sold',{product_id:product_id},function(res){
//                    ajax_loading(false);
//                    if(res.result){
//                        $.each(res.result,function(k,v){
//                            $('.list_product').append(get_html_add_product(v));
//                        })
//                    }
//                })
//            }


            var _interval_search_product = null;
            var searchRequest = null;
            $(document).on('keyup','.search_product',function(){
                var kw = $(this).val();
                if(kw.length < 3) return false;

                if (_interval_search_product) clearInterval(_interval_search_product);

                _interval_search_product = setInterval(function() {
                    clearInterval(_interval_search_product);

                    var url = '/products/search-product';
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
            $('.btn_add_product_sold').on('click',function(){
                $('input.search_product').focus();
            });
            $(document).on('click','.select_product',function(){
                $(".search_result_content").hide();
                var pid     = $(this).data('id');                
                ajax_loading(true);
                $.post('/products/store-products-sold', {product_sold_id: pid, product_id: product_id}, function (res) {
                    ajax_loading(false);

                    if (res.rs) {
                        alert_success(res.msg, function () {
                            var product = res.product;

                            if($('.item_product_'+pid).length > 0);
                            $('.list_product').append(get_html_add_product(product));
                            $('.search_product').val('');
                        });
                        
                    } else {
                        malert(res.msg);
                    }
                },'json');
            });
            $(document).on('click','.delete_action',function () {
                var product_sold_id = $(this).data('id');
                confirm_delete("Bạn có muốn xóa <?=$title?> này không?", function () {
                    $.post('/products/delete-products-sold', {product_sold_id: product_sold_id, product_id: product_id}, function(res){
                        if(res.rs){
                            alert_success(res.msg, function () {
                                $('.item_product_'+product_sold_id).remove();
                            });    
                        }else{
                            malert(res.msg);
                        }
                        
                    });
                });
            });
            

            function get_html_add_product(data){
                html = '<li class="item_product_'+data.product_id+'">';
                html += '    <div class="wrap-img item_product " data-id="'+data.product_id+'">';
                html += '        <img src="'+data.image+'" alt="">';
                html += '    </div>';
                html += '    <div class="information">';
                html += '        <p>'+data.name+'</p>';
                html += '        <p class="price">';
                html += '            <span class="sale-price">'+numeral(data.price).format()+'đ </span>';
                html += '            <span class="real-price">'+numeral(data.list_price).format()+'đ</span>';
                html += '        </p>';
                html += '    </div>';
                html += '    <a href="#" class="tooltip pull-right delete_action" data-id="'+data.product_id+'" >';
                html += '        <i class="icon-delete" title="Xóa">&nbsp;</i>';
                html += '        <span class="tooltiptext">Xoá</span>';
                html += '    </a>';
                html += '</li>';

                return html;
            }

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
        })
    </script>
@endsection