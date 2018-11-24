@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-banner">
            <h3 class="title-section">Bảo hành, đổi trả</h3>
            <div class="panel box-panel">
                <?php
                if (isset($settings['fujiyama_warranty_banner']) && $settings['fujiyama_warranty_banner']['value']) {
                    $image = $settings['fujiyama_warranty_banner']['value'];
                    $link = $settings['fujiyama_warranty_banner']['field'];
                } else {
                    $image = '';
                    $link = '';
                }
                ?>
                <div class="top">
                    <h3 class="title">Banner giới thiệu</h3>
                    @if ($image)
                        <a class="pull-right warranty_banner_update">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            <span>Cập nhật</span>
                        </a>
                    @endif
                </div>
                @if ($image)
                    <div class="banner banner-display">
                        <img id="warranty_banner" src="{{$image}}" alt="">
                    </div>
                @else
                    <div class="warranty_banner_add no-banner">
                        <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                        <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
                    </div>
                @endif
                <div class="banner-update">
                    <form id="form_warranty_banner_update" action="{{route('chain-store.setting-update')}}">
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
                            <input type="hidden" value="fujiyama_warranty_banner" name="key">
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
            $description = isset($settings['fujiyama_warranty_description']) ? $settings['fujiyama_warranty_description']['value'] : '';
            ?>
            <div class="panel box-panel warranty-description">
                <div class="top">
                    <h3 class="title">Chính sách bảo hành - đổi trả</h3>
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
                    <form id="form_warranty_description_update" action="{{route('chain-store.setting-update')}}">
                        <div class="update-content">
                            <label class="title-box">Mô tả ngắn</label>
                            <textarea class="value" name="value" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                            <input type="hidden" name="field" value="">
                            <input type="hidden" name="key" value="fujiyama_warranty_description">
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

    <script type="text/javascript" src="/js/warranty.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection