<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/2/2018
 * Time: 11:44 AM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Brands;
use Validator;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'brands';

        $this->data['title'] = 'Thương hiệu';
    }

    public function get_options(Request $request){
        $options = Brands::getBrandOptions();

        return response()->json([
            'rs' => 1,
            'msg' => 'Thành công',
            'data' => $options
        ]);
    }

    public function index(Request $request){

        $params = $request->all();
        if (!isset($params['status'])) $params['status'] = 'A';

        $objects = Brands::getData($params);
        $this->data['objects']      = $objects;

        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');
        if(isset($params['category_id']) && isset($list_categories['parent'][$params['category_id']])) {
            $params['category_ids'] = $list_categories['parent'][$params['category_id']];
            $params['category_ids'][] = $params['category_id'];
        }

        $this->data['category_options'] = $category_options;

        $this->data['params'] = $params;

        return view("{$this->data['controllerName']}.index", $this->data);

    }

    public function edit($id)
    {
        $this->data['title'] = 'Cập nhật thương hiệu';

        $data = Brands::getDataById($id);
        $this->data['data'] = $data;

        $Categories = new \App\Http\Controllers\CategoriesController();
        $list_cate = $Categories->get_list_category();
        $this->data['list_cate'] = $list_cate;

        $categories = \App\Models\CategoriesBrands::getCategoryByBrand_id($id);
        $this->data['categories'] = array_pluck($categories, 'category', 'category_id');

        return view("{$this->data['controllerName']}.edit", $this->data);
    }

    public function create()
    {

        $this->data['title'] = 'Tạo mới thương hiệu'; 

        $Categories = new \App\Http\Controllers\CategoriesController();
        $list_cate = $Categories->get_list_category();
        $this->data['list_cate'] = $list_cate;

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $brand_id = $request->input('brand_id', 0);

        if(!$brand_id)
        {
            $rules = [
                'name'      => 'required|unique:brand_descriptions',
            ];
            $messages = [
                'name.required'         => 'Nhập tên thương hiệu',
                'name.unique'           => 'Tên thương hiệu đã tồn tại',
            ];
        }
        else
        {
            $rules = [];
            $messages = [];
            if(!isset($data['description'])){
                $rules = [
                    'name' => 'required|unique:brand_descriptions,name,'.$brand_id.',brand_id',
                ];

                $messages = [
                    'name.required'         => 'Nhập tên thương hiệu',
                    'name.unique'           => 'Tên thương hiệu đã tồn tại',
                ];
            }
            
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

        if (isset($data['image_location']) && !isset($data['image_url'])) {
            $data['image_url'] = config('app.url_outside');
        }

        if(empty($data['alias']) && !empty($data['name']))
            $data['alias'] = str_slug($data['name'],'-');

        $lang_code = ['vi'];
        $dataUpdateCreate = [];
        foreach($lang_code as $lc){            
            $dataUpdateCreate[$lc] = $data;
            $dataUpdateCreate[$lc]['lang_code'] = $lc;
        }
        
        if (isset($data['brand_id']) && $data['brand_id']) {

            $data_brands = \App\Helpers\General::get_data_fillable(new \App\Models\Brands(), $data);
            
            $rs = \App\Models\Brands::where('brand_id', $brand_id)
                ->update($data_brands);

            if ($rs) {
                foreach($lang_code as $lc){
                    $data_descriptions = $dataUpdateCreate[$lc];

                    $data_descriptions = \App\Helpers\General::get_data_fillable(new \App\Models\BrandDescriptions(), $data_descriptions);

                    \App\Models\BrandDescriptions::where(['brand_id' => $brand_id,'lang_code' => $lc])
                        ->update($data_descriptions);
                }
                if(!empty($data['category_id'])){
                    foreach($data['category_id'] as $item){
                        \App\Models\CategoriesBrands::firstOrCreate(['category_id' => $item,'brand_id' => $brand_id]);
                    }

                    \App\Models\CategoriesBrands::where('brand_id',$brand_id)
                        ->whereNotIn('category_id',$data['category_id'])
                        ->delete();
                }

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thương hiệu thành công',
                ]);
            }
            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật thương hiệu không thành công'
            ]);

        }

        $rs = \App\Models\Brands::create($data);

        if ($rs) {
            $brand_id = $rs['brand_id'];

            $dataUpdateCreate['vi']['brand_id'] = $brand_id;
            foreach ($dataUpdateCreate as $item){
                \App\Models\BrandDescriptions::create($item);
            }

            if(!empty($data['category_id'])){
                foreach($data['category_id'] as $item){
                    \App\Models\CategoriesBrands::firstOrCreate(['category_id' => $item,'brand_id' => $brand_id]);
                }
            }

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm thương hiệu thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm thương hiệu không thành công'
        ]);
    }
}