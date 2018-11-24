@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-banner">
            <h3 class="title-section">Hướng dẫn mua hàng</h3>
            <div class="panel box-panel">
                <?php
                if (isset($settings['shopping_guide_banner']) && $settings['shopping_guide_banner']['value']) {
                    $image = $settings['shopping_guide_banner']['value'];
                    $link = $settings['shopping_guide_banner']['field'];
                } else {
                    $image = '';
                    $link = '';
                }
                ?>
                <div class="top">
                    <h3 class="title">Banner giới thiệu</h3>
                    @if ($image)
                        <a class="pull-right shopping_guide_banner_update">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Cập nhật</span>
                        </a>
                    @endif
                </div>
                @if ($image)
                    <div class="banner banner-display">
                        <img id="shopping_guide_banner" src="{{$image}}" alt="">
                    </div>
                @else
                    <div class="shopping_guide_banner_add no-banner">
                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                        <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                    </div>
                @endif
                <div class="banner-update">
                    <form id="form_shopping_guide_banner_update" action="{{route('chain-store.setting-update')}}">
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
                            <input type="hidden" value="shopping_guide_banner" name="key">
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
            if (isset($settings['shopping_guide_banner_2nd']) && $settings['shopping_guide_banner_2nd']['value']) {
                $image = $settings['shopping_guide_banner_2nd']['value'];
                $link = $settings['shopping_guide_banner_2nd']['field'];
            } else {
                $image = '';
                $link = '';
            }
            $description = isset($settings['shopping_guide_description']) ? $settings['shopping_guide_description']['value'] : '';
            ?>
            <div class="panel box-panel shopping-guide-2nd">
                <div class="top">
                    <h3 class="title">Hướng dẫn mua hàng</h3>
                    @if ($image)
                        <a class="pull-right update-action">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Cập nhật</span>
                        </a>
                    @endif
                </div>
                @if ($image)
                    <div class="box-content box-display">
                        <div class="box-img">
                            <img src="{{$image}}">
                        </div>
                        <div class="descrip-content">
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
                    <form id="form_shopping_guide_2nd_update" action="{{route('chain-store.setting-update-banner-description')}}">
                        <div class="update-banner">
                            <div class="col-md-9">
                                <img id="preview-file-description" class="preview-banner" src="{{$image}}" alt="">
                            </div>
                            <div class="col-md-3">
                                <ul class="wrap_btn">
                                    <li>
                                        <a href="#" class="btn-loadfile browse-image" data-target="image_shopping_guide_banner_2nd_url">
                                            <i class="icon-browser">&nbsp;</i>
                                            <span>Browse ...</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="show_file">
                                    <span class="file_name"></span>
                                    <input type="hidden" value="{{$image}}" name="banner_value" id="image_shopping_guide_banner_2nd_url" data-preview="#preview-file-description">
                                    <input type="hidden" value="shopping_guide_banner_2nd" name="banner_key">
                                </div>
                                <span class="size-note">Kích thước: <b>900 x 300 px</b></span>
                                <span class="size-note">Dung lượng: <b>500kb</b></span>
                                <span class="size-note">Định dạng: <b>jpg, png</b></span>
                                <label>Link banner</label>
                                <input type="text" name="field" value="{{$link}}" class="form-control" placeholder="Nhập link banner">
                            </div>
                        </div>
                        <div class="update-content">
                            <label class="title-box">Mô tả ngắn</label>
                            <textarea class="value" name="description_value" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                            <input type="hidden" name="description_key" value="shopping_guide_description">
                        </div>
                        <div class="action text-right">
                            <span class="cancel">Hủy bỏ</span>
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
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

    <script type="text/javascript" src="/js/shopping-guide.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection