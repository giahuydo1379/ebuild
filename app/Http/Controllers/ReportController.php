<?php

namespace App\Http\Controllers;

require_once app_path() . '/Helpers/Spout/src/Box/Spout/Autoloader/autoload.php';

use App\Helpers\General;
use App\Models\Products;
use App\Models\Warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductsCategories;
use App\Models\Categories;
use App\Models\AdminUsers;



class ReportController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'report';

        ini_set('max_execution_time', 0);
        ini_set("memory_limit","256M");
    }

    public function order(Request $request)
    {
        $this->data['type'] = ['order' => 'Báo cáo đơn hàng'];

        $orderStatus    = \App\Models\OrderStatus::getAllData();

        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category();


        $this->data['orderStatus'] = $orderStatus;
        $this->data['report_type'] = 'order';
        $this->data['list_categories']  = $list_categories;

        return view("{$this->data['controllerName']}.order", $this->data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product(Request $request)
    {
        $this->data['type'] = ['product_top_ten' => 'Top 10 sản phẩm bán chạy'];

        $orderStatus    = \App\Models\OrderStatus::getAllData();
        $this->data['orderStatus'] = $orderStatus;
        $this->data['report_type'] = 'product';

        return view("{$this->data['controllerName']}.product", $this->data);
    }

    public function category(Request $request)
    {
        $this->data['type'] = ['category_top_ten' => 'Top 10 ngành hàng bán chạy'];

        $orderStatus    = \App\Models\OrderStatus::getAllData();
        $this->data['orderStatus'] = $orderStatus;
        $this->data['report_type'] = 'category';

        return view("{$this->data['controllerName']}.product", $this->data);
    }

    public function coupon(Request $request){

        $this->data['type'] = ['coupon' => 'Coupon'];        
        $this->data['report_type'] = 'coupon';
        return view("{$this->data['controllerName']}.coupon", $this->data);
    }

    public function export(Request $request){
        $params = $request->all();

        if($params['report_type'] == 'product'){
            if($params['type'] == 'product_top_ten')
                $export = $this->export_product_top_ten($request);    
        } elseif($params['report_type'] == 'category'){
            if($params['type'] == 'category_top_ten')
                $export = $this->export_category_top_ten($request);
        } elseif($params['report_type'] == 'coupon'){
            $export = $this->export_coupon($request);
        } elseif($params['report_type'] == 'order'){
            $export = $this->export_order($request);
        }

        if($export){

            if(!empty($export['file'])){
                $request->session()->flash('file', $export['file']);
                return response()->json([
                    'rs'    => 1,
                    'msg'   => 'Xuất báo cáo thành công',     
                    'download' => 1,
                ]);    
            }

            return response()->json([
                'rs'    => 0,
                'msg'   => !empty($export['msg'])?$export['msg']:'Xuất báo cáo thành công',     
                'download' => 0,
            ]);    
            
        }

        return response()->json([
            'rs'    => 0,
            'msg'   => 'Xuất báo cáo không thành công',
            'download' => 0,
        ]);
    }
    public function export_order($request){
        $params = $request->all();

        try {
            $query = \App\Models\Order::select(['orders.*', 'orders_details.*', 'categories.id_path',
                'product_descriptions.product as product_name', 'suppliers.name as supplier_name',
                'payment_descriptions.payment as payment_name', 'orders_status.order_status_name',
                \DB::raw("CONCAT_WS(' ', provinces.type, provinces.name) as province_name"),
                \DB::raw("CONCAT_WS(' ', districts.type, districts.name) as district_name"),
                \DB::raw("CONCAT_WS(' ', wards.type, wards.name) as ward_name")]);

            $query->join('orders_details', 'orders_details.order_id', '=', 'orders.order_id');
            $query->leftJoin('products', 'products.product_id', '=', 'orders_details.product_id');
            $query->leftJoin('product_descriptions', 'products.product_id', '=', 'product_descriptions.product_id');
            $query->leftJoin('suppliers', 'suppliers.id', '=', 'products.supplier_id');

            $query->leftJoin('payment_descriptions',function($join) {
                $join->on('payment_descriptions.payment_id','=','orders.payment_id');
                $join->where('payment_descriptions.lang_code', 'VN');
            });

            $query->leftJoin('products_categories',function($join){
                $join->on('products_categories.product_id','=','products.product_id');
                $join->where('products_categories.link_type', 'M');
                $join->where('products_categories.position', 0);
            });
            $query->leftJoin('categories', 'categories.category_id', '=', 'products_categories.category_id');

            $query->leftJoin('orders_status', 'orders_status.status', '=', 'orders.status');
            $query->leftJoin('provinces', 'provinces.province_id', '=', 'orders.province_id');
            $query->leftJoin('districts', 'districts.district_id', '=', 'orders.district_id');
            $query->leftJoin('wards', 'wards.ward_id', '=', 'orders.ward_id');

            if(!empty($params['from_date'])) {
                $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
                $query->where('orders.timestamp','>=', strtotime($tmp));
            }

            if(!empty($params['to_date'])) {
                $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
                $query->where('orders.timestamp', '<=', strtotime($tmp));
            }

            if(!empty($params['status'])) {
                $query->whereIn('orders.status', $params['status']);
            }
            if(!empty($params['category_id'])){
                $query->whereExists(function($query) use ($params){
                    $query->select(\DB::raw(1))
                        ->from('orders_details')
                        ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                        ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                        ->whereRaw('(products_categories.category_id = '.$params['category_id'].')')
                        ->whereRaw('(products_categories.position = 0)');
                });
            }

            if(!empty($params['sub_category'])){
                $query->whereExists(function($query) use ($params){
                    $query->select(\DB::raw(1))
                        ->from('orders_details')
                        ->join('products_categories','products_categories.product_id','=','orders_details.product_id')
                        ->whereRaw('(`orders_details`.`order_id` = orders.order_id)')
                        ->whereRaw('(products_categories.category_id = '.$params['sub_category'].')')
                        ->whereRaw('(products_categories.position = 1)');
                });
            }
//        $query->where('orders.order_id', 63);

            $objects = $query->get()->toArray();

            $warehouses = Warehouses::getOption();

            $CategoriesController = new \App\Http\Controllers\CategoriesController();
            $category_options = $CategoriesController->get_list_category(true);
            $category_options = $category_options['item'];

            $stt = 0;
            foreach($objects as &$item){
                $stt++;
                $item['stt'] = $stt;

                $tmp = array_reverse(explode('/', $item['id_path']));
                $item['category_name'] = @$category_options[$tmp[0]]['category'];
                $item['category_name_1'] = @$category_options[$tmp[1]][''];
                $item['category_name_2'] = @$category_options[$tmp[2]]['category'];

                $item['created_at'] = General::output_date($item['created_at'], 1, 'H:i d-m-Y');

                $tmp = json_decode($item['transport_info'], 1);
                $item['distance'] = $tmp['distance'];
                $item['warehouse_name'] = @$warehouses[$tmp['warehouse']];

                $tmp = json_decode($item['feature_value'], 1);
                $item["Mác"] = '';
                $item["Độ sụt"] = '';
                $item["Chống Thấm"] = '';
                foreach ($tmp as $t) {
                    $item[$t['name']] = @$t['data'][0]['variant'];
                }
            }

            $header = ['STT', 'Mã ĐH', 'Ngày Đ. Hàng', 'Ngành hàng chính', 'Category cấp 1', 'Category cấp 2',
                'Tên NCC', 'Tên Sản Phẩm', 'SKU', "Mác", "Độ sụt", "Cường Độ Sớm", "Chống Thấm",
                'Số Lượng', 'Giá Ebuild (VNĐ)', 'Quãng đường vận chuyển', 'Phí V. Chuyển (VNĐ)', 'Phụ phí',
                'Tổng Tiền đơn hàng (VNĐ)', 'Mã Coupon', 'Tiền giãm', 'Tổng tiền cuối cùng (sau khi trừ code coupon / khuyến mãi)',
                'Cách Thanh Toán', 'Trạng Thái ĐH', 'Tên Người Đặt Hàng', 'Địa chỉ', 'Phường / Xã', 'Quận / Huyện',
                'Tỉnh / Thành Phố', 'Email', 'Số Điện Thoại', 'Kho Giao Hàng'];
            $fields = ['stt', 'validation_code', 'created_at', 'category_name', 'category_name_1', 'category_name_2',
                'supplier_name', 'product_name', 'product_code', "Mác", "Độ sụt", "Cường Độ Sớm", "Chống Thấm",
                'amount', 'price', 'distance', 'shipping_cost', 'surcharge',
                'subtotal', 'voucher_code', 'voucher_discount', 'total',
                'payment_name', 'order_status_name', 'firstname', 'b_address', 'ward_name', 'district_name', 'province_name',
                'email', 'phone', 'warehouse_name'];

            $file = \App\Helpers\Cexport::createFile('coupons', 'Danh sách mã coupon', $header, $fields, $objects);

            return ['file' => $file];
        }catch (\Exception $e) {
            $msg = $e->getMessage();
        }

        return false;
    }

    public function export_product_top_ten($request){
        $params = $request->all();
        $order_id = Order::getAllDataForReport($params);

        if(!$order_id)
            return ['msg' => 'Không có đơn hàng'];

        $product_status     = Products::get_status_options();
        $products           = OrderDetail::getProductsReport($order_id,10);
        $pids               = array_pluck($products,'product_id');        
        $product_amount     = array_pluck($products,'sum_amount','product_id');    
        $product_show       = Products::getProductsShowForReport($pids);        
        $productsCategories = ProductsCategories::getDataByProductId($pids);        
        $productsCategories = array_pluck($productsCategories,'category_id','product_id');
        $categories         = Categories::getDataByIds($productsCategories);
        $category_root_ids  = [];
        foreach($categories as $item){
            $id_path = explode('/', $item['id_path']);
            
            $category_root_ids[$item['category_id']] = $id_path[0];

            $categories_name[$item['category_id']] = $item['category'];
        }

        $categoriesRoot = Categories::getDataByIds($category_root_ids);
        $categoriesRoot = array_pluck($categoriesRoot,'category','category_id');

        //dd($product_show,$product_amount,$productsCategories,$category_root_ids,$categories_name,$categoriesRoot);
        try {
            $objects = [];
            $tmp = [];
            $stt = 1;
            foreach($product_amount as $product_id => $amount){
                
                $category_id    = !empty($productsCategories[$product_id])?$productsCategories[$product_id]:0;
                $category_root  = !empty($category_root_ids[$category_id])?$category_root_ids[$category_id]:0;                

                $item                   = $product_show[$product_id];

                $tmp['stt']             = $stt++;
                $tmp['brand']           = $item['brand_name'];
                $tmp['sku']             = $item['product_code'];
                $tmp['product_name']    = $item['name'];
                $tmp['status']          = $product_status[$item['status']];
                $tmp['list_price']      = $item['list_price'];
                $tmp['price']           = $item['price'];
                $percent_discount       = $item['list_price'] > 0 ? round( 100 - ($item['price'] * 100 / $item['list_price']) ) : 0;
                $tmp['percent_discount']    = $percent_discount;
                $tmp['amount']              = $amount;
                $tmp['category_root']       = @$categoriesRoot[$category_root];
                $tmp['category']            = @$categories_name[$category_id];

                $objects[] = $tmp;
            }
            if ($objects && count($objects) > 4000) {
                $msg = "Số lượng cho phép xuất file là 4000 dòng, tìm thấy: ".count($objects);
            } elseif ($objects) {
                $header = ['STT','Thương hiệu','SKU','Tên sản phẩm','Trạng thái','Giá T.Trường','Giá Thiên Hòa','% Giảm giá','Số lượng bán','Ngành hàng','Danh mục'];

                $fields = array_keys($tmp);

                //\App\Helpers\Cexport::export('products', 'Danh sách sản phẩm', $header, $fields, $objects);

                $file = \App\Helpers\Cexport::createFile('report_product', 'Báo cáo top 10 sản phẩm', $header, $fields, $objects);

                return ['file' => $file];
            }
        }catch (\Exception $e) {
            $msg = $e->getMessage();
        }
        return false;
    }

    public function export_category_top_ten($request){
        $params = $request->all();
        $order_id = Order::getAllDataForReport($params);

        if(!$order_id)
            return ['msg' => 'Không có đơn hàng'];
    
        $products           = OrderDetail::getProductsReport($order_id);
        $pids               = array_pluck($products,'product_id');        
        $product_amount     = array_pluck($products,'sum_amount','product_id');

        $productsCategories = ProductsCategories::getDataByProductId($pids);                
        $productsCategories = array_pluck($productsCategories,'category_id','product_id');

        $categories         = Categories::getDataByIds($productsCategories);
        
        $category_root_ids  = [];
        foreach($categories as $item){
            $id_path = explode('/', $item['id_path']);
            
            $category_root_ids[$item['category_id']] = $id_path[0];
        }

        $categoriesRoot = Categories::getDataByIds($category_root_ids);
        $categoriesRoot = array_pluck($categoriesRoot,'category','category_id');
        //dd($product_amount,$productsCategories);
        try {
            $category_root_amout = [];
            foreach($product_amount as $product_id => $amount){
                $amount = (int)$amount;
                if(empty($productsCategories[$product_id]))
                    continue;

                $category_id    = $productsCategories[$product_id];
                $category_root  = $category_root_ids[$category_id];

                if(!empty($category_root_amout[$category_root])){
                    $category_root_amout[$category_root] += $amount;
                }else{
                    $category_root_amout[$category_root] = $amount;
                }
            }

            arsort($category_root_amout);
            if(count($category_root_amout) > 10)
                $category_root_amout = array_slice($category_root_amout,0,10,true);

            $objects = [];
            $tmp = [];
            $stt = 1;
            foreach($category_root_amout as $category_root => $amount){
                $tmp['stt']             = $stt++;
                $tmp['category_root']   = $categoriesRoot[$category_root];
                $tmp['amount']          = $amount;
                
                $objects[] = $tmp;
            }

            if ($objects && count($objects) > 4000) {
                $msg = "Số lượng cho phép xuất file là 4000 dòng, tìm thấy: ".count($objects);
            } elseif ($objects) {
                $header = ['STT','Ngành hàng','Số lượng sản phẩm bán'];

                $fields = array_keys($tmp);

                //\App\Helpers\Cexport::export('products', 'Danh sách sản phẩm', $header, $fields, $objects);

                $file = \App\Helpers\Cexport::createFile('report_product', 'Báo cáo top 10 ngành hàng bán chạy', $header, $fields, $objects);

                return ['file' => $file];
            }
        }catch (\Exception $e) {
            $msg = $e->getMessage();
        }
        return false;
    }

    public function export_coupon($request){
        $params = $request->all();

        try {
            $params = ['list_coupons' => [$params['code']],'is_export' => 1];
            $objects = \App\Models\Coupons::getData($params);
            $objects = $objects->toArray();
            if(empty($objects))
                return ['msg' => 'Mã coupon không tồn tại'];

            $stt = 0;
            foreach($objects as &$item){
                $stt++;
                $item['stt'] = $stt;

                if($item['is_percent'] == 1){
                    $item['percent'] = '%';
                }else{
                    $item['percent'] = 'VND';
                }

                if($item['type'] == 'product'){
                    $item['object'] = \App\Models\CouponsApply::getProductCodeByCouponID($item['id']);
                }

                if($item['type'] == 'brand'){
                    $item['object'] = \App\Models\CouponsApply::getBrandNameByCouponID($item['id']);
                }

                $created_by = AdminUsers::getDataById($item['created_by']);
                
                $item['created_by'] = !empty($created_by['full_name'])?$created_by['full_name']:'';
            }
            $header = ['STT','Mã Coupon', 'Tên Coupon', 'Loại Coupon','Áp dụng', 'Giảm', 'Kiểu giảm giá', 'Giá trị đơn hàng','Ngày áp dụng','Ngày kết thúc','Số lượt sử dụng','Số lượt đã sử dụng','Ngày tạo','Người tạo'];
            $fields = ['stt','code', 'name', 'type','object','discount', 'percent', 'min_amount', 'date_from','date_to','times','used','created_at','created_by'];

            //\App\Helpers\Cexport::export('products', 'Danh sách sản phẩm', $header, $fields, $data);

            $file = \App\Helpers\Cexport::createFile('coupons', 'Danh sách mã coupon', $header, $fields, $objects);

            return ['file' => $file];
        }catch (\Exception $e) {
            $msg = $e->getMessage();
        }

        return false;

    }

    public function download(Request $request){
        $file = $request->session()->get('file', false);

        if($file)
            \App\Helpers\Cexport::downloadFile('Report',$file);
    }
}
