<?php
namespace App\Helpers;

class Common
{
    public static function get_columns_status() {
        return [
            0 => '<span class="label label-default">Ngừng kích hoạt</span>',
            1 => '<span class="label label-success">Đang kích hoạt</span>'
        ];
    }
}