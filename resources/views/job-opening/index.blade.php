@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-career BannerAct">
            <h3 class="title-section">Vị trí tuyển dụng</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Thêm mới vị trí tuyển dụng</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật vị trí tuyển dụng</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách vị trí tuyển dụng</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    <a href="javascript:void(0)" class="pull-right link add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>

                <?php
                $_objects = [];
                ?>
                <div class="banner banner-display">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-6">
                                <div class="col-md-1">
                                    <input type="checkbox" data-toggle="checkall" data-target="input[name=choose]" class="checkbox_check">
                                </div>
                                <div class="col-md-4 no-padding">
                                    <span>Tên vị trí</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Danh mục</span>
                                </div>
                                <div class="col-md-3 no-padding">
                                    <span>Tỉnh/ Thành phố</span>
                                </div>
                            </div>
                            <div class="col-md-6 no-padding ">
                                <div class="col-md-4 no-padding">
                                    <span>Mô tả công việc </span>
                                </div>
                                <div class="col-md-4">
                                    <span>Yêu cầu công việc</span>
                                </div>
                                <div class="col-md-2 no-padding">
                                    <span>Trạng thái</span>
                                </div>
                                <div class="col-md-2">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                            <form class="row-filter FilterRow text-center">
                                <div class="col-md-offset-1 col-md-3 no-padding">
                                    <div class="wrap_select">
                                        {!! Form::select("category_filter", ['' => 'Chọn danh mục']+$categories, null, ['id' => 'category_filter', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="wrap_select">
                                        <select class="form-control provinces" name="province_filter" id="province_filter" data-id="{{@$params['province_filter']}}">
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-2 no-padding">
                                    <div class="wrap_select">
                                        {!! Form::select("status_filter", \App\Helpers\General::get_status_options(), null, ['id' => 'status_filter', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-md-2 text-left submit">
                                    <button type="submit" value="" class="btn btn_primary">Lọc</button>
                                </div>
                            </form>
                            <div class="switch_bar SwitchBar">
                                <div class="wrap-switch">
                                    <label>Chọn thao tác: </label>
                                    <div class="select">
                                        {!! Form::select("status_action", \App\Helpers\General::get_status_actions(), null, ['id' => 'status_action', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" class="switch_status">
                                        <div class="slider round"><i class="icon-check">&nbsp</i></div>
                                    </label>
                                </div>
                            </div>
                            @foreach($objects['data'] as $index => $item)
                                <?php
                                $_objects[$item['id']] = $item;
                                ?>
                            <li class="row">
                                <div class="col-md-6">
                                    <div class="col-md-1">
                                        <input value="{{$item['id']}}" type="checkbox" name="choose" class="checkbox_check item_check">
                                    </div>
                                    <div class="col-md-4 no-padding content">
                                        <span>{{$item['position']}}</span>
                                    </div>
                                    <div class="col-md-4 content">
                                        <span>{{@$categories[$item['job_category_id']]}}</span>
                                    </div>
                                    <div class="col-md-3 content">
                                        <span>{{@$item['province_name']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 no-padding">
                                    <div class="col-md-4 no-padding content">
                                        <span>{{strip_tags($item['description'])}}</span>
                                    </div>
                                    <div class="col-md-4 content">
                                        <span>{{strip_tags($item['request'])}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="wrapper tooltip">
                                            <input type="checkbox" id="status-{{$index}}" class="slider-toggle" @if ($item['status']) checked @endif/>
                                            <label class="slider-viewport" for="status-{{$index}}" onclick="return false;">
                                                <div class="slider">
                                                    <div class="slider-button">&nbsp;</div>
                                                    <div class="slider-content left"><span>On</span></div>
                                                    <div class="slider-content right"><span>Off</span></div>
                                                </div>
                                            </label>
                                            <span class="tooltiptext">Chưa kích hoạt</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
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

                <div class="banner-update">
                    <form class="form-create" method="post" id="form_update" action="{{ route("job-opening.add") }}">
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên vị trí <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input name="position" id="position" type="text" class="form-control" placeholder="Nhập tên vị trí tuyển dụng">
                                    <label id="position-error" class="error" for="position" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tên Alias</label>
                                </div>
                                <div class="col-md-10">
                                    <input readonly type="text" name="alias" id="alias" class="form-control" placeholder="Alias được tự động tạo theo tên vị trí">
                                    <label id="position-error" class="error" for="position" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Danh mục <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <div class="wrap_select">
                                        {!! Form::select("job_category_id", ['' => 'Chọn danh mục']+$categories, null, ['id' => 'job_category_id', 'class' => 'form-control']) !!}
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="job_category_id-error" class="error" for="job_category_id" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Tỉnh/ TP <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <div class="wrap_select">
                                        <select class="form-control provinces" name="province_id" id="province_id">
                                            <option>Chọn Tỉnh/ thành phố</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                    <label id="province_id-error" class="error" for="province_id" style="display: none;"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Keyword <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input name="keywords" id="keywords" type="text" class="form-control" placeholder="Nhập keyword, cách nhau bởi dấu phẩy">
                                    <label id="keywords-error" class="error" for="keywords" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Số lượng <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input name="quantity" id="quantity" type="text" class="form-control" placeholder="Nhập số lượng">
                                    <label id="quantity-error" class="error" for="quantity" style="display: none;"></label>
                                </div>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Ngày hết hạn <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input name="expiration_date" id="expiration_date" type="text" class="form-control datetime" placeholder="Ngày hết hạn" readonly="">
                                    <label id="expiration_date-error" class="error" for="expiration_date" style="display: none;"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-2 text-right">
                                    <label>Trạng thái <span>*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <div class="wrapper">
                                        <input value="0" type="hidden" name="status"/>
                                        <input value="1" type="checkbox" id="status" name="status" class="slider-toggle" checked />
                                        <label class="slider-viewport" for="status">
                                            <div class="slider">
                                                <div class="slider-button">&nbsp;</div>
                                                <div class="slider-content left"><span>On</span></div>
                                                <div class="slider-content right"><span>Off</span></div>
                                            </div>
                                        </label>
                                    </div>
                                    <span class="note">Chọn để kích hoạt trạng thái</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Mô tả công việc <span>*</span></label>
                            </div>
                            <div class="col-md-11">
                                <textarea id="description" name="description" class="form-control ckeditor" cols="" placeholder="Nhập mô tả công việc"></textarea>
                                <label id="description-error" class="error" for="description" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Yêu cầu <span>*</span></label>
                            </div>
                            <div class="col-md-11">
                                <textarea id="request" name="request" class="form-control ckeditor" cols="" placeholder="Nhập yêu cầu công việc"></textarea>
                                <label id="request-error" class="error" for="request" style="display: none;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-1 text-right">
                                <label>Thông tin khác <span class="since">(nếu có)</span></label>
                            </div>
                            <div class="col-md-11">
                                <textarea id="other_information" name="other_information" class="form-control ckeditor" cols="" placeholder="Nhập thông tin khác"></textarea>
                            </div>
                        </div>
                        <div class="action text-right">
                            <span class="cancel Cancel">Hủy bỏ</span>
                            <input type="hidden" id="is_next" value="0">
                            <input type="hidden" id="is_reload" value="0">
                            <input type="hidden" name="id" id="id" value="0">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="submit" value="" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                            <button type="submit" value="" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới</button>
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
        $(document).ready(function() {

            $('.datetime').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '+1d'
            });

            get_provinces('.provinces');

            $('textarea.ckeditor').ckeditor();

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
                ajax_loading(true);
                $.post('{{route('job-opening.change-status')}}', {
                    ids: ids,
                    status: $('#status_action').val(),
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

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, vị trí tuyển dụng này không?", function () {
                    ajax_loading(true);
                    $.post('{{route('job-opening.delete')}}', {
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

                $('#is_next').val(0);
                $('#form_update')[0].reset();
                CKEDITOR.instances.description.setData( '' );
                CKEDITOR.instances.request.setData( '' );
                CKEDITOR.instances.other_information.setData( '' );

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
                var item = _objects[$(this).attr('data-id')];

                $('label.error').hide();
                $('#form_update #is_next').val(0);
                $('#form_update #id').val(item.id);
                $('#form_update #position').val(item.position);
                $('#form_update #job_category_id').val(item.job_category_id);
                $('#form_update #province_id').val(item.province_id);
                $('#form_update #alias').val(item.alias);
                $('#form_update #description').val(item.description);
                $('#form_update #request').val(item.request);
                $('#form_update #other_information').val(item.other_information);
                $('#form_update #keywords').val(item.keywords);
                $('#form_update #quantity').val(item.quantity);
                $('#form_update #expiration_date').val(item.expiration_date);
                if (item.status) {
                    $('#form_update #status').attr('checked', 'checked');
                } else {
                    $('#form_update #status').removeAttr('checked');
                }

                $('.add-action').click();
            });

            $('#form_update').validate({
                ignore: ".ignore",
                rules: {
                    title: "required",
                    job_category_id: "required",
                    province_id: "required",
                    keywords: "required",
                    description: "required",
                    request: "required",
                    quantity:'required',
                    expiration_date:'required',
                },
                messages: {
                    title: "Vui lòng nhập tiêu đề vị trí tuyển dụng",
                    job_category_id: "Vui lòng chọn danh mục tuyển dụng",
                    province_id: "Vui lòng chọn tỉnh thành phố",
                    keywords: "Vui lòng từ khóa seo",
                    description: "Vui lòng nhập mô tả vị trí tuyển dụng",
                    request: "Vui lòng nhập yêu cầu vị trí tuyển dụng",
                    quantity: "Vui lòng nhập số lượng tuyển dụng",
                    expiration_date: "Vui lòng chọn ngày hết hạn",
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

                                    $('#is_reload').val(1);
                                    $('#is_next').val(0);
                                    $('#form_update')[0].reset();

                                    CKEDITOR.instances.description.setData( '' );
                                    CKEDITOR.instances.request.setData( '' );
                                    CKEDITOR.instances.other_information.setData( '' );

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
@endsection