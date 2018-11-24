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
use App\Models\ProductsCategories;
use Doctrine\Common\Annotations\Annotation\Required;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use Illuminate\Validation\Rule;
use Validator;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'services';
        $this->data['title'] = 'dịch vụ';
    }     

    public function get_list_category($return_all=false) {

        $list_all_cate = Service::getAllData();

        $tmp = [];
        foreach($list_all_cate as $key => $value){
            $tmp['parent'][$value['parent_id']][]   = $value['id'];
            $tmp['item'][$value['id']]     = $value;
        }

        $list_all_cate = [];
        $this->handlingServices($list_all_cate,$tmp,0);

        if ($return_all) {
            $tmp['options'] = $list_all_cate;
            return $tmp;
        }
        
        return $list_all_cate;
    }

    public function handlingServices(&$result, $data, $parent=0, $step = '') {
        if (isset($data['parent'][$parent])) {
            foreach ($data['parent'][$parent] as $key => $item) {

                $result[] = array(
                    'id' => $item,
                    'name' => $step . $data['item'][$item]['name'],
                );
                if(isset($data['parent'][$item]))
                    $this->handlingServices($result,$data,$item,$step.'|--');
            }
        }
    }
    public function index(Request $request){
        $params         = $request->all();
        $objects        = Service::getData($params);
        $service_all    = $this->get_list_category();
        
        $this->data['service_all']  = array_pluck($service_all,'name','id');
        $this->data['objects']      = $objects;
        $this->data['params']       = $params;
        return view("{$this->data['controllerName']}.index", $this->data);        
    }

    public function create(Request $request){        
        $service_all    = $this->get_list_category();
        
        $this->data['service_all'] = array_pluck($service_all,'name','id');

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function edit(Request $request, $id){
        
        $object    = Service::find($id)->toArray();
        $service_all    = $this->get_list_category();
        
        $this->data['data'] = $object;
        $this->data['service_all'] = array_pluck($service_all,'name','id');
        
        $this->data['audits'] = \App\Models\Audits::getAudits(['App\Models\Service'], $id);
        $this->data['id']   = $id;
        $this->data['event_options'] = \App\Models\Audits::getOptionsEvent();

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request){
        $data = $request->all();
        
        $id = $request->input('id', 0);
        $status = $request->input('status', 1);

        $rules = [
            'name'  => 'required',
        ];
        $messages = [
            'name.required' => 'Nhập tên dịch vụ',
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
        $lang_code = 'vi';
        if (!isset($data['slug'])) {
            $data['slug'] = str_slug($data['name'], '-');
        }
        if (!isset($data['image_url'])) {
            $data['image_url'] = env('APP_URL',url('/'));
        }
        $data['user_modified'] = Auth::user()->id;
        if($id){
            $data_products = \App\Helpers\General::get_data_fillable(new Service(), $data);
            
            $object = Service::find($id);

            $rs = $object ? $object->update($data_products) : false;

            if($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật dịch vụ thành công',
                    'data' => $data,
                    'redirect' => route($this->data['controllerName'].'.edit',['id' => $id])
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật dịch vụ không thành công',
                'data' => $data,
            ]);

        }
        $data['user_created'] = Auth::user()->id;
        $rs = Service::create($data);

        if($rs){
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới dịch vụ thành công',
                'data' => $data,
                'redirect' => route($this->data['controllerName'].'.edit',['id' => $id])
            ]);

        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới dịch vụ không thành công',
            'data' => $data,
        ]);
    }
    
    public function save_product_files($data,$product_id=null){
        $ProductFiles = new \App\Models\ProductFiles();

        if(!$product_id) return true;

        $image_main = $ProductFiles->where(['pid' => $product_id, 'is_main' => 1])->first();

        if ($image_main) {
            if ($image_main->file_name != $data['image_main']) {
                $image_main->update(['file_type' => 1, 'file_name' => $data['image_main'], 'image_url' => config('app.url_outside')]);
            }
        } else {
            $ProductFiles->create(['pid' => $product_id, 'is_main' => 1, 'file_type' => 1,
                'file_name' => $data['image_main'], 'image_url' => config('app.url_outside')]);
        }

        $where_image_details = ['pid' => $product_id, 'is_main' => 0];
        if(!empty($data['image_details'])){
            $rs_image_details = [];
            foreach($data['image_details'] as $key => $value){
                $tmp_where = $where_image_details;
                $tmp_where['id'] = $key;

                $first = $ProductFiles->where($tmp_where)->first();
                if($first) {
                    $rs_image_details[] = $key;
                    if ($first->file_name != $value) {
                        $first->update(['file_name' => $value, 'file_type' => 1, 'image_url' => config('app.url_outside')]);
                    }
                }else{
                    $dataCreate = $where_image_details;
                    $dataCreate['file_name'] = $value;
                    $dataCreate['file_type'] = 1;
                    $dataCreate['image_url'] = config('app.url_outside');
                    $rs_image_detail_tmp = $ProductFiles->create($dataCreate);
                    if($rs_image_detail_tmp)
                        $rs_image_details[] = $rs_image_detail_tmp['id'];
                }
            }

            $ProductFiles->where($where_image_details)->whereNotIn('id',$rs_image_details)->delete();
        }else{
            $ProductFiles->where($where_image_details)->delete();
        }
    }

    public function store_features_values($product_id, $features_values=false) {
        $lang_code = 'vi';
        FeaturesValues::where('product_id', $product_id)->delete();

        if ($features_values) {
            foreach ($features_values as $feature_id => $feature_variants) {
                $item = [
                    'feature_id' => $feature_id,
                    'product_id' => $product_id,
                    'lang_code' => $lang_code
                ];

                foreach ($feature_variants as $variant_id) {
                    $item['variant_id'] = $variant_id;
                    FeaturesValues::create($item);
                }
            }
        }
    }

    public function update_count(Request $request){
        $data = $request->all();
        $product_ids = $request->input('product_ids', []);

        $rules = [
            'product_count'            => 'required',
            'product_ids'       => 'required|array',
        ];
        $messages = [
            'product_count.required'      => 'Truyền trạng số lượng sản phẩm',
            'product_ids.required'      => 'Truyền trạng ids sản phẩm',
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
        foreach ($product_ids as $product_id) {
            $object = Products::find($product_id);
            if ($object) {
                $object->update([
                    'amount' => $data['product_count']
                ]);
                $rs++;
            }
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển số lượng sản phẩm thành công',
                'data' => $data
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển số lượng sản phẩm không thành công',
            'data' => $data,
        ]);

    }

    public function change_value_field(Request $request){
        $data = $request->all();
        $product_ids = $request->input('product_ids', []);

        $rules = [
            'field'            => 'required',
            'value'            => 'required',
            'product_ids'       => 'required|array',
        ];
        $messages = [
            'field.required'      => 'Truyền trạng tên icon',
            'value.required'      => 'Truyền trạng giá trị icon',
            'product_ids.required'      => 'Truyền trạng ids sản phẩm',
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
        foreach ($product_ids as $product_id) {
            $object = Products::find($product_id);
            if ($object) {
                $object->update([
                    $data['field'] => $data['value']
                ]);
                $rs++;
            }
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển trạng thái icon '.$data['name'].' cho sản phẩm thành công',
                'data' => $data
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển trạng thái icon '.$data['name'].' cho sản phẩm không thành công',
            'data' => $data,
        ]);

    }

    public function change_has_gift(Request $request){
        $data = $request->all();
        $product_ids = $request->input('product_ids', []);

        $rules = [
            'has_gift'            => 'required',
            'product_ids'       => 'required|array',
        ];
        $messages = [
            'has_gift.required'      => 'Truyền trạng thái icon quà tặng sản phẩm',
            'product_ids.required'      => 'Truyền trạng ids sản phẩm',
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
        foreach ($product_ids as $product_id) {
            $object = Products::find($product_id);
            if ($object) {
                $object->update([
                    'has_gift' => $data['has_gift']
                ]);
                $rs++;
            }
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển trạng thái icon quà tặng cho sản phẩm thành công',
                'data' => $data
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển trạng thái icon quà tặng cho sản phẩm không thành công',
            'data' => $data,
        ]);

    }

    public function change_status(Request $request){
        $data = $request->all();
        $product_ids = $request->input('product_ids', []);

        $rules = [
            'status'            => 'required',
            'product_ids'       => 'required|array',
        ];
        $messages = [
            'status.required'      => 'Truyền trạng thái sản phẩm',
            'product_ids.required'      => 'Truyền trạng ids sản phẩm',
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
        foreach ($product_ids as $product_id) {
            $object = Service::find($product_id);
            if ($object) {
                $object->update([
                    'status' => $data['status'],
                    'user_modified' => Auth::user()->id
                ]);
                $rs++;
            }
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển trạng thái thành công',
                'data' => $data
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển trạng thái không thành công',
            'data' => $data,
        ]);

    }

    public function update_seo(Request $request){
        $data = $request->all();
        $product_id = $request->input('product_id', 0);

        $rules = [
            'product'       => 'required',
        ];
        $messages = [
            'product.required'      => 'Nhập tên sản phẩm',
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
        $lang_code = 'vi';
        $data_descriptions = \App\Helpers\General::get_data_fillable(new \App\Models\ProductDescriptions(), $data);

        $object = \App\Models\ProductDescriptions::where(['product_id' => $product_id,'lang_code' => $lang_code])->first();

        $rs = $object ? $object->update($data_descriptions) : false;
        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Cập nhật sản phẩm thành công',
                'data' => $data,
                'redirect' => route('products.edit',['id' => $product_id])
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Cập nhật sản phẩm không thành công',
            'data' => $data,
        ]);

    }

    public function save_products_categories($data, $product_id=null){
        $ProductsCategories = new \App\Models\ProductsCategories();

        if(isset($data['category_id']) && $data['category_id']) {
            foreach ($data['category_id'] as $item) {
                $where = ['product_id' => $product_id, 'category_id' => $item];

                $first = $ProductsCategories->where($where)->first();

                $dataUpdate = $where;

                if ($first) {
                    $first->where($where)->update($dataUpdate);
                } else {
                    $ProductsCategories->create($dataUpdate);
                }
            }
            $ProductsCategories->where('product_id', $product_id)
                ->whereNotIn('category_id', $data['category_id'])->delete();
        } else {
            $ProductsCategories->where('product_id', $product_id)->delete();
        }
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

    public function store_products_sold(Request $request){
        $data = $request->all();

        $rules = [
            'product_id'       => 'required',
            'product_sold_id'  => 'required'
        ];
        $messages = [
            'product_id.required'      => 'Thiếu product_id',
            'product_sold_id.required' => 'Thiếu product_sold_id'
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

        $rs = \App\Models\ProductsSold::where($data)->first();

        if(!$rs)
            $rs = \App\Models\ProductsSold::create($data);

        $product = Products::getProductsShow([$data['product_sold_id']]); 
        if($product)
            $product = $product[0];
        if($rs)
            return response()->json([
                'rs'        => 1,
                'msg'       => 'Thêm sản phẩm thành công',
                'data'      => $data,
                'product'   => $product
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm sản phẩm không thành công',
            'data' => $data,
        ]);
    }

    public function get_list_products_sold(Request $request){
        $product_id = $request->input('product_id');

        $pids = \App\Models\ProductsSold::select('product_sold_id')
                ->where('product_id',$product_id)->orderBy('created_at')->get();
        $result = [];
        if($pids){
            $result = Products::getProductsShow($pids->toArray());
        }

        return ['result' => $result];
    }

    public function destroy_products_sold(Request $request){
        $data = $request->all();

        $rules = [
            'product_id'       => 'required',
            'product_sold_id'  => 'required'
        ];
        $messages = [
            'product_id.required'      => 'Thiếu product_id',
            'product_sold_id.required' => 'Thiếu product_sold_id'
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
        $data = \App\Helpers\General::get_data_fillable(new \App\Models\ProductsSold(), $data);

        $rs = \App\Models\ProductsSold::where($data)->delete();
        if($rs)
            return response()->json([
                'rs'        => 1,
                'msg'       => 'Xóa sản phẩm thành công',
                'data'      => $data,
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa sản phẩm không thành công',
            'data' => $data,
        ]);
    }
    public function destroy(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id) {
            Service::find($id)->delete();
            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa không thành công'
        ]);
    }
}