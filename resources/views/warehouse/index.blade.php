@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title">Quản lý nhà kho</h2>
            </div>
            <form action="<?=route('warehouse.index')?>" method="get">
                <section class="search_box">
                    <div class="header-panel">
                        <h3 class="title_supplier">Tìm kiếm</h3>
                        <div class="wrap_link">
                            <a href="<?=route('warehouse.create')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo nhà kho mới</span></a>
                            <a href="<?=route('warehouse.import')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Import địa điểm giao hàng</span></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 haft-padding-left">
                            <div class="form-group">
                                <label>Tên nhà kho</label>
                                <input type="text" name="name" value="<?=@$params['name']?>" placeholder="Nhập tên nhà kho">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Danh mục sản phẩm</label>
                                {!! Form::select("category_id", ['' => ''] + $category_options, @$params['category_id'],
                                        ['id' => 'category_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo danh mục']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nhà cung cấp</label>
                                {!! Form::select("supplier_id", ['' => 'Chọn nhà cung cấp'] + $supplier, @$params['supplier_id'], ['id' => 'supplier_id', 'class' => 'form-control select2']) !!}
                            </div>
                        </div>
                        <div class="col-md-3 haft-padding-left">
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" value="<?=@$params['phone']?>" placeholder="Nhập số điện thoại">
                            </div>
                        </div>
                        <div class="col-md-3 haft-padding-left">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <div class="wrap_select">
                                    <select class="form-control" name="status">
                                        <option value="">Chọn trạng thái</option>
                                        <option value="1" <?=isset($params['status']) && $params['status'] == '1' ?'selected':'' ?> >Kích hoạt</option>
                                        <option value="0" <?=isset($params['status']) && $params['status'] == '0' ?'selected':'' ?> >Không kích hoạt</option>
                                    </select>
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3" style="float: right">
                            <button type="submit" class="btn_edit btn_search_supply" style="float: right">Tìm kiếm</button>
                        </div>
                    </div>
                </section>
            </form>
            <section class="table_supply">
                <div class="wrap-table">
                    <div class="table-supply">
                        <div class="header_table">
                            <div class="col-md-7">
                                <div class="col-md-6">
                                    <span>Tên nhà kho</span>
                                </div>
                                <div class="col-md-6">
                                    <span>Nhà cung cấp</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="col-md-3">
                                    <span>Số điện thoại</span>
                                </div>
                                <div class="col-md-6">
                                    <span>Trạng thái</span>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                        </div>
                        @if(!empty($objects['data']))
                            <ul class="panel-group" id="accordion1">
                                @foreach($objects['data'] as $item)
                                    <li class="row panel">
                                        <div class="col-md-7">
                                            <div class="col-md-6"><span><?=$item['name']?></span></div>
                                            <div class="col-md-6"><span><?=$item['supplier_name']?></span></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="col-md-3">
                                                <?=$item['phone']?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="wrapper tooltip">
                                                    <input type="checkbox" id="checkbox" class="slider-toggle" <?=$item['status'] == '1'?'checked':''?>  onclick="return false;" onkeydown="return false;"/>
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
                                            <div class="col-md-3">
                                                <a href="<?=route('warehouse.edit',['id' => $item['id']])?>" class="tooltip">
                                                    <i class="icon-edit" title="Chỉnh sửa">&nbsp</i>
                                                    <i class="icon-edit-hover" title="Chỉnh sửa">&nbsp</i>
                                                    <span class="tooltiptext">Chi tiết</span>
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
                        @endif
                        @include('includes.paginator')
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(function(){
            init_select2('.select2');
            $('.delete-action').on('click', function () {
                var obj = this;
                confirm_delete("Bạn có muốn xóa nhà kho này?", function () {
                    ajax_loading(true);
                    $.get('{{route('warehouse.delete')}}', {
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