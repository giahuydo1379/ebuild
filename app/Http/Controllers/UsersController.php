<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'users';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Quản lý khách hàng';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Users::select('users.*');

        $objects->orderBy('user_id', 'desc');

        $objects = $objects->paginate($limit)->toArray();
        if (isset($_GET['debug']) && $_GET['debug']==1) dd($objects);
        $this->data['objects'] = $objects;

        $genders = \App\Helpers\General::get_gender_options();
        $this->data['genders'] = $genders;

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
        
        if(!empty($data['birthday']))
            $data['birthday'] = date('Y-m-d',strtotime($data['birthday']));

        $id = $request->input('user_id', 0);

        if(!$id)
        {
            $rules = [
                'username' => 'required|max:150|unique:users',
            ];

            $messages = [
                'username.required' => 'Nhập tên đăng nhập',
                'username.unique' => 'Tên đăng nhập đã tồn tại',
            ];
        }
        else
        {
            $rules = [
                'username' => 'required|max:150',
            ];

            $messages = [
                'username.required' => 'Nhập tên đăng nhập',
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

        if (isset($data['user_id']) && $data['user_id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Users(), $data);
            
            $rs = \App\Models\Users::where('user_id', $id)
                ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật khách hàng thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật khách hàng không thành công'
            ]);

        }

        $data['password'] = Hash::make('123!@#');

        $rs = \App\Models\Users::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm khách hàng thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm khách hàng không thành công'
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
    public function change_status(Request $request)
    {
        $status = $request->input('is_enabled', 0);
        $ids = $request->input('ids', []);

        if ($ids) {
            \App\Models\Users::whereIn('user_id', $ids)
                ->update(['status' => $status]);

            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển trạng thái thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển trạng thái không thành công'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('user_id', 0);

        if ($id) {
            \App\Models\Users::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa khách hàng thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa khách hàng không thành công'
        ]);
    }
}
