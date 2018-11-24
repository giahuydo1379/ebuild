<div class="Radio Radio14" style="display: none;">
    <form method="post" id="form_update14" class="frm_update" action="{{ route("promotions.add") }}">
        <input type="hidden" name="id" id="id" value="0">
        <input type="hidden" name="package_id" value="14">
        <input type="hidden" name="_token" value="{!! csrf_token() !!}">

        <div class="form-group">
            <label for="">Tên chương trình tặng quà <span>*</span></label>
            <input name="name" id="name" type="text" class="form-control" placeholder="Nhập tên chương trình tặng quà">
            <label id="name-error" class="error" for="name" style="display: none;"></label>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <label for="">Thời gian áp dụng <span>*</span></label>
                <div class="time">
                    <div class="input-group date">
                        <input name="from_date" id="from_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                        <span class="fa fa-calendar"></span>
                    </div>
                </div>
                <label id="from_date-error" class="error" for="from_date" style="display: none;"></label>
            </div>
            <div class="col-md-6">
                <label >&nbsp</label>
                <div class="time to-time">
                    <div class="input-group date">
                        <input name="to_date" id="to_date" class="form-control datepicker" size="20" type="text" value="" placeholder="Chọn ngày-tháng-năm">
                        <span class="fa fa-calendar"></span>
                    </div>
                </div>
                <label id="to_date-error" class="error" for="to_date" style="display: none;"></label>
            </div>
        </div>
        <div class="form-group">
            <label for="">Đơn hàng có giá trị từ <span>*</span></label>
            <div class="input-unit">
                <input value="" name="apply_objects" id="apply_objects" type="hidden">
                <input value="" type="text" class="form-control fm-number"
                       placeholder="Điền giá trị đơn hàng"
                       data-target="#form_update14 #apply_objects"
                       data-display="#form_update14 display-price">
                <span class="unit">VNĐ</span>
            </div>
            <label id="apply_objects-error" class="error" for="apply_objects" style="display: none;"></label>
        </div>
        <div class="form-group">
            <label for="">Nhập SKU sản phẩm quà tặng <span>*</span></label>
            <input value="" name="gift_products[]" id="gift_products" type="hidden">
            <input name="gift_order_sku" id="gift_order_sku"
                   data-display="#form_update14 display-sku"
                   data-target="#form_update14 #gift_products"
                   type="text" class="form-control gift_order_sku" placeholder="Điền mã SKU sản phẩm quà tặng">
            <label id="gift_order_sku-error" class="error" for="gift_order_sku" style="display: none;"></label>
        </div>
        <div class="form-group">
            <label for="">Tóm tắt chương trình khuyến mãi</label>
            <ul class="list-pro">
                <li>Tặng 01 sản phẩm <b class="color"><display-sku>A</display-sku></b> cho đơn
                    hàng có giá trị từ <b class="color"><display-price>0</display-price> VNĐ </b>trở lên
                </li>
            </ul>
        </div>
    </form>
</div>