<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'orders';
    //protected $primaryKey = 'id';
    protected $primaryKey = 'order_id';

    protected static $_status_paymented = 'C';//['C','J','K','L','M','Q'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['points_received','points_used','company_id','user_id','admin_id',
        'total','subtotal','subtotal_product','subtotal_service','surcharge', 'discount', 'subtotal_discount', 'voucher_discount', 'voucher_code', 'payment_surcharge', 'payment_surcharge',
        'shipping_ids', 'shipping_cost', 'timestamp', 'status', 'channel_sale', 'notes', 'details', 'promotions',
        'promotion_ids', 'title', 'firstname', 'lastname', 'company', 'b_title', 'b_firstname', 'b_lastname',
        'b_address', 'b_address_2', 'b_city', 'b_state', 'b_country', 'b_zipcode', 'b_phone',
        's_title', 's_firstname', 's_lastname', 's_address', 's_address_2', 's_city', 's_county', 's_state', 's_country',
        's_zipcode', 's_phone', 'phone', 'fax', 'url', 'email', 'payment_id', 'tax_exempt',
        'lang_code', 'ip_address', 'repaid', 'validation_code', 'province_id', 'district_id', 'ward_id', 'is_vat','lock','type','transport_info'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function boot() {
        parent::boot();
        //lock đơn hàng khi hủy đơn hàng
        static::updating(function($model) {     
            $original = $model->getOriginal();
            if($original['lock'] == 0 && $model->status === 'I'){
                $model->lock = 1;    
            }
        });
    }

    public static function fillable_no_auditable() {
        return [
          'b_address', 'b_address_2',
          'b_firstname', 's_firstname', 's_phone', 'b_phone', 's_city', 'points_used', 'points_received',
            'company_id', 'user_id', 'subtotal_discount', 'voucher_discount', 'payment_surcharge', 'shipping_ids',
            'shipping_cost', 'channel_sale', 'promotions', 'promotion_ids', 'title', 'lastname', 'company',
            'b_title', 'b_lastname', 'b_state', 'b_country', 'b_zipcode', 's_title', 's_lastname', 's_address_2',
            's_county', 's_state', 's_country', 's_zipcode', 'fax', 'url', 'lang_code', 'tax_exempt', 'repaid',
            'item_id', 'order_id', 'product_id', 'extra'
        ];
    }

    public static function getOptionsEvent() {
        return [
            'created-App\Models\Orders' => 'Thêm mới đơn hàng',
            'created-App\Models\Order' => 'Thêm mới đơn hàng',
            'created-App\Models\OrderDetail' => 'Thêm sản phẩm',
            'updated-App\Models\Orders' => 'Cập nhật',
            'updated-App\Models\Order' => 'Cập nhật',
            'updated-App\Models\OrderDetail' => 'Cập nhật sản phẩm',
            'deleted-App\Models\Orders' => 'Xóa đơn hàng',
            'deleted-App\Models\Order' => 'Xóa đơn hàng',
            'deleted-App\Models\OrderDetail' => 'Xóa sản phẩm',
        ];
    }

    public static function getOptionsFieldName() {
        return [
            'validation_code' => 'Mã đơn hàng',
            'status' => 'Trạng thái',
            'total' => 'Tổng cộng',
            'subtotal' => 'Thành tiền',
            'notes' => 'Ghi chú khách hàng',
            'details' => 'Ghi chú nhân viên',
            'firstname' => 'Người nhận hàng',
            'phone' => 'Số điện thoại',
            's_address' => 'Địa chỉ giao hàng',
            'is_vat' => 'Xuất hóa đơn',
            'admin_id' => 'Nhân viên phụ trách',
            'product_code' => 'Mã sản phẩm',
            'product_name' => 'Sản phẩm',
            'price' => 'Giá sản phẩm',
            'amount' => 'Số lượng',
            'type' => 'Đơn hàng từ',
            'discount' => 'Giảm giá',
        ];
    }

    public function getReportOrderLastYearGroupByStatus() {
        $from = \Carbon\Carbon::now()->addYear(-1)->format('Y-01-01');
        $to = \Carbon\Carbon::now()->addYear(-1)->format('Y-12-31 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderRangeGroupByStatus($from, $to) {
        if (!is_integer($from)) {
            $from = strtotime($from);
        }
        if (!is_integer($to)) {
            $to = strtotime($to);
        }

        $objects = $this->select('status as order_status',
            \DB::raw('COUNT( order_id ) AS total'),
            \DB::raw('COALESCE(SUM( total ),0) AS sales'),
            \DB::raw('COALESCE(sum(subtotal),0) as revenue'))
            ->where('timestamp', '>=', $from)
            ->where('timestamp', '<=', $to)
            ->groupBy('status')
            ->get();

        $rs = [];

        foreach ($objects as $value) {
            $rs[$value['order_status']] = $value->toArray();
        }

        return $rs;
    }

    public function getReportOrderInDayGroupByStatus() {
        $from = \Carbon\Carbon::now()->format('Y-m-d 00:00:00');
        $to = \Carbon\Carbon::now()->format('Y-m-d 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderInYesterdayGroupByStatus() {
        $from = \Carbon\Carbon::yesterday()->format('Y-m-d 00:00:00');
        $to = \Carbon\Carbon::yesterday()->format('Y-m-d 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderInWeekGroupByStatus() {
        $from = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
        $to = \Carbon\Carbon::now()->format('Y-m-d 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderInLastWeekGroupByStatus() {
        $from = \Carbon\Carbon::now()->addWeek(-1)->startOfWeek()->format('Y-m-d 00:00:00');
        $to = \Carbon\Carbon::now()->addWeek(-1)->endOfWeek()->format('Y-m-d 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderInMonthGroupByStatus() {
        $from = \Carbon\Carbon::now()->format('Y-m-01 00:00:00');
        $to = \Carbon\Carbon::now()->format('Y-m-d 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderInLastMonthGroupByStatus() {
        $from = \Carbon\Carbon::now()->addMonth(-1)->startOfMonth()->format('Y-m-01 00:00:00');
        $to = \Carbon\Carbon::now()->addMonth(-1)->endOfMonth()->format('Y-m-d 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderInYearGroupByStatus() {
        $from = \Carbon\Carbon::now()->format('Y-01-01 00:00:00');
        $to = \Carbon\Carbon::now()->format('Y-m-d 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public static function getData($params=[],$limit = 10,$lang_code='vi'){
        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $query = self::select('orders.*', 'payment_descriptions.payment', 'admin_users.username', 'admin_users.full_name as admin_full_name');

        $query->leftJoin('admin_users','admin_users.id','=','orders.admin_id');

        if(isset($params['admin_id'])) {
            $query->where('orders.admin_id',$params['admin_id']);
        }

        $query->leftJoin('payment_descriptions',function($join) use ($lang_code){
            $join->on('payment_descriptions.payment_id','=','orders.payment_id');
            $join->where('payment_descriptions.lang_code',$lang_code);
        });

        if(isset($params['type'])) {
            $query->where("orders.type", $params['type']);
        }

        if(isset($params['code'])) {
            $query->whereRaw("( orders.order_id='{$params['code']}' OR orders.validation_code='{$params['code']}')");
        }

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('timestamp','>=', strtotime($tmp));
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('timestamp','<=',strtotime($tmp));
        }

        if(!empty($params['b_firstname']))
            $query->where('orders.b_firstname',$params['b_firstname']);

        if(!empty($params['b_phone']))
            $query->where('orders.b_phone',$params['b_phone']);

        if(!empty($params['email']))
            $query->where('orders.email',$params['email']);

        if(!empty($params['status']))
            $query->whereIn('orders.status',$params['status']);

        if(!empty($params['province_id']))
            $query->where('orders.province_id',$params['province_id']);

        if(!empty($params['district_id']))
            $query->where('orders.district_id',$params['district_id']);

        if(!empty($params['payment_id']))
            $query->whereIn('orders.payment_id',$params['payment_id']);

        if(!empty($params['category_id'])){
            $query->whereExists(function($query1) use ($params){
                $query1->select(\DB::raw(1))
                    ->from('orders_details')
                    ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                    ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                    ->whereRaw('(products_categories.category_id = '.$params['category_id'].')')
                    ->whereRaw('(products_categories.position = 0)');
            });
        }

        if(!empty($params['sub_category'])){
            $query->whereExists(function($query2) use ($params){
                $query2->select(\DB::raw(1))
                    ->from('orders_details')
                    ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                    ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                    ->whereRaw('(products_categories.category_id = '.$params['sub_category'].')')
                    ->whereRaw('(products_categories.position = 1)');
            });
        }
//        dd($query->sum('total'));
        $query->orderBy('orders.order_id','desc');

        $rs = $query->paginate($limit);
        $rs = $rs->toArray();

        return $rs;
    }
    public static function getRevenue($params=[]){
        $query = self::select(\DB::raw('total'));

        if(isset($params['admin_id'])) {
            $query->where('orders.admin_id',$params['admin_id']);
        }

        if(isset($params['type'])) {
            $query->where("orders.type", $params['type']);
        }

        if(isset($params['code'])) {
            $query->whereRaw("( orders.order_id='{$params['code']}' OR orders.validation_code='{$params['code']}')");
        }

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('timestamp','>=', strtotime($tmp));
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('timestamp','<=',strtotime($tmp));
        }

        if(!empty($params['b_firstname']))
            $query->where('orders.b_firstname',$params['b_firstname']);

        if(!empty($params['b_phone']))
            $query->where('orders.b_phone',$params['b_phone']);

        if(!empty($params['email']))
            $query->where('orders.email',$params['email']);

        if(!empty($params['status']))
            $query->whereIn('orders.status',$params['status']);

        if(!empty($params['province_id']))
            $query->where('orders.province_id',$params['province_id']);

        if(!empty($params['district_id']))
            $query->where('orders.district_id',$params['district_id']);

        if(!empty($params['payment_id']))
            $query->whereIn('orders.payment_id',$params['payment_id']);

        if(!empty($params['category_id'])){
            $query->whereExists(function($query1) use ($params){
                $query1->select(\DB::raw(1))
                    ->from('orders_details')
                    ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                    ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                    ->whereRaw('(products_categories.category_id = '.$params['category_id'].')')
                    ->whereRaw('(products_categories.position = 0)');
            });
        }

        if(!empty($params['sub_category'])){
            $query->whereExists(function($query2) use ($params){
                $query2->select(\DB::raw(1))
                    ->from('orders_details')
                    ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                    ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                    ->whereRaw('(products_categories.category_id = '.$params['sub_category'].')')
                    ->whereRaw('(products_categories.position = 1)');
            });
        }

        return $query->sum('total');
    }

    public static function getPaymented($params=[], $lang_code='vi'){
        if (is_array(self::$_status_paymented)) {
            $query = self::whereIn('status', self::$_status_paymented);
        } else {
            $query = self::where('status', 'C');
        }

        if(isset($params['admin_id'])) {
            $query->where('orders.admin_id',$params['admin_id']);
        }

        if(isset($params['type'])) {
            $query->where("orders.type", $params['type']);
        }

        if(isset($params['code'])) {
            $query->whereRaw("( orders.order_id='{$params['code']}' OR orders.validation_code='{$params['code']}')");
        }

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('timestamp','>=', strtotime($tmp));
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('timestamp','<=',strtotime($tmp));
        }

        if(!empty($params['b_firstname']))
            $query->where('orders.b_firstname',$params['b_firstname']);

        if(!empty($params['b_phone']))
            $query->where('orders.b_phone',$params['b_phone']);

        if(!empty($params['email']))
            $query->where('orders.email',$params['email']);

        if(!empty($params['status']))
            $query->whereIn('orders.status',$params['status']);

        if(!empty($params['province_id']))
            $query->where('orders.province_id',$params['province_id']);

        if(!empty($params['district_id']))
            $query->where('orders.district_id',$params['district_id']);

        if(!empty($params['payment_id']))
            $query->whereIn('orders.payment_id',$params['payment_id']);

        if(!empty($params['category_id'])){
            $query->whereExists(function($query1) use ($params){
                $query1->select(\DB::raw(1))
                    ->from('orders_details')
                    ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                    ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                    ->whereRaw('(products_categories.category_id = '.$params['category_id'].')')
                    ->whereRaw('(products_categories.position = 0)');
            });
        }

        if(!empty($params['sub_category'])){
            $query->whereExists(function($query2) use ($params){
                $query2->select(\DB::raw(1))
                    ->from('orders_details')
                    ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                    ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                    ->whereRaw('(products_categories.category_id = '.$params['sub_category'].')')
                    ->whereRaw('(products_categories.position = 1)');
            });
        }

        return $query->sum('total');
    }

    public static function getDataById($order_id,$lang_code='vi'){

        $query = self::select('orders.*',
            'orders_status.order_status_name','payment_descriptions.payment'
        );

        $query->leftJoin('orders_status',function($join) use ($lang_code){
            $join->on('orders_status.status','=','orders.status');
            $join->where('orders_status.lang_code',$lang_code);
        });

        $query->leftJoin('payment_descriptions',function($join) use ($lang_code){
            $join->on('payment_descriptions.payment_id','=','orders.payment_id');
            $join->where('payment_descriptions.lang_code',$lang_code);
        });

        $rs = $query->find($order_id)->toArray();

        return $rs;
    }

    public static function getDetailById($order_id,$lang_code='vi'){

        $query = self::select('orders.*',
            'orders_status.order_status_name','payment_descriptions.payment',
            \DB::raw("CONCAT_WS(' ', provinces.type, provinces.name) as province_name"),
            \DB::raw("CONCAT_WS(' ', districts.type, districts.name) as district_name"),
            \DB::raw("CONCAT_WS(' ', wards.type, wards.name) as ward_name"),
            'admin_users.username', 'admin_users.full_name as admin_full_name'
        );

        $query->leftJoin('admin_users','admin_users.id','=','orders.admin_id');

        $query->leftJoin('orders_status',function($join) use ($lang_code){
            $join->on('orders_status.status','=','orders.status');
            $join->where('orders_status.lang_code',$lang_code);
        });

        $query->leftJoin('payment_descriptions',function($join) use ($lang_code){
            $join->on('payment_descriptions.payment_id','=','orders.payment_id');
            $join->where('payment_descriptions.lang_code','VN');
        });

        $query->leftJoin('provinces', 'provinces.province_id', '=', 'orders.province_id')
            ->leftJoin('districts', 'districts.district_id', '=', 'orders.district_id')
            ->leftJoin('wards', 'wards.ward_id', '=', 'orders.ward_id');
               
        $rs = $query->find($order_id)->toArray();

        $b_address = [$rs['b_address']];
        if ($rs['ward_name'] && strpos($b_address[0], $rs['ward_name']) === false) {
            $b_address[] = $rs['ward_name'];
        }
        if ($rs['district_name'] && strpos($b_address[0], $rs['district_name']) === false) {
            $b_address[] = $rs['district_name'];
        }
        if ($rs['province_name'] && strpos($b_address[0], $rs['province_name']) === false) {
            $b_address[] = $rs['province_name'];
        }
        $b_address = implode(", ", $b_address);
        $rs['b_address'] = $b_address;

        return $rs;
    }
    public static function get_company_invoice($order_id) {
        $object = \App\Models\ProfileFieldsData::where([
            'object_id' => $order_id,
            'object_type' => 'O',
            'field_id' => 50
        ])->first();

        return $object ? $object->toArray() : [];
    }
    public static function insert_company_invoice($order_id, $value=null, $value_json=null) {
        $object = ProfileFieldsData::where([
            'object_id' => $order_id,
            'object_type' => 'O',
            'field_id' => 50
        ])->first();

        if ($object) {
            if ($value) {
                $object->update(['value' => $value, 'extra' => $value_json]);
            } else {
                $object->delete();
            }
            return true;
        }

        if ($value) {
            ProfileFieldsData::create([
                'object_id' => $order_id,
                'object_type' => 'O',
                'field_id' => 50,
                'value' => $value,
                'extra' => $value_json
            ]);
        }
    }

    public static function getAllDataForReport($params=[]){

        $query = self::select('orders.*');

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('timestamp','>=', strtotime($tmp));
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('timestamp','<=',strtotime($tmp));
        }

        if(!empty($params['status']))
            $query->whereIn('orders.status',$params['status']);

        return $query->get()->pluck('order_id')->toArray();
    }
}
