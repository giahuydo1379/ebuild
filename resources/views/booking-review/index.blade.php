@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <section class="section section-news BannerAct">
            <h3 class="title-section">Đánh giá đơn hàng</h3>
            <div class="panel box-panel Panel">
                <div class="top">
                    <h3 class="title TitleCreate" @if ($objects['total']) style="display: none;" @endif>Danh sách đánh giá</h3>
                    <h3 class="title TitleUpdate" style="display: none;">Cập nhật gói dịch vụ</h3>
                    <h3 class="title TitleDisplay" @if (!$objects['total']) style="display: none;" @endif>Danh sách đánh giá</h3>
                    <a href="javascript:void(0)" class="pull-right link BackAction" onclick="$('#form_update .cancel').click();" style="display: none;">
                        <i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>

                    <a href="javascript:void(0)" class="pull-right link add-action" @if (!$objects['total']) style="display: none;" @endif>
                        <!-- <i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới -->
                    </a>
                </div>

                <?php
                $_objects = [];
                ?>
                @if ($objects['total'])
                <div class="banner banner-display">
                    <div class="table-display">
                        <div class="header_table">
                            <div class="col-md-6">
                                <div class="col-md-1">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Khách hàng</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Đơn hàng</span>
                                </div>
                                <div class="col-md-2">
                                    <span>Rating</span>
                                </div>
                                <div class="col-md-2 no-padding">
                                    Hình ảnh
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    <span>Tiêu đề</span>
                                </div>                                
                                <div class="col-md-7">
                                    <span>Nội dung</span>
                                </div>
                                <div class="col-md-1 no-padding">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                            @foreach($objects['data'] as $index => $item)                                
                            <li class="row">
                                <div class="col-md-6">
                                    <div class="col-md-1">
                                        <span><?=$index+1?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <span><?=$item['fullname']?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span><?=$item['code']?></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span><?=$item['rating']?></span>
                                    </div>
                                    <div class="col-md-2 no-padding">
                                        <a class="show_image"><img src="<?=$item['image_url'].$item['image_location']?>"></a>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="col-md-4">
                                        <?=$item['title']?>
                                    </div>
                                    <div class="col-md-7">
                                        <?=$item['comment']?>
                                    </div>
                                    <div class="col-md-1">
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
                @else
                <div class="no-banner">
                    <p>Chưa có đánh giá!</p>
                </div>
                @endif
            </div>
            <div id="ModalImage" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="//placehold.it/1000x600" class="img-responsive">
                    </div>
                </div>
              </div>
            </div>
        </section>
    </div>
@endsection
@section('after_styles')
<style type="text/css">
  .show_image{
        cursor: pointer;
  }
</style>
@endsection
@section('after_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('.show_image').on('click',function(){
                $('#ModalImage .modal-body').html($(this).html());
                $('#ModalImage').modal('show');
            });

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa, đánh giá này?", function () {
                    ajax_loading(true);
                    $.post('{{route($controllerName.'.delete')}}', {
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