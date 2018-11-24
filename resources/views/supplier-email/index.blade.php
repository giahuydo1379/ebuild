@extends('layouts.master')

@section('content')
    <style>
        .frm_update .row {
            margin-bottom: 10px;
        }
    </style>
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section"><?=$title?></h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật <?=$title?></h3>
                    <h3 class="title TitleDisplay" @if (!$objects) style="display: none;" @endif>Danh sách</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects)
                <div class="banner banner-display">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-7">
                                <div class="col-md-2">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-5 no-padding">
                                    <span>Nhà cung cấp</span>
                                </div>
                                <div class="col-md-5">
                                    <span>Email nhận thông tin</span>
                                </div>
                            </div>
                            <div class="col-md-5 no-padding">
                                <div class="col-md-11">
                                    <span>Trạng thái</span>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                        <?php $index = 1;?>
                        @foreach($objects['data'] as $key => $items)
                        <?php $_objects[$items['id']] = $items; ?>
                            <li class="row">
                                <div class="col-md-7">
                                    <div class="col-md-2">
                                        <span>{{$key+=1}}</span>
                                    </div>  
                                    <div class="col-md-5 no-padding content">
                                        <span>{{@$supplier_options[$items['supplier_id']]}}</span>
                                    </div>
                                    <div class="col-md-5">
                                        @foreach($items['supplier_emails'] as $value)
                                        <p>{{$value['email']}}</p>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-5 no-padding">
                                    <div class="col-md-11 content">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="wrapper tooltip">
                                            <input type="checkbox" id="active-{{$items['id']}}" class="slider-toggle" @if ($items['status']) checked @endif/>
                                            <label class="slider-viewport" for="active-{{$items['id']}}" onclick="return false;">
                                                <div class="slider">
                                                    <div class="slider-button">&nbsp;</div>
                                                    <div class="slider-content left"><span>On</span></div>
                                                    <div class="slider-content right"><span>Off</span></div>
                                                </div>
                                            </label>
                                            <span class="tooltiptext">Chưa kích hoạt</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="tooltip update-action" data-id="{{$items['id']}}">
                                            <i class="icon-edit-pen active UpdateAction" aria-hidden="true"></i>
                                            <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Cập nhật</span>
                                        </a>
                                        <a class="tooltip delete-action" data-id="{{$items['id']}}">
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
                    <form class="frm_update" method="post" id="form_update" action="{{ route('supplier-email.store') }}">
                        <div class="row">
                            <div class="col-md-6 padding-15">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label style="position: initial;">Nhà cung cấp <span>*</span></label>
                                        {!! Form::select("supplier_id", ['' => ''] + $supplier_options, @$params['supplier_id'],
                                                ['id' => 'supplier_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo nhà cung cấp']) !!}
                                        <label id="name-error" class="error" for="title" style="display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label style="position: initial;">Ngành hàng <span>*</span></label>
                                        <select class="form-control select2" name="category_id" id="category_id" data-placeholder="Chọn danh mục">
                                            <option value=""></option>
                                            @foreach($list_categories as $item)
                                                <option <?=@($item['category_id'] == $data['category_id']?'selected':'') ?> value="<?=$item['category_id']?>" ><?=$item['category']?></option>
                                            @endforeach
                                        </select>
                                        <label id="name-error" class="error" for="title" style="display: none;"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <label style="position: initial;">Địa chỉ email nhận thông báo đơn hàng mới <span>*</span></label>
                                        </div>
                                        <div class="col-md-10 no-padding-left">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Nhập email">
                                            <label id="name-error" class="error" for="title" style="display: none;"></label>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn_primary add-email">Thêm</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="wrapper">
                                        <input type="hidden" name="status" value="0" />
                                        <input type="checkbox" id="status" name="status" value="1" class="slider-toggle" checked />
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
                            <div class="col-md-6 padding-15">
                                <div class="col-md-12" style="padding:0px 10px;">
                                    <label style="position: initial;">Địa chỉ email sẽ nhận thông báo đơn hàng mới</label>
                                    <div id="show-email-list">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <div class="action">
                                    <span class="cancel Cancel">Hủy bỏ</span>
                                    <input type="hidden" id="is_reload" value="0">
                                    <input type="hidden" id="is_next" value="0">
                                    <input type="hidden" name="id" id="id" value="0">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('after_scripts')
    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        $(document).ready(function() {
            init_select2('.select2');

            $('#position').on('change',function(){
                ajax_loading(true);
                $.get('{{route('menu-item.get-menus-parent')}}', {
                    position: $(this).val()
                }, function(res){
                    ajax_loading(false);
                    var html = '<option value=""></option>';
                    $.each(res.data, function (id, val) {
                        html += '<option value="'+id+'">'+val+'</option>';
                    });
                    $('#parent_id').html(html).val($('#parent_id').attr('data-id')).trigger('change');
                });
            });
            $(document).on('click','.add-email',function (e) {
                e.preventDefault();
                var email       = $('.frm_update #email').val();
                var supplier_id = $('.frm_update #supplier_id').val();
                var category_id = $('.frm_update #category_id').val();
                if(!email) {
                    malert('Vui lòng nhập Email');
                    return false;
                }
                var checkEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if (!checkEmail.test(email)){
                    malert('Email sai định dạng');
                    return false;
                };

                $('.frm_update #email').val('');
                set_html_add_email(email, supplier_id, category_id);
            });
            function set_html_add_email(email, supplier_id, category_id, item) {
                var item = item||$.now();
                var id = "#item_" + item;

                html = '<p id="item_' + item + '">'+ email +' <b><a href="javascript:void(0)" onclick="$(\'' + id + '\').remove()"><span class="glyphicon glyphicon-remove"></span></a></b>';
                html += '<input type="hidden" name="emailPost['+ item +'][email]" value="'+ email +'">';
                html += '<input type="hidden" name="emailPost['+ item +'][supplier_id]" value="'+ supplier_id +'">';
                html += '<input type="hidden" name="emailPost['+ item +'][category_id]" value="'+ category_id +'"></p>';
                $('#show-email-list').append(html);
                return html;
            }

            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có chắc chắn muốn xóa không?", function () {
                    ajax_loading(true);
                    $.post('{{route('supplier-email.delete')}}', {
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

                $('.TitleUpdate').show();
                $('.TitleDisplay').hide();
                $('.BackAction').show();
                $('.type_').hide();
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
                $('#id').val(0);
                $('#show-email-list').html('');

                $('.type_').hide();
                $('.add-action-none').parent().slideDown();
                $('.banner-display').slideDown();
                $('.TitleUpdate').hide();
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
            });

            $('.update-action').on('click', function () {
                
                $('.add-action').click();
                $('.TitleCreate').hide();
                $('.TitleUpdate').show();
                var item = _objects[$(this).attr('data-id')];
                $('#id').val(item.id);
                $('#supplier_id').val(item.supplier_id).trigger('change');
                $('#category_id').val(item.category_id).trigger('change');
                $('#show-email-list').html('');
                if(item.supplier_emails){
                    $.each(item.supplier_emails,function(k,v){
                        var item_id = 'p'+k+$.now();
                        set_html_add_email(v.email,v.supplier_id,v.category_id,item_id);
                    });
                }
                if(item.status == 1){
                    $('#status').prop('checked',true);
                }else{
                    $('#status').prop('checked',false);
                }
                $('label.error').hide();              
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    supplier_id:"required",
                    category_id:"required"
                },
                messages: {
                    supplier_id:"Chọn nhà cung cấp",
                    category_id:"Chọn ngành hàng"
                },
                submitHandler: function(form) {

                    var submit = true;
                    if($('#show-email-list input').length < 1){
                        malert('Vui lòng nhập Email');
                        return false
                    }

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
                                    $('.type_').hide();
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
                            $('#alias').val(data.data.alias);
                        }
                    });

                    return false;
                }
            });
        });

    </script>
@stop