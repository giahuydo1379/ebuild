@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view view_product create_product">
            <div class="header">
                @if(!empty($data))
                <h2 class="title">Chi tiết dịch vụ <span>/ < <?=$data['product']?> ></span></h2>
                @else
                <h2 class="title">Tạo mới dịch vụ</h2>
                @endif
                <a href="<?=route('product-service.index')?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
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
                    <form class="frm_product" action="<?=route('product-service.store')?>" method="post">
                    <div class="part_information">
                        <div class="part_information">
                            <h3 class="title_supplier">Thông tin dịch vụ</h3>
                            <div class="col-md-6">
                                <ul>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Tên dịch vụ</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="product" value="<?=@$data['product']?>" placeholder="Nhập tên dịch vụ">
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Danh mục chính:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select class="form-control select2" name="category_id[]" multiple id="category_id" data-placeholder="Chọn danh mục">
                                                <option value=""></option>
                                                @foreach($list_categories as $item)
                                                    <option <?=@(in_array($item['category_id'], $data['category_id'])?'selected':'') ?> value="<?=$item['category_id']?>" ><?=$item['category']?></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="row">
                                        <div class="col-md-4">
                                            <label>Trạng thái</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="wrapper">
                                                <input type="hidden" name="status" value="D" />
                                                <input type="checkbox" id="status" name="status" value="A" class="slider-toggle" <?=@($data['status']=='A'?'checked':'')?> />
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
                                            <label>Giá bán ({{config('app.name')}}):</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="price" name="price" class="fm-number"
                                                   placeholder="Nhập giá bán trên {{config('app.name')}}" value="<?=@$data['price']?>">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div style="clear:both;"></div>


                    <input type="hidden" name="product_id" value="<?=@$data['product_id']?>">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button type="submit" class="btn_edit col-md-2 col-md-offset-10" name="form" value="product">Lưu dịch vụ</button>
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
        .view_product .content_product .part_create .col-md-9 #list span img {
            width: 140px;
        }
    </style>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>

    <script type="text/javascript">
        var product_id = '';
        @if(isset($product_id))
            product_id = <?=$product_id?>;
        @endif

        $(function(){
            init_select2('.select2');
            init_fm_number('.fm-number');

            $('.fm-number').each(function( index ) {
                $(this).val( numeral($(this).val()).format() );
            });

            $('.cancel').on('click',function(){
                $(this).closest('form')[0].reset();
            });

            $('.frm_product').validate({
                ignore: ".ignore",
                rules: {
                    product: "required",
                    "category_id[]": "required",
                    price: "required",
                },
                messages: {
                    image_location: "Chọn hình sản phẩm",
                    product: "Nhập tên dịch vụ",
                    product_code: "Nhập SKU sản phẩm",
                    "category_id[]": "Chọn danh mục",
                    amount: "Nhập số lượng sản phẩm",
                    price: "Nhập giá bán",
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
                                location.href = '<?=route('product-service.index')?>';
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
        })
    </script>
@endsection