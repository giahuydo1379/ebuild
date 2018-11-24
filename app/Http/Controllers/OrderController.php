<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Products;
use App\Models\OrderDetail;
use Validator;
use App\Models\Province;

class OrderController extends Controller
{
    protected $data = []; // the information we send to the view
    protected $key_cart_order;
    /**
     * Create a new controller instance.
     */
    protected $key_shipping_cost = 'key_shipping_cost';
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'order';

        $this->key_cart_order   = 'cart-order';
        $this->data['title']    = 'Đơn hàng';

    }

    public function index(Request $request){
        $request->session()->forget($this->key_shipping_cost);
    	$params 	= $request->all();
    	$objects 	= Order::getData($params);    	
    	$order_ids 	=	array_pluck($objects['data'],'order_id');
    	$orders_detail 	= OrderDetail::getData($order_ids);

    	$revenue 	= Order::getRevenue($params);
    	$paymented 	= Order::getPaymented($params);

    	$orderStatus 	= \App\Models\OrderStatus::getAllData();
    	$paymentDescription 	= \App\Models\PaymentDescription::getAllData();

    	$admin_users = \App\Models\AdminUsers::getSales();
    	$admin_users = array_pluck($admin_users,'name','id');

        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category();

    	$this->data['objects']		= $objects;
    	$this->data['orders_detail']	= $orders_detail;
    	$this->data['revenue']		= $revenue;
    	$this->data['paymented']	= $paymented;
    	$this->data['orderStatus']	= $orderStatus;
    	$this->data['paymentDescription']	= $paymentDescription;
    	
    	$this->data['params']		= $params;
        $this->data['admin_users']		= $admin_users;
        $this->data['list_categories']  = $list_categories;

    	return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function print_order(Request $request, $id){
        $html = $request->input('html', 0);

        $params 	= $request->all();

        $object 		= Order::getDetailById($id);
        $orderDetail 	= OrderDetail::getData([$id]);

        $format_time = explode(" ", $object['created_at']);
        $object['created_at'] = $format_time[1] .' - '. $format_time[0];

        $this->data['object']		= $object;
        $this->data['orderDetail']	= $orderDetail;

        $this->data['company_invoice'] = \App\Models\Order::get_company_invoice($id);

        if ($html) {
            return view("{$this->data['controllerName']}.print", $this->data);
        }

        $pdf = \PDF::loadView("{$this->data['controllerName']}.print", $this->data);
        return $pdf->stream('print.pdf');
    }

    public function show(Request $request,$id){
    	$params 	= $request->all();

    	$object 		= Order::getDetailById($id);
    	$orderDetail 	= OrderDetail::getData([$id]);
    	$orderStatus 	= \App\Models\OrderStatus::getAllData();
    	$orderStatus 	= array_pluck($orderStatus,'order_status_name','status');

        $object['transport_info']   = !empty($object['transport_info'])?json_decode($object['transport_info'],1):[];
    	$this->data['object']		= $object;
    	$this->data['orderDetail']	= $orderDetail;
    	$this->data['orderStatus']	= $orderStatus;        
        $this->data['company_invoice'] = \App\Models\Order::get_company_invoice($id);

    	if ($object['admin_id']) {
            $admin = \App\User::find($object['admin_id']);
            if ($admin) {
                $admin = $admin->toArray();
            } else {
                $admin = ['username' => $object['admin_id'], 'full_name' => $object['admin_id']];
            };
            $this->data['admin'] = $admin;
        }

        $this->data['audits'] = \App\Models\Audits::getAudits(['App\Models\Order', 'App\Models\Orders', 'App\Models\OrderDetail'], $id);
        $this->data['event_options'] = Order::getOptionsEvent();
        $this->data['field_name_options'] = Order::getOptionsFieldName();
        $this->data['fillable_no_auditable'] = Order::fillable_no_auditable();

    	return view("{$this->data['controllerName']}.show", $this->data);
    }

    public function edit(Request $request, $id){
        $request->session()->forget($this->key_shipping_cost);
    	$object 		= Order::getDataById($id);
        if($object['lock'] == 1)
             return redirect()->route('order.show',['id' => $id]);

    	$orderDetail 	= OrderDetail::getData([$id]);
    	$orderStatus 	= \App\Models\OrderStatus::getAllData();
//         dd($orderDetail);        $cart_products = [];
        if(!empty($orderDetail[$id])){
            foreach($orderDetail[$id] as $item){
                $cart_products[$item['product_id']] = [
                    'product_id'    => $item['product_id'],
                    'amount'        => $item['amount'],
                    'price'         => $item['price'],
                    'brand_id'      => $item['brand_id'],
                    'weight'        => $item['weight'],
                    'product_type'  => $item['product_type']
                ];
            }  
        }
        
        session([$this->key_cart_order => ['update' => 0,'data' => $cart_products]]);

    	$this->data['object']		= $object;
    	$this->data['orderDetail']	= $orderDetail;
    	$this->data['orderStatus']	= $orderStatus;

    	if ($object['is_vat']) {
            $company_invoice =  \App\Models\Order::get_company_invoice($id);
            $company_invoice = json_decode($company_invoice['extra'], 1);
        } else {
            $company_invoice = [];
        }
        $this->data['company_invoice']	= $company_invoice;

    	return view("{$this->data['controllerName']}.edit", $this->data);
    }

    public function create(Request $request){
        $request->session()->forget($this->key_shipping_cost);
        $request->session()->forget($this->key_cart_order);
        return view("{$this->data['controllerName']}.edit");
    }

    public function store(Request $request) {
        $params = $request->all();
        $id     = $request->input('id', 0);

        $cart_order  = $request->session()->get( $this->key_cart_order );
        if(empty($cart_order['data']))
            return response()->json([
                'rs'    => 0,
                'msg'   => 'Đơn hàng không có sản phẩm',
                'data'  => $params
            ]);
            
        $cart_products = $cart_order['data'];

        $rules = [            
            'b_firstname'   => 'required',
            'province_id'   => 'required',
            'district_id'   => 'required',
            'ward_id'       => 'required',
            'b_address'     => 'required',
            'b_phone'       => 'required',
            'email'         => 'required',
        ];
        $messages = [            
            'b_firstname.required'   => 'Nhập tên',
            'province_id.required'   => 'Nhập Tỉnh thành phố',
            'district_id.required'   => 'Nhập quận huyện',
            'ward_id.required'       => 'Nhập phường xã',
            'b_address.required'     => 'Nhập địa chỉ',
            'b_phone.required'       => 'Nhập số điện thoại',
            'email.required'         => 'Nhập email',
        ];

        $valid = Validator::make($params, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs'        => 0,
                'msg'       => 'Dữ liệu nhập không hợp lệ',
                'errors'    => $valid->errors()->messages(),
                'data'      => $params
            ]);
        }

        $address = $params['b_address'];
        
        $params['c_province_id'] = $params['province_id'];

        $ward = Province::select(
            \DB::raw("CONCAT_WS(' ', provinces.type, provinces.name) as province_name")
        )
            ->where('province_id', $params['province_id'])
            ->first();

        
        if ($ward['province_name']) 
            $params['c_province_id'] = $ward['province_name'];

        $data_order = [        
            'firstname'     => $params['b_firstname'],
            'b_firstname'   => $params['b_firstname'],
            'b_address'     => $params['b_address'],
            'b_city'        => $params['c_province_id'],
            'b_phone'       => $params['b_phone'],
            's_firstname'   => $params['b_firstname'],
            's_address'     => $params['b_address'],
            's_city'        => $params['c_province_id'],
            's_phone'       => $params['b_phone'],
            'phone'         => $params['b_phone'],
            'email'         => $params['email'],
            'province_id'   => $params['province_id'],
            'district_id'   => $params['district_id'],
            'ward_id'       => $params['ward_id'],
            'notes'         => $params['notes'],
            'details'       => $params['details'],
            'is_vat'        => $params['is_vat']
        ];
        

        if(!$id || $cart_order['update'] == 1){

            $error = [];
            $pre_order = false;

            $products = \App\Models\Products::getProductsShow(array_keys($cart_products));

            $tmp = [];
            $product_missing_amout = [];
            foreach ($products as $item) {
                $pid = $item['product_id'];
                if (isset($cart_products[$pid]['pre_order']) && $cart_products[$pid]['pre_order']) {
                    $pre_order = true;
                }
                $tmp[$pid] = $item;
                $tmp[$pid]['feature_value'] = \App\Models\FeaturesValues::getDetail($pid);
                if($item['product_type'] == 'S')
                    continue;
                if ( !$pre_order && ($item['amount']==0 || $item['amount'] < $cart_products[$pid]['amount']) ) {

                    $error['zero'] = true;
                    $product_missing_amout[] = $item['name'];
                }
            }

            if ($error) {
                 return response()->json([
                    'rs'        => 0,
                    'msg'       => 'Sản phẩm '.implode(', ',$product_missing_amout).' không đủ số lượng',
                ]);
            }
            $products = $tmp;

            $discount = isset($params['discount']) ? $params['discount'] : 0;

            $total = $subtotal = $total_service = $total_product = $surcharge = 0;

            $cart_brands = [];
            foreach ($cart_products as $item) {
                $tmp = $item['product_id'];

                if (!isset($item['discount'])) $item['discount'] = 0;
                $tmp = $item['product_id'];

                $subtotal += $products[$tmp]['price'] * $cart_products[$tmp]['amount'] - $item['discount'];

                if($products[$tmp]['product_type'] == 'P'){
                    $total_product  += $products[$tmp]['price'] * $cart_products[$tmp]['amount'];
                    $surcharge      += \App\Models\Products::getSurcharge($item['product_id'],$item['amount']);    
                }else{
                    $total_service += $products[$tmp]['price'] * $cart_products[$tmp]['amount'];
                }            

                if (isset($item['brand_id'])) {
                    if (isset($cart_brands[$item['brand_id']])) {
                        $cart_brands[$item['brand_id']] += $item['amount'];
                    } else {
                        $cart_brands[$item['brand_id']] = $item['amount'];
                    }
                }
            }  
            $data_order['subtotal_product'] = $total_product;
            $data_order['subtotal_service'] = $total_service;
            $data_order['surcharge']        = $surcharge;
            $data_order['shipping_cost']    = $params['shipping_cost']??0;
            $data_order['subtotal'] = $subtotal;
            $data_order['total']    = $subtotal - $discount + $surcharge +  $data_order['shipping_cost'];
            $data_order['discount'] = $discount;

        }
        
        if(!$id){
            $data_order += [
                'points_used' => 0,
                'points_received' => 0,
                'company_id' => 0,
                'user_id' => 0,
                'admin_id' => \Auth::user()->id,
                'subtotal_discount' => 0,
                'voucher_discount' => 0,
                'payment_surcharge' => 0,
                'payment_surcharge' => 0,
                'shipping_ids' => 6,
                'timestamp' => time(),
                'notes'     => $params['notes'],
                'status' => "N",
                'channel_sale' => "W",
                'details' => $params['details'],
                'promotions' => "",
                'promotion_ids' => "",
                'title' => "",
                'lastname' => "",
                'company' => "",
                'b_title' => "",
                'b_lastname' => "",
                'b_address_2' => "",
                'b_state' => "",
                'b_country' => "",
                'b_zipcode' => "",
                's_title' => "",
                's_lastname' => "",
                's_address_2' => "",
                's_county' => "",
                's_state' => "",
                's_country' => "",
                's_zipcode' => "",
                'fax' => "",
                'url' => "",
                'payment_id' => 12,
                'tax_exempt' => "N",
                'lang_code' => "vi",
                'ip_address' => $request->ip(),
                'repaid' => 0,
                'validation_code' => "TH".date('Ymd'),
                'type' => 'admin',
                'is_vat' => $params['is_vat'],
                'transport_info' => !empty($params['transport_info'])?json_encode($params['transport_info']):''
            ];

            $order = Order::create($data_order);
            $order_code = $this->generator_order_code('EB');
            $order->validation_code = $order_code;
            $order->save();
        }else{

            $order = Order::find($id);

            if($order->lock == 1) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Cập nhật không thành công. Đơn hàng đã khóa!'
                ]);
            }

            if (!$order->admin_id) {
                $data_order['admin_id'] = \Auth::user()->id;
            }

            $rs = $order->update($data_order);

            if(!$rs)
                return response()->json([
                    'rs'        => 0,
                    'msg'       => 'Cập nhật đơn hàng không thành công'
                ]);
        }

        if ($order) {
            //             insert thông tin xuất hóa đơn
            if (isset($params['is_vat']) && $params['is_vat']) {
                $tmp = ['Đơn vị: '.$params['company_name']];
                $tmp[] = 'Địa chỉ: '.$params['company_address'];
                $tmp[] = 'Mã số thuế: '.$params['company_tax_code'];
                $tmp = implode(' - ', $tmp);

                $data = [
                    'company_name' => $params['company_name'],
                    'company_address' => $params['company_address'],
                    'company_tax_code' => $params['company_tax_code']
                ];

                \App\Models\Order::insert_company_invoice($order->order_id, $tmp, json_encode($data));
            } else {
                \App\Models\Order::insert_company_invoice($order->order_id);
            }


            if($cart_order['update'] == 0)
                return response()->json([
                    'rs'        => 1,
                    'msg'       => 'Cập nhật đơn hàng thành công',
                    'list'      => route('order.index'),
                    'detail'    => route('order.show',['id' => $order->order_id])
                ]);

            if($id) {
                $orders_detail = OrderDetail::where('order_id', $id)->get();
                foreach ($orders_detail as $detail) {
                    Products::where('product_id', $detail->product_id)
                    ->where('product_type','P')->increment('amount', $detail->amount);

                    // neu ko co trong gio hang thi xoa no di
                    if (!isset($cart_products[$detail->product_id])) {
                        $detail->delete();
                    }
                }
            }

            $promotions = $this->promotions_cart_store($cart_products, $cart_brands, $products);

            $order_id = $order->order_id;
            $index = 1;

            foreach ($cart_products as $tmp => $item) {
                if (isset($promotions[$tmp])) {
                    $extra = array(
                        'product_options' =>
                            array(
                                12611 => '17004',
                            ),
                        'product' => $products[$tmp]['name'],
                        'company_id' => '0',
                        'is_edp' => 'N',
                        'edp_shipping' => 'N',
                        'base_price' => $products[$tmp]['price'],
                        'product_options_value' => $promotions[$tmp]
                    );
                } else {
                    $extra = array();
                }

                if (!isset($item['discount'])) $item['discount'] = 0;

                $data_detail = [
                    'item_id' => time()+$index++,
                    'order_id' => $order_id,
                    'product_id' => $item['product_id'],
                    'product_code' => $products[$tmp]['product_code'],
                    'price' => $products[$tmp]['price'],
                    'amount' => $item['amount'],
                    'discount' => $item['discount'],
                    'extra' => serialize($extra),
                    //'feature_value' => $products[$tmp]['feature_value']
                ];

                $detail = $id ? OrderDetail::where(['order_id'=>$id, 'product_id'=>$item['product_id']])->first() : false;
                if ($detail) {

                    $rs = OrderDetail::where(['order_id'=>$id, 'product_id'=>$item['product_id']])->update($data_detail);
                } else {
                    $rs = OrderDetail::create($data_detail);
                }

                // update lai so luong san pham
                if (!$pre_order && $rs && $item['product_type'] == 'P') {
                    \App\Models\Products::where('product_id', $item['product_id'])->decrement('amount', $item['amount']);
                }
            }

            $request->session()->forget($this->key_cart_order);

            if($id)
                return response()->json([
                    'rs'        => 1,
                    'msg'       => 'Cập nhật đơn hàng thành công',
                    'list'      => route('order.index'),
                    'detail'    => route('order.show',['id' => $order->order_id])
                ]);

            return response()->json([
                'rs'        => 1,
                'msg'       => 'Thêm mới đơn hàng thành công',
                'list'      => route('order.index'),
                'detail'    => route('order.show',['id' => $order->order_id])
            ]);
        }

        if($id)
            return response()->json([
                'rs'        => 0,
                'msg'       => 'Cập nhật đơn hàng không thành công',
            ]);

        return response()->json([
            'rs'        => 0,
            'msg'       => 'Thêm mới đơn hàng không thành công',
        ]);
    }

    public function change_lock_status(Request $request){
        $params = $request->all();

        $object = Order::find($params['order_id']);
        if(empty($params['status']))
            unset($params['status']);

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

    public function change_status(Request $request){
        $data = $request->all();
        $order_ids = $request->input('order_ids', []);
        
        $rules = [
            'status'	=> 'required',
            'order_ids' => 'required|array',
        ];
        $messages = [
            'status.required'      => 'Chọn trạng thái đơn hàng',
            'order_ids.required'   => 'Chọn đơn hàng',
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
        foreach ($order_ids as $order_id) {
            $object = Order::find($order_id);
            if ($object) {
                $data = [
                    'status' => $data['status']
                ];

                if (!$object->admin_id) {
                    $data['admin_id'] = \Auth::user()->id;
                }

                $object->update($data);
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
            'order_id'		=> 'required',
            'b_firstname' 	=> 'required',
            'province_id' 	=> 'required',
            'district_id' 	=> 'required',
            'ward_id' 		=> 'required',
            'b_address' 	=> 'required',
            'b_phone' 		=> 'required',
            'email' 		=> 'required',
        ];
        $messages = [
            'order_id'		=> 'Không có id đơn hàng',
            'b_firstname' 	=> 'Nhập tên',
            'province_id' 	=> 'Nhập Tỉnh thành phố',
            'district_id' 	=> 'Nhập quận huyện',
            'ward_id' 		=> 'Nhập phường xã',
            'b_address' 	=> 'Nhập địa chỉ',
            'b_phone' 		=> 'Nhập số điện thoại',
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

        $address = $params['b_address'];
        
        $params['c_province_id'] = $params['province_id'];

        $ward = Province::select(
            \DB::raw("CONCAT_WS(' ', provinces.type, provinces.name) as province_name")
        )
            ->where('province_id', $params['province_id'])
            ->first();

        
        if ($ward['province_name']) 
            $params['c_province_id'] = $ward['province_name'];

        $data_order = [        
            'firstname'     => $params['b_firstname'],
            'b_firstname'   => $params['b_firstname'],
            'b_address'     => $params['b_address'],
            'b_city'        => $params['c_province_id'],
            'b_phone'       => $params['b_phone'],
            's_firstname'   => $params['b_firstname'],
            's_address'     => $params['b_address'],
            's_city'        => $params['c_province_id'],
            's_phone'       => $params['b_phone'],
            'phone'         => $params['b_phone'],
            'email'         => $params['email'],
            'province_id'   => $params['province_id'],
            'district_id'   => $params['district_id'],
            'ward_id'       => $params['ward_id']
        ];

        $object = Order::find($params['order_id']);
        
        $rs = 0;	
        if($object) {
            if (!$object->admin_id) {
                $data_order['admin_id'] = \Auth::user()->id;
            }

            $rs = $object->update($data_order);
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

    public function update_note(Request $request){

    	$params = $request->all();

    	$object = Order::find($params['order_id']);
        
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
        $products_sold = $request->input('products_sold', []);
        $product_id = $request->input('product_id', '');
        $amount = $request->input('amount', '');
        $product_services_ids = $request->input('product_services_ids', []);
        $product_services = [];

        if($request->has('distance')){
            $distance = $request->input('distance');
            $shipping_cost = 0;
        }else{
            $shipping_cost = $request->session()->get( $this->key_shipping_cost, 0 );
        }

        if ($products_sold) {
            $products_sold[] = $product_id;
            $objects = \App\Models\Products::getProductAddCartById($products_sold);
            $object = $objects[$product_id];
        } elseif($product_services_ids){
            $product_services = \App\Models\Products::getProductAddCartById($product_services_ids);
        }elseif($product_id) {
            $objects = [];
            $object = \App\Models\Products::getProductAddCartById($product_id);
        }

        // neu het hang
        if (!$request->has('distance') && !$product_services_ids && $object['amount'] <= config('app.number_out_stock', 2)) {
            return response()->json([
                'status' => 0,
                'msg' => 'Sản phẩm đã hết hàng.',
                'data' => $object
            ]);
        }

        // lay danh sach san pham trong gio hang
        $cart_products = $request->session()->get( $this->key_cart_order );
        
        $cart_products = $cart_products['data'];
        if($product_services_ids){
            foreach ($cart_products as $pid => $item) {
                if(isset($item['product_type']) && $item['product_type'] =='S'){
                    unset($cart_products[$pid]);
                }
            }
        }
        
        foreach ($product_services as $p) {
            $cart_products[$p['product_id']] = [
                'product_id' => $p['product_id'],
                'amount' => 1,
                'price' => $p['price'],
                'brand_id' => 0,
                'product_type' => 'S'
            ];
        }
        
        // them moi hoac cap nhat san pham trong gio hang
        foreach ($products_sold as $pid) {
            if (isset($objects[$pid]) && $objects[$pid]['amount'] > 0) {
                $cart_products[$pid] = [
                    'product_id' => $pid,
                    'amount' => 1,
                    'price' => $objects[$pid]['price'],
                    'brand_id' => $objects[$pid]['brand_id'],
                    'product_type' => 'P'
                ];
            }
        }

        if($product_id){
            $cart_products[$product_id] = [
                'product_id' => $product_id,
                'amount' => $amount,//$amount < $object['amount'] ? $amount : $object['amount'],
                'price' => $object['price'],
                'brand_id' => $object['brand_id'],
                'product_type' => 'P'
            ];    
        }

        // duyet de tin tong
        $count = 0;
        $total_product = 0;
        $total_service = 0;
        $surcharge     = 0;
        $shipping_costs = 0;
        $cart_brands = [];

        foreach ($cart_products as $pid => $item) {
            if (isset($item['brand_id'])) {
                if (isset($cart_brands[$item['brand_id']])) {
                    $cart_brands[$item['brand_id']] += $item['amount'];
                } else {
                    $cart_brands[$item['brand_id']] = $item['amount'];
                }
            }

            if(isset($item['product_type']) && $item['product_type'] == 'S'){                
                $total_service += $item['price'];
            }else{
                $total_product += $item['amount'] * $item['price'];
                $count++;
                
                $surcharge += \App\Models\Products::getSurcharge($item['product_id'],$item['amount']);

                if($request->has('distance') && $distance > 5){
                    $warehouse_id           = $request->input('warehouse_id',0);
                    $shipping_cost_default  = \App\Models\Warehouses::getShippingCost($warehouse_id,$pid);
                    $shipping_cost          = ($distance - 5) * $shipping_cost_default;

                    if($shipping_cost > 0 && $shipping_cost <= 1000){
                        $shipping_cost = 1000;
                    }
                    if($shipping_cost > 1000){
                        $shipping_cost = ceil($shipping_cost / 1000)*1000;   
                    }
                }

                $shipping_costs += $shipping_cost * $item['amount'];
            }
        }
        session([$this->key_shipping_cost => $shipping_cost]);
        session([$this->key_cart_order => ['update' => 1,'data' => $cart_products]]);

        $promotions = $this->promotions_cart($cart_products, $cart_brands);

        return response()->json([
            'status' => 1,
            'msg' => '',
            'data' => [
                'count' => $count,
                'total_product' => $total_product,
                'total_service' => $total_service,
                'subtotal' => $total_product + $total_service + $surcharge + $shipping_costs,
                'total' => $total_product + $total_service + $surcharge + $shipping_costs,
                'price' => $object['price']??0,
                'promotions' => $promotions,
                'product' => $object??[],
                'shipping_cost' => $shipping_costs,
                'surcharge' => $surcharge
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
            //$weight += $item['weight'];
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
            if(!$brand_id)
                continue;
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
            $promotions[$pid] = view('order.promotion', $item)->render();
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

    public function getService(Request $request){
        $cart_products  = $request->session()->get( $this->key_cart_order );
        $product_ids = [];
        $service_ids = [];
        foreach($cart_products['data'] as $item){
            if($item['product_type'] == 'S'){
                $service_ids[] = $item['product_id'];
                continue;
            }
            $product_ids[] = $item['product_id'];
        }

        $products = \App\Models\ProductsCategories::where('link_type','M')
        ->whereIn('product_id',$product_ids)->get()->toArray();
        
        $product_services   = [];
        $category_ids   = [];
        foreach ($products as $item) {
            $category = \App\Models\Categories::find($item['category_id']);
            if($category){
                if(in_array($item['category_id'], $category_ids))
                    continue;

                $services_tmp = \App\Models\Products::getProductsByCategoryId(
                    $item['category_id'], 
                    [
                        'product_type' => 'S'
                    ]
                )??[];

                $product_services = array_merge($product_services,$services_tmp);
            }
        }

        return response()->json([
            'rs'            => 1,
            'data'          => $product_services,
            'service_ids'   => $service_ids
        ]);
    }

    public function generator_order_code($prefix='EB') {
        $order_date = date('ymd');

        $count = 100;
        while ($count > 0) {
            $code_rand = mt_rand(1000, 9999);

            $rs = \App\Models\OrdersCode::firstOrCreate(['order_date' => $order_date, 'code' => $code_rand], ['is_used' => \DB::raw('is_used + 1')]);
            if ($rs->is_used !== 1) {
                break;
            }
            $count--;
        }

        if ($count <= 0) {
            //\App\Helpers\General::telegram_log('generator_order_code fail: '.$code_rand);
        }

        return $prefix . $order_date . sprintf("%'.04d", $code_rand);
    }

    public function get_warehouse(Request $request){        
        $ward_id            = $request->input('ward_id',false);
        $district_id        = $request->input('district_id',false);

        if(!$ward_id || !$district_id)
            return response()->json([
                'rs'    => 0,
                'data'  => []
            ]);

        $warehouse_id = \App\Models\WarehousesPlaces::getWarehouseId($ward_id,$district_id);
        if(!$warehouse_id)
            return response()->json([
                'rs'    => 0,
                'data'  => []
            ]);

        $warehouse = \App\Models\Warehouses::getDataByid($warehouse_id);
        
        if(empty($warehouse))
            return response()->json([
                'rs'    => 0,
                'data'  => []
            ]);

        $warehouse['full_address'] = \App\Helpers\General::getAddress($warehouse['ward_id'],$warehouse['address']);
        
        return response()->json([
            'rs'    => 1,
            'data'  => $warehouse
        ]);
    }
}