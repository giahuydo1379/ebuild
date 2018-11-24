<div class="month-year">
    <i class="fa fa-angle-left time_prev" data-page="<?=$time_prev?>"></i>
    <div class="name"><span class="title_month">Tháng <?=str_repeat("0", 2-strlen($dt->month)).$dt->month?></span> <span class="year">năm <?=$dt->year?></span></div>
    <i class="fa fa-angle-right time_next" data-page="<?=$time_next?>"></i>
</div>
<div class="tb-time table-responsive">
    <div class="table">
        <ul class="list-date">
            @foreach($date as $key => $value)
            <li class="item <?=$key == 0?'active':''?>" data-month="<?=str_repeat("0", 2-strlen($dt->month)).$value['month']?>">
                <div class="header"><span class="date"><?=$value['day']?></span> <?=$value['dayOfWeek']?></div>
                <ul class="list-time">
                    @foreach($time as $item)
                    <?php
                        $key_time = $value['date'].' '.$item.':00';
                    ?>
                    <li><span class="btn <?=isset($date_booking[$key_time])?'btn-ordered':'btn-available btn_time'?>" data-month="<?=str_repeat("0", 2-strlen($dt->month)).$value['month']?>" data-time="<?=date('Y-m-d H:i:s',strtotime($dt->year.'-'.$dt->month.'-'.$value['day'].' '.$item))?>"><?=$item?></span>
                        <!-- <span class="icon-alert">&nbsp;</span> -->
                    </li>
                    @endforeach
                </ul>
                <div class="continue">
                    <i class="fa fa-chevron-down"></i>
                    <i class="fa fa-chevron-up"></i>
                </div>
            </li>
            @endforeach
        </ul>
        <div class="footer-table">
            <div class="show_ availabel"><span class="cell"></span> Còn trống</div>
            <div class="show_ choose"><span class="cell"></span> Đang chọn</div>
            <div class="show_ ordered"><span class="cell"></span> Đã đặt trước</div>
        </div>
    </div>
</div>