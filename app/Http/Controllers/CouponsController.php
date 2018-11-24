<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/7/2018
 * Time: 10:17 AM
 */

namespace App\Http\Controllers;
use App\Models\Brands;
use App\Models\Features;
use App\Models\FeaturesValues;
use Doctrine\Common\Annotations\Annotation\Required;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Auth;

class CouponsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'coupons';
    }

    public function index(Request $request){    
        $params = $request->all();     
        
        //neu trinh duyet khong mo tab moi
        $file = $request->session()->get('file', false);
        if($file){
            $request->session()->flash('file',$file);
            return redirect()->to(route('coupons.download'));
        }
        
        $objects = \App\Models\Coupons::getData($params);

        $brands = \App\Models\Brands::getAllData();
        $this->data['brands'] = array_pluck($brands,'name','brand_id');

        $categories = \App\Models\Categories::getAllCategoriesShowFront();
        $this->data['categories'] =  array_pluck($categories, 'category', 'category_id');

        $this->data['objects'] = $objects;        
        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function store(Request $request){
        $params = $request->all();
        $id = $request->input('id', 0);
     
        $rules = [
            'name'          => 'required',
            // 'from_date'     => 'required',
            // 'from_time'     => 'required',
            // 'to_date'       => 'required',
            // 'to_time'       => 'required',
            'discount'      => 'required',
            'min_amount'    => 'required',
            'times'         => 'required',
            'min_amount'    => 'required',

        ];
        if(!$id){
            $rules['total_coupons'] = 'required';
        }
        $messages = [];
        if(!empty($params['code'])){
            $rules['code'] = 'unique:coupons';
            $messages['code.unique'] = 'Mã coupon đã tồn tại';

            $params['total_coupons'] = 1;
        }

        $valid = Validator::make($params, $rules, $messages);
        if(!empty($params['from_date'])){
            if(!isset($params['from_time']))
                $params['from_time'] = '';

            $params['date_from']  = \Carbon\Carbon::parse($params['from_date'])->format('Y-m-d').' '.$params['from_time'];
        } else $params['date_from'] = NULL;

        if(!empty($params['to_date'])){
            if(!isset($params['to_time']))
                $params['to_time'] = '';

            $params['date_to']    = \Carbon\Carbon::parse($params['to_date'])->format('Y-m-d').' '.$params['to_time'];
        } else $params['date_to'] = NULL;

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $params
            ]);
        }

        if(empty($params['is_percent']))
            $params['is_percent'] = 0;

        if($id){
            $data = $params;    

            if(array_key_exists('code',$data))
                unset($data['code']);
            if(array_key_exists('total_coupons',$data))
                unset($data['total_coupons']);

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Coupons(), $data);
                        
            $rs = \App\Models\Coupons::where('id', $id)
                ->update($data);

            if($data['type'] == 'product' || $data['type'] == 'brand' || $data['type'] == 'category'){

                if($data['type'] == 'product') {
                    $object = $params['object'];
                } elseif($data['type'] == 'brand') {
                    $object = $params['brand_id'];
                } else {
                    $object = $params['category_id'];
                }

                \App\Models\CouponsApply::where('coupon_id',$id)->delete();
                foreach($object as $object_id){
                    $dataCouponsApply = [
                        'coupon_id' => $id,
                        'object_id' => $object_id,
                        'type'      => $data['type']
                    ];
                    \App\Models\CouponsApply::create($dataCouponsApply);
                }
            }

            if($rs)
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thành công'
                ]);

            return response()->json([
                'rs' => 1,
                'msg' => 'Cập nhật không thành công'
            ]);

        }
        
        $randomletter = strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 1));
        $list_coupons = [];
         for ( $i = 0; $i < $params['total_coupons'] ; $i++) { 
            $random = $randomletter.substr( md5(date('YmdHis').rand().$i), 0, 7);
            $data = $params;
            $data['code'] = !empty($params['code'])?$params['code']:$random;
            $data['created_by'] = Auth::user()->id;
            $rs = \App\Models\Coupons::create($data);
            if($rs){
                if($data['type'] == 'product' || $data['type'] == 'brand' || $data['type'] == 'category'){

                    if($data['type'] == 'product') {
                        $object = $data['object'];
                    } elseif($data['type'] == 'brand') {
                        $object = $data['brand_id'];
                    } else {
                        $object = $data['category_id'];
                    }

                    \App\Models\CouponsApply::where('coupon_id', $rs->id)->delete();

                    foreach($object as $object_id){
                        $dataCouponsApply = [
                            'coupon_id' => $rs->id,
                            'object_id' => $object_id,
                            'type'      => $data['type']
                        ];
                        \App\Models\CouponsApply::create($dataCouponsApply);
                    }
                }
                $list_coupons[] = $data['code'];
            }
        }

        $file = $this->export($list_coupons);
        $request->session()->flash('file', $file);
        return response()->json([
            'rs'    => 1,
            'msg'   => 'Tạo mã coupons thành công',     
            'download' => 1,
        ]);
    }

    public function export($list_coupons){
        try {
            $params = ['list_coupons' => $list_coupons,'is_export' => 1];
            $objects = \App\Models\Coupons::getData($params);

            if ($objects) {

                $data = $objects->toArray();
                $stt = 0;
                foreach($data as &$item){
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
                }

                $header = ['STT','Mã Coupon', 'Tên Coupon', 'Loại Coupon','Áp dụng', 'Giảm', 'Kiểu giảm giá', 'Giá trị đơn hàng','Ngày áp dụng','Ngày kết thúc','Số lượt sử dụng','Ngày tạo'];
                $fields = ['stt','code', 'name', 'type','object','discount', 'percent', 'min_amount', 'date_from','date_to','times','created_at'];

                $tmpFile = \App\Helpers\Cexport::createFile('coupons', 'Danh sách mã coupon', $header, $fields, $data);

                return $tmpFile;
            }
        }
        catch (\Exception $e) {
            $msg = $e->getMessage();

            return response()->json([
                'rs'    => 0,
                'msg'   => $msg,
            ]);
        }
    }

    public function check_coupon(Request $request){
        $params = $request->all();
        $rules = [ 'code' => 'unique:coupons'];
        $valid = Validator::make($params, $rules);
        if($valid->fails())
            die('false');
        die('true');
    }

    public function getProductCode(Request $request){
        $params = $request->all();

        $data = \App\Models\CouponsApply::getProductCodeByCouponID($params['id']);

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function Download(Request $request){
        $file = $request->session()->get('file', false);     
        
        if(isset($_SESSION['file']))
            unset($_SESSION['file']);

        if($file)
            \App\Helpers\Cexport::downloadFile('List_coupons',$file); 

        return redirect()->to(route('coupons.index'));     
    }

    public function update_status(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => 'required|integer',
            'status' => 'required|integer'
        ];

        $messages = [
            'id' => 'Vui lòng truyền id',
            'status' => 'Vui lòng truyền trạng thái',
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

        $id = $request->input('id', 0);
        $status = $request->input('status', 0);

        $title = $status ? 'Kích hoạt' : 'Dừng';
        if ($id) {
            $object = \App\Models\Coupons::find($id);

            $object->status = $status;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => $title.' mã coupon thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => $title.' mã coupon không thành công'
        ]);
    }

    public function getBrand(Request $request){

        $params = $request->all();

        $data = \App\Models\CouponsApply::getBrandIDByCouponID($params['id']);

        return response()->json([
            'rs' => 1,
            'data' => $data
        ]);
    }

    public function get_category(Request $request){

        $params = $request->all();

        $data = \App\Models\CouponsApply::getBrandIDByCouponID($params['id']);

        return response()->json([
            'rs' => 1,
            'data' => $data
        ]);
    }
}