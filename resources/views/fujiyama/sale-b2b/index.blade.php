@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-banner BannerAct">
            <h3 class="title-section">Hợp tác bán hàng</h3>
            <div class="panel box-panel sale-b2b-banner">
                <?php
                if (isset($settings['fujiyama_sale_b2b_banner']) && $settings['fujiyama_sale_b2b_banner']['value']) {
                    $image = $settings['fujiyama_sale_b2b_banner']['value'];
                    $link = $settings['fujiyama_sale_b2b_banner']['field'];
                } else {
                    $image = '';
                    $link = '';
                }
                ?>
                <div class="top">
                    <h3 class="title">Banner giới thiệu</h3>
                    @if ($image)
                    <a class="pull-right update-action">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span>Cập nhật</span>
                    </a>
                    @endif
                </div>
                @if ($image)
                <div class="banner banner-display">
                    <img src="{{$image}}" alt="banner">
                </div>
                @else
                <div class="no-banner">
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif
                <div class="banner-update">
                    <form id="form_sale_b2b_banner" action="{{route('chain-store.setting-update')}}">
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
                            <input type="hidden" value="{{$image}}" name="value" id="image_url" data-preview="#form_sale_b2b_banner .preview-banner">
                        </div>
                        <span class="size-note">Kích thước 900 x 300 px</span>
                        <label>Link banner</label>
                        <input type="text" value="{{$link}}" name="field" class="form-control field" placeholder="Nhập link liên kết">
                        <input type="hidden" value="fujiyama_sale_b2b_banner" name="key">
                        <div class="action pull-right">
                            <span class="cancel">Hủy bỏ</span>
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button class="btn button-update BtnUpdate">Cập nhật thông tin</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </section>
        <section class="section section-infor-listsuper ActionList">
            <!-- end box pannel -->
            <div class="panel box-panel sale-b2b-info">
                <?php
                $description = isset($settings['fujiyama_sale_b2b_description']) ? $settings['fujiyama_sale_b2b_description']['value'] : '';
                ?>
                <div class="top">
                    <h3 class="title">Giới thiệu chương trình bán hàng b2b</h3>
                    @if ($description)
                    <a class="pull-right update-action">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span>Cập nhật</span>
                    </a>
                    @endif
                </div>
                @if ($description)
                <div class="box-content box-display">
                    <div class="descrip-content">
                        <label class="title-box">Giới thiệu chương trình</label>
                        <div class="content">{!! $description !!}</div>
                    </div>
                </div>
                @else
                <div class="no-banner" >
                    <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                    <button class="btn add-action"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                </div>
                @endif

                <div class="box-update">
                    <form id="form_sale_b2b_info_update" action="{{route('setting.update-description')}}">
                    <div class="update-content">
                        <label class="title-box title-short">Giới thiệu chương trình</label>
                        <textarea class="value" name="value" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                        <label id="value-error" class="error" for="value" style="display: none;">Vui lòng nhập thông tin mô tả</label>
                    </div>
                    <div class="action text-right">
                        <span class="cancel">Hủy bỏ</span>
                        <input type="hidden" name="key" value="fujiyama_sale_b2b_description">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="submit" class="btn button-update BtnUpdate">Cập nhật thông tin</button>
                    </div>
                    </form>
                </div>
            </div>
            <div class="panel box-panel sale-b2b-logo">
                <div class="top">
                    <h3 class="title">Logo doanh nghiệp bán hàng b2b</h3>
                    @if ($page_business_lines)
                    <a class="pull-right update-action">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span>Cập nhật</span>
                    </a>
                    @endif
                </div>
                @if ($page_business_lines)
                <div class="box-content box-display">
                    <div class="list-bank">
                        <label class="title-box">Logo doanh nghiệp</label>
                        <ul class="list-logo">
                            @foreach($page_business_lines as $item)
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
                    <form id="form_sale_b2b_logo" action="{{route('sale-b2b.update-logo')}}">
                    <div class="part_create">
                        <h3 class="title_supplier">Hình ảnh sản phẩm</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <p><b>Logo doanh nghiệp</b></p>
                                <div class="image-upload">
                                    <div class="image-upload">
                                        <label for="file-input" class="browse-image" data-target="image_url_logo">
                                            <img class="preview-banner-logo" src="/html/assets/images/img_upload.png" alt="logo">
                                            <div class="wrap-bg" style="display: none;">
                                                <img class="display " src="/html/assets/images/icon-camera.png" alt="logo">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="infor-img">
                                    <label>Link banner</label>
                                    <input type="text" id="link_logo" class="form-control" placeholder="Nhập link banner">
                                    <input type="hidden" value="{{$image}}" id="image_url_logo" data-preview="#form_sale_b2b_logo .preview-banner-logo">
                                    <label class="logo error" style="display: none;">Chọn banner và nhập link liên kết</label>
                                    <span>Kích thước: <b>250 x 250 px</b></span>
                                    <div class="action">
                                        <button type="button" class="btn button-update add-logo">Thêm</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="wrap_images image-upload">
                                    <output id="list" class="sale-2b2_logo">
                                        @foreach($page_business_lines as $item)
                                            <span><img src="{{$item['name']}}"><input type="hidden" name="ids[]" value="{{$item['id']}}"></span>
                                        @endforeach
                                    </output>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="action text-right">
                        <span class="cancel Cancel">Hủy bỏ</span>
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="type" value="fujiyama_sale_b2b">
                        <button class="btn button-update BtnUpdate">Cập nhật thông tin</button>
                    </div>
                    </form>
                </div>
            </div>
            <!-- end box pannel -->
        </section>
    </div>
@endsection

@section('after_scripts')
    @include('includes.js-ckeditor')

    <script type="text/javascript" src="/js/sale-b2b.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection