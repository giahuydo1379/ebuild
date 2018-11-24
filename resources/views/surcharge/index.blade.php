@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title">Quản lý phụ phí</h2>
            </div>
            <form action="<?=route('surcharge.index')?>" method="get">
                <section class="search_box">
                    <div class="header-panel">
                        <h3 class="title_supplier">Tìm kiếm</h3>
                        <div class="wrap_link">
                            <a href="<?=route('surcharge.create')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo phụ phí mới</span></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 haft-padding-left">
                            <div class="form-group">
                                <label>Tên phụ phí</label>
                                <input type="text" name="name" value="<?=@$params['name']?>" placeholder="Nhập tên phụ phí">
                            </div>
                        </div>
                        <div class="col-md-3 haft-padding-left">
                            <label>Đơn vị</label>
                            <div class="wrap_select">
                                <select class="form-control select2" name="unit_id">
                                    <option value="">chọn đơn vị</option>
                                    @if(!empty($list_units))
                                        @foreach($list_units as $key => $item)
                                            <option value="<?=$item['id']?>"<?php if(!empty($params['unit_id']) && ($item['id'] == $params['unit_id'])) echo 'selected'; ?> ><?=$item['name']?></option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Danh mục sản phẩm</label>
                                {!! Form::select("category_id", ['' => ''] + $category_options, @$params['category_id'],
                                        ['id' => 'category_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo danh mục']) !!}
                            </div>
                        </div>
                        <div class="col-md-3 haft-padding-left">
                            <div class="form-group">
                                <ul>
                                    <li class="row">
                                        <label>Nhà cung cấp</label>
                                        {!! Form::select("supplier_id", ['' => ''] + $suppliers, @$params['supplier_id'],
                                                    ['class' => 'form-control select2', "id" => "supplier_id", "data-placeholder"=>"Chọn nhà cung cấp"]) !!}
                                    </li>

                                </ul>
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
                            <div class="col-md-12">
                                <div class="col-md-1">
                                    <span>STT</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Tên phụ phí</span>
                                </div>                           
                                <div class="col-md-3">
                                    <span>Đơn vị</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Nhà cung cấp</span>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                        @if(!empty($objects['data']))
                            <ul class="panel-group" id="accordion1">
                                @foreach($objects['data'] as $key => $item)
                                    <li class="row panel">
                                        <div class="col-md-12">
                                            <div class="col-md-1"><span><?=$key+=1?></span></div>
                                            <div class="col-md-3"><span><?=@$item['name']?></span></div>                                        
                                            <div class="col-md-3"><span><?=@$item['unit_name']?></span></div>
                                            <div class="col-md-3">
                                                <span><?=@$item['suppliers_name']?></span>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <a href="<?=route('surcharge.edit',['id' => $item['id']])?>" class="tooltip">
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
                confirm_delete("Bạn có muốn xóa phụ phí này này?", function () {
                    ajax_loading(true);
                    $.get('{{route('surcharge.delete')}}', {
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