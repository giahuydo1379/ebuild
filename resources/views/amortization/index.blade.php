@extends('layouts.master')

@section('content')
    <style>
        .add-action-line {
            position: relative;
            top: 32px;
            text-decoration: underline;
            color: #ed1b24;
            font-weight: bold;
        }
        #form_amortization_line .list-category {
            padding-left: 0;
            color: #818181;
        }
        #form_amortization_line .list-category li {
            display: inline-block;
            width: 100%;
            padding: 7px 15px;
            position: relative;
        }
        #form_amortization_line .col-md-3.name-cate:before {
            content: "\f111";
            font: normal normal normal 14px/1 FontAwesome;
            position: absolute;
            top: 5px;
            left: 0;
            font-size: 8px;
            display: inline-block;
        }
    </style>
    <div class="col-md-">
        <section class="section section-banner">
            <h3 class="title-section">Giới thiệu công ty</h3>
            <div class="panel box-panel">
                <?php
                if (isset($settings['amortization_banner']) && $settings['amortization_banner']['value']) {
                    $image = $settings['amortization_banner']['value'];
                    $link = $settings['amortization_banner']['field'];
                } else {
                    $image = '';
                    $link = '';
                }
                ?>
                <div class="top">
                    <h3 class="title">Banner giới thiệu</h3>
                    @if ($image)
                        <a class="pull-right amortization_banner_update">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Cập nhật</span>
                        </a>
                    @endif
                </div>
                @if ($image)
                    <div class="banner banner-display">
                        <img id="amortization_banner" src="{{$image}}" alt="Banner giới thiệu">
                    </div>
                @else
                    <div class="amortization_banner_add no-banner">
                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                        <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                    </div>
                @endif
                <div class="banner-update">
                    <form id="form_banner_update" action="{{route('chain-store.setting-update')}}">
                        <div class="col-md-9">
                            <img class="preview-banner" src="{{$image}}" alt="Banner giới thiệu">
                        </div>
                        <div class="col-md-3">
                            <ul class="wrap_btn">
                                <li>
                                    <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_url">
                                        <i class="icon-browser">&nbsp;</i>
                                        <span>Browse ...</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="show_file">
                                <span class="file_name"></span>
                                <input type="hidden" value="{{$image}}" name="value" id="image_url" data-preview="#form_banner_update .preview-banner">
                            </div>
                            <span class="size-note">Kích thước 900 x 300 px</span>
                            <label>Link liên kết</label>
                            <input type="text" value="{{$link}}" name="field" class="form-control field" placeholder="Nhập link liên kết">
                            <input type="hidden" value="amortization_banner" name="key">
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

            {{--['amortization_bank','amortization_partner','amortization_line']--}}
            <div class="panel box-panel Panel amortization-bank-partner">
                <?php
                $amortization_bank = isset($page_business_lines['amortization_bank']) ? $page_business_lines['amortization_bank'] : [];
                $amortization_partner = isset($page_business_lines['amortization_partner']) ? $page_business_lines['amortization_partner'] : [];
                ?>
                <div class="top">
                    <h3 class="title">Logo ngân hàng, đối tác</h3>
                    @if ($amortization_bank || $amortization_partner)
                    <a class="pull-right update-action">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span>Cập nhật</span>
                    </a>
                    @endif
                </div>

                @if ($amortization_bank || $amortization_partner)
                <div class="box-content box-display">
                    <div class="list-bank">
                        <label class="title-box">Logo ngân hàng</label>
                        <ul class="list-logo">
                        @foreach($amortization_bank as $item)
                            <li><img src="{{$item['name']}}" alt="logo"></li>
                        @endforeach
                        </ul>
                    </div>
                    <div class="list-partner">
                        <label class="title-box">Logo đối tác</label>
                        <ul class="list-logo">
                        @foreach($amortization_partner as $item)
                            <li><img src="{{$item['name']}}" alt="logo"></li>
                        @endforeach
                        </ul>
                    </div>
                </div>
                @else
                <div class="no-banner">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif

                <div class="box-update">
                    <form id="form_amortization_bank_partner" action="{{route('amortization.update-bank-partner')}}">
                    <div class="part_create">
                        <h3 class="title_supplier">Hình ảnh sản phẩm</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Logo ngân hàng</b></p>
                                <div class="image-upload">
                                    <label for="file-input" class="browse-image" data-target="image_url_bank">
                                        <img class="preview-banner-bank" src="/html/assets/images/img_upload.png" alt="your image">
                                        <div class="wrap-bg" style="display: none;">
                                            <img class="display " src="/html/assets/images/icon-camera.png" alt="your image" >
                                        </div>
                                    </label>
                                </div>
                                <div class="infor-img">
                                    <label>Link banner</label>
                                    <input type="text" id="link_bank" class="form-control" placeholder="Nhập link banner">
                                    <input type="hidden" value="{{$image}}" id="image_url_bank" data-preview="#form_amortization_bank_partner .preview-banner-bank">
                                    <label class="bank error" style="display: none;">Chọn banner và nhập link liên kết</label>
                                    <span>Kích thước: <b>250 x 250 px</b></span>
                                    <div class="action">
                                        <button type="button" class="btn button-update add-bank">Thêm</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="wrap_images image-upload">
                                    <output id="list" class="amortization_bank">
                                        @foreach($amortization_bank as $item)
                                            <span><img src="{{$item['name']}}"><input type="hidden" name="ids[]" value="{{$item['id']}}"></span>
                                        @endforeach
                                    </output>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Logo đối tác</b></p>
                                <div class="image-upload">
                                    <label for="file-input" class="browse-image" data-target="image_url_partner">
                                        <img class="preview-banner-partner" src="/html/assets/images/img_upload.png" alt="your image">
                                        <div class="wrap-bg" style="display: none;">
                                            <img class="display " src="/html/assets/images/icon-camera.png" alt="your image" >
                                        </div>
                                    </label>
                                </div>
                                <div class="infor-img">
                                    <label>Link banner</label>
                                    <input type="text" id="link_partner" class="form-control" placeholder="Nhập link banner">
                                    <input type="hidden" value="{{$image}}" name="value" id="image_url_partner" data-preview="#form_amortization_bank_partner .preview-banner-partner">
                                    <label class="partner error" style="display: none;">Chọn banner và nhập link liên kết</label>
                                    <span>Kích thước: <b>250 x 250 px</b></span>
                                    <div class="action">
                                        <button type="button" class="btn button-update add-partner">Thêm</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="wrap_images image-upload">
                                    <output id="list" class="amortization_partner">
                                        @foreach($amortization_partner as $item)
                                            <span><img src="{{$item['name']}}"><input type="hidden" name="ids[]" value="{{$item['id']}}"></span>
                                        @endforeach
                                    </output>
                                </div>
                            </div>
                        </div>
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

            <div class="panel box-panel Panel amortization-info">
                <?php
                $description = isset($settings['amortization_info']) ? $settings['amortization_info']['value'] : '';
                ?>
                <div class="top">
                    <h3 class="title">Giới thiệu chương trình trả góp</h3>
                    @if ($description)
                    <a class="pull-right update-action">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span>Cập nhật</span>
                    </a>
                    @endif
                </div>
                @if ($description)
                <div class="box-content box-display infor-tragop">
                    <div class="scrollbar">
                        <label class="title-box">Thông tin về chương trình trả góp</label>
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
                    <form id="form_amortization_info_update" action="{{route('setting.update-description')}}">
                        <div class="update-content">
                            <label class="title-box">Mô tả ngắn</label>
                            <textarea class="value" name="value" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                            <label id="value-error" class="error" for="value" style="display: none;">Vui lòng nhập thông tin mô tả</label>
                        </div>
                        <div class="action text-right">
                            <input type="hidden" name="key" value="amortization_info">
                            <span class="cancel">Hủy bỏ</span>
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end box pannel -->

            <div class="panel box-panel box-line Panel amortization-process-buy">
                <?php
                if (isset($settings['amortization_process_buy']) && $settings['amortization_process_buy']['value']) {
                    $image = $settings['amortization_process_buy']['value'];
                    $link = $settings['amortization_process_buy']['field'];
                } else {
                    $image = '';
                    $link = '';
                }
                ?>
                <div class="top">
                    <h3 class="title">Quy trình mua hàng trả góp</h3>
                    @if ($image)
                    <a class="pull-right update-action">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span>Cập nhật</span>
                    </a>
                    @endif
                </div>
                @if ($image)
                <div class="box-content box-display">
                    <label class="title-box">Banner</label>
                    <div class="box-img">
                        <img src="{{$image}}">
                    </div>
                </div>
                @else
                <div class="no-banner">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif

                <div class="box-update" >
                    <form id="form_amortization_process_buy_update" action="{{route('chain-store.setting-update')}}">
                    <div class="update-banner">
                        <div class="col-md-9">
                            <img class="preview-banner" src="{{$image}}" alt="Banner quy trình trả góp">
                        </div>
                        <div class="col-md-3">
                            <ul class="wrap_btn">
                                <li>
                                    <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_url_buy">
                                        <i class="icon-browser">&nbsp;</i>
                                        <span>Browse ...</span>
                                        <input type="file" name="newfile">
                                    </a>
                                </li>
                            </ul>
                            <div class="show_file">
                                <span class="file_name"></span>
                                <input type="hidden" value="{{$image}}" name="value" id="image_url_buy" data-preview="#form_amortization_process_buy_update .preview-banner">
                            </div>
                            <span class="size-note">Kích thước: <b>900 x 300 px</b></span>
                            <span class="size-note">Dung lượng: <b>500kb</b></span>
                            <span class="size-note">Định dạng: <b>jpg, png</b></span>
                            <label>Link banner</label>
                            <input type="text" value="{{$link}}" name="field" class="form-control" placeholder="Nhập link banner">
                        </div>
                    </div>
                    <div class="action text-right">
                        <input type="hidden" value="amortization_process_buy" name="key">
                        <span class="cancel Cancel">Hủy bỏ</span>
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="submit" class="btn button-update">Cập nhật thông tin</button>
                    </div>
                    </form>
                </div>
            </div>
            <!-- end box pannel -->

            <div class="panel box-panel Panel amortization-line">
                <?php
                $amortization_line = isset($page_business_lines['amortization_line']) ? $page_business_lines['amortization_line'] : [];
                ?>
                <div class="top">
                    <h3 class="title">ngành hàng áp dụng trả góp</h3>
                    @if ($amortization_line)
                    <a class="pull-right update-action">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span>Cập nhật</span>
                    </a>
                    @endif
                </div>

                @if ($amortization_line)
                <div class="box-content box-display">
                    <div class="descrip-content">
                        <label class="title-box">Ngành hàng kinh doanh</label>
                        <ul class="list-category">
