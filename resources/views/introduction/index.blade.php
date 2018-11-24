@extends('layouts.master')
{{--$keys = [--}}
{{--];--}}
@section('content')
    <style>
        .add-action-line {
            position: relative;
            top: 32px;
            text-decoration: underline;
            color: #ed1b24;
            font-weight: bold;
        }
        #form_introduction_commodity_update .list-category {
            padding-left: 0;
            color: #818181;
        }
        #form_introduction_commodity_update .list-category li {
            display: inline-block;
            width: 100%;
            padding: 7px 15px;
            position: relative;
        }
        #form_introduction_commodity_update .col-md-3.name-cate:before {
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
        <h3 class="title-section"><?=$title?></h3>
        <div class="panel box-panel">
            <?php
            if (isset($object['image_location'])) {
                $image = $object['image_location'];
                $image_url = $object['image_url'];
                $link = $object['image_link'];
            } else {
                $image = '';
                $image_url = '';
                $link = '';
            }
            ?>
            <div class="top">
                <h3 class="title">Banner</h3>
                @if ($image)
                <a class="pull-right introduction_banner_update">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    <span>Cập nhật</span>
                </a>
                @endif
            </div>
            @if ($image)
            <div class="banner banner-display">
                <img id="introduction_banner" src="{{$image_url.$image}}" alt="Banner giới thiệu">
            </div>
            @else
            <div class="introduction_banner_add no-banner">
                <p>Chưa có thông tin, bạn vui lòng thêm mới!</p>
                <button class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm mới</button>
            </div>
            @endif
            <div class="banner-update">
                <form id="form_banner_update" action="{{route('introduction.update-image')}}">
                    <div class="col-md-9">
                        <img class="preview-banner" src="{{$image_url.$image}}" alt="Banner giới thiệu">
                    </div>
                    <div class="col-md-3">
                        <ul class="wrap_btn">
                            <li>
                                <a href="javascript:void(0)" class="btn-loadfile browse-image" data-target="image_location">
                                    <i class="icon-browser">&nbsp;</i>
                                    <span>Browse ...</span>
                                </a>
                            </li>
                        </ul>
                        <div class="show_file">
                            <span class="file_name"></span>
                            <input type="hidden" value="{{$image}}" name="image_location" id="image_location"
                                   data-preview="#form_banner_update .preview-banner" data-url="#form_banner_update #image_url">
                            <input type="hidden" value="<?=$image_url?>" name="image_url" id="image_url">
                            <input type="hidden" value="<?=$slug?>" name="slug">
                            <input type="hidden" value="<?=@$object['id']?>" name="id">
                        </div>
                        <span class="size-note">Kích thước 900 x 300 px</span>
                        <label>Link liên kết</label>
                        <input type="text" value="{{$link}}" name="image_link" class="form-control field" placeholder="Nhập link liên kết">
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
        $description = isset($object['content']) ? $object['content'] : '';
        ?>
        <div class="panel box-panel introduction-sales">
            <div class="top">
                <h3 class="title">thông tin</h3>
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
                <form id="form_update_content" action="{{route('introduction.update-content')}}">
                <div class="update-content">
                    <label class="title-box">Mô tả ngắn</label>
                    <textarea class="value" name="content" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                </div>
                <div class="action text-right">
                    <span class="cancel">Hủy bỏ</span>
                    <input type="hidden" value="<?=$slug?>" name="slug">
                    <input type="hidden" value="<?=@$object['id']?>" name="id">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button class="btn button-update">Cập nhật thông tin</button>
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
<?php
$js = \App\Helpers\General::get_version_js();
?>
    <script type="text/javascript" src="/js/introduction.js?v=<?=$js?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection