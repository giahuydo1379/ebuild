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
use App\Models\Supplier;
use Doctrine\Common\Annotations\Annotation\Required;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductsPlaces;
use App\Models\Categories;
use App\Models\Units;
use Illuminate\Validation\Rule;
use Ixudra\Curl\Facades\Curl;
use Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'products'; 
        $this->data['title'] = 'Sản phẩm';
    }

    public function index(Request $request){
        $params = $request->all();
        $params['limit'] = $request->input('limit', 20);
        if (!array_key_exists('status', $params)) $params['status'] = 'A';

        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');
        if(isset($params['category_id']) && isset($list_categories['parent'][$params['category_id']])) {
            $params['category_ids'] = $list_categories['parent'][$params['category_id']];
            $params['category_ids'][] = $params['category_id'];
        }
        $supplier_options  = Supplier::get_options();
        $objects = Products::getData($params);

        $product_ids = array_column($objects['data'], 'product_id');

        $sub_category = ProductsCategories::select('category_id', 'product_id')
            ->whereIn('product_id', $product_ids)
            ->where('link_type', 'M')
            ->where('position', 1)->get()->toArray();
        $tmp = [];
        foreach ($sub_category as $item) {
            $tmp[$item['product_id']][] = $item['category_id'];
        }
        $sub_category = $tmp;
        $this->data['sub_category'] = $sub_category;

        $brand_options = Brands::getBrandOptions();

        $this->data['objects']              = $objects;
        $this->data['brand_options']        = $brand_options;
        $this->data['category_options']     = $category_options;
        $this->data['supplier_options']     = $supplier_options;
        $this->data['params']               = $params;

        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function create(Request $request){
        $this->data['title'] = 'Tạo mới sản phẩm';

        $list_brands            = Brands::getAllData();
        $list_units              = Units::getAllData();
        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category();

        $this->data['list_brands']      = $list_brands;
        $this->data['list_units']      = $list_units;
        $this->data['list_categories']  = $list_categories;
        $this->data['suppliers']  = Supplier::get_options();
        $this->data['parent_options'] = Products::getOptions();

        $features = Features::getOptionsFeatures();
        $this->data['features'] = $features;

        $this->data['features_values'] = [];
        $this->data['event']    = 'create';


        $parent_id = $request->input('parent_id',false);
        if($parent_id){
            $objects    = Products::getDataById($parent_id);
            if(empty($objects))
                abort(404);
            $objects['parent_id']   = $parent_id;
            $this->data['data']     = $objects;

            $features_values = FeaturesValues::select('product_features_values.feature_id',
                'product_features_values.variant_id', 'product_features_variants.name as variant','product_features_values.is_show_frontend')
                ->where('product_features_values.product_id', $parent_id)
                ->join('product_features', function($join1) {
                    $join1->on('product_features.id','=','product_features_values.feature_id');
                    $join1->where('product_features.status', 1);
                })
                ->join('product_features_variants', function($join2) {
                    $join2->on('product_features_variants.id','=','product_features_values.variant_id');
                })
                ->orderBy('product_features.position', 'asc')
                ->orderBy('product_features_variants.position', 'asc')
                ->get()->toArray();

            $tmp = [];
            foreach ($features_values as $item) {
                $tmp[$item['feature_id']][] = $item;
            }
            $this->data['features_values'] = $tmp;
            $this->data['products_places'] = ProductsPlaces::getProductsPlacesById($parent_id);
        }

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function edit(Request $request, $id){
        $this->data['title'] = 'Cập nhật sản phẩm';
        $objects    = Products::getDataById($id);

        if (!$objects) {
            abort(404);
        }

        $list_brands        = \App\Models\Brands::getAllData();
        $list_units        = \App\Models\Units::getAllData();
        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category();

        $this->data['list_brands']      = $list_brands;
        $this->data['list_units']      = $list_units;
        $this->data['list_categories']  = $list_categories;
        $this->data['suppliers']  = Supplier::get_options();
        $this->data['parent_options'] = Products::getOptions();
        $objects['date_start_sell'] = \App\Helpers\General::output_date($objects['date_start_sell'], true);

        $objects['sub_category'] = ProductsCategories::where('product_id', $id)
            ->where('link_type', 'M')
            ->where('position', 1)->pluck('category_id')->toArray();

        $this->data['data']  = $objects;

        $features = Features::getOptionsFeatures();
        $this->data['features'] = $features;

        $lang_code = 'vi';
        $features_values = FeaturesValues::select('product_features_values.feature_id',
            'product_features_values.variant_id', 'product_features_variants.name as variant','product_features_values.is_show_frontend')
            ->where('product_features_values.product_id', $id)
            ->join('product_features', function($join1) {
                $join1->on('product_features.id','=','product_features_values.feature_id');
                $join1->where('product_features.status', 1);
            })
            ->join('product_features_variants', function($join2) {
                $join2->on('product_features_variants.id','=','product_features_values.variant_id');
            })
            ->orderBy('product_features.position', 'asc')
            ->orderBy('product_features_variants.position', 'asc')
            ->get()->toArray();

        $tmp = [];
        foreach ($features_values as $item) {
            $tmp[$item['feature_id']][] = $item;
        }
        $this->data['features_values'] = $tmp;

        $this->data['audits'] = \App\Models\Audits::getAudits(['App\Models\Products', 'App\Models\ProductDescriptions'], $id);
        $this->data['product_id']   = $id;
        $this->data['event_options'] = \App\Models\Audits::getOptionsEvent();
        $this->data['field_name_options'] = Products::getOptionsFieldName();
        $this->data['fillable_no_auditable'] = Products::fillable_no_auditable();
        $this->data['products_places'] = ProductsPlaces::getProductsPlacesById($id);

        $this->data['event']    = 'edit';
        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request){
        $data = $request->all();
        $data['parent_id'] = intval($data['parent_id']);
        $product_id = $request->input('product_id', 0);
        $status = $request->input('status', 'A');
        
        if($product_id)
        {
            $rules = [
                'product'       => 'required',
                'product_code'  => 'unique:products,product_code,'.$product_id.',product_id,status,'.$status,
                'image_location'    => 'required',
                'category_id'   => 'required',
                'amount'        => 'required',
                'price'         => 'required',
            ];
            $messages = [
                'product.required'      => 'Nhập tên sản phẩm',
                'product_code.required' => 'Nhập SKU sản phẩm',
                'product_code.unique'   => 'SKU sản phẩm đã tồn tại',
                'image_location.required'   => 'Chọn hình ảnh chính sản phẩm',
                'category_id.required'  => 'Chọn danh mục',
                'amount.required'       => 'Nhập số lượng sản phẩm',
                'price.required'        => 'Nhập giá bản sản phẩm',
            ];
        } else {
            $rules = [
                'product'       => 'required',
                'product_code'  => 'required|unique:products',
                'image_location'    => 'required',
                'category_id'   => 'required',
                'amount'        => 'required',
                'price'         => 'required',
            ];
            $messages = [
                'product.required'      => 'Nhập tên sản phẩm',
                'product_code.required' => 'Nhập SKU sản phẩm',
                'product_code.unique'   => 'SKU sản phẩm đã tồn tại',
                'image_location.required'   => 'Chọn hình ảnh chính sản phẩm',
                'category_id.required'  => 'Chọn danh mục',
                'amount.required'       => 'Nhập số lượng sản phẩm',
                'price.required'        => 'Nhập giá bản sản phẩm',
            ];
        }

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
        $data['alias'] = str_slug($data['product'],'-');
        $data['date_start_sell'] = \Carbon\Carbon::parse($data['date_start_sell'])->format('Y-m-d');

        if (empty($data['image_url'])) $data['image_url'] = config('app.url_outside');

        if($product_id){

            $data_products = \App\Helpers\General::get_data_fillable(new \App\Models\Products(), $data);

            $object = \App\Models\Products::find($product_id);

            $rs = $object ? $object->update($data_products) : false;

            if($rs) {
                $this->store_features_values($product_id, @$data['variant_ids']);

                $data_descriptions = \App\Helpers\General::get_data_fillable(new \App\Models\ProductDescriptions(), $data);

                $object = \App\Models\ProductDescriptions::where(['product_id' => $product_id, 'lang_code' => $lang_code])->first();
                if ($object) $object->update($data_descriptions);

                $this->save_product_files($data,$product_id);
                $this->save_products_categories($data, $product_id);
                $this->save_products_sub_categories($data, $product_id);
                $this->storeProductsPlaces($data,$product_id,1);

                $url = config('app.url_outside').'/es/add-product/'.$product_id;
                $res = Curl::to( $url )->get();

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật sản phẩm thành công',
                    'data' => $data,
                    'redirect' => route('products.edit',['id' => $product_id])
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật sản phẩm không thành công',
                'data' => $data,
            ]);

        }

        $rs = \App\Models\Products::create($data);

        if($rs){

            $product_id = $rs['product_id'];

            $this->store_features_values($product_id, @$data['variant_ids']);

            $data['product_id']     = $product_id;
            $data['lang_code']      = $lang_code;

            \App\Models\ProductDescriptions::create($data);

            $this->save_product_files($data,$product_id);
            $this->save_products_categories($data, $product_id);
            $this->save_products_sub_categories($data, $product_id);
            $this->storeProductsPlaces($data,$product_id);

            $url = config('app.url_outside').'/es/add-product/'.$product_id;
            $res = Curl::to( $url )->get();

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới sản phẩm thành công',
                'data' => $data,
                'redirect' => route('products.edit',['id' => $product_id])
            ]);

        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới sản phẩm không thành công',
            'data' => $data,
        ]);
    }

    public function save_product_files($data,$product_id=null){
        $ProductFiles = new \App\Models\ProductFiles();

        if(!$product_id) return true;

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
                    'is_show_frontend' => !empty($feature_variants['is_show_frontend'])?1:0,

                ];

                foreach ($feature_variants['data'] as $variant_id) {
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
            $object = Products::find($product_id);
            if ($object) {
                $object->update([
                    'status' => $data['status']
                ]);
                $rs++;
            }
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển trạng thái sản phẩm thành công',
                'data' => $data
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển trạng thái sản phẩm không thành công',
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
        $categories = Categories::select('id_path')->find($data['category_id'])->toArray();

        $cate_id_path = explode('/',$categories['id_path']);
        $cate_id_path[] = $data['category_id'];
        $cate_id_path = array_unique($cate_id_path);

        $ProductsCategories = new \App\Models\ProductsCategories();

        foreach($cate_id_path as $item){
            if (!$item) continue;

            $where = ['product_id' => $product_id, 'category_id' => $item];

            $first = $ProductsCategories->where($where)->first();

            $dataUpdate = $where;
            if($item == $data['category_id']){

                $dataUpdate['link_type'] = 'M';
            }else{
                $dataUpdate['link_type'] = 'A';
            }

            $dataUpdate['position'] = 0;

            if($first){
                $first->where($where)->update($dataUpdate);
            }else{
                $ProductsCategories->create($dataUpdate);
            }
        }
        $ProductsCategories->where('product_id',$product_id)->whereNotIn('category_id',$cate_id_path)->delete();
    }

    public function save_products_sub_categories($data, $product_id=null){
        $categories = isset($data['sub_category']) ? ( Categories::select('id_path')
            ->whereIn('category_id', $data['sub_category'])->pluck('id_path')->toArray()) : [];

        $cate_id_path = [];
        foreach ($categories as $item) {
            $item = explode('/', $item);
            foreach ($item as $cid) {
                $cate_id_path[$cid] = $cid;
            }
        }

        if (isset($data['sub_category'])) {
            foreach ($data['sub_category'] as $cid) {
                $cate_id_path[$cid] = $cid;
            }
            $cate_id_path = array_values($cate_id_path);
        }

        $ProductsCategories = new \App\Models\ProductsCategories();

        foreach($cate_id_path as $item) {
            if (!$item) continue;

            $where = ['product_id' => $product_id, 'category_id' => $item];

            $first = $ProductsCategories->where($where)->first();

            $dataUpdate = $where;
            if(in_array($item, $data['sub_category'])) {
                $dataUpdate['link_type'] = 'M';
                $dataUpdate['position'] = 1;
            }else{
                $dataUpdate['link_type'] = 'A';
            }

            if($first) {
                $first->where($where)->update($dataUpdate);
            }else{
                $dataUpdate['position'] = 1;
                $ProductsCategories->create($dataUpdate);
            }
        }

        // xoa sub category còn lại
        $ProductsCategories->where('product_id', $product_id)
            ->where('position', 1)
            ->whereNotIn('category_id', $cate_id_path)->delete();
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

    public function storeProductsPlaces($data,$product_id,$update=false){
        if($update){
            \App\Models\ProductsPlaces::where('product_id',$product_id)->delete();
        }
        $data = $data['products_places']??[]; 
        
        foreach($data as $item){
            if(empty($item['province_id']) 
                || empty($item['district_id'])
            ) continue;
            $item['product_id'] = $product_id;
            \App\Models\ProductsPlaces::create($item);
        }
    }
}