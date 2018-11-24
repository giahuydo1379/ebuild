<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Booking extends Model
{
    use CrudTrait;

    protected static $_status_paymented = 1;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'booking';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['customer_id', 'admin_id', 'address_location', 'address_number',
        'start_date', 'end_date', 'benefit', 'contact_id','customer_note', 'notes',
        'payment_method_id','total_amount','service_id','booking_status_id',
        'status','user_created', 'user_modified', 'is_deleted', 'deleted_at'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function customer(){
        return $this->belongsTo('App\Models\Customers', 'customer_id');
    }
    public function service(){
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
    public function bookingDetail(){
        return $this->hasMany('App\Models\BookingDetail', 'booking_id', 'id');
    }
    public function bookingFreezerDetail(){
        return $this->hasOne('App\Models\BookingFreezerDetail', 'booking_id', 'id');
    }
    public function contact(){
        return $this->belongsTo('App\Models\Contacts', 'contact_id');
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public static function getDetailBooking($id){
        $query = self::select('booking.*',
            'services.name as service_name',
            'customers.name as customer_name',
            'contacts.name as contact_name','contacts.phone','contacts.email');

        $query->leftJoin('customers',function($query){
            $query->on('customers.id','=','booking.customer_id');
        });
        $query->leftJoin('services',function($query){
            $query->on('services.id','=','booking.service_id');
            $query->where('services.is_deleted',0);
        });
        $query->leftJoin('contacts',function($query){
            $query->on('contacts.id','=','booking.contact_id');
        });

        $result = $query->find($id)->toArray();

        return $result;
    }

    public static function fillable_no_auditable() {
        return [
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
            'code' => 'Mã đơn hàng',
            'booking_status_id' => 'Trạng thái',
            'total_amount' => 'Tổng cộng',
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
            'source' => 'Đơn hàng từ',
            'discount' => 'Giảm giá',
        ];
    }

    public function getReportOrderLastYearGroupByStatus() {
        $from = \Carbon\Carbon::now()->addYear(-1)->format('Y-01-01');
        $to = \Carbon\Carbon::now()->addYear(-1)->format('Y-12-31 23:59:59');

        return $this->getReportOrderRangeGroupByStatus($from, $to);
    }

    public function getReportOrderRangeGroupByStatus($from, $to) {
        $objects = $this->select('booking_status_id as booking_status',
            \DB::raw('COUNT( id ) AS total'),
            \DB::raw('COALESCE(SUM( total_amount ),0) AS sales'),
            \DB::raw('COALESCE(sum(IF(booking_status_id='.config('booking.status_order_collected').', total_amount, 0)),0) as revenue'))
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->groupBy('booking_status_id')
            ->get();

        $rs = [];

        foreach ($objects as $value) {
            $rs[$value['booking_status']] = $value->toArray();
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

        $query = self::select('booking.*', 'payments_method.name as payment',
            'users.username', 'users.fullname as admin_fullname',
            'services.name as service_name',
            'contacts.name as fullname', 'contacts.phone', 'contacts.email'
            );

        $query->leftJoin('contacts',function($join){
            $join->on('contacts.id', '=', 'booking.contact_id');
        });

        $query->leftJoin('services',function($join){
            $join->on('services.id', '=', 'booking.service_id');
        });

        $query->leftJoin('users','users.id','=','booking.admin_id');

        if(isset($params['admin_id'])) {
            $query->where('booking.admin_id',$params['admin_id']);
        }

        $query->leftJoin('payments_method', function($join) {
            $join->on('payments_method.id','=','booking.payment_method_id');
        });

        if(isset($params['type'])) {
            $query->where("booking.source", $params['type']);
        }

        if(isset($params['code'])) {
            $query->whereRaw("( booking.id='{$params['code']}' OR booking.code='{$params['code']}')");
        }

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('booking.created_at','>=', $tmp);
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('booking.created_at','<=', $tmp);
        }

        if(!empty($params['fullname']))
            $query->where('contacts.name',$params['fullname']);

        if(!empty($params['phone']))
            $query->where('contacts.phone',$params['phone']);

        if(!empty($params['email']))
            $query->where('contacts.email',$params['email']);

        if(!empty($params['status']))
            $query->whereIn('booking.booking_status_id', $params['status']);

        if(!empty($params['province_id']))
            $query->where('booking.province_id',$params['province_id']);

        if(!empty($params['district_id']))
            $query->where('booking.district_id',$params['district_id']);

        if(!empty($params['payment_method_id']))
            $query->whereIn('booking.payment_method_id',$params['payment_method_id']);

        $query->orderBy('booking.id','desc');

        $rs = $query->paginate($limit);
        $rs = $rs->toArray();

        return $rs;
    }
    public static function getRevenue($params=[]){
        $query = self::select(\DB::raw('total_amount'));

        $query->leftJoin('contacts',function($join){
            $join->on('contacts.id', '=', 'booking.contact_id');
        });

        if(isset($params['admin_id'])) {
            $query->where('booking.admin_id',$params['admin_id']);
        }

        if(isset($params['type'])) {
            $query->where("booking.source", $params['type']);
        }

        if(isset($params['code'])) {
            $query->whereRaw("( booking.id='{$params['code']}' OR booking.code='{$params['code']}')");
        }

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('booking.created_at','>=', $tmp);
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('booking.created_at','<=', $tmp);
        }

        if(!empty($params['fullname']))
            $query->where('contacts.name',$params['fullname']);

        if(!empty($params['phone']))
            $query->where('contacts.phone',$params['phone']);

        if(!empty($params['email']))
            $query->where('contacts.email',$params['email']);

        if(!empty($params['status']))
            $query->whereIn('booking.booking_status_id', $params['status']);

        if(!empty($params['province_id']))
            $query->where('booking.province_id',$params['province_id']);

        if(!empty($params['district_id']))
            $query->where('booking.district_id',$params['district_id']);

        if(!empty($params['payment_method_id']))
            $query->whereIn('booking.payment_method_id',$params['payment_method_id']);

        return $query->sum('total_amount');
    }

    public static function getPaymented($params=[], $lang_code='vi'){
        if (is_array(self::$_status_paymented)) {
            $query = self::whereIn('booking_status_id', self::$_status_paymented);
        } else {
            $query = self::where('booking_status_id', 'C');
        }

        $query->leftJoin('contacts',function($join){
            $join->on('contacts.id', '=', 'booking.contact_id');
        });

        if(isset($params['admin_id'])) {
            $query->where('booking.admin_id',$params['admin_id']);
        }

        if(isset($params['type'])) {
            $query->where("booking.source", $params['type']);
        }

        if(isset($params['code'])) {
            $query->whereRaw("( booking.id='{$params['code']}' OR booking.code='{$params['code']}')");
        }

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('booking.created_at','>=', $tmp);
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('booking.created_at','<=', $tmp);
        }

        if(!empty($params['fullname']))
            $query->where('contacts.name',$params['fullname']);

        if(!empty($params['phone']))
            $query->where('contacts.phone',$params['phone']);

        if(!empty($params['email']))
            $query->where('contacts.email',$params['email']);

        if(!empty($params['status']))
            $query->whereIn('booking.booking_status_id', $params['status']);

        if(!empty($params['province_id']))
            $query->where('booking.province_id',$params['province_id']);

        if(!empty($params['district_id']))
            $query->where('booking.district_id',$params['district_id']);

        if(!empty($params['payment_method_id']))
            $query->whereIn('booking.payment_method_id',$params['payment_method_id']);

        return $query->sum('total_amount');
    }

    public static function getDataById($booking_id, $lang_code='vi'){

        $query = self::select('booking.*',
            'booking_status.booking_status_name','payments_method.name as payment'
        );

        $query->leftJoin('booking_status',function($join) use ($lang_code){
            $join->on('booking_status.booking_status_id','=','booking.booking_status_id');
            $join->where('booking_status.lang_code',$lang_code);
        });

        $query->leftJoin('payments_method', function($join) {
            $join->on('payments_method.id','=','booking.payment_method_id');
        });

        $rs = $query->find($booking_id)->toArray();

        return $rs;
    }

    public static function getDetailById($booking_id,$lang_code='vi'){

        $query = self::select('booking.*'
            ,'booking_status.booking_status_name', 'payments_method.name as payment',
            'users.username', 'users.fullname as admin_fullname',
            'services.name as service_name',
            'contacts.name as fullname','contacts.phone','contacts.email'
        );

        $query->leftJoin('users','users.id','=','booking.admin_id');

        $query->leftJoin('booking_status',function($join) use ($lang_code){
            $join->on('booking_status.booking_status_id','=','booking.booking_status_id');
            $join->where('booking_status.lang_code',$lang_code);
        });

        $query->leftJoin('payments_method', function($join) {
            $join->on('payments_method.id','=','booking.payment_method_id');
        });

        $query->leftJoin('services', 'services.id', '=', 'booking.service_id');
        $query->leftJoin('contacts',function($query){
            $query->on('contacts.id','=','booking.contact_id');
        });

        $rs = $query->find($booking_id)->toArray();

        return $rs;
    }
    public static function get_company_invoice($booking_id) {
        $object = \App\Models\OrdersData::where([
            'booking_id' => $booking_id,
            'type' => 'invoice'
        ])->first();

        return $object ? $object->toArray() : [];
    }
    public static function insert_company_invoice($booking_id, $value=null, $value_json=null) {
        $object = OrdersData::where([
            'booking_id' => $booking_id,
            'type' => 'invoice'
        ])->first();

        if ($object) {
            if ($value) {
                $object->update(['data' => $value]);
            } else {
                $object->delete();
            }
            return true;
        }

        if ($value) {
            OrdersData::create([
                'booking_id' => $booking_id,
                'type' => 'invoice',
                'data' => $value
            ]);
        }
    }

    public static function getAllDataForReport($params=[]){

        $query = self::select('booking.*');

        if(!empty($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $query->where('booking.created_at','>=', $tmp);
        }

        if(!empty($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $query->where('booking.created_at','<=', $tmp);
        }

        if(!empty($params['status']))
            $query->whereIn('booking.booking_status_id', $params['status']);

        return $query->get()->pluck('id')->toArray();
    }

    public static function getDetailByOrderId($booking_id){
        $result = self::select('booking.*', 'services.name as service_name')
            ->leftJoin('services',function($join){
                $join->on('services.id', '=', 'booking.service_id');
            })
            ->find($booking_id);

        if($result)
            return $result->toArray();
        return [];
    }
}
