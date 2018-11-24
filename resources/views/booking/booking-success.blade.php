@extends('layouts.master')

@section('content')
<div class="col-md-">
    <div class="wrap_view wp-booking-success">
        <div class="booking-success">
            <div class="wp-alert text-center ">
                <i class="icon-success">&nbsp;</i>
                <h2>Bạn đã tạo mới đơn hàng thành công</h2>
            </div>
            <div class="table-information text-center">
                <div class="row">
                    <div class="text text-left pull-left">
                        <p>Mã đơn hàng là <b class="yellow"><?=$order['code']?></b></p>
                        <P>Thời gian sử dụng dịch vụ lúc: <span class="yellow"><?=\App\Helpers\General::output_date($order['time'], true, 'H:i d-m-Y')?></span></P>
                        <P>Các dịch vụ có trong đơn hàng: </P>
                    </div>

                    <div class="wp-btn pull-right">
                        <a href="<?=route('order.print', ['id' => $order['order_id']])?>" class="btn btn-red"><i class="fa fa-print" aria-hidden="true"></i> In đơn hàng</a>
                        <a href="<?=route('order.index')?>" class="btn btn-default"><i class="fa fa-list" aria-hidden="true"></i> Danh sách đơn hàng</a>
                    </div>
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
                            <td  class="text-center">
                                <p class="red"><?=number_format($order['subtotal'])?>đ</p>
                                {{--<p> <span class="red">40.000đ</span></p>--}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                TỔNG GIÁ TRỊ GÓI DỊCH VỤ
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
                        <div class="row">
                            <label for="">Hình thức thanh toán:</label> <span>Tiền mặt</span>
                        </div>
                        <div class="row">
                            <label for="">Thời gian sử dụng dịch vụ:</label> <span><?=$order['duration']?> phút</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_styles')
    <style type="text/css">
        .wp-booking-success {
            padding: 0px 30px;
        }
        .booking-success {
            width: 100%;
        }
        .information .info label {
            float: left;
        }
        .information .info .person {
            float: left;
            margin-left: 10px;
        }
    </style>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(function(){

        });
    </script>
@endsection