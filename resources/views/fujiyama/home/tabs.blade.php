<div class="tab-main swiper-container">
    <div class="tab swiper-wrapper">
        <div class="swiper-slide">
            <a style="width: 175px;text-align: right;" href="{{route('fujiyama.home.block-product', ['sort' => 1])}}"
               class="tablinks block_product_fujiyama_1">Block sản phẩm 01</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('fujiyama.home.block-product', ['sort' => 2])}}" class="tablinks block_product_fujiyama_2">Block sản phẩm 02</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('fujiyama.home.block-product', ['sort' => 3])}}" class="tablinks block_product_fujiyama_3">Block sản phẩm 03</a>
        </div>
    </div>
    <div class="content-arrow arrow-right">
        <div class="swiper-button-next"></div>
    </div>
    <div class="content-arrow arrow-left">
        <div class="swiper-button-prev"></div>
    </div>
</div>