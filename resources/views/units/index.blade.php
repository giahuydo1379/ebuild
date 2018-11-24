@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title">Quản lý đơn vị sản phẩm</h2>
            </div>
            <form action="<?=route('units.index')?>" method="get">
                <section class="search_box">
                    <div class="header-panel">
                        <h3 class="title_supplier">Tìm kiếm đơn vị sản phẩm</h3>
                        <div class="wrap_link">
                            <a href="<?=route('units.create')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo đơn vị sản phẩm mới</span></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 haft-padding-left">
                            <div class="form-group">
                                <label>Tên đơn vị</label>
                                <input type="text" name="name" value="" placeholder="Nhập tên đơn vị">
                            </div>
                        </div>
                        <div class="col-md-2 haft-padding-right">
                            <div class="form-group">
                                <button type="submit" class="btn_edit btn_search_supply">Tìm kiếm</button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
            <section class="table_supply" style="width: 50%;">
                <div class="wrap-table">
                    <div class="table-supply">
                        <div class="header_table">
                            <div class="col-md-10">
                                <div class="col-md-6">
                                    <span>ID</span>
                                </div>
                                <div class="col-md-6">
                                    <span>Tên đơn vị</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="col-md-12"></div>
                            </div>
                        </div>
                        @if(!empty($objects['data']))
                        <ul class="panel-group" id="accordion1">
                            @foreach($objects['data'] as $item)
                            <li class="row panel">
                                <div class="col-md-10">
                                    <div class="col-md-6"><span><?=$item['id']?></span></div>
                                    <div class="col-md-6"><span><?=$item['name']?></span></div>
                                </div>
                                <div class="col-md-2">
                                    <div class="col-md-12">
                                        <a href="<?=route('units.edit',['id' => $item['id']])?>" class="tooltip">
                                            <i class="icon-edit" title="Chỉnh sửa">&nbsp</i>
                                            <i class="icon-edit-hover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Chi tiết</span>
                                        </a>
                                        <a class="tooltip delete-action" data-id="<?=$item['id']?>">
                                            <i class="icon-delete active DeleteAction" aria-hidden="true"></i>
                                            <i class="icon-delete-hover DeleteHover" title="Xóa">&nbsp;</i>
                                            <span class="tooltiptext">Xóa</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        @include('includes.paginator')
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('after_scripts')
<script type="text/javascript">
    $(function(){
        $('.delete-action').on('click', function () {
            var obj = this;
            confirm_delete("Bạn có muốn xóa đơn vị sản phẩm này?", function () {
                ajax_loading(true);
                $.get('{{route('units.delete')}}', {
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