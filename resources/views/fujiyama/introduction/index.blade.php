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
        <h3 class="title-section">Giới thiệu công ty</h3>
        <div class="panel box-panel">
            {{--'fujiyama_introduction_banner',--}}
            <?php
            if (isset($settings['fujiyama_introduction_banner']) && $settings['fujiyama_introduction_banner']['value']) {
                $image = $settings['fujiyama_introduction_banner']['value'];
                $link = $settings['fujiyama_introduction_banner']['field'];
            } else {
                $image = '';
                $link = '';
            }
            ?>
            <div class="top">
                <h3 class="title">Banner giới thiệu</h3>
                @if ($image)
                <a class="pull-right introduction_banner_update">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    <span>Cập nhật</span>
                </a>
                @endif
            </div>
            @if ($image)
            <div class="banner banner-display">
                <img id="introduction_banner" src="{{$image}}" alt="Banner giới thiệu">
            </div>
            @else
            <div class="introduction_banner_add no-banner">
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
                            <input type="hidden" value="fujiyama_introduction_banner" name="key">
                        </div>
                        <span class="size-note">Kích thước 900 x 300 px</span>
                        <label>Link liên kết</label>
                        <input type="text" value="{{$link}}" name="field" class="form-control field" placeholder="Nhập link liên kết">
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
        {{--'fujiyama_introduction_sales_banner','fujiyama_introduction_sales_description',--}}
        <?php
        if (isset($settings['fujiyama_introduction_sales_banner']) && $settings['fujiyama_introduction_sales_banner']['value']) {
            $image = $settings['fujiyama_introduction_sales_banner']['value'];
            $link = $settings['fujiyama_introduction_sales_banner']['field'];
        } else {
            $image = '';
            $link = '';
        }
        $description = isset($settings['fujiyama_introduction_sales_description']) ? $settings['fujiyama_introduction_sales_description']['value'] : '';
        ?>
        <div class="panel box-panel introduction-sales">
            <div class="top">
                <h3 class="title">thông tin giới thiệu</h3>
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
                <form id="form_introduction_sales_update" action="{{route('setting.update-banner-description')}}">
                <div class="update-content">
                    <label class="title-box">Mô tả ngắn</label>
                    <textarea class="value" name="description[value]" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                    <input type="hidden" name="description[key]" value="fujiyama_introduction_sales_description">
                </div>
                <div class="action text-right">
                    <span class="cancel">Hủy bỏ</span>
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button class="btn button-update">Cập nhật thông tin</button>
                </div>
                </form>
            </div>
        </div>
        <!-- end box pannel -->
        <div class="panel box-panel introduction-commodity">
            {{--'fujiyama_introduction_commodity_description'--}}
            <?php
            $description = isset($settings['fujiyama_introduction_commodity_description']) ? $settings['fujiyama_introduction_commodity_description']['value'] : '';
            ?>
            <div class="top">
                <h3 class="title">thông tin hàng hóa</h3>
                <small> Phần này hiện ở trang <strong>Hệ thống siệu thị</strong> Mục: các mặt hàng đang kinh doanh tại {{config('app.name')}}</small>
                @if ($description || $page_business_lines)
                <a class="pull-right update-action">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    <span>Cập nhật</span>
                </a>
                @endif
            </div>
            @if ($description || $page_business_lines)
            <div class="box-content box-display" >
                <div class="descrip-content">
                    <label class="title-box">Mô tả ngắn</label>
                    <div class="content">{!! $description !!}</div>
                </div>
                <div class="descrip-content">
                    <label class="title-box">Ngành hàng kinh doanh</label>
                    <ul class="list-category">
                    @foreach($page_business_lines as $item)
                        <li class="line-{{$item['id']}}">
                            <div class="col-md-2 name-cate">{{@$item['logo']}}</div>
                            <div class="col-md-3">{{$item['name']}}</div>
                            <div class="col-md-6 link"><a href="{{$item['link']}}" title="{{$item['name']}}">{{$item['link']}}</a></div>
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
                <form id="form_introduction_commodity_update" action="{{route('setting.update-lines-description')}}">
                <div class="update-content">
                    <label class="title-box title-short">Mô tả ngắn</label>
                    <textarea class="value" name="description[value]" placeholder="Nhập thông tin mô tả" rows="5" cols="20">{{$description}}</textarea>
                    <input type="hidden" name="description[key]" value="fujiyama_introduction_commodity_description">
                    <input type="hidden" name="line_type" value="fujiyama_introduction">
                    <label id="description[value]-error" class="error" for="description[value]" style="display: none;">Vui lòng nhập nội dung mô tả</label>
                    <label class="title-box">Ngành hàng kinh doanh</label>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Tên class icon</label>
                            <input type="text" id="image_url_logo" name="lines[0][logo]" class="form-control" placeholder="Nhập tên class icon">
                            <label id="image_url_logo-error" class="error" for="image_url_logo" style="display: none;">Vui lòng nhập tên class icon</label>
                        </div>
                        <div class="col-md-3">
                            <label>Tên ngành hàng</label>
                            <input type="text" id="lines_name" name="lines[0][name]" class="form-control" placeholder="Nhập tên ngành hàng">
                            <label id="lines_name-error" class="error" for="lines_name" style="display: none;">Vui lòng nhập tên ngành hành</label>
                        </div>
                        <div class="col-md-4">
                            <label>Link ngành hàng</label>
                            <input type="text" id="lines_link" name="lines[0][link]" class="form-control" placeholder="Nhập link ngành hàng">
                            <label id="lines_link-error" class="error" for="lines_link"  style="display: none;">Vui lòng nhập link ngành hàng</label>
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0)" class="add-action-line"><i class="fa fa-plus" aria-hidden="true"></i> Thêm ngành hàng</a>
                        </div>
                    </div>
                    <div class="col-md-8 descrip-content">
                        <label class="title-box">Danh sách đã thêm</label>
                        <ul class="list-category">
                        @foreach($page_business_lines as $index => $item)
                            <?php
                                $item['logo'] = isset($item['logo']) ? $item['logo'] : '';
                                ?>
                            <li class="line-{{$item['id']}}">
                                <div class="col-md-2 name-cate">{{@$item['logo']}}</div>
                                <div class="col-md-3">{{$item['name']}}</div>
                                <input type="hidden" value="{{$item['id']}}" name="lines[{{$index+1}}][id]">
                                <input type="hidden" value="{{$item['logo']}}" name="lines[{{$index+1}}][logo]">
                                <input type="hidden" value="{{$item['name']}}" name="lines[{{$index+1}}][name]">
                                <input type="hidden" value="{{$item['link']}}" name="lines[{{$index+1}}][link]">
                                <div class="col-md-6 link">
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
<?php
$js = \App\Helpers\General::get_version_js();
?>
    <script type="text/javascript" src="/js/introduction.js?v=<?=$js?>"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            @if (!$page_business_lines)
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