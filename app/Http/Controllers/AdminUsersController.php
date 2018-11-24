<?php

namespace App\Http\Controllers;

use App\Models\AdminUsers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'admin-users';
    }
    public function getCombogridData(Request $request)
    {        
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'desc'),
            'search' => $request->input('q', ''),
            'department_id' => $request->input('department_id', ''),
        ];

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        if (!$request->has('page')) {
            $offset = $request->input('offset', 0);
            $page = round($offset / $limit) + 1;
            $request->request->add(['page' => $page]);
        }

        $objects = AdminUsers::leftJoin('departments', 'departments.id', '=', 'admin_users.department_id')
            ->where('admin_users.id', '!=', 1)
            ->where('admin_users.is_enabled', 1);

        $keyword = $request->input('q', '');
        if ($keyword) {
            $objects->where(function ($query) use ($keyword) {
                $query->where('admin_users.full_name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('admin_users.email', 'LIKE', '%' . $keyword . '%');
            });
        }

        $department_id = $request->input('department_id', '');
        if ($department_id) {
            $objects->where('admin_users.department_id', '=', $department_id);
        }

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        if ($sort && $order) {
            $objects->orderBy($sort, $order);
        }

        $objects = $objects->paginate($limit, [
            \DB::raw('admin_users.*'),
            \DB::raw('departments.name as department_name'),
        ])->toArray();

        return response()->json([
            'total' => $objects['total'],
            'rows' => isset($objects['data']) ? array_values($objects['data']) : [],
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Quản lý người dùng CMS';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\AdminUsers::select('admin_users.*')
            ->where('admin_users.id', '!=', 1);

        $objects->orderBy('id', 'desc');

        $objects = $objects->paginate($limit)->toArray();
        
        if (isset($_GET['debug']) && $_GET['debug']==1) dd($objects);
        $this->data['objects'] = $objects;

        $genders = \App\Helpers\General::get_gender_options();
        $this->data['genders'] = $genders;

        $roles = \App\Models\Roles::getAllData();
        $this->data['roles'] = array_pluck($roles,'name','id');        

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

        if(!empty($data['birthday']))
            $data['birthday'] = date('Y-m-d',strtotime($data['birthday']));
        
        if(!$id)
        {
            $rules = [
                'username' => 'required|max:150|unique:admin_users',
                'password' => 'required'
            ];

            $messages = [
                'username.required' => 'Nhập tên đăng nhập',
                'username.unique' => 'Tên đăng nhập đã tồn tại',
                'password.required' => 'Nhập mật khẩu'
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

        if(empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['role_id']) && in_array(1, $data['role_id'])) {
            $data['department_id'] = 3;
        } else {
            $data['department_id'] = 0;
        }

        if (isset($data['id']) && $data['id']) {

            $dataUpdate = \App\Helpers\General::get_data_fillable(new \App\Models\AdminUsers(), $data);            

            $rs = \App\Models\AdminUsers::where('id', $id)
                ->update($dataUpdate);

            \App\Models\UserHasRole::where('user_id',$id)->delete();
            if(!empty($data['role_id'])){
                foreach($data['role_id'] as $role_id)
                    \App\Models\UserHasRole::create(['user_id' => $id, 'role_id' => $role_id]);
            }

            Cache::flush();

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật người dùng CMS thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật người dùng CMS không thành công'
            ]);

        }

        $rs = \App\Models\AdminUsers::create($data);

        if ($rs) {

            if(!empty($data['role_id'])){
                foreach($data['role_id'] as $role_id)
                    \App\Models\UserHasRole::create(['user_id' => $rs->id, 'role_id' => $role_id]);
            }

            Cache::flush();

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm người dùng CMS thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm người dùng CMS không thành công'
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
            \App\Models\AdminUsers::whereIn('id', $ids)
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
            \App\Models\AdminUsers::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa người dùng CMS thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa người dùng CMS không thành công'
        ]);
    }

    public function getRole(Request $request){
        $params = $request->all();

        $data = \App\Models\UserHasRole::select('role_id')->where('user_id',$params['id'])->get()->pluck('role_id')->toArray();

        return response()->json([
            'rs' => 1,
            'data' => $data
        ]);
    }
}
