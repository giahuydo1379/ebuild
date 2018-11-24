@extends('layouts.master')
@section('after_styles')
<style type="text/css">
    #request{
        resize: auto;
    }
    .form-create label {
        position: static;
    }
    .form-create .col-md-6, .col-md-12 {
        padding-right: 15px !important;
        padding-left: 15px !important;;
    }
    .form-group {
        margin-bottom: 15px !important;
    }
</style>
@endsection
@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">Góp ý, báo lỗi</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách Góp ý, báo lỗi</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects['total'])
                    <div class="banner banner-display">
                        <div class="table-display">
                            <div class="header_table">
                                <div class="col-md-12">
                                    <div class="col-md-1">
                                        <span>STT</span>
                                    </div>
                                    <div class="col-md-3 no-padding">
                                        <span>Tên khách hàng</span>
                                    </div>
                                    <div class="col-md-5">
                                        <span>Nội dung</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Trạng thái</span>
                                    </div>
                                    <div class="col-md-1">
                                        
                                    </div>
                                </div>
                            </div>
                            <ul class="category_product">
                                @foreach($objects['data'] as $index => $item)
                                    <?php
                                    $_objects[$item['id']] = $item;
                                    ?>
                                    <li class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-1">
                                                <span>{{$index+1}}</span>
                                            </div>
                                            <div class="col-md-3 no-padding">
                                                <span>{{$item['customer_name']}}</span>
                                            </div>
                                            <div class="col-md-5">
                                                <span>{{$item['content']}}</span>
                                            </div>
                                            <div class="col-md-2">
                                                {!!$status_btn[$item['status']]!!}
                                            </div>
                                            <div class="col-md-1">
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
                        <p>Chưa có thông tin!</p>
                       
                    </div>
                @endif
                <div class="banner-update">
                    <form class="form-create" method="post" id="form_update" action="{{ route($controllerName.".add") }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nội dung góp ý, báo lỗi</label>
                                <textarea class="form-control" id="content"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <div>
                                    <div class="wrap_select">
                                        {!! Form::select("status", $status, '', ['id' => 'status', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="gender-error" class="error" for="gender" style="display: none;"></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Trả lời</label>
                                <textarea class="form-control" id="reply" name="reply"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="action">
                                <span class="cancel Cancel">Hủy bỏ</span>
                                <input type="hidden" name="id" id="id" value="0">
                                <input type="hidden" id="is_reload" value="0">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="submit" class="btn btn_primary BtnUpdate" >Lưu</button>
                            </div>
                         </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        console.log(_objects);  
        $(document).ready(function() {
            $('textarea#reply').ckeditor();
            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có chắc chắn muốn xóa?", function () {
                    ajax_loading(true);
                    $.post('{{route($controllerName.'.delete')}}', {
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
                $('.TitleDisplay').hide();
                $('.BackAction').show();
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
                $('#form_update')[0].reset();

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
            });

            $('.update-action').on('click', function () {
                var id = $(this).attr('data-id');
                var item = _objects[id];                
                $('label.error').hide();

                if(item.type == 'bussiness'){
                    $('.bussiness').show();
                }else{
                    $('.bussiness').hide();
                }

                $('#form_update #id').val(id);
                $('#form_update #company_name').val(item.company_name);
                $('#form_update #tax_code').val(item.tax_code);
                $('#form_update #address').val(item.address);
                $('#form_update #name').val(item.name);
                $('#form_update #phone').val(item.phone);
                $('#form_update #email').val(item.email);
                $('#form_update #content').val(item.content);
                $('#form_update #reply').val(item.reply);
                $('#form_update #status').val(item.status);
                

                $('.banner-display').slideUp();
                $('.banner-update').slideDown();

                $('.TitleCreate').show();
                $('.TitleDisplay').hide();
                $('.BackAction').show();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                },
                messages: {
                   
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
                                $('#is_reload').val(1);
                                location.reload();
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