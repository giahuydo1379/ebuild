<div class="tab-main swiper-container">
    <div class="tab swiper-wrapper">
        <div class="swiper-slide">
            <a style="width: 130px;text-align: right;" href="{{route('report.order')}}"
               class="tablinks order">Đơn hàng</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('report.product')}}" class="tablinks product">Sản phẩm</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('report.category')}}" class="tablinks category">Ngành hàng</a>
        </div>
        <div class="swiper-slide">
            <a href="{{route('report.coupon')}}" class="tablinks coupon">Coupon</a>
        </div>
    </div>
    <div class="content-arrow arrow-right">
        <div class="swiper-button-next"></div>
    </div>
    <div class="content-arrow arrow-left">
        <div class="swiper-button-prev"></div>
    </div>
</div>