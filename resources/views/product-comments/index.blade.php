@extends('layouts.master')

@section('after_styles')
<style type="text/css">
    .view-detail{
        cursor: pointer;
        padding-right: 5px;
    }
    .TitleDisplay{
        cursor: pointer;
    }
</style>
@endsection
@section('content')
    <div class="col-md-">
        <section class="section section-article BannerAct">
            <h3 class="title-section">Bình luận sản phẩm</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách bình luận</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                </div>
                <?php
                $_objects = [];
                ?>
                @if ($objects['total'])
                    <div class="banner banner-display">
                        <div class="table-display">
                            <div class="header_table">
                                <div class="col-md-5">
                                    <div class="col-md-1">
                                        <span>STT</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>Họ tên</span>
                                    </div>
                                    <div class="col-md-5">
                                        <span>Email</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Rating</span>
                                    </div>
                                </div>
                                <div class="col-md-7 no-padding ">
                                    <div class="col-md-3 no-padding">
                                        <span>Sản phẩm </span>
                                    </div>
                                    <div class="col-md-5">
                                        <span>Bình luận</span>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Trạng thái</span>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                            </div>
                            <ul class="category_product">
                                @foreach($objects['data'] as $index => $item)
                                    <?php
                                    $_objects[$item['id']] = $item;
                                    ?>
                                    <li class="row">
                                        <div class="col-md-5">
                                            <div class="col-md-1">
                                                <span>{{$index+1}}</span>
                                            </div>
                                            <div class="col-md-4">
                                                <span>{{$item['fullname']}}</span>
                                            </div>
                                            <div class="col-md-5">
                                                <span>{{$item['email']}}</span>
                                            </div>
                                            <div class="col-md-2">
                                                <span>{{$item['rating']}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-7 no-padding ">
                                            <div class="col-md-3 no-padding">
                                                <span>{{$item['product_name']}}</span>
                                            </div>
                                            <div class="col-md-5">
                                                <span>{{$item['comment']}}</span>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="wrapper tooltip">
                                                    <input type="checkbox" id="status-{{$item['id']}}" class="slider-toggle" @if ($item['status']) checked @endif/>
                                                    <label class="slider-viewport change-status" for="status-{{$index}}" data-id={{$item['id']}}>
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
                                                <a class="tooltip view-detail" data-id="{{$item['id']}}">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Xem chi tiết</span>
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
                @endif
                <div class="banner-update">
                    <div class="col-md-12 content_header">
                        <h5>Chi tiết bình luận</h5>
                    </div>
                    <div class="col-md-12 content_title">
                        
                    </div>
                    <div class="col-md-12 content_comment">
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        var _objects = {!! json_encode($_objects) !!};
        console.log(_objects);
        $(document).ready(function() {
            init_datepicker('.datepicker');

            $('input.slider-toggle').each(function (key, msg) {
                if ($(this).is(':checked')) {
                    $(this).closest('div').find('.tooltiptext').text('Đã kích hoạt');
                } else {
                    $(this).closest('div').find('.tooltiptext').text('Chưa kích hoạt');
                }
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, khách hàng này không?", function () {
                    ajax_loading(true);
                    $.post('{{route('product-comments.delete')}}', {
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

            $(document).on('click','.view-detail',function(){
                id = $(this).data('id');                
                console.log(_objects[id]);
                $('.banner-update .content_title').html(_objects[id].comment.title);
                $('.banner-update .content_comment').html(_objects[id].comment.comment);
                $('.banner-display').slideUp();
                $('.banner-update').slideDown();
            })

            .on('click','.TitleDisplay',function(){
                $('.banner-update .content_title').html('');
                $('.banner-update .content_comment').html('');
                $('.banner-display').slideDown();
                $('.banner-update').slideUp();
            })

            .on('click','.change-status',function(){
                var id = $(this).data('id');                
                var obj = this;
                var status = _objects[id].status;
                confirm_delete("Bạn có chắc muốn thay đổi trạng thái của bình luận này?", function () {
                    ajax_loading(true);
                    $.post('{{route('product-comments.change-status')}}', {
                        id: id,
                        status: status,
                        _token: '{!! csrf_token() !!}'
                    }, function(data){
                        ajax_loading(false);
                        alert_success(data.msg, function () {
                            if(data.rs == 1)
                            {
                                if(status == 1){
                                    _objects[id].status = 0;
                                    $('#status-'+id).removeAttr( "checked" );
                                    console.log(_objects[id].status);
                                }else if(status == 0){
                                    _objects[id].status = 1;
                                    $('#status-'+id).prop('checked',true);
                                    console.log(_objects[id].status);
                                }
                            }
                        });
                    });
                },'icon_none');
                return false;
            });
        });
    </script>
@endsection