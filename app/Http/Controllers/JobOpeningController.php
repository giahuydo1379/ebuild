<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class JobOpeningController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'job-opening';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Vị trí tuyển dụng';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\JobOpening::select('job_opening.*', 'provinces.name as province_name');
        $objects->leftJoin('provinces', 'provinces.province_id', '=', 'job_opening.province_id');

        if (isset($params['category_filter']) && $params['category_filter']!=='') {
            $objects->where('job_opening.job_category_id', $params['category_filter']);
        }
        if (isset($params['province_filter']) && $params['province_filter']!=='') {
            $objects->where('job_opening.province_id', $params['province_filter']);
        }
        if (isset($params['status_filter']) && $params['status_filter']!=='') {
            $objects->where('job_opening.status', $params['status_filter']);
        }
//        $objects->where('status', 13);
        $objects->orderBy('id', 'desc');

        $objects = $objects->paginate($limit)->toArray();
        if (isset($_GET['debug']) && $_GET['debug']==1) dd($objects);
        $this->data['objects'] = $objects;

        $categories = \App\Models\JobCategory::select('id', 'name')
                    ->where('status', 1)
                    ->pluck('name', 'id')->toArray();
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
        $id = $request->input('id', 0);

        $data['alias'] = str_slug($data['position']);

        $rules = [
            'position' => 'required|max:255',
            'alias' => 'required|unique:job_opening',
            'job_category_id' => 'required',
            'province_id' => 'required',
            'description' => 'required',
            'request' => 'required',
            'keywords' => 'required',
            'quantity' => 'required',
            'expiration_date' => 'required',
        ];

        $messages = [
            'position.required' => 'Nhập tên vị trí',
            'alias.required' => 'Alias đã được sử dụng.',
            'job_category_id.required' => 'Chọn danh mục tuyển dụng.',
            'province_id.required' => 'Chọn tỉnh thành phố.',
            'description.required' => 'Nhập mô tả.',
            'request.required' => 'Nhập yêu cầu.',
            'keywords.required' => 'Nhập từ khóa để SEO.',
            'quantity.required' => 'Vui lòng nhập số lượng tuyển dụng',
            'expiration_date.required' => 'Vui lòng chọn ngày hết hạn',
        ];

        if ($id) {
            $rules['alias'] = 'required|unique:job_opening,alias,'.$id;
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

        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\JobOpening(), $data);

            $rs = \App\Models\JobOpening::where('id', $id)
                ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật vị trí tuyển dụng thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật vị trí tuyển dụng không thành công'
            ]);

        }

        $data['user_created'] = $data['user_modified'];
        $rs = \App\Models\JobOpening::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm vị trí tuyển dụng thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm vị trí tuyển dụng không thành công'
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
        $status = $request->input('status', 0);
        $ids = $request->input('ids', []);

        if ($ids) {
            \App\Models\JobOpening::whereIn('id', $ids)
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
            \App\Models\JobOpening::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa vị trí tuyển dụng thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa vị trí tuyển dụng không thành công'
        ]);
    }
}
