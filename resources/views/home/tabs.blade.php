<div class="tab-main swiper-container">
    <div class="tab swiper-wrapper">
        <div class="swiper-slide">
            <a href="{{route('home.index')}}" class="tablinks top_products_v2">Top 10 sản phẩm (giá tốt nhất)</a>
        </div>
        <?php /*
        <div class="swiper-slide">
            <a href="{{route('home.shock-more')}}" class="tablinks shock_more">Không thể sốc hơn</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('home.only-at-dmth')}}" class="tablinks only_at_dmth">Chỉ có tại {{config('app.name')}}</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('home.brand-hot')}}" class="tablinks brand_hot">Thương hiệu nổi bật</a>
        </div>
        <div class="swiper-slide">
            <a t href="{{route('home.brand-popular')}}" class="tablinks brand_popular">Thương hiệu yêu thích</a>
        </div>
        */?>
        <div class="swiper-slide">
            <a href="{{route('home.block-product', ['sort' => 1])}}" class="tablinks block_product_v2_1">Block sản phẩm 01</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('home.block-product', ['sort' => 2])}}" class="tablinks block_product_v2_2">Block sản phẩm 02</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('home.block-product', ['sort' => 3])}}" class="tablinks block_product_v2_3">Block sản phẩm 03</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('home.block-product', ['sort' => 4])}}" class="tablinks block_product_v2_4">Block sản phẩm 04</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('home.block-product', ['sort' => 5])}}" class="tablinks block_product_v2_5">Block sản phẩm 05</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('home.block-product', ['sort' => 6])}}" class="tablinks block_product_v2_6">Block sản phẩm 06</a>
        </div>
    </div>
    <div class="content-arrow arrow-right">
        <div class="swiper-button-next"></div>
    </div>
    <div class="content-arrow arrow-left">
        <div class="swiper-button-prev"></div>
    </div>
</div>