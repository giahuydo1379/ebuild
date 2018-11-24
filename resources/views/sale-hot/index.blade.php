@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-promotion">
            <h3 class="title-section">Khuyến mãi hot</h3>
            <div class="panel box-panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Tạo khuyến mãi hot</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật khuyến mãi hot</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách khuyến mãi hot</h3>
                    
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>

                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                </div>
                <?php
                $layouts = \App\Helpers\General::get_miscrosite_layout();
                $_objects = [];
                $product_ids = [];
                $brand_ids = [];
                $types = [
                    "normal" => "Bình thường",
                    "product" => "Sản phẩm",
                    "category" => "Danh mục SP",
                    "brand" => "Thương hiệu",
                ]
                ?>
                @if ($objects['total'])
                <div class="banner-promotion banner-display">
                    <div class="table-responsive">
                        <table class="table table-display">
                            <thead class="header_table">
                            <tr>
                                <th>Tên khuyến mãi hot</th>
                                <th>Hình ảnh</th>
                                <th>Thứ tự hiển thị</th>
                                <th>Liên kế</th>
                                <th>Ngày bắt đầu / kết thúc</th>
                                <th>Trạng thái</th>
                                <th class="col-status">Dừng chương trình</th>
                                <th class="col-action">Chức năng</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="8" style="padding: 0px;">
                                    <form class="row-filter">
                                        <div class="col-md-10">
                                            <div class="col-md-offset-10 col-md-2 no-padding">
                                                {!! Form::select("status_filter", \App\Helpers\General::get_status_options(), null, ['id' => 'status_filter', 'class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-2 no-padding">
                                            <button type="submit" class="btn btn_primary">Lọc</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @foreach($objects['data'] as $index => $item)
                                <?php
                                $item['from_time'] = '';
                                $item['from_date'] = '';
                                $item['to_time'] = '';
                                $item['to_date'] = '';

                                if(!empty($item['date_from'])){
                                    $item['from_time'] = \App\Helpers\General::output_time_of_date($item['date_from'], true);
                                    $item['from_date'] = \App\Helpers\General::output_date($item['date_from'], true);
                                }
                                if(!empty($item['date_to'])){

                                    $item['to_time'] = \App\Helpers\General::output_time_of_date($item['date_to'], true);
                                    $item['to_date'] = \App\Helpers\General::output_date($item['date_to'], true);
                                }

                                $_objects[$item['id']] = $item;
                                ?>
                            <tr>
                                <td class="col-name"><a href="javascript:void(0)" class="update-action" data-id="{{$item['id']}}" title="<?=$item['name']?>">
                                        <b>{{$item['name']}}</b></a ></td>
                                <td class="col-banner"><img src="{{$item['image_url'] . $item['image_location']}}" alt="{{config('app.name')}} Khuyến mãi hot"></td>
                                <td class="col-position">{{$item['position']}}</td>
                                <td>{{$item['link']}}</td>
                                <td class="col-date">
                                    @if($item['from_date'] || $item['to_date'])
                                    <span>{{$item['from_date']}} - {{$item['from_time']}}</span><br>
                                    <span>{{$item['to_date']}} - {{$item['to_time']}}</span>
                                    @else
                                    <span>Không giới hạn</span>
                                    @endif
                                </td>
                                <td><div class="wrapper tooltip">
                                        <input type="checkbox" id="status-{{$index}}" class="slider-toggle" @if ($item['status']) checked @endif/>
                                        <label class="slider-viewport" for="status-{{$index}}" onclick="return false;">
                                            <div class="slider">
                                                <div class="slider-button">&nbsp;</div>
                                                <div class="slider-content left"><span>On</span></div>
                                                <div class="slider-content right"><span>Off</span></div>
                                            </div>
                                        </label>
                                        <span class="tooltiptext">Chưa kích hoạt</span>
                                    </div></td>
                                <td class="col-status">@if ($item['status'])
                                        <button class="btn btn-stop" data-id="{{$item['id']}}">Dừng</button>
                                    @else
                                        <button class="btn btn-start" data-id="{{$item['id']}}">Kích hoạt</button>
                                    @endif</td>
                                <td class="col-action">
                                    <a class="tooltip update-action" data-id="{{$item['id']}}">
                                        <i class="icon-edit-pen active UpdateAction" aria-hidden="true" ></i>
                                        <i class="icon-edit-pen-hover UpdateHover" title="Chỉnh sửa">&nbsp</i>
                                        <span class="tooltiptext">Cập nhật</span>
                                    </a>
                                    <a class="tooltip delete-action" data-id="{{$item['id']}}">
                                        <i class="icon-delete active DeleteAction" aria-hidden="true"></i>
                                        <i class="icon-delete-hover DeleteHover" title="Xóa">&nbsp</i>
                                        <span class="tooltiptext">Xóa</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @include('includes.paginator')
                </div>
                @else
                <div class="no-banner">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-action-none"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif

                <div class="create-landingpage banner-update">
                    <form class="form-create img-promotion" method="post" id="form_update" action="{{ route("sale-hot.add") }}">
                        <div class="col-md-7 pdl_0">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>Tên chương trình <span>*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chường trình khuyến mãi hot">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>Mô tả chương trình <span>*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="description" id="description" cols="30" rows="3" placeholder="Nhập mô tả chương trình"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>Thứ tự hiển thị <span>*</span></label>
                                </div>
                                <div class="col-md-9">
                                    {!! Form::select("position",\App\Helpers\General::get_ordering_options(), null, ['id' => 'position', 'class' => 'form-control select2']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label>Link liên kết<span>*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" name="link" id="link" class="form-control" placeholder="Nhập link liên kết chương trình">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label>Trạng thái chương trình: </label>
                                </div>
                                <div class="col-md-8">
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
                                </div>
                            </div>
                            <div class="form-group time-show">
                                <label for="">Thời gian diễn ra chương trình:</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <span class="lb-time">Bắt đầu từ:</span>
                                    </div>
                                    <div class="col-md-3 no-padding">
                                        <div class="input-group clockpicker">
                                            <input type="text" name="from_time" id="from_time" class="form-control" value="">
                                            <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                        </div>
                                        <label id="from_time-error" class="error" for="from_time"></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="time">
                                            <div class="input-group date">
                                                <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày bắt đầu">
                                                <span class="fa fa-calendar"></span>
                                            </div>
                                        </div>
                                        <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <span class="lb-time">Kết thúc lúc:</span>
                                    </div>
                                    <div class="col-md-3 no-padding">
                                        <div class="input-group clockpicker">
                                            <input type="text" name="to_time" id="to_time" class="form-control" value="">
                                            <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                        </div>
                                        <label id="to_time-error" class="error" for="to_time"></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="time">
                                            <div class="input-group date">
                                                <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Ngày kết thúc">
                                                <span class="fa fa-calendar"></span>
                                            </div>
                                        </div>
                                        <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group wrap-btn">
                                <div class="col-md-3" style="text-align: right;padding-top: 6px;">
                                    <span class="cancel main-cancel">Hủy bỏ</span>
                                </div>
                                <div class="col-md-9">
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(0)">Lưu</button>
                                    <button type="submit" class="btn btn_primary BtnUpdate" onclick="$('#is_next').val(1)">Lưu & Thêm mới chương trình</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="choose-banner">
                                <label>Hình ảnh <span>*</span></label>
                                <div class="wrap-choose">
                                    <ul class="wrap_btn">
                                        <li>
                                            <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_location">
                                                <i class="icon-browser">&nbsp;</i>
                                                <span>Browse ...</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <span class="size-note">Kích thước: <b>644 x 300 px</b></span>
                                    <span class="size-note">Dung lượng: <b>250kb</b></span>
                                    <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                </div>
                            </div>
                            <label id="image_location-error" class="error" for="image_location" style="display: none;"></label>
                            <div class="display-banner">
                                <input type="hidden" value="" name="image_location" id="image_location" data-url="#image_url" data-preview="#form_update .preview-banner">
                                <input type="hidden" value="" name="image_url" id="image_url">
                                <img class="preview-banner" src="/html/assets/images/image-salehot.png" alt="salehot" />
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id" value="0">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" id="is_reload" value="0">
                        <input type="hidden" id="is_next" value="0">
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_styles')
    <link rel="stylesheet" href="{{asset('/assets/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.css')}}">
    <link href="/assets/plugins/iCheck/skins/flat/green.css" rel="stylesheet">
    <style type="text/css">
        .form-create {
            padding-top: 20px;
        }
        .form-create .form-group .col-md-4, .time-show {
            text-align: left;
            padding-left: 15px;
        }
        .col-position {
            width: 100px;
        }
        .col-banner{
            width: 200px;
        }
        .col-date{
            width: 290px;
        }
        .col-name {
            width: 400px;
        }
        .col-status {
            text-align: center;
            width: 135px;
        }
        .col-action {
            text-align: center;
            width: 100px;
        }
    </style>
@endsection
<?php
$version_js = \App\Helpers\General::get_version_js();
?>
@section('after_scripts')
    <script type="text/javascript" src="/assets/plugins/ckfinder/ckfinder.js"></script>
    <script src="/assets/plugins/bootstrap-clockpicker/bootstrap-clockpicker.min.js"></script>   
    <script src="/assets/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="{{asset('js/sale-hot.js?v='.$version_js)}}"></script>
    <script type="text/javascript">
        var _objects = {!! @json_encode($_objects) !!};

        $(function(){
            init_select2('.select2');
        })
    </script>
@endsection