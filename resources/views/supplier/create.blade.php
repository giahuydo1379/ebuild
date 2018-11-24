@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title"><?=$title?></h2>
            </div>
            <div class="view_detail_brand create_brand" >
                <div class="header-panel">
                    <h3 class="title ">Thông tin chi tiết nhà cung cấp</h3>
                    <div class="wrap_link">
                        <a href="<?=route('supplier.index')?>"><i class="fa fa-reply" aria-hidden="true"></i><span>Quay lại</span></a>
                    </div>
                </div>
                <form action="<?=route('supplier.store')?>" id="frm_update" class="frm_update" method="post">
                <div class="col-md-6">
                    <div class="box box1_edit">
                        <div class="top">
                            <div class="col-md-2">
                                <div class="image-upload">
                                    <label for="file-input">

                                        <img class="preview-banner" src="<?=!empty($object['image_location'])?$object['image_url'].$object['image_location']:'/html/assets/images/banner-promotion.png'?>" alt="{{config('app.name')}} promotion" />

                                        <img class="btn-loadfile browse-image" id="" src="/html/assets/images/icon_choose_img.png" data-target="image_location" alt="your image"  />
                                    </label>
                                    <input type="hidden" value="" name="image_location" id="image_location" data-preview=".frm_update .preview-banner">

                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Logo</h4>
                            </div>
                        </div>
                        <div class="bottom">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tên nhà cung cấp:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="name" value="<?=@$object['name']?>" placeholder="Nhập tên nhà cung cấp">
                                    <label id="name-error" class="error" for="name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Số điện thoại:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="phone" value="<?=@$object['phone']?>" placeholder="Nhập tên nhà cung cấp">
                                    <label id="phone-error" class="error" for="phone" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Email:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="email" id="email" name="email" class="form-control" value="<?=@$object['email']?>" placeholder="Nhập email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Chọn nhà kho:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control select2 warehouse_id" name="warehouse_id" id=""/>
                                    <button type="button" class="btn" data-toggle="modal" data-target="#myModal" style="margin-top: 5px;">
                                        <span class="glyphicon glyphicon-plus"></span>Thêm nhà kho
                                    </button>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Mã số thuế:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="tax_code" value="<?=@$object['tax_code']?>" placeholder="Nhập tên thương hiệu">
                                    <label id="tax_code-error" class="error" for="tax_code" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tỉnh/ Thành phố</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="wrap_select">
                                        <select name="province_id" class="form-control provinces" onchange="get_districts_by_province(this)" data-destination="#frm_update .districts" data-id="<?=@$object['province_id']?>">
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="province_id-error" class="error" for="province_id" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Quận/ Huyện</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="wrap_select">
                                       <select name="district_id" class="form-control districts" onchange="get_wards_by_district(this)" data-destination="#frm_update .wards" data-id="<?=@$object['district_id']?>">
                                            <option value="">Chọn Quận / Huyện</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="district_id-error" class="error" for="district_id" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Phường / Xã</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="wrap_select">
                                        <select name="ward_id" class="form-control wards" data-id="<?=@$object['ward_id']?>">
                                            <option value="">Chọn Phường / Xã</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="ward_id-error" class="error" for="ward_id" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Địa chỉ</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="wrap_select">
                                        <input type="text" name="address" class="form-control" value="<?=@$object['address']?>">
                                    </div>
                                    <label id="address-error" class="error" for="address" style="display: none;"></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Người đại diện:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="contact_name" id="contact_name" name="contact_name" class="form-control" value="<?=@$object['contact_name']?>" placeholder="Nhập tên người đại diện">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Điện thoại:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="contact_phone" id="contact_phone" name="contact_phone" class="form-control" value="<?=@$object['contact_phone']?>" placeholder="Nhập số điện thoại người đại diện">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Email:</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="contact_email" id="contact_email" name="contact_email" class="form-control" value="<?=@$object['contact_email']?>" placeholder="Nhập email người đại diện">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Trạng thái:</label>
                                </div>
                                <div class="col-md-9">
                                    <div class="wrapper">                   
                                        <input value="0" type="hidden" name="status" <?=@($object['status'] == 0? 'checked':'')?> />
                                        <input type="checkbox" value="1" id="status" name="status" class="slider-toggle" <?=@($object['status'] == 1 || !isset($object)? 'checked':'')?> />
                                        <label class="slider-viewport" for="status">
                                            <div class="slider">
                                                <div class="slider-button">&nbsp;</div>
                                                <div class="slider-content left"><span>On</span></div>
                                                <div class="slider-content right"><span>Off</span></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box_descript">
                        <div class="header_box">
                            <b class="title"> Mô tả ngắn </b>
                        </div>
                        <div class="body_box">
                            <div class="edit edit_vietnam">
                                <textarea class="short_description" name="short_description"><?=@$object['short_description']?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box box_descript" style="margin-top:15px;">
                        <div class="header_box">
                            <b class="title"> Mô tả đầy đủ </b>
                        </div>
                        <div class="body_box">
                            <div class="edit edit_vietnam">
                                <textarea class="full_description" name="full_description"><?=@$object['full_description']?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
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
    <!-- Trigger the modal with a button -->


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Thêm mới nhà kho</h4>
            </div>
            <form id="frm_warehouse" action="<?=route('warehouse.store')?>">
                <div class="modal-body">
                    @if(!empty($object))
                    <div class="row">
                        <div class="col-md-6">
                            <label>Nhà cung cấp</label>
                            <input type="hidden" name="supplier_id" value="<?=@$object['id']?>">
                            <input type="text" class="form-control" placeholder="" value="<?=@$object['name']?>" disabled>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <label>Tên nhà kho</label>
                            <input type="text" name="name" class="form-control name" placeholder="Nhập tên hệ thống">
                        </div>
                        <div class="col-md-6">
                            <label>Số điên thoại</label>
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Tỉnh / Thành phố</label>
                            <div class="wrap_select">
                                <select name="province_id" class="form-control provinces" onchange="get_districts_by_province(this)" data-destination="#frm_warehouse .districts">
                                    <option value="">Chọn Tỉnh / Thành phố</option>
                                </select>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Quận / Huyện</label>
                            <div class="wrap_select">
                               <select name="district_id" class="form-control districts" onchange="get_wards_by_district(this)" data-destination="#frm_warehouse .wards">
                                    <option value="">Chọn Quận / Huyện</option>
                                </select>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Phường / Xã</label>
                            <div class="wrap_select">
                                <select name="ward_id" class="form-control wards">
                                    <option value="">Chọn Phường / Xã</option>
                                </select>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="">
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="$('#frm_warehouse')[0].reset()" class="btn btn-default btn_cancel" data-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-danger">Thêm mới</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@section('after_styles')
    <style type="text/css">
        #frm_warehouse label{
            float: left;
        }
        #myModal .modal-header{
            background: #ed1c24;
        }
    </style>
