<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Xác nhận dịch vụ - Đặt lịch chăm sóc xe :: Carspa    </title>
    <style>
        h1 {
            margin: 0;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
            background-color: transparent;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        .table-information .table {
            border: 1px solid #f8ba05;
        }
        .table-information .table thead th {
            background: #f8ba05;
            padding: 8px 16px;
            color: #000;
        }
        .table-information .table tbody td {
            line-height: 30px;
            padding: 8px 16px;
            color: #000;
            font-weight: 700;
        }
        .table > thead > tr > th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .information {
            padding: 0 20px;
            border: 1px solid #f8ba05;
            margin-bottom: 10px;
        }
        .information .header {
            font-size: 16px;
            text-transform: uppercase;
            color: #000;
            font-weight: 700;
            line-height: 50px;
            border-bottom: 1px solid #f8ba05;
        }
        .information .body {
            padding: 20px 0;
            line-height: 28px;
        }
        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .information .info label {
            float: left;
        }
        .information .info .person {
            float: left;
            margin-left: 10px;
        }
        .information .body .person span {
            display: block;
        }
        .row:after {
            clear: both;
        }
        .row:before, .row:after  {
            content: " ";
            display: table;
        }
        footer .bottom-footer {
            text-align: center;
            background: #f8ba05;
            height: 40px;
            line-height: 40px;
            display: inline-block;
            width: 100%;
            color: #000;
        }
        .left {
            float: left;
        }
        .right {
            float: right;
        }
        .red {
            color: #ff0000;
        }
        .yellow {
            color: #f8ba05;
        }
    </style>
</head>
<body style="font-family: 'Roboto', sans-serif;width: 800px;
    margin-left: auto;
    margin-right: auto;font-size: 14px;">
<?php
$settings = \App\Helpers\General::get_settings();
?>
<header>
    <div class="container" style="border-bottom: 4px solid #000000;">
        <div class="left">
            <h1 class="logo"><a href="/"><img src="<?=url(@$settings['image_logo_header']['value'])?>" alt="logo"></a></h1>
        </div>
        <div class="right" style="
    text-align: center;
    padding: 27px 50px;">
            <strong>Hotline<br> <b class="red"><?=$settings['hotline']['value'] ?? ''?></b></strong>
        </div>
        <div style="clear:both;"></div>
    </div>
</header>
<div style="clear:both;"></div>
<section class="container booking-success">
    <div class="table-information text-center">
        <div class="text text-left">
            <p>Cảm ơn bạn đã sử dụng dịch vụ của <span class="yellow"><b>CARSPA</b></span>. Mã đơn hàng của bạn là <b class="yellow"><?=$order['code']?></b></p>
            <P>Bạn sử dụng dịch vụ lúc: <span class="yellow"><?=\App\Helpers\General::output_date($order['time'], true, 'H:i d-m-Y')?></span></P>
            <P>Các dịch vụ bạn đã đặt bao gồm:</P>
        </div>

        <div class="table-responsive">
            <table class="table table-striped text-left">
                <thead>
                <tr>
                    <th>Mã dịch vụ</th>
                    <th>Tên dịch vụ</th>
                    <th>Phí dịch vụ</th>
                    <th class="text-center">Tổng chi phí</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?=$order['category_name']?></td>
                    <td><?=$order['package_name']?></td>
                    <td><?=number_format($order['package_price'])?>đ</td>
                    <td class="text-center"><?=number_format($order['package_price'])?>đ</td>
                </tr>
                @if(!empty($order_detail['product']))
                    @foreach($order_detail['product'] as $key => $item)
                        <tr>
                            <td><?=$item['product_code']?></td>
                            <td><?=$item['product']?></td>
                            <td><?=number_format($item['price'])?>đ</td>
                            <td class="text-center"><?=number_format($item['price'])?>đ</td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td colspan="3">
                        <p>Tổng tiền dịch vụ</p>
                        {{--<p>Mã giảm giá: <span class="red">MDA123HJA</span></p>--}}
                    </td>
                    <td class="text-center">
                        <p class="red"><?=number_format($order['subtotal'])?>đ</p>
                        {{--<p> <span class="red">40.000đ</span></p>--}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        TỔNG GIÁ TRỊ DỊCH VỤ
                    </td>
                    <td class="text-center">
                        <p class="red"><?=number_format($order['total'])?>đ</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="information text-left">
            <div class="header">Thông tin khách hàng</div>
            <div class="body">
                <div class="row info">
                    <label for="">Thông tin khách hàng: </label>
                    <div class="person">
                        <span>Họ tên khách hàng: <?=$order['fullname']?></span>
                        <span>Điện thoại: <?=$order['phone']?> -   Email: <?=$order['email']?></span>
                    </div>
                </div>
                <div style="clear:both;"></div>
                <div class="row">
                    <label for="">Hình thức thanh toán:</label> <span>Tiền mặt</span>
                </div>
                <div style="clear:both;"></div>
                <div class="row">
                    <label for="">Thời gian sử dụng dịch vụ:</label> <span><?=$order['duration']?> phút</span>
                </div>
            </div>
        </div>
    </div>
</section>
<!--footer-->
<footer>
    <div class="bottom-footer">
        Copyright © 2018 CarSpa. Design by <a href="http://blackeyes.co" target="_blank">Blackeyes team</a>    </div>
</footer>
</body>
</html>