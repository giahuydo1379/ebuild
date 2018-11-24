@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view view_product">
            <div class="header">
                <h2 class="title">Danh sách dịch vụ</h2>
            </div>
            <section class="view_donhang ">
                <div class="header-panel">
                    <h3 class="title ">Tìm kiếm nhanh</h3>
                    <div class="wrap_link">
                        <a href="<?=route('product-service.create')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo dịch vụ mới</span></a>
                    </div>
                </div>
                <form action="" method="get" class="search_donhang" id="frm-search">
                    <div class="form_search_fast row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tên dịch vụ</label>
                                <input type="text" name="name" value="<?=@$params['name']?>" placeholder="Nhập tên dịch vụ">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Danh mục dịch vụ</label>
                                {!! Form::select("category_id", ['' => ''] + $category_options, @$params['category_id'],
                                        ['id' => 'category_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn theo danh mục']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="form-group">
                            <label>Trạng thái dịch vụ</label>
                            <div class="wrap_select">
                                <select class="form-control" name="status">
                                    <option value="">Chọn trạng thái</option>
                                    <option value="A" <?=!empty($params['status']) && $params['status'] == 'A' ?'selected':'' ?> >Kích hoạt</option>
                                    <option value="D" <?=!empty($params['status']) && $params['status'] == 'D' ?'selected':'' ?> >Không kích hoạt</option>
                                </select>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <button type="submit" class="btn_search" value="">Tìm kiếm</button>
                    </div>
                </form>
            </section>
            <section class="table ">
                <div class="table_donhang">
                    <div class="header_table">
                        <div class="row">
                            <div class="col-md-7">
                                <div class=" col-md-6">Tên dịch vụ</div>
                                <div class=" col-md-6">Danh mục</div>
                            </div>
                            <div class="col-md-5">
                                <div class=" col-md-6">Giá bán</div>
                                <div class=" col-md-3">Trạng thái</div>
                                <div class=" col-md-3"></div>
                            </div>
                        </div>
                    </div>
                    @if(!empty($objects['data']))
                    <ul class="body_table panel-group" id="accordion5">

                        @foreach($objects['data'] as $item)
                        <li class="row panel">
                            <div class="col-md-7">
                                <div class="status col-md-6"><?=$item['product']?></div>
                                <div class=" col-md-6"><?=$item['category']?></div>
                            </div>
                            <div class="col-md-5">
                                <div class=" col-md-6"> <span class="orange"><?=number_format($item['price'])?>đ</span></div>
                                <div class=" col-md-3">
                                    <div class="wrapper_">
                                        <input type="checkbox" id="checkbox0" class="slider-toggle" <?=$item['status'] == 'A'?'checked':'' ?> onclick="return false;" onkeydown="return false;"/>
                                        <label class="slider-viewport" for="checkbox0">
                                            <div class="slider">
                                                <div class="slider-button">&nbsp;</div>
                                                <div class="slider-content left"><span>On</span></div>
                                                <div class="slider-content right"><span>Off</span></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <i class="icon-view-fast" title="Xem nhanh" data-toggle="collapse" data-parent="#accordion5" href="#collapsea<?=$item['product_id']?>">&nbsp</i>
                                    <i class="icon-view-fast-hover" title="Xem nhanh" style="display: none;" data-toggle="collapse" data-parent="#accordion5" href="#collapsea<?=$item['product_id']?>">&nbsp</i>
                                    <a href="<?=route('product-service.edit',['id' => $item['product_id']])?>"><i class="icon-view-detail" title="Xem chi tiết">&nbsp</i></a>
                                    <i class="icon-view-detail-hover" title="Xem chi tiết" style="display: none;">&nbsp</i>
                                </div>
                            </div>
                            <div class="view_fast panel-collapse collapse" id="collapsea<?=$item['product_id']?>">
                                <div class="wrap_pannel">
                                    <p class="title_viewfast">THông tin dịch vụ</p>
                                    <div class="col-md-7">
                                        <div class="box">
                                            <ul>
                                                <li class="row"><b>Tên dịch vụ: </b> <span><?=$item['product']?></span></li>
                                                <li class="row"><b>Danh mục: </b> <span><?=$item['category']?></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="box">
                                            <ul>
                                                <li class="row"><b>Giá bán:  </b> <span><?=number_format($item['price'])?> VNĐ</span></li>
                                                <?php $percent_discount = $item['list_price'] > 0 ? round( 100 - ($item['price'] * 100 / $item['list_price']) ) : 0; ?>
                                            </ul>
                                        </div>
                                        <div class="col-md-7 col-md-offset-6">
                                            <span class="cancel">Hủy bỏ</span>
                                            <button type="button" class="btn_edit">Sửa dịch vụ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                @include('includes.paginator')
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
            init_fm_number('.fm-number');
            $('.fm-number').each(function( index ) {
                $(this).val( numeral($(this).val()).format() );
            });

            $('#number_product').on('change', function () {
                $('#number_from').val($(this).val());
                $('#number_to').val($(this).val());
            });
            $('#price_product').on('change', function () {
                var option = $('#price_product option:selected');
                $('#price_from').val(numeral(option.attr('data-from')).format());
                $('#price_to').val(numeral(option.attr('data-to')).format());
            });
            $('#frm-search').on('submit', function () {
                $('.fm-number').each(function( index ) {
                    $(this).val( numeral($(this).val()).value() );
                });
            });
        });
    </script>
@endsection