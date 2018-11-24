@extends('layouts.master')

@section('content')
<div class="col-md-">
    <div class="wrap_view view_product create_product">
        <div class="header">
            <h2 class="title">Hệ thống filter <span style="display: none;">/ Giá</span></h2>
        </div>
    </div>
    <section class="table_supply table-category tb_filter">
        <div class="wrap-table table_filter filter_parent">
            <div class="header-panel">
                <h3 class="title_supplier">Danh sách filter</h3>
                <div class="wrap_link">
                    <a href="<?=route('filters.create-filter')?>" class="add-one"><i class="fa fa-plus-circle" aria-hidden="true"></i> <span>Tạo filter mới</span></a>
                    <a href="<?=route('filters.create')?>" class="add-list"><i class="fa fa-list-alt" aria-hidden="true"></i>Tạo danh mục filter mới</a>
                    <a href="<?=route('filters.index')?>"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                </div>
            </div>
            <div class="table-supply products">
                <div class="header_table">
                    <div class="col-md-5">
                        <div class="col-md-4">
                            <span>STT</span>
                        </div>
                        <div class="col-md-8">
                            <span>Filter</span>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-5">
                            <span>Thứ tự </span>
                        </div>
                        <div class="col-md-5">
                            <span>Trạng thái</span>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                </div>
                <ul class="category_product">
            @foreach($objects['data'] as $index => $item)
                    <li class="row">
                        <div class="col-md-5">
                            <div class="col-md-4"><span><?=$index+1?></span></div>
                            <div class="col-md-8">
                                <a href="#"><?=$item['name']?></a>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="col-md-5">
                                <span><?=$item['position']?></span>
                            </div>
                            <div class="col-md-5">
                                <div class="wrapper tooltip">
                                    <input type="checkbox" id="status-{{$index}}" class="slider-toggle" @if ($item['status']) checked @endif/>
                                    <label class="slider-viewport" for="status-{{$index}}" onclick="return false;">
                                        <div class="slider">
                                            <div class="slider-button">&nbsp;</div>
                                            <div class="slider-content left"><span>On</span></div>
                                            <div class="slider-content right"><span>Off</span></div>
                                        </div>
                                    </label>
                                    <span class="tooltiptext">@if ($item['status']) Đã kích hoạt @else Chưa kích hoạt @endif</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a href="<?=route('filters.edit-filter', ['id' => $item['id']])?>" class="tooltip">
                                    <i class="icon-edit" title="Chỉnh sửa">&nbsp</i>
                                    <i class="icon-edit-hover" title="Chỉnh sửa">&nbsp</i>
                                    <span class="tooltiptext">Cập nhật</span>
                                </a>
                                <a href="javascript:void(0)" class="tooltip delete-action" data-id="<?=$item['id']?>">
                                    <i class="icon-delete" title="Xóa">&nbsp</i>
                                    <i class="icon-delete-hover" title="Xóa">&nbsp</i>
                                    <span class="tooltiptext">Xoá</span>
                                </a>
                            </div>
                        </div>
                    </li>
            @endforeach
                </ul>

                @include('includes.paginator')
            </div>
        </div>
    </section>
</div>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(function(){
            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có chắc chắn muốn xóa?", function () {
                    ajax_loading(true);
                    $.post('{{route('filters.delete-filter')}}', {
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
        })
    </script>
@endsection