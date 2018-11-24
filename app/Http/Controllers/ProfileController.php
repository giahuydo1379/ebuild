<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'profile';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Thông tin tài khoản';

        $user = Auth::user()->toArray();
        
        $departments = \App\Models\Departments::find($user['department_id']);
        $user['department_name'] = $departments['name'];

        $genders = \App\Helpers\General::get_gender_options();
        $this->data['genders'] = $genders;
        $this->data['user'] = $user;
        $this->data['info'] = 'active';
        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function change_password(Request $request){
        $user = Auth::user()->toArray();

        $this->data['user'] = $user;
        $this->data['change_password'] = 'active';
        return view("{$this->data['controllerName']}.change-password", $this->data);
    }


    public function ajax_change_password(Request $request){
        $data =$request->all();
        $rules = [
                'current_password'          => 'required|old_password:' . Auth::user()->password,
                'password'                  => 'required|confirmed',
                'password_confirmation'     => 'required',
                'captcha'                   => 'required|captcha'
            ];

            $messages = [
                'current_password.required'     => 'Nhập Mật khẩu hiện tại',
                'current_password.old_password' => 'Mật khẩu hiện tại không đúng',
                'password.required'             => 'Nhập Mật khẩu mới',
                'password.confirmed'            => 'Mật khẩu không trùng khớp',
                'password_confirmation.required'      => 'Nhập lại Mật khẩu mới',
                'captcha.required'              => 'Nhập mã xác nhận',
                'captcha.captcha'          => 'Mã xác nhận không đúng',
            ];

        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {

            return Hash::check($value, current($parameters));

        });

        $valid = Validator::make($data, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages()
            ]);
        }

        $data = ['password' => Hash::make($data['password'])];
        
        $rs = \App\Models\AdminUsers::where('id', Auth::user()->id)
                ->update($data);

        if($rs){
            return response()->json([
                'rs' => 1,
                'msg' => 'Thay đổi mật khẩu thành công'
            ]);
        }

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
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
    }
}