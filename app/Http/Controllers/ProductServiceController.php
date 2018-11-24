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
use App\Models\Categories;
use Illuminate\Validation\Rule;
use Validator;

class ProductServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'product-service';
        $this->data['title'] = 'Dịch vụ';
    }

    public function index(Request $request){
        $params = $request->all();
        if (!array_key_exists('status', $params)) $params['status'] = 'A';

        $params['product_type'] = 'S';
        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');
        if(isset($params['category_id']) && isset($list_categories['parent'][$params['category_id']])) {
            $params['category_ids'] = $list_categories['parent'][$params['category_id']];
            $params['category_ids'][] = $params['category_id'];
        }

        $objects = Products::getData($params);

        $brand_options = Brands::getBrandOptions();

        $this->data['objects']              = $objects;
        $this->data['brand_options']        = $brand_options;
        $this->data['category_options']     = $category_options;
        $this->data['params']               = $params;

        return view("{$this->data['controllerName']}.index", $this->data);

    }

    public function create(Request $request){
        $this->data['title'] = 'Tạo mới dịch vụ';
        // $list_brands            = Brands::getAllData(); dd($list_brands);
        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category();

        $this->data['list_categories']  = $list_categories;

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function edit(Request $request, $id){
        $this->data['title'] = 'Cập nhật Dịch vụ';
        $objects    = Products::getDataById($id);

        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category();

        $this->data['list_categories']  = $list_categories;

        $category_ids = ProductsCategories::where('product_id', $id)->where('link_type', 'M')->pluck('category_id')->toArray();
        $objects['category_id'] = $category_ids;

        $this->data['data']  = $objects;

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request){
        $data = $request->all();
        $product_id = $request->input('product_id', 0);
        $status = $request->input('status', 'A');

        $rules = [
            'product'       => 'required',
            'category_id'   => 'required',
            'price'         => 'required',
        ];
        $messages = [
            'product.required'      => 'Nhập tên dịch vụ',
            'category_id.required'  => 'Chọn danh mục',
            'price.required'        => 'Nhập giá bản dịch vụ',
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
        $data['product_type'] = 'S';
        if($product_id){

            $data_products = \App\Helpers\General::get_data_fillable(new \App\Models\Products(), $data);

            $object = \App\Models\Products::find($product_id);

            $rs = $object ? $object->update($data_products) : false;

            if($rs) {

                $data_descriptions = \App\Helpers\General::get_data_fillable(new \App\Models\ProductDescriptions(), $data);

                $object = \App\Models\ProductDescriptions::where(['product_id' => $product_id, 'lang_code' => $lang_code])->first();
                if ($object) $object->update($data_descriptions);

                $this->save_products_categories($data, $product_id);

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật dịch vụ thành công',
                    'data' => $data,
                    'redirect' => route('products.edit',['id' => $product_id])
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật dịch vụ không thành công',
                'data' => $data,
            ]);

        }

        $rs = \App\Models\Products::create($data);

        if($rs){

            $product_id = $rs['product_id'];

            $data['product_id']     = $product_id;
            $data['lang_code']      = $lang_code;

            \App\Models\ProductDescriptions::create($data);

            $this->save_products_categories($data,$product_id);

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới dịch vụ thành công',
                'data' => $data,
                'redirect' => route('products.edit',['id' => $product_id])
            ]);

        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới dịch vụ không thành công',
            'data' => $data,
        ]);
    }

    public function save_products_categories($data, $product_id=null){
        $categories = Categories::select('id_path')
            ->whereIn('category_id', $data['category_id'])->pluck('id_path')->toArray();

        $cate_id_path = [];
        foreach ($categories as $item) {
            $item = explode('/', $item);
            foreach ($item as $cid) {
                $cate_id_path[$cid] = $cid;
            }
        }

        foreach ($data['category_id'] as $cid) {
            $cate_id_path[$cid] = $cid;
        }
        $cate_id_path = array_values($cate_id_path);

        $ProductsCategories = new \App\Models\ProductsCategories();

        foreach($cate_id_path as $item){
            if (!$item) continue;

            $where = ['product_id' => $product_id, 'category_id' => $item];

            $first = $ProductsCategories->where($where)->first();

            $dataUpdate = $where;
            if(in_array($item, $data['category_id'])) {

                $dataUpdate['link_type'] = 'M';
            }else{
                $dataUpdate['link_type'] = 'A';
            }

            if($first){
                $first->where($where)->update($dataUpdate);
            }else{
                $ProductsCategories->create($dataUpdate);
            }
        }
        $ProductsCategories->where('product_id', $product_id)->whereNotIn('category_id', $cate_id_path)->delete();
    }
}