@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-news BannerAct">
            <h3 class="title-section">Gói dịch vụ</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Tạo gói dịch vụ</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật gói dịch vụ</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách gói dịch vụ</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;">
                        <i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif>
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects['total'])
                <div class="banner banner-display">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-7">
                                <div class="col-md-1">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Tên gói dịch vụ</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Danh mục</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Giá</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="col-md-5">
                                    <span>Thời lượng</span>
                                </div>
                                <div class="col-md-5">
                                    <span>Vị trí</span>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                        @foreach($objects['data'] as $index => $item)
                                <?php
                                $_objects[$item['id']] = $item;
                                ?>
                            <li class="row">
                                <div class="col-md-7">
                                    <div class="col-md-1">
                                        <span><?=$index+1?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <span><?=$item['package_name']?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <span><?=$item['category']?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span><?=number_format($item['price'])?></span>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-5">
                                        <span><?=$item['duration']?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <span><?=$item['position']?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="tooltip update-action" data-id="{{$item['id']}}">
                                            <i class="icon-edit-pen active UpdateAction" aria-hidden="true"></i>
                                            <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Cập nhật</span>
                                        </a>
                                        <a class="tooltip delete-action" data-id="{{$item['id']}}">
                                            <i class="icon-delete active DeleteAction" aria-hidden="true"></i>
                                            <i class="icon-delete-hover DeleteHover" title="Xóa">&nbsp</i>
                                            <span class="tooltiptext">Xóa</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        </ul>

                        @include('includes.paginator')
                    </div>
                </div>
                @else
                <div class="no-banner">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-action-none"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif

                <div class="banner-update">
                    <form class="form-create" method="post" id="form_update" action="">
                        <div class="col-md-7 col-md-offset-2">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Tên gói dịch vụ <span>*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="package_name" id="package_name" class="form-control" placeholder="Nhập tên gói dịch vụ">
                                    <label id="package_name-error" class="error" for="package_name" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Danh mục <span>*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="wrap_select">
                                        {!! Form::select("category_id",["" => ""]+$categories, null, ['id' => 'category_id', 'class' => 'form-control select2', 'data-placeholder'=>"Chọn danh mục"]) !!}
                                    </div>
                                    <label id="ordering-error" class="error" for="ordering" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Dịch vụ <span>*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="wrap_select">
                                        <select id="product_ids" name="product_id[]" class="form-control select2" multiple="multiple" data-placeholder="Chọn dịch vụ">
                                        </select>
                                    </div>
                                    <label id="ordering-error" class="error" for="ordering" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Giá <span>*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" name="price" id="price" class="form-control" placeholder="Giá tiền gói dịch vụ">
                                    <label id="price-error" class="error" for="price" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Thời lượng <span>*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" name="duration" id="duration" class="form-control" placeholder="Thời lượng gói dịch vụ">
                                    <label id="duration-error" class="error" for="duration" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Vị trí</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="number" name="position" id="position" class="form-control" placeholder="Vị trí gói dịch vụ">
                                    <label id="position-error" class="error" for="position" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="form-group wrap-btn">
                                <div class="col-md-4">
                                    <span class="cancel">Hủy bỏ</span>
                                </div>
                                <div class="col-md-8">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" name="id" id="id" value="0">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" value="" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                    <button type="submit" value="" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm danh mục mới</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('after_styles')
@endsection
@section('after_scripts')
    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        $(document).ready(function() {
            init_select2('.select2');

            function setService(category_id,product_id){
                if (category_id) {
                    ajax_loading(true);
                    $.get('/package/get-service', {category_id: category_id}, function (res) {
                        ajax_loading(false);
                        if (res.data) {
                            var html = '';

                            var product_ids = [];
                            $.each(res.data, function (k, v) {
                                product_ids.push(k);
                                html += '<option value="' + v.product_id + '" data-price="' + v.price + '">' + v.product + '</option>'
                            });
                            if (!product_id) {
                                product_id = product_ids;
                                $('#product_ids').html(html).select2('val', product_id);
                                setPrice();
                            } else {
                                $('#product_ids').html(html).select2('val', product_id);
                            }
                        }
                    });
                } else {
                    $('#product_ids').html('').trigger('change');
                }
            }

            function setPrice(){
                sum = 0;
                 $('#product_ids :selected').each(function() {
                    sum += Number($(this).data('price'));
                });
                $('#price').val(sum);
            }

            $(document).on('change','#category_id',function(){
                setService($(this).val(),'');
            });
            $(document).on('change','#product_ids',function(){
                setPrice();
            });
            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, gói dịch vụ này không?", function () {
                    ajax_loading(true);
                    $.post('{{route('package.delete')}}', {
                        id: $(obj).attr('data-id'),
                        _token: '{!! csrf_token() !!}'
                    }, function(data){
                        ajax_loading(false);
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
                $('.BackAction').show();
                $('.TitleDisplay').hide();
            });
            $('.add-action-none').on('click', function () {
                $(this).parent().slideUp();
                $('.banner-update').slideDown();
                $('.BackAction').show();
            });
            $('#form_update .cancel').on('click', function (e) {
                e.preventDefault();

                if ($('#is_reload').val() == '1') {
                    location.reload();
                    return false;
                }

                $('#is_next').val(0);
                $('#form_update')[0].reset();
                $('#form_update #category_id').select2('val', '').trigger('change');

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

                $('.TitleUpdate').hide();
            });

            $('.update-action').on('click', function () {
                var item = _objects[$(this).attr('data-id')];

                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id);
                $('#form_update #package_name').val(item.package_name);
                $('#form_update #category_id').select2('val',item.category_id);
                ajax_loading(true);
                $.get('/package/get-service-package',{package_id:item.id},function(res){
                    ajax_loading(false);
                    if(res.data){
                        setService(item.category_id,res.data);        
                    }
                });
                
                $('#form_update #price').val(item.price);
                $('#form_update #duration').val(item.duration);
                $('#form_update #position').val(item.position);

                // if (item.status) {
                //     $('#form_update #status').attr('checked', 'checked');
                // } else {
                //     $('#form_update #status').removeAttr('checked');
                // }

                $('.add-action').click();

                $('.TitleCreate').hide();
                $('.TitleUpdate').show();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    package_name: "required",
                    category_id: "required",
                    'product_id[]':{
                        required:true,
                    },
                    price: "required",
                    duration: "required"
                },
                messages: {
                    package_name: "Nhập tên gói dịch vụ",
                    category_id: "Chọn danh mục",
                    'product_id[]':"Chọn dịch vụ",
                    price: "Nhập giá tiền của gói dịch vụ",
                    duration: "Nhập thời lượng của gói dịch vụ"
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
                                if ($('#is_next').val()=='1') {
                                    $('.add-action').click();
                                    $('#id').val(0);
                                    $('#is_reload').val(1);
                                    $('#is_next').val(0);
                                    $('#category_id').select2("val", "");
                                    $('#product_ids').select2("val", "").html("");
                                    $('#form_update')[0].reset();

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

                    return false;
                }
            });
        });
    </script>
@endsection