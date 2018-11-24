<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Jobs\SendEmailOrderSuccess;
use App\Models\BookingFreezerDetail;
use App\Models\Contacts;
use App\Models\Service;
use App\Models\ServicesExtra;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Categories;
use App\Models\Packages;
use App\Models\Products;
use App\Models\BookingDetail;
use Validator;

class BookingController extends Controller
{
    protected $data = []; // the information we send to the view
    protected $key_cart_order;
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'booking';
        $this->data['title'] = 'Đơn hàng';
        $this->key_cart_order = 'cart-order';
    }

    public function booking_success(Request $request, $order_id){
        $this->data['title'] = 'Tạo mới đơn hàng thành công';
        $this->data['order']        = Booking::getDetailByOrderId($order_id);
        $this->data['order_detail'] = \App\Models\BookingDetail::getDataByOrderId($order_id);

        if(empty($this->data['order']) || empty($this->data['order_detail']))
            abort(404);


        return view("{$this->data['controllerName']}.booking-success", $this->data);
    }

    public function index(Request $request){
    	$params 	= $request->all();
    	$objects 	= Booking::getData($params);

    	$order_ids 	=	array_pluck($objects['data'],'order_id');
        $orders_detail       = BookingDetail::getDataByOrderId($order_ids);        
    	$revenue 	= Booking::getRevenue($params);
    	$paymented 	= Booking::getPaymented($params);

    	$booking_status 	= \App\Models\BookingStatus::getAllData('vi');
    	$paymentDescription 	= \App\Models\PaymentDescription::getAllData();

    	$admin_users = \App\Models\AdminUsers::getSales();
    	$admin_users = array_pluck($admin_users,'name','id');

    	$this->data['objects']		= $objects;
    	$this->data['orders_detail']	= $orders_detail;
    	$this->data['revenue']		= $revenue;
    	$this->data['paymented']	= $paymented;
    	$this->data['booking_status']	= $booking_status;
    	$this->data['paymentDescription']	= $paymentDescription;
    	
    	$this->data['params']		= $params;
        $this->data['admin_users']		= $admin_users;

    	return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function print_booking(Request $request, $id){
        $html = $request->input('html', 0);

        $params 	= $request->all();
    
        $order              = Booking::getDetailById($id);
        $order_detail       = BookingDetail::getDataByOrderId($id);

        if (isset($order_detail[0]['service_extra_ids'])) {
            $tmp = json_decode($order_detail[0]['service_extra_ids'], 1);
            $services_extra = ServicesExtra::whereIn('id', array_pluck($tmp, 'id'))->pluck('name', 'id')->toArray();

            $this->data['service_extra_ids'] = $tmp;
            $this->data['services_extra'] = $services_extra;
        }

        $this->data['order']		= $order;
        $this->data['order_detail']	= $order_detail;

        if ($html) {
            return view("{$this->data['controllerName']}.print", $this->data);
        }

        $pdf = \PDF::loadView("{$this->data['controllerName']}.print", $this->data);
        return $pdf->stream('print.pdf');
    }

    public function show(Request $request,$id){
    	$params 	= $request->all();

    	$object = Booking::getDetailById($id);
        $booking_detail = BookingDetail::getDataByOrderId($id);

        if (isset($booking_detail[0]['service_extra_ids'])) {
            $tmp = json_decode($booking_detail[0]['service_extra_ids'], 1);
            $services_extra = ServicesExtra::whereIn('id', array_pluck($tmp, 'id'))->pluck('name', 'id')->toArray();

            $this->data['service_extra_ids'] = $tmp;
            $this->data['services_extra'] = $services_extra;
        }

    	$booking_status 	= \App\Models\BookingStatus::getAllData('vi');
    	$booking_status 	= array_pluck($booking_status,'booking_status_name','booking_status_id');

    	$this->data['object']		= $object;
    	$this->data['booking_detail']	= $booking_detail;
    	$this->data['booking_status']	= $booking_status;
        
    	if ($object['admin_id']) {
            $admin = \App\User::find($object['admin_id']);
            if ($admin) {
                $admin = $admin->toArray();
            } else {
                $admin = ['username' => $object['admin_id'], 'fullname' => $object['admin_id']];
            };
            $this->data['admin'] = $admin;
        }

        $this->data['audits'] = \App\Models\Audits::getAudits(['App\Models\Booking', 'App\Models\BookingFreezerDetail',
            'App\Models\BookingDetail'], $id);

        $this->data['event_options'] = Booking::getOptionsEvent();
        $this->data['field_name_options'] = Booking::getOptionsFieldName();
        $this->data['fillable_no_auditable'] = Booking::fillable_no_auditable();
    	return view("{$this->data['controllerName']}.show", $this->data);
    }

    public function edit(Request $request, $id){
    	$object 		= Booking::getDetailById($id);

    	$tmp = strtotime($object['start_date']);
        $object['start_date'] = date('d-m-Y', $tmp);
        $object['time'] = date('H:i', $tmp);

        if ($object['end_date']) {
            $tmp = strtotime($object['end_date']);
            $object['end_date'] = date('d-m-Y', $tmp);
            $object['end_time'] = date('H:i', $tmp);
        }

    	$service_id = $object['service_id'];

    	if ($service_id == config('app.freezer')) {
            $booking_detail = BookingFreezerDetail::getDataByOrderId($id);
            $booking_detail[0]['service_extra_ids'] = @$booking_detail[0]['services_extra'];
        } else {
            $booking_detail = BookingDetail::getDataByOrderId($id);
        }

        $service_extra_ids = [];
        if (isset($booking_detail[0]['service_extra_ids'])) {
            $tmp = json_decode($booking_detail[0]['service_extra_ids'], 1);
            foreach ($tmp as $item) {
                $service_extra_ids[] = $item['id'];
            }
        }

        $this->data['object']           = $object;
        $this->data['booking_detail']   = $booking_detail;
        $this->data['service_extra_ids']   = $service_extra_ids;

        if ($service_id) {
            $this->data['url'] = route('booking.edit', ['id' => $id]);

            $view = $this->set_data($request,$service_id);
            if(view()->exists($view)){
                return view($view, $this->data);
            }
            abort(404);
        }
    }

    public function create(Request $request, $slug=null, $service_id=null) {
        $objects        = Auth::request_api('service/serviceRoot');
        $service_root   = $objects['data']??[];
        $this->data['objects'] = $service_root;

        if ($service_id) {
            $service_root   = array_pluck($service_root,'id');
            if(!in_array($service_id, $service_root)){
                abort(404);
            }
            
            $this->data['url'] = route('booking.create', ['slug' => $slug, 'service_id' => $service_id]);

            $view = $this->set_data($request,$service_id);

            if(view()->exists($view)){
                return view($view, $this->data);
            }
            abort(404);
        }
        return view("{$this->data['controllerName']}.choose-service",$this->data);
    }

    public function set_data($request,$service_id){
        $params = $request->all();
        $this->data['service']      = Service::find($service_id);
        $this->data['service_id']   = $service_id;
        $this->data['key_step1'] = 'data_step1_'.$service_id;
        $this->data['key_step2'] = 'data_step2_'.$service_id;
        $this->data['key_step3'] = 'data_step3_'.$service_id;

        if(!empty($params['step'])){

            switch ($params['step']) {
                case 2:
                    $this->set_data_payments_method();
                    break;
                default:
                    break;
            }

            $view = "booking.booking-step".$params['step'];

            return $view;
        }

        $content = Auth::request_api('service-unit/all',['service_id' => $service_id]);
        $this->data['service_unit'] = $content['data']??[];

        $content = Auth::request_api('services-extra/all',['service_id' => $service_id]);
        $this->data['services_extra'] = $content['data']??[];

        // if (auth()->check()) {
        //     $content            = Auth::request_api('customer/employees-favorite');
        //     $employees_favorite = $content['data']??[];
        //     if(!empty($employees_favorite)){
        //         $employees_favorite = array_pluck($employees_favorite,'name','id');
        //     }
        //     $this->data['employees_favorite'] = $employees_favorite;
        // } else {
        //     $this->data['employees_favorite'] = [];
        // }
        $this->data['employees_favorite'] = [];
        $view = "booking.booking";
        return $view;   
    }

    public function set_data_clean_house($request, $service_id){
        $params = $request->all();

        $this->data['key_step1'] = 'data_step1_'.$service_id;
        $this->data['key_step2'] = 'data_step2_'.$service_id;
        $this->data['key_step3'] = 'data_step3_'.$service_id;

        if(!empty($params['step'])){

            switch ($params['step']) {
                case 2:
                    $this->set_data_payments_method();
                    break;
                default:
                    break;
            }

            $view = "booking.clean-house-step".$params['step'];

            return $view;
        }

        $content = Auth::request_api('service-unit/all',['service_id' => $service_id]);
        $this->data['service_unit'] = $content['data']??[];

        $content = Auth::request_api('services-extra/all',['service_id' => $service_id]);
        $this->data['services_extra'] = $content['data']??[];

        $this->data['service_id'] = $service_id;

        $content = Auth::request_api('service/'.$service_id);
        $this->data['service'] = $content['data']??[];

        $view = "booking.clean-house";
        return $view;
    }

    public function set_data_payments_method(){
        $content = Auth::request_api('payments-method/all');
        $this->data['payments_method'] = $content['data']??[];
    }

    public function set_data_washing($request){

        $params = $request->all();
        $service_id = config('app.washing');
        $this->data['washing_water_id']    = config('app.washing_water');
        $this->data['washing_dry_id']      = config('app.washing_dry');
        $this->data['service_id'] = $service_id;
        if(!empty($params['step'])){

            if(!empty($params['step'])){

                switch ($params['step']) {
                    case 2:
                        $this->set_data_washing_dry();
                    case 3:
                        $this->set_data_payments_method();
                        break;
                    default:
                        break;
                }

                $view = "booking.washing-step".$params['step'];

                return $view;
            }
        }

        $content = Auth::request_api('service-unit/all',['service_id' => $this->data['washing_water_id']]);
        $this->data['washing_water_price'] = $content['data'][0]['price']??0;

        $content = Auth::request_api('service-unit/all',['service_id' => $service_id]);
        $this->data['services_extra'] = $content['data'][0]??[];

        $view = "booking.washing";
        return $view;
    }

    public function set_data_washing_dry(){
        $washing_dry_id = config('app.washing_dry');
        $content = Auth::request_api('service/all-children/'.$washing_dry_id);

        $washing_dry = $content['data']??[];
        $tmp = [];
        foreach($washing_dry as $item){
            $tmp[$item['parent_id']][] = $item;
        }
        $this->data['washing_dry'] = $tmp;
    }

    public function set_data_freezer($request){
        $params = $request->all();
        $service_id = config('app.freezer');
        $this->data['service_id'] = $service_id;
        if(!empty($params['step'])){

            switch ($params['step']) {
                case 2:
                    $this->set_data_payments_method();
                    break;
                default:
                    break;
            }

            $view = "booking.freezer-step".$params['step'];

            return $view;
        }

        $content = Auth::request_api('freezers/freezers-services');

        $this->data['freezers']     = $content['data']['freezers']??[];
        $this->data['freezers_capacity']    = $content['data']['freezers_capacity']??[];
        $this->data['freezers_number_max']  = $content['data']['freezers_number_max']??[];

        $content = Auth::request_api('services-extra/all',['service_id' => $service_id]);
        $this->data['services_extra'] = $content['data']??[];

        $view = "booking.freezer";
        return $view;
    }

    public function getPriceFreezers(Request $request){
        $params = $request->all();

        $content = Auth::request_api('freezers/get-price-freezers',$params,'post');
        if(!empty($content['data']['price'])){
            return response()->json([
                'rs'    => 1,
                'price' => $content['data']['price']
            ]);
        }

        return response()->json([
            'rs'    => 0,
            'price' => 0
        ]);
    }

    public function store(Request $request)
    {
        $params = $request->all();
        if(empty($params['service_id']))
            return false;

        $action = 'booking';

        if (!isset($params['id']) || !$params['id']) {
            $params['source']   = 'admin';
            $params['admin_id'] = \Auth::user()->id;
        }

        $res = Auth::request_api($action, $params, 'post');

        if(isset($res['status']) && $res['status']=='success'){
            return response()->json([
                'rs'        => 1,
                'msg'       => 'Đăng ký sử dụng dịch vụ thành công',
                'params' => $params,
                'res' => $res,
            ]);
        }

        return response()->json([
            'rs' => 0,
            'params' => $params,
            'res' => $res,
            'msg' => 'Dữ liệu nhập không hợp lệ, Vui lòng kiểm tra lại thông tin'
        ]);
    }

    public function change_lock_status(Request $request){
        $params = $request->all();

        $object = Booking::find($params['id']);
        if(isset($params['status'])) {
            $params['booking_status_id'] = $params['status'];
            unset($params['status']);
        }

        $rs = 0;    
        if($object) {
            if (!$object->admin_id) {
                $params['admin_id'] = \Auth::user()->id;
            }

            $rs = $object->update($params);
        } else {
            return response()->json([
                'rs' => 0,
                'msg' => 'Không tìm thấy đơn hàng: '.$params['id'],
            ]);
        }

        if($rs){

            if(isset($params['booking_status_id'])){
                \App\Models\BookingEmployees::update_status($params['id'],$params['booking_status_id']);
            }

            return response()->json([
                'rs' => 1,
                'msg' => 'Cập nhật đơn hàng thành công',
                'data' => $params
            ]);
        }
            

        return response()->json([
            'rs' => 0,
            'msg' => 'Cập nhật đơn hàng không thành công',
            'data' => $params,
        ]);
    }

    public function change_status(Request $request){
        $data = $request->all();
        $ids = $request->input('ids', []);
        
        $rules = [
            'status'	=> 'required',
            'ids' => 'required|array',
        ];
        $messages = [
            'status.required'      => 'Chọn trạng thái đơn hàng',
            'ids.required'   => 'Chọn đơn hàng',
        ];

        $valid = Validator::make($data, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $data
            ]);
        }

        $rs = 0;
        foreach ($ids as $id) {
            $object = Booking::find($id);
            if ($object) {
                $data = [
                    'booking_status_id' => $data['status']
                ];

                if (!$object->admin_id) {
                    $data['admin_id'] = \Auth::user()->id;
                }

                $result = $object->update($data);
                if($result){
                    \App\Models\BookingEmployees::update_status($id,$data['booking_status_id']);
                }
                $rs++;
            }
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển trạng thái đơn hàng thành công',
                'data' => $data
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển trạng thái đơn hàng không thành công',
            'data' => $data,
        ]);

    }

    public function update_address(Request $request){
    	$params = $request->all();
        $rules = [
            'id'    => 'required',
            'fullname' 	    => 'required',
            'phone' 		=> 'required',
            'email' 		=> 'required',
        ];
        $messages = [
            'id'	        => 'Không có id đơn hàng',
            'fullname' 	    => 'Nhập tên',
            'phone' 		=> 'Nhập số điện thoại',
            'email' 		=> 'Nhập email',
        ];

        $valid = Validator::make($params, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $params
            ]);
        }

        $data_order = [
            'name'          => $params['fullname'],
            'phone'         => $params['phone'],
            'email'         => $params['email'],
        ];

        $object = Booking::find($params['id']);
        
        $rs = 0;	
        if($object) {
            if (!$object->admin_id) {
                $object->admin_id = \Auth::user()->id;
                $object->save();
            }

            $tmp = \App\Helpers\General::get_data_fillable(new \App\Models\Contacts(), $data_order);
            $rs = Contacts::where('id', $object->contact_id)->update($tmp);
        } else {
            return response()->json([
                'rs' => 0,
                'msg' => 'Không tìm thấy đơn hàng: '.$params['id'],
            ]);
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Cập nhật đơn hàng thành công',
                'data' => $params
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Cập nhật đơn hàng không thành công',
            'data' => $params,
        ]);
    }

    public function update_note(Request $request){

    	$params = $request->all();

    	$object = Booking::find($params['id']);
        
        $rs = 0;	
        if($object) {
            if (!$object->admin_id) {
                $params['admin_id'] = \Auth::user()->id;
            }
            $rs = $object->update($params);
        } else {
            return response()->json([
                'rs' => 0,
                'msg' => 'Không tìm thấy đơn hàng: '.$params['order_id'],
            ]);
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Cập nhật đơn hàng thành công',
                'data' => $params
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Cập nhật đơn hàng không thành công',
            'data' => $params,
        ]);
    }

    public function search_product(Request $request){
    	$kw = $request->input('kw', '');	
    	$except_pids = $request->input('except_pids', '');

    	$data = Products::search($kw, 10, 'vi', $except_pids);
    	if(empty($data))
    		return response()->json([
	            'rs' => 0,
	            'msg' => 'not found',
	            'data' => [],
        	]);

    	return response()->json([
            'rs' => 1,
            'msg' => 'success',
            'data' => $data,
        ]);
    }

    public function add_product(Request $request) {
        $product_id = $request->input('product_id', '');
        $amount = $request->input('amount', 1);
        $discount = $request->input('discount', 0);

        $object = \App\Models\Products::getProductAddCartById($product_id);

        // neu het hang
        if ($object['amount'] < 1) {
            return response()->json([
                'status' => 0,
                'msg' => 'Sản phẩm đã hết hàng. Bạn vui lòng kiểm tra lại!',
                'data' => ['product' => $object]
            ]);
        }

        if ($object['amount'] < $amount) {
            return response()->json([
                'status' => 0,
                'msg' => 'Chỉ còn '.$object['amount'].' sản phẩm. Bạn vui lòng kiểm tra lại!',
                'data' => ['product' => $object]
            ]);
        }

        // lay danh sach san pham trong gio hang
        $cart_products  = $request->session()->get( $this->key_cart_order );
        
        $cart_products = $cart_products['data'];

        // them moi hoac cap nhat san pham trong gio hang
        $cart_products[$product_id] = [
            'product_id'    => $product_id,
            'amount'        => $amount,//$amount < $object['amount'] ? $amount : $object['amount'],
            'discount'      => $discount,
            'price'         => $object['price'],
            'brand_id'      => $object['brand_id'],
            'weight'        => $object['weight'],
        ];

        // duyet de tin tong
        $subtotal = 0;
        $weight = 0;
        $cart_brands = [];
        
        foreach ($cart_products as $pid => $item) {
            if (!isset($item['discount'])) $item['discount'] = 0;
            $subtotal += $item['amount'] * $item['price'] - $item['discount'];
            $weight += $item['weight'];

            if (isset($item['brand_id'])) {
                if (isset($cart_brands[$item['brand_id']])) {
                    $cart_brands[$item['brand_id']] += $item['amount'];
                } else {
                    $cart_brands[$item['brand_id']] = $item['amount'];
                }
            }
        }
        $count = count($cart_products);

        session([$this->key_cart_order => ['update' => 1,'data' => $cart_products]]);

        $promotions = $this->promotions_cart($cart_products, $cart_brands);

        return response()->json([
            'status' => 1,
            'msg' => '',
            'data' => [
                'count' => $count,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'weight' => $weight,
                'price' => $object['price'],
                'promotions' => $promotions,
                'product' => $object
            ]
        ]);
    }

    public function remove_product(Request $request) {
        
        $product_id = $request->input('product_id', '');

        $cart_products = $request->session()->get( $this->key_cart_order );
        
        $cart_products = $cart_products['data'];

        foreach ($cart_products as $index => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_products[$index]);
                break;
            }
        }

        $total = 0;
        $weight = 0;
        
        foreach ($cart_products as $pid => $item) {
            $total += $item['amount'] * $item['price'];
            $weight += $item['weight'];
        }

        session([$this->key_cart_order =>['update' => 1,'data' => $cart_products]]);

        return response()->json(['total' => $total,'weight' => $weight]);
    }

    public function promotions_cart($cart_products, $cart_brands, $is_store=false) {
        $promotions = \App\Models\Promotion::getAllPromotion();

        if(empty($promotions)) return [];

        $product_ids = [];
        $rs = [];
        // duyet id san pham va so luong mua
        foreach ($cart_products as $pid => $item) {
            $brand_id = $item['brand_id'];
            $rs[$pid] = $this->check_promotons_for_product($promotions, $pid, $item['amount'], $brand_id, $cart_brands[$brand_id]);

            // chi show qua tang cho thuong hieu o san pham đau tien
            if (isset($rs[$pid]['promotions'][7])) {
                $cart_brands[$brand_id] = false;
            }

            $product_ids = array_merge($product_ids, $rs[$pid]['gift_product_ids']);
        }
        
        //viet lai
        $products = \App\Models\Products::getProductsShow($product_ids);
        
        $product_ids = [];
        foreach($products as $item){
            $product_ids[$item['product_id']] = $item;
        }
        $products = $product_ids;

        if ($is_store) {
            return ['promotions' => $rs, 'gifts' => $products];
        }

        $promotions = [];
        foreach ($rs as $pid => $item) {
            $item['products'] = $products;
            $promotions[$pid] = view("{$this->data['controllerName']}.promotion", $item)->render();
        }

        return $promotions;
    }

    public function check_promotons_for_product($promotions, $product_id, $qty, $brand_id, $brand_qty) {
        $rs = [];
        $product_ids = [];

        // duyet cac chuong trinh khuyen mai
        foreach($promotions as $item) {
            $apply_objects = json_decode($item['apply_objects'],1);

            switch ($item['package_id']) {
                case 1:
                {
                    // neu san pham co khuyen mai va so luong phu hop
                    if (isset($apply_objects[$product_id]) && $qty >= $apply_objects[$product_id]) {
                        $item['apply_objects'] = $apply_objects;
                        $rs[$item['id']] = $item;
                    }
                }
                    break;
                case 2:
                case 4:
                    {
                        $item['gift_products'] = json_decode($item['gift_products'], 1);
                        // neu san pham co khuyen mai va so luong phu hop
                        if (isset($apply_objects[$product_id]) && $qty >= $apply_objects[$product_id] && is_array($item['gift_products'])) {
                            $item['apply_objects'] = $apply_objects;
                            $product_ids           = array_merge($product_ids, $item['gift_products']);

                            $rs[$item['id']] = $item;
                        }
                    }
                    break;
                case 7:
                    {
                        $item['gift_products'] = json_decode($item['gift_products'], 1);
                        // neu thuong hiệu co khuyen mai va so luong phu hop
                        if (isset($apply_objects[$brand_id]) && $brand_qty && $brand_qty >= $apply_objects[$brand_id] && is_array($item['gift_products'])) {
                            $item['apply_objects'] = $apply_objects;
                            $product_ids           = array_merge($product_ids, $item['gift_products']);

                            $rs[$item['id']] = $item;
                        }
                    }
                    break;
            }
        }

        return ['promotions' => $rs, 'gift_product_ids' => $product_ids];
    }

    public function promotions_cart_store($cart_products, $cart_brands, $products)
    {
        $p = $this->promotions_cart($cart_products, $cart_brands, true);

        if (!isset($p['promotions'])) return [];

        $promotions = [];

        foreach ($p['promotions'] as $pid => $item) {
            if (isset($item['promotions'])) {
                $promotions[$pid] = $this->promotions_cart_store_item($item['promotions'], $p['gifts'],
                    isset($products[$pid]) ? $products[$pid] : false);
            }
        }

        return $promotions;
    }

    public function promotions_cart_store_item($promotions, $gifts, $product)
    {
        $rs = [];

        foreach ($promotions as $item) {
            switch ($item['package_id']) {
                case 1:
                    $rs[] = array (
                        'option_id' => $item['id'],
                        'option_type' => 'R',
                        'inventory' => 'N',
                        'option_name' => $item['name'],
                        'option_text' => '',
                        'description' => '',
                        'inner_hint' => '',
                        'incorrect_message' => '',
                        'modifier' => '0.000',
                        'modifier_type' => 'A',
                        'position' => '1',
                        'variant_name' => '',
                        'value' => '17004',
                    );
                    break;
                case 2:
                    $tmp = $product ? $product['product_id'] : '';
                    $tmp = $item['apply_objects'][$tmp];
                    $name = str_replace('{{number}}', $tmp, $item['name']);
                    $pid = $item['gift_products'][0];
                    if (isset($gifts[$pid])) {
                        $rs[] = array(
                            'option_id' => $item['id'],
                            'option_type' => 'R',
                            'inventory' => 'N',
                            'option_name' => $name,
                            'option_text' => '',
                            'description' => '',
                            'inner_hint' => '',
                            'incorrect_message' => '',
                            'modifier' => '0.000',
                            'modifier_type' => 'A',
                            'position' => '1',
                            'variant_name' => $gifts[$pid]['name'] . ($gifts[$pid]['price'] ? " (" . number_format($gifts[$pid]['price']) . " VNĐ)" : ""),
                            'value' => $pid,
                        );
                    }
                    break;
                case 4:
                    $tmp = $product ? $product['product_id'] : '';
                    $tmp = $item['apply_objects'][$tmp];
                    $name = str_replace('{{number}}', $tmp, $item['name']);

                    $giff = [];
                    foreach ($item['gift_products'] as $pid) {
                        $giff[] = $gifts[$pid]['name'] . ($gifts[$pid]['price'] ? " (" . number_format($gifts[$pid]['price']) . " VNĐ)" : "");
                    }

                    if (count($giff)) {
                        $rs[] = array(
                            'option_id' => $item['id'],
                            'option_type' => 'R',
                            'inventory' => 'N',
                            'option_name' => $name,
                            'option_text' => '',
                            'description' => '',
                            'inner_hint' => '',
                            'incorrect_message' => '',
                            'modifier' => '0.000',
                            'modifier_type' => 'A',
                            'position' => '1',
                            'variant_name' => implode(", và ", $giff),
                            'value' => $pid,
                        );
                    }
                    break;
                case 7:
                    $tmp = $product ? $product['brand']['variant_id'] : '';
                    $tmp = $item['apply_objects'][$tmp];
                    $name = str_replace('{{number}}', $tmp, $item['name']);
                    $tmp = $product ? $product['brand']['variant_name'] : '';
                    $name = str_replace('{{brand}}', $tmp, $name);
                    $pid = $item['gift_products'][0];
                    if (isset($gifts[$pid])) {
                        $rs[] = array(
                            'option_id' => $item['id'],
                            'option_type' => 'R',
                            'inventory' => 'N',
                            'option_name' => $name,
                            'option_text' => '',
                            'description' => '',
                            'inner_hint' => '',
                            'incorrect_message' => '',
                            'modifier' => '0.000',
                            'modifier_type' => 'A',
                            'position' => '1',
                            'variant_name' => $gifts[$pid]['name'] . ($gifts[$pid]['price'] ? " (" . number_format($gifts[$pid]['price']) . " VNĐ)" : ""),
                            'value' => '17004',
                        );
                    }
                    break;
            }
        }

        return $rs;
    }
}