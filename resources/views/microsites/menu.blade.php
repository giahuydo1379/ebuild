<?php 
use Illuminate\Support\Facades\Route;
$route_current = Route::getCurrentRoute()->getName();
?>
<a href="{{route('microsites.index')}}" class="tablinks <?=$route_current == 'microsites.index'?'active':''?>"">Sale off (Sản phẩm giảm giá)</a>
<a href="{{route('microsites.gold-time')}}" class="tablinks <?=$route_current == 'microsites.gold-time'?'active':''?>"">Giờ vàng giá sốc</a>
<a href="{{route('microsites.exchange-points')}}" class="tablinks <?=$route_current == 'microsites.exchange-points'?'active':''?>"">Tích điểm đổi quà</a>
<a href="{{route('microsites.normal')}}" class="tablinks <?=$route_current == 'microsites.normal'?'active':''?>"">Bình thường</a>
<a href="{{route('microsites.pre-order')}}" class="tablinks <?=$route_current == 'microsites.pre-order'?'active':''?>">Pre-Order</a>