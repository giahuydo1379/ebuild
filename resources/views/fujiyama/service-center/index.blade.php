@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-banner">
            <h3 class="title-section">Trung tâm bảo hàng</h3>
            <div class="panel box-panel">
                <?php
                if (isset($settings['fujiyama_service_center_banner']) && $settings['fujiyama_service_center_banner']['value']) {
                    $image = $settings['fujiyama_service_center_banner']['value'];
                    $link = $settings['fujiyama_service_center_banner']['field'];
                } else {
                    $image = '';
                    $link = '';
                }
                ?>
                <div class="top">
                    <h3 class="title">Banner giới thiệu</h3>
                    @if ($image)
                        <a class="pull-right service_center_banner_update">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Cập nhật</span>
                        </a>
                    @endif
                </div>
                @if ($image)
                    <div class="banner banner-display">
                        <img id="service_center_banner" src="{{$image}}" alt="">
                    </div>
                @else
                    <div class="service_center_banner_add no-banner">
                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                        <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                    </div>
                @endif
                <div class="banner-update">
                    <form id="form_service_center_banner_update" action="{{route('chain-store.setting-update')}}">
                        <div class="col-md-9">
                            <img id="preview-file-upload" class="preview-banner" src="{{$image}}" alt="">
                        </div>
                        <div class="col-md-3">
                            <ul class="wrap_btn">
                                <li>
                                    <a href="#" class="btn-loadfile browse-image" data-target="image_url">
                                        <i class="icon-browser">&nbsp;</i>
                                        <span>Browse ...</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="show_file">
                                <span class="file_name"></span>
                            </div>
                            <span class="size-note">Kích thước 900 x 300 px</span>
                            <label>Link liên kết</label>
                            <input type="text" value="{{$link}}" name="field" class="form-control field" placeholder="Nhập link liên kết">
                            <input type="hidden" value="{{$image}}" name="value" id="image_url" data-preview="#preview-file-upload">
                            <input type="hidden" value="fujiyama_service_center_banner" name="key">
                            <div class="action pull-right">
                                <span class="cancel">Hủy bỏ</span>
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="section section-infor-listsuper">
            <?php
            $description = isset($settings['fujiyama_service_center_description']) ? $settings['fujiyama_service_center_description']['value'] : '';
            ?>
            <div class="panel box-panel service-center-description">
                <div class="top">
                    <h3 class="title">Giới thiệu trung tâm bảo hành</h3>
                    @if ($description)
                        <a class="pull-right update-action">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Cập nhật</span>
                        </a>
                    @endif
                </div>
                @if ($description)
                    <div class="box-content box-display">
                        <div class="description-content">
                            <label class="title-box">Mô tả ngắn</label>
                            <div class="content">{!! $description !!}</div>
                        </div>
                    </div>
                @else
                    <div class="no-banner">
                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                        <button class="btn add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                    </div>
                @endif

                <div class="box-update">
                    <form id="form_service_center_description_update" action="{{route('chain-store.setting-update')}}">
                        <div class="update-content">
                            <label class="title-box">Mô tả ngắn</label>
                            <textarea class="value" name="value" id="value" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                            <input type="hidden" name="field" value="">
                            <input type="hidden" name="key" value="fujiyama_service_center_description">
                        </div>
                        <div class="action text-right">
                            <span class="cancel">Hủy bỏ</span>
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end box pannel -->
            <div class="panel box-panel maintain-panel cs-opening">
                <div class="top">
                    <h3 class="title">Hệ thống trung tâm bảo hành</h3>
                    <a class="pull-right add-action add-opening">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        <span>Thêm mới</span>
                    </a>
                </div>
                <?php
                $service_centers = isset($services) ? $services : [];
                ?>
                @if (!empty($service_centers))

                <div class="box box-display display-list">
                    <ul class="list-maintain">
                        <li class="row body-box">
                            <?php $index = 1; ?>
                        @foreach($service_centers as $item)
                            <div class="col-md-6 {{$index}}">
                                <div class="city">
                                    <h4 class="title-city"><i class="fa fa-wrench" aria-hidden="true"></i> <span>Trung tâm bảo hành {{$item['name'] }}</span></h4>
                                    <ul class="list-super">
                                        <li>
                                            <strong>Trung tâm bảo hành - {{ $item['district_name'] }}</strong>
                                            <p>{{ $item['address'] }}</p>
                                            <div class="pull-right">
                                                <a class="tooltip action-delete" data-id="{{$item['id']}}">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Xóa</span>
                                                </a>
                                                <a class="tooltip action-update-opening" data-id="{{$item['id']}}">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Cập nhật</span>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @if ( $index++%2==0 )
                        </li>
                        <li class="row body-box">
                            @endif
                        @endforeach
                        </li>
                    </ul>
                </div>
                @else
                <div class="no-banner add-opening-none">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif

                <div class="box box-update update-list">
                    <form id="form_cs_opening" action="{{ route("service-center.add") }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tên</label>
                                    <input type="text" name="name" class="form-control name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tỉnh/ Thành phố</label>
                                    <div class="wrap_select">
                                        <select name="province_id" class="form-control provinces" onchange="get_districts_by_province(this)" data-destination="#form_cs_opening .districts">
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Quận/ Huyện</label>
                                    <div class="wrap_select">
                                        <select name="district_id" class="form-control districts" onchange="get_wards_by_district(this)" data-destination="#form_cs_opening .wards">
                                            <option value="">Chọn Quận / Huyện</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Phường/ Xã</label>
                                    <div class="wrap_select">
                                        <select name="ward_id" class="form-control wards">
                                            <option value="">Chọn Phường / Xã</option>
                                        </select>
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input type="text" name="address" class="form-control address" placeholder="Nhập địa chỉ">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control phone" placeholder="Nhập số điện thoại">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Thời gian mở cửa</label>
                                    <input type="text" name="opening_time" class="form-control opening_time" placeholder="Nhập thời gian mở cửa">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Embed map</label>
                                    <input type="text" name="embed_map" class="form-control embed_map">
                                </div>
                            </div>    
                        </div>                        
                        <div class="row action pull-right">
                            <span class="cancel">Hủy bỏ</span>
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="hidden" name="id" id="opening_id" value="">
                            <input type="hidden" name="brand_id" value="<?=env('FUJIYAMA_BRAND_ID')?>">
                            <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript" src="/js/service-center.js"></script>
    <script type="text/javascript">
        var _opening = {!! json_encode($service_centers) !!};

        $(document).ready(function() {
            $('textarea.value').ckeditor();

            $('.action-delete').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có chắc muốn xóa!", function () {
                    ajax_loading(true);
                    $.post('{{route('service-center.delete')}}', {
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
        });
    </script>
@endsection