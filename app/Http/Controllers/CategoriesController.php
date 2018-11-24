<?php

namespace App\Http\Controllers;

use App\Models\Features;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use \App\Models\Categories;

class CategoriesController extends Controller
{
    protected $data = []; // the information we send to the view    
    protected $_arr  = [];
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'categories';

        $this->data['title'] = 'Danh mục';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['limit'] = $request->input('limit', 30);

        $objects = Categories::getData($params);
        $this->data['objects']      = $objects;

        if(!empty($params['parent_id']) && !empty($objects['data'])){
            $categoryCurrent = Categories::getDataById($params['parent_id']);
            $this->data['categoryCurrent'] = $categoryCurrent;

            $id_path = $categoryCurrent['id_path'];
            $id_path = explode('/',$id_path);
            $listCatePath = Categories::getDataByIds($id_path);

            $path = [];
            foreach($listCatePath as $item){
                $path[] = '<span>'.$item['category'].'</span>';
            }

            $this->data['path'] = implode('/',$path);
        }

        return view("{$this->data['controllerName']}.index", $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = 'Tạo mới danh mục';

        $list_all_cate = $this->get_list_category();

        $this->data['list_all_cate'] = $list_all_cate;

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $category_id = $request->input('category_id', 0);

        if(!$category_id)
        {
            $rules = [
                'category'  => 'required', //|unique:category_descriptions
                'position'  => 'required'
            ];
            $messages = [
                'category.required'     => 'Nhập tên danh mục',
                'category.unique'       => 'Tên danh mục đã tồn tại',
                'position.required'     => 'Nhập thứ tự',
            ];
        }
        else
        {
            $rules = [
                'category' => 'required', //|unique:category_descriptions,category,'.$category_id.',category_id
                'position' => 'required'
            ];

            $messages = [
                'category.required'     => 'Nhập tên danh mục',
                'category.unique'       => 'Tên danh mục đã tồn tại',
                'position.required'     => 'Nhập thứ tự',
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

        if(empty($data['alias']))
            $data['alias'] = str_slug($data['category'],'-');

        if (!isset($data['image_url'])) {
            $data['image_url'] = config('app.url_outside');
        }

        if (isset($data['category_id']) && $data['category_id']) {

            if($data['parent_id'] != 0) {
                $category_parent = Categories::getDataById($data['parent_id']);
                $arr_path = explode('/',$category_parent['id_path']);
                array_push($arr_path,$category_id);
                $data['id_path'] = implode('/',$arr_path);
            }else{
                $data['id_path'] = $category_id;
            }

            $data_categories = \App\Helpers\General::get_data_fillable(new \App\Models\Categories(), $data);
            
            $rs = \App\Models\Categories::where('category_id', $category_id)
                ->update($data_categories);

            if ($rs) {

                $data_category_descriptions = \App\Helpers\General::get_data_fillable(new \App\Models\CategoryDescriptions(), $data);

                $rs = \App\Models\CategoryDescriptions::where(['category_id' => $category_id,'lang_code' => $lang_code])
                    ->update($data_category_descriptions);

                if($rs){
                    return response()->json([
                        'rs' => 1,
                        'msg' => 'Cập nhật danh mục thành công',
                    ]);
                }
            }
            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật danh mục không thành công'
            ]);

        }

        $rs = \App\Models\Categories::create($data);

        if ($rs) {
            $category_id = $rs['category_id'];
            if($data['parent_id'] != 0){
                $category_parent = Categories::getDataById($data['parent_id']);
                $arr_path = explode('/',$category_parent['id_path']);
                array_push($arr_path,$category_id);
                $id_path = implode('/',$arr_path);
            }else{
                $id_path = $category_id;
            }

            $rs->update(['id_path' => $id_path]);

            $data['category_id']    = $category_id;
            $data['lang_code']      = $lang_code;

            $rs = \App\Models\CategoryDescriptions::create($data);

            if($rs){
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Thêm danh mục thành công',
                ]);
            }
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm danh mục không thành công'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->data['title'] = 'Chi tiết danh mục';

        $data = Categories::getDataById($id);

        $this->data['data'] = $data;

        $list_all_cate = $this->get_list_category();
        $this->data['list_all_cate'] = $list_all_cate;

        $features = Features::getFeaturesByCategory($id);
        $this->data['features'] = $features;

        return view("{$this->data['controllerName']}.edit", $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['title'] = 'Cập nhật danh mục';

        $data = Categories::getDataById($id);

        $this->data['object'] = $data;

        $list_all_cate = $this->get_list_category();
        $this->data['list_all_cate'] = $list_all_cate;

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id', 0);

        $lang_code = 'vi';
        if ($id) {
            \App\Models\Categories::find($id)->delete();
            \App\Models\CategoryDescriptions::where(['category_id'=> $id,'lang_code' => $lang_code])->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa danh mục sản phẩm thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa danh mục sản phẩm không thành công'
        ]);
    }

    public function get_list_category($return_all=false) {

        $list_all_cate = Categories::getAllData();

        $tmp = [];
        foreach($list_all_cate as $key => $value){
            $tmp['parent'][$value['parent_id']][]   = $value['category_id'];
            $tmp['item'][$value['category_id']]     = $value;
        }

        $list_all_cate = [];

        \App\Helpers\General::handlingCategories($list_all_cate, $tmp,0);

        if ($return_all) {
            $tmp['options'] = $list_all_cate;
            return $tmp;
        }

        return $list_all_cate;
    }
}
