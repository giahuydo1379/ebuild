@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view">
            <div class="header">
                <h2 class="title">Xuất sản phẩm</h2>
            </div>
            <form action="<?=route('exports.index')?>" method="get">
                <section class="view_donhang">
                    <div class="header-panel">
                        <h3 class="title_supplier">Xuất sản phẩm từ hệ thống ra file excel</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-1 haft-padding-left">
                            <i class="fa fa-file-excel-o" aria-hidden="true" style="font-size: 60px;"></i>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Chọn từ ngày</label>
                                <div class="time">
                                    <div class="input-group date">
                                        <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="<?=@$params['from_date']?>" placeholder="Chọn ngày cập nhật">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <div class="time">
                                    <div class="input-group date" >
                                        <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="<?=@$params['to_date']?>" placeholder="Chọn ngày cập nhật">
                                        <span class="fa fa-calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sources-exports">
                        <p class="sub_title"><i class="icon-payment">&nbsp</i> <span>Chọn kiểu xuất (export) file sản phẩm</span></p>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="radio-inline">
                                    <input class="flat" type="radio" value="all" name="status"> Tất cả sản phẩm (Đã & Chưa kích hoạt)
                                </label>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="radio-inline">
                                    <input checked="checked" class="flat" type="radio" value="A" name="status"> Tất cả sản phẩm (Đã kích hoạt)
                                </label>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="radio-inline">
                                    <input class="flat" type="radio" value="H" name="status"> Tất cả sản phẩm (Chưa kích hoạt)
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input checked="checked" class="flat" type="radio" value="product-code" name="type"> Theo mã sản phẩm
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="ip_text" name="sku" value="" placeholder="Nhập mã sản phẩm">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input class="flat" type="radio" value="category" name="type"> Theo ngành hàng
                                    </label>
                                </div>
                                <div class="form-group">
                                    {!! Form::select("category_id", ['' => ''] + $category_options, @$params['category_id'],
                                        ['id' => 'category_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo danh mục']) !!}
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                    <div class="row text-right">
                        <div class="col-md-12">
                            <input type="hidden" value="1" name="is_export">
                            <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Xuất Excel</button>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection

@section('after_styles')
    <!-- iCheck -->
    <link href="/assets/plugins/iCheck/skins/flat/green.css" rel="stylesheet">
    <style>
        .form-group {
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('after_scripts')
    <!-- iCheck -->
    <script src="/assets/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if(isset($message) && $message)
            malert("{!! $message['text'] !!}", "{!! $message['title'] !!}");
            @endif

            init_datepicker('.datepicker');
            init_icheck('input.flat');
            init_select2('.select2');
        });
    </script>
@endsection