<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'employees';
    }

    public function check_cmnd(Request $request)
    {
        $id = $request->input('id', null);
        $email = $request->input('cmnd', null);

        $object = Employee::where('cmnd', $email);
        if ($id) {
            $object->where('id', '!=', $id);
        }
        $object = $object->first();

        if ($object) return response()->json(false);

        return response()->json(true);
    }
    public function check_code(Request $request)
    {
        $id = $request->input('id', null);
        $email = $request->input('code', null);

        $object = Employee::where('code', $email);
        if ($id) {
            $object->where('id', '!=', $id);
        }
        $object = $object->first();

        if ($object) return response()->json(false);

        return response()->json(true);
    }
    public function check_email(Request $request)
    {
        $id = $request->input('id', null);
        $email = $request->input('email', null);

        $object = Employee::where('email', $email);
        if ($id) {
            $object->where('id', '!=', $id);
        }
        $object = $object->first();

        if ($object) return response()->json(false);

        return response()->json(true);
    }
    public function check_phone(Request $request)
    {
        $id = $request->input('id', null);
        $phone = $request->input('phone', null);
        $object = Employee::where('phone', $phone);
        if ($id) {
            $object->where('id', '!=', $id);
        }
        $object = $object->first();

        if ($object) return response()->json(false);

        return response()->json(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'nhân viên';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Employee::select('employees.*');

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

        $id = $request->input('id', 0);

        $rules = [
            'name'      => 'required',
        ];
        $messages = [
            'name.required'     => 'Bạn phải nhập tên nhân viên',
            'birthday.required' => 'Bạn phải chọn ngày sinh',
            'code.required'     => 'Bạn phải nhập mã nhân viên',
            'code.unique'       => 'Mã nhân viên đã được sử dựng',
            'phone.required'    => 'Bạn phải nhập số điện thoại',
            'phone.unique'      => 'Số điện thoại đã có người sử dựng',
            'email.required'    => 'Bạn phải nhập email',
            'email.email'       => 'Email không đúng định dạng',
            'email.unique'      => 'Email đã có người sử dựng',
            //'password.required' => 'Nhập mật khẩu'
        ];

        if(!$id)
        {

            $rules += [
                'code'         => 'required|unique:employees,code',
                'phone'         => 'required|unique:employees,phone',
                'email'         => 'required|email|unique:employees,email',
                'cmnd'         => 'required|unique:employees,cmnd',
            ];
        }
        else
        {
            $rules += [
                'code'         => 'required|unique:employees,code,'.$id.',id',
                'phone'         => 'required|unique:employees,phone,'.$id.',id',
                'email'         => 'required|email|unique:employees,email,'.$id.',id',
                'cmnd'         => 'required|unique:employees,cmnd,'.$id.',id',
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

        if (isset($data['birthday'])) {
            $data['birthday'] = \Carbon\Carbon::parse($data['birthday'])->format('Y-m-d');
        }

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (!isset($data['image_url']) && isset($data['image_location']) && strpos($data['image_location'], 'http')) {
            $data['image_url'] = config('app.url_outside');
        }

        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Employee(), $data);

            $rs = \App\Models\Employee::where('id', $id)
                ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật nhân viên thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật nhân viên không thành công'
            ]);

        }

        $rs = \App\Models\Employee::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm nhân viên thành công. Mật khẩu là: '.$data['password'],
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm nhân viên không thành công'
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
            \App\Models\Employee::whereIn('id', $ids)
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
            \App\Models\Employee::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa nhân viên thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa nhân viên không thành công'
        ]);
    }
}
