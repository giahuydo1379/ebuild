<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationCategories;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'notification';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Thông báo';

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = Notification::select('*');
        $objects->orderBy('notification_id', 'desc');

        $objects = $objects->paginate($limit)->toArray();
        if (isset($_GET['debug']) && $_GET['debug']==1) dd($objects);
        $this->data['objects'] = $objects;

        $categories = NotificationCategories::select('notification_category_id', 'notification_category_name')
                    ->where('status', 1)
                    ->pluck('notification_category_name', 'notification_category_id')->toArray();
        $this->data['categories'] = $categories;

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
        $id = $request->input('notification_id', 0);

        $rules = [
            'object_display_name' => 'required|max:255',
            'notification_category_id' => 'required',
            'object_content' => 'required',
        ];

        $messages = [
            'object_display_name.required' => 'Nhập tiêu đề thông báo.',
            'notification_category_id.required' => 'Chọn danh mục thông báo.',
            'object_content.required' => 'Nhập nội dung thông báo.',
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

        $data['user_modified'] = $this->get_user_id();
        if (isset($data['notification_id']) && $data['notification_id']) {

            $data = \App\Helpers\General::get_data_fillable(new Notification(), $data);

            $rs = Notification::find($id)->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thông báo thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật thông báo không thành công'
            ]);

        }

        $data['user_created'] = $data['user_modified'];
        $rs = Notification::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm thông báo thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm thông báo không thành công'
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
            Notification::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa thông báo thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa thông báo không thành công'
        ]);
    }
}
