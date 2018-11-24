@extends('layouts.master')

@section('content')
    <!--page-container-->
    <div class="col-md-">
        <div class="wrap_view view_product create_product">
            <div class="header">
                <h2 class="title">Chọn dịch vụ để thêm mới đơn hàng</h2>
                <a href="<?=url()->previous();?>"><i class="fa fa-reply" aria-hidden="true"></i>  Quay lại</a>
            </div>
        </div>
        <section class="choose-services">
            @if(!empty($objects))
            @foreach($objects as $item)
            <div class="block text-center">
                <a href="<?=route('booking.create', ['slug' => $item['slug'], 'service_id' => $item['id']])?>">
                    <img src="<?=$item['image_url'].$item['image_location']?>" alt="clean house">
                    <span class="name-service"><?=$item['name']?></span>
                </a>
            </div>
            @endforeach
            @endif
        </section>
    </div>
@stop