<?php
$arr_params = app('request')->all();
if (isset($arr_params['page'])) {
    $page = $arr_params['page'];
    unset($arr_params['page']);
} else {
    $page = 1;
}

$params = $arr_params ? "&".http_build_query($arr_params) : "";

$data_href = $arr_params ? $objects['path']."?".http_build_query($arr_params) : $objects['path'];
?>
<div class="page">
    <div class="wrap">
        <div class="left">
            <a href="{{$objects['path']}}?page=1<?=$params?>"><i class="fa fa-angle-double-left">&nbsp</i></a>
            <div> <a href="{{$objects['prev_page_url']}}<?=($page==1?'?page=1':'').$params?>"><p>Trước</p></a></div>
        </div>
        <p class="display">Trang {{$objects['current_page']}}/{{$objects['last_page']}}</p>
        <div class="right">
            <div><a href="{{$objects['next_page_url']}}<?=$params?>">
                    <p>Sau</p></a></div>
            <a href="{{$objects['path']}}?page={{$objects['last_page']}}<?=$params?>"><i class="fa fa-angle-double-right">&nbsp</i></a>
        </div>
    </div>

    <div class="per_page">
        {!! Form::select("limit", \App\Helpers\General::get_limit_options(), $objects['per_page'], ['data-href' => $data_href,
        'id' => 'limit', 'class' => 'form-control']) !!}
        <i class="fa fa-angle-down">&nbsp</i>
    </div>
</div>