@endsection

@section('after_scripts')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>
    <script type="text/javascript">
        var $supplier_id    = <?=$object['id']??0?>;
        var $object         = <?=!empty($object)?json_encode($object):'{}'?>;
        var $warehouse_id   = [];
        $(function(){
            if($object.warehouses){
                $.each($object.warehouses,function(k,v){
                    $warehouse_id.push(v.id)
                });
            }
            setWarehouseOption($supplier_id,$warehouse_id);
            get_provinces('.frm_update .provinces');
            get_provinces('#frm_warehouse .provinces');
            $('.cancel').on('click',function(){
                $(this).closest('form')[0].reset();
            });

            $('.frm_update').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    email: {
                        required:true,
                        email:true
                    },
                    phone:{
                        required:true,
                        minlength: 10,
                        maxlength: 11,
                    },
                    'province_id': 'required',
                    'district_id': 'required',
                    'ward_id': 'required',
                    'adrress': 'required',
                },
                messages: {
                    name: "Nhập tên thương hiệu",
                    'phone': {
                        required: 'Nhập số điện thoại',
                        minlength: "Số điện thoại tối thiểu 10 số",
                        maxlength: "Số điện thoại tối đa 11 số",
                    },
                    'email': {
                        required: 'Nhập địa chỉ email',
                        email: 'Email không đúng định dạng',
                    },
                    'province_id': 'Chọn Tỉnh / Thành phố',
                    'district_id': 'Chọn Quận / Huyện',
                    'ward_id': 'Chọn Phường / Xã',
                    'adrress': 'Nhập địa chỉ',
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
                                location.href = '<?=route("supplier.index")?>';
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

            $('#frm_warehouse').validate({
                ignore: ".ignore",
                rules: {
                    name: "required",
                    phone:{
                        required:true,
                        minlength: 10,
                        maxlength: 11,
                    },
                    'province_id': 'required',
                    'district_id': 'required',
                    'ward_id': 'required',
                    'address': 'required',
                },
                messages: {
                    name: "Nhập tên thương hiệu",
                    'phone': {
                        required: 'Nhập số điện thoại',
                        minlength: "Số điện thoại tối thiểu 10 số",
                        maxlength: "Số điện thoại tối đa 11 số",
                    },
                    'province_id': 'Chọn Tỉnh / Thành phố',
                    'district_id': 'Chọn Quận / Huyện',
                    'ward_id': 'Chọn Phường / Xã',
                    'address': 'Nhập địa chỉ',
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
                            alert_success(data.msg);
                            $('#frm_warehouse .btn_cancel').trigger('click');

                            var data_warehouse = $('#frm_update .warehouse_id').select2('data');
                            var selected = [];
                            $.each(data_warehouse,function(k,v){
                                selected.push(parseInt(v.id));
                            });
                            selected.push(parseInt(data.id));
                            $('#frm_update .warehouse_id').select2('destroy');
                            setWarehouseOption($supplier_id,selected);
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

            function setWarehouseOption(supplier_id,selected){
                var supplier_id = supplier_id || 0;
                $.get('/warehouse/get-option',{supplier_id:supplier_id},function(res){
                    if(res.data){
                        var option = [];
                        $.each(res.data, function(k,v){
                            option.push({
                                id:k,
                                text:v
                            });
                        });
                        $('#frm_update .warehouse_id').select2({
                            multiple: true,
                            data: option
                        });
                        if(selected){
                            $("#frm_update .warehouse_id").val(selected).trigger('change');
                        }
                    }
                })
            }
        })
    </script>
@endsection