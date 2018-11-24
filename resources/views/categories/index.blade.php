@extends('layouts.master')

@section('content')
    <div class="col-md- products">
        <div class="wrap_view ">
            <div class="header ">
                <h2 class="title title-path">Quản lý danh mục sản phẩm <?=!empty($path)?'/'.$path:''?></h2>
            </div>
        </div>
        <section class="table_supply table-category">
            <div class="panel box-panel">
                <div class="top">
                    <h3 class="title Title1" style="">Danh sách danh mục</h3>
                    <a href="<?=route('categories.create')?>" class="pull-right link add" style=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
                    @if(!empty($categoryCurrent))
                    <a href="<?=route('categories.index')?>?parent_id=<?=$categoryCurrent['parent_id']?>" id="" class="pull-right link"><i class="fa fa-reply" aria-hidden="true"></i> Quay lại</a>
                    @endif
                </div>
                <div class="wrap-table">
                    <div class="table-supply ">
                        <div class="header_table">
                            <div class="col-md-7">
                                <div class="col-md-2">
                                    <span>ID</span>
                                </div>
                                <div class="col-md-7">
                                    <span>Tên danh mục</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Danh mục cha</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="col-md-4">
                                    <span>Thứ tự hiển thị</span>
                                </div>
                                <div class="col-md-4">
                                    <span>Trạng thái</span>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                        <ul class="category_product">
                            <li class="row panel">
                                <form class="row-filter FilterRow text-center">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-5">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4 no-padding">
                                            <div class="wrap_select">
                                                {!! Form::select("status-filter", \App\Helpers\General::get_status_category_options(),
                                                    @$params['status-filter'], ['id' => 'status_filter', 'class' => 'form-control']) !!}
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-left submit">
                                            <button type="submit" value="" class="btn btn_primary">Lọc</button>
                                        </div>
                                    </div>
                                </form>
                            </li>
                        @if(!empty($objects['data']))
                            @foreach($objects['data'] as $item)
                            <li class="row">
                                <div class="col-md-7">
                                    <div class="col-md-2"><span><?=$item['category_id']?></span></div>
                                    <?php
                                    $id_path = explode('/', $item['id_path']);
                                    $id_path = max(count($id_path)-1, 0)*20;
                                    ?>
                                    <div class="col-md-7" style="padding-left: {{$id_path}}px;">
                                        <a href="<?=route('categories.index')?>?parent_id=<?=$item['category_id']?>">
                                            <i class="fa fa-folder-o" aria-hidden="true"></i>
                                            <i class="fa fa-folder-open-o" aria-hidden="true" style="display: none;"></i>
                                            <span><?=$item['category']?></span>
                                        </a>
                                    </div>
                                    <div class="col-md-3"><a href="<?=route('categories.index')?>?parent_id=<?=@$item['parent_id']?>">
                                            <span><?=$item['parent_name']?$item['parent_name']:'Chính'?></span></a></div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-4">
                                        <span><?=$item['position']?></span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="wrapper tooltip">
                                            <input type="checkbox" id="checkbox" class="slider-toggle" <?=$item['status'] == 'A'?'checked':''?>  onclick="return false;" onkeydown="return false;"/>
                                            <label class="slider-viewport" for="checkbox">
                                                <div class="slider">
                                                    <div class="slider-button">&nbsp;</div>
                                                    <div class="slider-content left"><span>On</span></div>
                                                    <div class="slider-content right"><span>Off</span></div>
                                                </div>
                                            </label>
                                            <span class="tooltiptext">Kích hoạt</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="<?=route('categories.edit',['id' => $item['category_id']])?>" class="tooltip">
                                            <i class="icon-edit" title="Chỉnh sửa">&nbsp</i>
                                            <i class="icon-edit-hover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Cập nhật</span>
                                        </a>
                                        <a href="#" class="tooltip delete-action" data-id="<?=$item['category_id']?>" >
                                            <i class="icon-delete" title="Xóa">&nbsp</i>
                                            <i class="icon-delete-hover" title="Xóa">&nbsp</i>
                                            <span class="tooltiptext">Xoá</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        @endif
                        </ul>

                        @include('includes.paginator')
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript">

        $(function(){

            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có chắc chắn muốn xóa?", function () {
                    ajax_loading(true);
                    $.post('{{route('categories.delete')}}', {
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