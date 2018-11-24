<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class MenuItemController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'menu-item';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Menu';

        $objects = \App\Models\MenuItem::getData($request->all());
        $this->data['objects'] = $objects;

        $pages = \App\Models\Pages::select('*')->where('status','publish')->get()->toArray();
        $this->data['pages'] = array_pluck($pages,'title','id');

        $categories = $this->get_list_category(false);
        $this->data['categories'] = array_pluck($categories,'name','id');

        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function get_menus_parent(Request $request)
    {
        $categories = $this->get_list_category(false, $request->input('position', ''));
        $categories = array_pluck($categories,'name','id');

        return response()->json([
            'rs' => 1,
            'data' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $id = $request->input('id', 0);

        $data['slug'] = str_slug($data['name']);

        $rules = [
            'name' => 'required|max:255',
            //'slug' => 'required|unique:menu_items',
            'type' => 'required',
            'position' => 'required'
        ];

        $messages = [
            'name.required'     => 'Nhập tiêu đề.',
            'slug.unique'       => 'Alias đã được sử dụng.',
            'type.required'     => 'Chọn loại liên kết.',
            'position.required' => 'Chọn vị trí.'
        ];

        if ($id) {
            //$rules['slug'] = 'required|unique:menu_items,slug,'.$id;
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
        if($data['type'] == 'page_link'){
            $data['link']       = "";
        }else{
            $data['page_id'] = 0;
        }

        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\MenuItem(), $data);

            $rs = \App\Models\MenuItem::where('id', $id)
                ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật không thành công'
            ]);

        }

        $rs = \App\Models\MenuItem::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới không thành công'
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        if ($id) {
            \App\Models\MenuItem::find($id)->delete();

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

    public function handlingCategories($data, $parent=0, $step = 0) {
        $str_step = sprintf("%'-".($step*2)."s", '');

        if (!isset($data['parent'])) {
            return [];
        }

        foreach( $data['parent'][$parent] as $key => $item ){

            $this->_arr[] = array(
                'id' => $item,
                'name' => $str_step.$data['item'][$item]['name'],
            );

            if($step < 2 && isset($data['parent'][$item]))
                $this->handlingCategories($data, $item, $step+1);
        }

        return $this->_arr;
    }

    public function get_list_category($return_all=false, $position='') {

        $list_all_cate = \App\Models\MenuItem::select('*');
        if ($position) $list_all_cate->where('position', $position);
        $list_all_cate = $list_all_cate->get()->toArray();

        $tmp = [];
        foreach($list_all_cate as $key => $value){
            $tmp['parent'][intval($value['parent_id'])][]   = $value['id'];
            $tmp['item'][$value['id']]     = $value;
        }

        $list_all_cate = $this->handlingCategories($tmp,0);

        if ($return_all) {
            $tmp['options'] = $list_all_cate;
            return $tmp;
        }

        return $list_all_cate;
    }
}
