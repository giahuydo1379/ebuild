<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class AdviceController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'advice';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Bài viết tư vấn';

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Advices::select('*');
//        $objects->where('status', 13);
        $objects->orderBy('id', 'desc');

        $objects = $objects->paginate($limit)->toArray();
        if (isset($_GET['debug']) && $_GET['debug']==1) dd($objects);
        $this->data['objects'] = $objects;

        $categories = \App\Models\AdviceCategories::select('id', 'name')
                    ->where('status', 1)
                    ->pluck('name', 'id')->toArray();
        $this->data['categories'] = $categories;

        $brand_options = \App\Models\Brands::getBrandOptions();
        $this->data['brand_options'] = $brand_options;

        return view("{$this->data['controllerName']}.index", $this->data);
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

        $data['alias'] = str_slug($data['title']);

        $rules = [
            'title' => 'required|max:255',
            'alias' => 'required|unique:advices',
            'category_id' => 'required',
            'image_url' => 'required',
            'content' => 'required',
            'keywords' => 'required',
        ];

        $messages = [
            'title.required' => 'Nhập tiêu đề bài viết.',
            'alias.required' => 'Alias đã được sử dụng.',
            'category_id.required' => 'Chọn danh mục bài viết.',
            'image_url.required' => 'Chọn ảnh đại diện.',
            'content.required' => 'Nhập nội dung bài viết.',
            'keywords.required' => 'Nhập từ khóa để SEO.',
        ];

        if ($id) {
            $rules['alias'] = 'required|unique:advices,alias,'.$id;
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

        if(empty($data['brand_id']))
            $data['brand_id'] = 0;

        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Advices(), $data);

            $rs = \App\Models\Advices::where('id', $id)
                ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật bài viết thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật bài viết không thành công'
            ]);

        }

        $data['user_created'] = $data['user_modified'];
        $rs = \App\Models\Advices::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm bài viết thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm bài viết không thành công'
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
            \App\Models\Advices::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa bài viết thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa bài viết không thành công'
        ]);
    }
}
