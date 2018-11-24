<?php
namespace App\Helpers;

use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class General
{
    public static function getAddress($ward_id,$address=''){
        $ward = \App\Models\Ward::select(
            \DB::raw("CONCAT_WS(' ', provinces.type, provinces.name) as province_name"),
            \DB::raw("CONCAT_WS(' ', districts.type, districts.name) as district_name"),
            \DB::raw("CONCAT_WS(' ', wards.type, wards.name) as ward_name")
        )
            ->where('ward_id', $ward_id)
            ->leftJoin('districts', 'districts.district_id', '=', 'wards.district_id')
            ->leftJoin('provinces', 'provinces.province_id', '=', 'districts.province_id')
            ->first();
        $address = [$address];
        if ($ward['ward_name'] && strpos($address[0], $ward['ward_name']) === false) {
            $address[] = $ward['ward_name'];
        }
        if ($ward['district_name'] && strpos($address[0], $ward['district_name']) === false) {
            $address[] = $ward['district_name'];
        }
        if ($ward['province_name'] && strpos($address[0], $ward['province_name']) === false) {
            $address[] = $ward['province_name'];
        }
        $address = implode(", ", $address);
        return $address;
    }
    
    public static function get_settings($name=null, $re_cache=false) {
        $key = 'Settings:All';

        $objects = Cache::get( $key );

        if ($re_cache || !$objects) {
            $objects = \App\Models\Setting::getAllSettings();

            Cache::forever($key, $objects);
        }

        if ($name) {
            return @$objects[$name];
        }

        return $objects;
    }

    public static function handlingCategories(&$result, $data, $parent=0, $step = 0) {
        $str_step = '';
        for ($i=0; $i<$step; $i++) {
            $str_step .= '|--';
        }

        if (!isset($data['parent'])) return $result;

        foreach( $data['parent'][$parent] as $key => $item){

            $result[] = array(
                'category_id' => $item,
                'category' => $str_step.$data['item'][$item]['category'],
            );

            //$step < 2 &&
            if(isset($data['parent'][$item]))
                self::handlingCategories($result, $data, $item, $step+1);
        }
    }

    public static function check_date_start_sell($date, $now=null) {
        if (!$date || $date=='0000-00-00') return true;
        $today = \Carbon\Carbon::today()->format('Y-m-d');

        if(strtotime($date) > strtotime($today)) return false;

        return true;
    }

    public static function get_format_date() {
        return 'd-m-Y';
    }

    public static function order_sources() {
        return ['' => 'Web', 'web' => 'Web', 'wap' => 'Mobile', 'admin' => 'Admin'];
    }
    public static function get_current_status($status) {
        switch ($status) {
            case 'Y':
                return 1; // xac nhận don hang
                break;
            case 'E':
            case 'G':
            case 'U':
            case 'R':
                return 2;
                break;
            case 'C':
            case 'W':
            case 'X':
            case 'S':
            case 'H': //"10.Q10 - Hoàn tất"
            case 'K': //"11.Q7 - Hoàn tất"
            case 'J': //"J" => "12.Q12 - Hoàn tất"
            case 'Q': //  "Q" => "13.TP - Hoàn tất"
            case 'L': //  "L" => "14.GV - Hoàn tất"
            case 'M': //  "M" => "15.BD - Hoàn tất"
            case 'P': //  "P" => "16.Đã thanh toán"
            case 'A': //  "A" => "17.Đến trung tâm mua hàng"
            case 'I': //  "I" => "18.Huỷ bỏ"
            case 'F': //  "F" => "19.Thất bại"
            case 'O': //  "O" => "20.Đang chờ xác nhận"
            case 'D': //  "D" => "21.Bị từ chối"
            case 'B': //  "B" => "22.Hẹn lại sau"
            case 'Z': //  "Z" => "23.Giá sốc Q10"
            case 'V': //  "V" => "24.Chăm sóc khách hàng"
                return 3;
                break;
            default:
                return 0;
                break;
        }

        return 0;
    }

    public static function get_link_product_preview($item) {
        return env('URL_OUTSIDE', '').'/'.($item['alias']?$item['alias']:str_slug($item['product'])).'-'.$item['product_id'].'.html?preview=1';
    }

    public static function get_link_brand($item) {
        return env('URL_OUTSIDE', '').'/'.($item['alias']?$item['alias']:str_slug($item['name'])).'-b'.$item['brand_id'].'.html';
    }

    public static function get_link_sale_off($item) {
        return env('URL_OUTSIDE', '').'/so/'.$item['alias'].'-'.$item['id'].'.html';
    }

    public static function get_link_gold_time($item) {
        return env('URL_OUTSIDE', '').'/gt/'.$item['alias'].'-'.$item['id'].'.html';
    }

    public static function get_link_pre_order($item) {
        return env('URL_OUTSIDE', '').'/po/'.$item['alias'].'-'.$item['id'].'.html';
    }

    public static function get_miscrosite_layout()
    {
        return ['normal' => 'Giao diện bình thường', 'horizontal' => 'Giao diện menu ngang','vertical' => 'Giao diện menu dọc'];
    }
    public static function string_area_to_array($string)
    {
        if ($string != ''){
            $arr_barcode = preg_split('/\r\n|[\r\n]|,|;/', $string);
            foreach ($arr_barcode as $key => $value) {
                $tmp = trim($value);
                if ($tmp == '') {
                    unset($arr_barcode[$key]);
                    continue;
                }
                $arr_barcode[$key] = $tmp;
            }

            return $arr_barcode;
        }

        return array();
    }

    public static function get_status_actions() {
        return [
            '1' => 'Kích hoạt',
            '0' => 'Không kích hoạt',
        ];
    }
    public static function get_feature_type_options() {
        return [
            '' => 'Chọn loại filter',
            'service' => 'Dịch vụ',
            'product' => 'Sản phẩm',
        ];
    }
    public static function get_status_category_options() {
        return self::get_status_product_options();
    }
    public static function get_status_product_options() {
        return [
            '' => 'Chọn trạng thái',
            'A' => 'Kích hoạt',
            'D' => 'Vô hiệu hoá',
            'H' => 'Ẩn',
            'X' => 'Đã xóa',
        ];
    }

    public static function get_time_options() {
        return [
            "" => "Chọn thời gian",
            "to_day" => "Hôm nay",
            "this_week" => "Trong tuần",
            "this_month" => "Trong tháng",
            "this_year" => "Trong năm",
            "last_year" => "Năm trước",
        ];
    }

    public static function get_status_options() {
        return [
            '' => 'Chọn trạng thái',
            '1' => 'Đã kích hoạt',
            '0' => 'Chưa kích hoạt',
        ];
    }

    public static function get_menu_type_options() {
        return [
            ''              => '',
            'page_link'     => 'Page Link',
            'internal_link' => 'Internal Link',
            'external_link' => 'External Link'
        ];
    }
    
    public static function get_menu_position_options() {
        return [
            ''              => '',
            'menu-top'      => 'Top',
            'main-nav'      => 'Chính',
            'footer'        => 'Footer',
            'about'         => 'Trang giới thiệu',
            'support'       => 'Trang hỗ trợ khách hàng',
            'account'       => 'Trang tài khoản',
            'menu-left-mobile'       => 'Menu left mobile'
        ];
    }

    public static function get_gender_options() {
        return [
            '' => 'Chọn giới tính',
            '0' => 'Nữ',
            '1' => 'Nam',
            '2' => 'Khác'
        ];
    }

    public static function get_sort_options($id=null) {
        return [
            'price-asc' => 'Giá từ thấp đến cao',
            'price-desc' => 'Gia từ cao đến thấp',
            'product_count-desc' => 'Số lượng',
        ];
    }

    public static function get_class_saleoff_options() {
        return [
            'icon_tulanh' => 'Tủ lạnh',
            'icon-camera' => 'Điện tử - Kỹ thuật số',
            'icon-may-giat' => 'Máy giặt',
            'icon_tv' => 'Tivi (HCM)',
            'icon-mobile' => 'Mobile - Tablet',
            'icon_laptop' => 'PC - Laptop',
        ];
    }

    public static function get_ordering_options($id=null) {
        $rs = [];
        for ($i=1; $i<20; $i++) {
            $rs[$i] = 'Vị trí ' . $i;
        }

        return $rs;
    }

    static function get_version_js($re_cache=false) {
        $key = 'get_version_js';

        $value = Cache::get( $key );

        if ($re_cache || !$value) {
            $value = time();

            Cache::forever($key, $value);
        }

        return $value;
    }
    static function get_version_css($re_cache=false) {
        $key = 'get_version_css';

        $value = Cache::get( $key );

        if ($re_cache || !$value) {
            $value = time();

            Cache::forever($key, $value);
        }

        return $value;
    }

    static function checkResponseApi($request, $res) {
        if (isset($res['status']) && $res['status']=='Unauthorized') {
            if($request->ajax()) {
                \App\Helpers\Auth::removeToken();
                $res['href'] = route('login');
                die(json_encode($res));
            }
        }

        return redirect(route('login'));
    }

    public static function output_date($date, $is_return=false, $format='d-m-Y') {
        if(!$date || $date=="0000-00-00") {
            if ($is_return) {
                return "";
            }
            echo "";
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        if ($is_return) {
            return $date->format($format);
        }

        echo $date->format($format);
    }
    public static function output_time_of_date($date, $is_return=false) {
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        if ($is_return) {
            return $date->format('H:i');
        }

        echo $date->format('H:i');
    }
    /**
     * push data xuống view
     *
     * @return mixed
     * @author HaLV
     */
    static function buildDataView($data = array()) {
        extract($data);
        return call_user_func_array('compact', array_keys($data));
    }

    static function get_controller_action() {
        $action = app('request')->route()->getAction();

        $route = isset($action['as']) ? $action['as'] : '';
        $controller = class_basename($action['controller']);

        $controller = explode('@', $controller);

        return array(
            'controller' => $controller[0],
            'action' => $controller[1],
            'route_name' => $route,
            'as' => $route,
            'prefix' => $action['prefix'],
            'namespace' => $action['namespace'],
        );
    }

    public static function get_data_fillable($model, $data) {
        $fillable = $model->getFillable();

        $rs = [];
        foreach ($fillable as $field) {
            if (array_key_exists($field, $data)) {
                $rs[$field] = $data[$field];
            }
        }

        if(method_exists($model,'get_default_value')){
            $defaul_value = $model->get_default_value();

            foreach ($defaul_value as $key => $value){
                if(!isset($rs[$key]) || $rs[$key] == null){
                    $rs[$key] = $value;
                }

            }
        }
        return $rs;
    }

    public static function saveFileUpload($filename, $path)
    {
        if (empty($filename)) return '';

        $root = rtrim(public_path(), '/') . '/';

        // file goc
        $path_file = realpath( $root ."uploads/tmp/". $filename );

        if( file_exists ($path_file) ) {

            // tao thu muc
            if (! is_dir ( $root . $path )) {
                mkdir ( $root . $path, 0777, true );
                if( chmod($root . $path, 0777) ) {
                    // more code
                    chmod($root . $path, 0755);
                }
            }

            // file dich
            $info = pathinfo($path_file);
            $filename = $path .time().'-'. str_slug(basename($path_file,'.'.$info['extension']), '-' ).'.'.$info['extension'];

            rename($path_file, $root . $filename );

            // xoa hinh tmp
            @unlink($path_file);
//            $filename = url($filename);

            return array('url' => url('/'), 'filename' => $filename);
        }

        return '';
    }

    public static function get_limit_options() {
        return [
            '10' => '10 dòng/Trang',
            '20' => '20 dòng/Trang',
            '30' => '30 dòng/Trang',
            '40' => '40 dòng/Trang',
            '50' => '50 dòng/Trang',
        ];
    }

    public static function get_link_image($item, $width=270, $height=270) {
        if (isset($item['file_name'])) {
            if ($height==500 && $item['file_type']==0) {
                return $item['image_url'].str_replace('/315/315/', '/0/500/', $item['file_name']);
            }
            return $item['image_url'].$item['file_name'];
        }

        $tmp = intval( $item['detailed_id'] / 1000 );

        return config('app.url') . '/images/thumbnails/'.$tmp.'/'.$width.'/'.$height.'/'.$item['image_path'];
    }

    public static function getStaticPages(){
        $pages = \App\Models\Pages::select('*')
            ->orderBy('position', 'asc')
            ->get()->toArray();

        $tmp = [];
        foreach ($pages as $item) {
            $tmp[$item['group_manage']][] = $item;
        }

        return $tmp;
    }
}