@foreach($amortization_line as $item)
                            <li>
                                <div class="col-md-3 name-cate">{{$item['name']}}</div>
                                <div class="col-md-8 link"><a href="{{$item['link']}}" title="{{$item['name']}}">{{$item['link']}}</a></div>
                                <div class="col-md-1 text-right pull-right">
                                    <a class="tooltip action-delete-line" data-id="{{$item['id']}}">
                                        <i class="fa fa-times action-delete" aria-hidden="true"></i>
                                        <span class="tooltiptext">Xóa</span>
                                    </a>
                                </div>
                            </li>
@endforeach

                        </ul>
                    </div>
                </div>
                @else
                <div class="no-banner">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif

                <div class="box-update">
                    <form id="form_amortization_line" action="{{route('setting.update-lines-description')}}">
                    <div class="update-content">
                        <label class="title-box">Ngành hàng kinh doanh</label>
                        <input type="hidden" name="line_type" value="amortization_line">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tên ngành hàng</label>
                                <input type="text" id="lines_name" name="lines[0][name]" class="form-control" placeholder="Nhập tên ngành hàng">
                                <label id="lines_name-error" class="error" for="lines_name" style="display: none;">Vui lòng nhập tên ngành hành</label>
                            </div>
                            <div class="col-md-4">
                                <label>Link ngành hàng</label>
                                <input type="text" id="lines_link" name="lines[0][link]" class="form-control" placeholder="Nhập link ngành hàng">
                                <label id="lines_link-error" class="error" for="lines_link"  style="display: none;">Vui lòng nhập link ngành hàng</label>
                            </div>
                            <div class="col-md-4">
                                <a href="javascript:void(0)" class="add-action-line"><i class="fa fa-plus" aria-hidden="true"></i> Thêm ngành hàng</a>
                            </div>
                        </div>
                        <div class="col-md-8 descrip-content" style="margin-top: 10px;">
                            <label class="title-box">Danh sách đã thêm</label>
                            <ul class="list-category">
                                @foreach($amortization_line as $index => $item)
                                    <li class="line-{{$item['id']}}">
                                        <div class="col-md-3 name-cate">{{$item['name']}}</div>
                                        <input type="hidden" value="{{$item['id']}}" name="lines[{{$index+1}}][id]">
                                        <input type="hidden" value="{{$item['name']}}" name="lines[{{$index+1}}][name]">
                                        <input type="hidden" value="{{$item['link']}}" name="lines[{{$index+1}}][link]">
                                        <div class="col-md-8 link">
                                            <a href="{{$item['link']}}" title="{{$item['name']}}">{{$item['link']}}</a>
                                        </div>
                                        <div class="col-md-1 text-right pull-right">
                                            <a class="tooltip action-delete-uline">
                                                <i class="fa fa-times action-delete" aria-hidden="true"></i>
                                                <span class="tooltiptext">Xóa</span>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="action text-right">
                        <span class="cancel Cancel">Hủy bỏ</span>
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="submit" class="btn button-update BtnUpdate">Cập nhật thông tin</button>
                    </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript" src="/js/amortization.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if (!$amortization_line)
            add_rule_line();
            @endif

            $('.action-delete-line').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, ngành hàng này?", function () {
                    ajax_loading(true);
                    $.post('{{route('page-business-line.delete')}}', {
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