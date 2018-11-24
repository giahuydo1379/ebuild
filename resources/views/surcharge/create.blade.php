@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title"><?=$title?></h2>
            </div>
            <div class="view_detail_brand create_brand" >
                <div class="header-panel">
                    <h3 class="title ">Thông tin phụ phí</h3>
                    <div class="wrap_link">
                        <a href="<?=route('surcharge.index')?>"><i class="fa fa-reply" aria-hidden="true"></i><span>Quay lại</span></a>
                    </div>
                </div>
                <form action="<?=route('surcharge.store')?>" id="frm_update" class="frm_update" method="post">

                    <div class="row">
                        <div class="col-md-3">
                            <label>Tên phụ phí</label>
                            <input type="text" name="name" class="form-control name" placeholder="Nhập tên phụ phí" value="<?=@$object['name']?>">
                        </div>
                        <div class="col-md-3">
                            <label>Đơn vị</label>
                            <div class="wrap_select">
                                <select class="form-control select2" name="unit_id">
                                    <option value="">chọn đơn vị</option>
                                    @if(!empty($list_units))
                                        @foreach($list_units as $key => $item)
                                            <option value="<?=$item['id']?>"<?php if(!empty($object['unit_id']) && ($item['id'] == $object['unit_id'])) echo 'selected'; ?> ><?=$item['name']?></option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Danh mục sản phẩm</label>
                            <select class="form-control select2" name="category_id[]" multiple id="category_id" data-placeholder="Chọn danh mục">
                                <option value=""></option>
                                @foreach($list_categories as $item)
                                    <option <?=@(in_array($item['category_id'], $category_ids)?'selected':'') ?> value="<?=$item['category_id']?>" ><?=$item['category']?></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="wrap_select">
                                <ul>
                                    <li class="row">
                                        <label>Nhà cung cấp</label>
                                        {!! Form::select("supplier_id", ['' => ''] + $suppliers, @$object['supplier_id'],
                                                    ['class' => 'form-control select2', "id" => "supplier_id", "data-placeholder"=>"Chọn nhà cung cấp"]) !!}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{--add địa điểm giao hàng--}}
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12" >
                            <p style="font-weight: bold;">Chi tiết phụ phí</p> 
                            <p><span class="color"> Lưu ý: </span><span>Nhập trường "Đến" bằng 0 để thể hiện giá trị lớn đến vô cùng</span></p>
                            <button id="add_location" class="btn-save" style="float:none;">Thêm</button>
                            <div id="show-surcharge-list">
                                @if(!empty($surcharge_detail))
                                @foreach($surcharge_detail as $key => $item)
                                <div class="row item_surcharge" >
                                 <div class="col-md-3">
                                    <label>Từ</label>
                                     <input type="text" name="surcharge[<?=$key?>][from]" class="fm-number from" placeholder="Nhập từ" value="<?=@$item['from']?>">
                                 </div>
                                 <div class="col-md-3">
                                    <label>Đến</label>
                                    <input type="text" name="surcharge[<?=$key?>][to]" class="fm-number to" placeholder="Nhập đến" value="<?=@$item['to']?>">
                                 </div>
                                 <div class="col-md-3">
                                    <label>Số tiền</label>
                                     <input type="text" name="surcharge[<?=$key?>][price]" class="fm-number" placeholder="Giá" value="<?=@$item['price']?>">
                                 </div>
                                 <div class="col-md-3" style="cursor:pointer;padding:10px;" >
                                    <a href="javascript:void(0)" onclick="$(this).closest('.row').remove()" style="position: absolute;top: 33px;"><span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                 </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="wrap_btn">
                            <input type="hidden" name="id" value="<?=@$object['id']?>">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <a href="javascript:void(0)" class="cancel">Hủy bỏ</a>
                            <button type="submit" class="btn-save">Lưu</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(function(){
            init_select2('.select2');
            init_fm_number('.fm-number');
            $('.cancel').on('click',function(){
                $(this).closest('form')[0].reset();
                init_fm_number('.fm-number');
            });

            $('.frm_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    'unit_id': 'required',
                    'supplier_id': 'required',
                    'category_id': 'required',
                },
                messages: {
                    name: "Nhập tên phụ phí",
                    'unit_id': 'Chọn đơn vị',
                    'supplier_id': 'Chọn nhà cung cấp',
                    'category_id': 'Chọn danh mục sản phẩm',
                },
                submitHandler: function(form) {
                    // do other things for a valid form
                    $('.fm-number').each(function( index ) {
                        $(this).val( numeral($(this).val()).value() );
                    });
                    var submit = true;
                    if($('.item_surcharge').length > 0){
                        $('.item_surcharge').each(function(){
                            var from = $(this).find('.from').val();
                            var to   = $(this).find('.to').val();
                            
                            if(from == 0 && to == 0)
                                submit = false;
                        });
                    }else{
                        malert('Thêm chi tiết phụ phí');
                        return false;
                    }
                    if(!submit){
                        malert('Trường "Từ" và "Đến" không thể đồng thời bằng 0');
                        return false
                    }
                    
                    var data = $(form).serializeArray();

                    var url = $(form).attr('action');
                    ajax_loading(true);
                    $.post(url, data).done(function(data){
                        ajax_loading(false);
                        if(data.rs == 1)
                        {
                            alert_success(data.msg, function () {
                                location.href = '<?=route("surcharge.index")?>';
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

            $(document).on('click','#add_location',function (e) {
                e.preventDefault();
                set_html_add_address_place();
            });

            function set_html_add_address_place() {
                var item = $.now();
                var id = "#item_" + item;
                html = '<div class="row item_surcharge" id="item_' + item + '" >';
                html += '<div class="col-md-3">';
                html += '<label>Từ</label>' ;
                html += '<input type="text" name="surcharge[' + id + '][from]" class="fm-number from" placeholder="Nhập từ" value="">' ;
                html += '</div>' ;
                html += '<div class="col-md-3">';
                html += '<label>Đến</label>' ;
                html += '<input type="text" name="surcharge[' + id + '][to]" class="fm-number to" placeholder="Nhập đến" value="">' ;
                html += '</div>' ;
                html += '<div class="col-md-3">';
                html += '<label>Số tiền</label>' ;
                html += '<input type="text" name="surcharge[' + id + '][price]" class="fm-number" placeholder="Giá" value="">' ;
                html += '</div>' ;
                html += '<div class="col-md-3" style="cursor:pointer;padding:10px;"><a href="javascript:void(0)" style="position: absolute;top: 33px;" onclick="$( \'' + id + '\').remove()"><span class="glyphicon glyphicon-remove"></span></a></div>'
                html += '</div>' ;

                $('#show-surcharge-list').prepend(html);
                init_fm_number('.fm-number');
                return html;
            }
        })
    </script>
@endsection