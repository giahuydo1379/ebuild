<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'banners';
    }

    public function about(Request $request)
    {
        $type='about';

        $this->data['title'] = 'Hình ảnh về Thiên Hòa - Khuyến mãi';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Banner::select('banners.*')
            ->where('page', $type);

        $objects->orderBy('banners.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();
        if (isset($_GET['debug']) && $_GET['debug']==1) dd($objects);
        $this->data['objects'] = $objects;

        $this->data['page'] = $type;

        return view("{$this->data['controllerName']}.about", $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type='home')
    {
        $this->data['title'] = 'Banner - Khuyến mãi';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);
        
        $objects = \App\Models\Banner::select('banners.*')
            ->where('page', $type);

        if (isset($params['position_filter'])) {
            $objects->where('position_id', $params['position_filter']);
        }

        $objects->orderBy('banners.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

        $this->data['objects'] = $objects;

        $positions = \App\Models\BannerPosition::select('id', 'description', 'page', 'position', 'max_item', 'size',
            'file_type', 'max_file_size', 'ordering')
            ->where('page', $type)
            ->orderBy('page')
            ->orderBy('ordering', 'asc')
            ->get()->toArray();
        $tmp = [];
        $tmp_options = [];
        foreach ($positions as $item) {
            $tmp[$item['id']] = $item;
            $tmp_options[$item['id']] = $item['description'];
        }
        $this->data['positions'] = $tmp;
        $this->data['positions_options'] = $tmp_options;

        $this->data['page'] = $type;

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

        $rules = [
            'name' => 'required|max:255',
            'url' => 'required|max:255',
            'position_id' => 'required',
            'ordering' => 'required',
            'image_location' => 'required',
            'page' => 'required',
        ];

        $messages = [
            'name.required' => 'Nhập tên banner',
            'url.required' => 'Nhập link liên kết',
            'position_id.required' => 'Chọn vị trí banner',
            'ordering.required' => 'Chọn thứ tự hiển thị',
            'image_location.required' => 'Chọn ảnh hiển thị',
            'page.required' => 'Nhập loại banner',
        ];

        if (!$id) {
            $data['image_url'] = config('app.url_outside');
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

        $title = $data['page'] === 'about' ? 'hình ảnh' : 'banner';
        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Banner(), $data);

            $object = \App\Models\Banner::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            if(isset($data['image_location']) && $object->image_location != $data['image_location']) {
                $data['image_url'] = config('app.url_outside');
            }

            $rs = \App\Models\Banner::where('id', $id)
                ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật '.$title.' thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật '.$title.' không thành công'
            ]);

        }

        $data['user_created'] = $data['user_modified'];
        $rs = \App\Models\Banner::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm '.$title.' thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm '.$title.' không thành công'
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
            \App\Models\Banner::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa banner thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa banner không thành công'
        ]);
    }
}
