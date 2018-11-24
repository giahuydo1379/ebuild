<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'customers';
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

        $objects = \App\Models\Customers::select('customers.*');

        $objects->orderBy('id', 'desc');

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

        $id = $request->input('id', 0);

        $rules = [
            'name'      => 'required',
        ];
        $messages = [
            'name.required'     => 'Bạn phải nhập họ tên',
            'birthday.required' => 'Bạn phải chọn ngày sinh',
            'phone.required'    => 'Bạn phải nhập số điện thoại',
            'phone.unique'      => 'Số điện thoại đã có người sử dựng',
            'email.required'    => 'Bạn phải nhập email',
            'email.email'       => 'Email không đúng định dạng',
            'email.unique'      => 'Email đã có người sử dựng',
        ];

        if(!$id)
        {

            $rules += [
                'code'         => 'required|unique:customers,code',
                'phone'         => 'required|unique:customers,phone',
                'email'         => 'required|email|unique:customers,email',
            ];
        }
        else
        {
            $rules += [
                'code'         => 'required|unique:customers,code,'.$id.',id',
                'phone'         => 'required|unique:customers,phone,'.$id.',id',
                'email'         => 'required|email|unique:customers,email,'.$id.',id',
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

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Customers(), $data);

            $rs = \App\Models\Customers::where('id', $id)
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

        $rs = \App\Models\Customers::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm khách hàng thành công. Mật khẩu là: '.$password,
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
            \App\Models\Customers::whereIn('id', $ids)
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
        $id = $request->input('id', 0);

        if ($id) {
            \App\Models\Customers::find($id)->delete();

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
