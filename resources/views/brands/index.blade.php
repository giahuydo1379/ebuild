@extends('layouts.master')

@section('content')
    <div class="col-md-">
        <div class="wrap_view manage_brand">
            <div class="header ">
                <h2 class="title">Quản lý thương hiệu</h2>
            </div>
            <form action="<?=route('brands.index')?>" method="get">
            <section class="search_box">
                <div class="header-panel">
                    <h3 class="title_supplier">Tìm kiếm thương hiệu</h3>
                    <div class="wrap_link">
                        <a href="<?=route('brands.create')?>"><i class="fa fa-plus-circle" aria-hidden="true"></i><span>Tạo thương hiệu mới</span></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 haft-padding-left">
                        <div class="form-group">
                            <label>Tên thương hiệu</label>
                            <input type="text" name="name" value="<?=@$params['name']?>" placeholder="Nhập tên thương hiệu">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Danh mục</label>
                            {!! Form::select("category_id", ['' => ''] + $category_options, @$params['category_id'],
                                        ['id' => 'category_id', 'class' => 'form-control select2', 'data-placeholder' => 'Chọn danh mục']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Trạng thái thương hiệu</label>
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
                    <div class="col-md-3 haft-padding-right">
                        <div class="form-group">
                            <button type="submit" class="btn_edit btn_search_supply">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </section>
            </form>
            <section class="table_supply">
                <div class="wrap-table">
                    <div class="table-supply">
                        <div class="header_table">
                            <div class="col-md-7">
                                <div class="col-md-2">
                                    <span>ID</span>
                                </div>
                                <div class="col-md-5">
                                    <span>Tên thương hiệu</span>
                                </div>
                                <div class="col-md-5">
                                    <span>Tên Alias</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="col-md-3">
                                    <span>Logo</span>
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
                                <?php
                                $link = \App\Helpers\General::get_link_brand($item);
                                ?>
                            <li class="row panel">
                                <div class="col-md-7">
                                    <div class="col-md-2"><span><?=$item['brand_id']?></span></div>
                                    <div class="col-md-5"><span><?=$item['name']?></span></div>
                                    <div class="col-md-5"><span><?=!empty($item['alias'])?$item['alias']:str_slug($item['name'],'-')?></span></div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-3">
                                        <div class="wrap-img">
                                            <img src="<?=$item['image_url'].$item['image_location']?>" alt="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                    <div class="col-md-3">
                                        <a href="javascript:void(0)" class="tooltip">
                                            <i class="icon-view-fast" title="Xem nhanh" data-toggle="collapse" data-parent="#accordion1" href="#collapsea<?=$item['brand_id']?>">&nbsp</i>
                                            <i class="icon-view-fast-hover" title="Xem nhanh" style="display: none;" data-toggle="collapse" data-parent="#accordion1" href="#collapsea<?=$item['brand_id']?>">&nbsp</i>
                                            <span class="tooltiptext">Xem nhanh</span>
                                        </a>
                                        <a href="<?=route('brands.edit',['id' => $item['brand_id']])?>" class="tooltip">
                                            <i class="icon-edit" title="Chỉnh sửa">&nbsp</i>
                                            <i class="icon-edit-hover" title="Chỉnh sửa">&nbsp</i>
                                            <span class="tooltiptext">Chi tiết</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="view_fast panel-collapse collapse" id="collapsea<?=$item['brand_id']?>" >
                                    <div class="col-md-6">
                                        <div class="box box1">
                                            <div class="top">
                                                <div class="col-md-2">
                                                    <div class="wrap-img">
                                                        <img src="<?=$item['image_url'].$item['image_location']?>" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <h4><?=$item['name']?></h4>
                                                </div>
                                            </div>
                                            <div class="bottom">
                                                <p><b>Tên thương hiệu:</b> <?=$item['name']?></p>
                                                <p><b>Tên tiếng anh:</b> <?=$item['name_en']?></p>
                                                <p><b>Tên Alias:</b> <?=!empty($item['alias'])?$item['alias']:str_slug($item['name'],'-')?></p>
                                                <p><b>Thuộc danh mục:</b>  </p>
                                                <b>Trạng thái:</b>
                                                <div class="wrapper">
                                                    <input type="checkbox" id="checkbox" class="slider-toggle" <?=$item['status'] == 'A'?'checked':''?>  onclick="return false;" onkeydown="return false;"/>
                                                    <label class="slider-viewport" for="checkbox">
                                                        <div class="slider">
                                                            <div class="slider-button">&nbsp;</div>
                                                            <div class="slider-content left"><span>On</span></div>
                                                            <div class="slider-content right"><span>Off</span></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <p><b>Link thương hiệu:</b> <a href="<?=$link?>"> <?=$link?></a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box box_descript">
                                            <div class="header_box">
                                                <b class="title"> Mô tả thương hiệu </b>
                                            </div>
                                            <div class="body_box">
                                                <div class="part">
                                                    <strong>Mô tả thương hiệu bằng tiếng Việt</strong>
                                                    <p><?=$item['description']?></p>
                                                </div>
                                                <div class="part">
                                                    <strong>Mô tả thương hiệu bằng tiếng Anh</strong>
                                                    <p><?=$item['description_en']?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wrap_btn">
                                            <a href="<?=route('brands.edit',['id' => $item['brand_id']])?>" type="button" class="btn_next">Sửa thông tin</a>
                                        </div>
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
        $(function() {
            init_select2('.select2');
        });
    </script>
@endsection