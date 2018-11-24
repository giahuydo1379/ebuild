<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'contact';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Quản lý Liên hệ';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Contact::select('*')
            ->where('brand_id', 0)
            ->where('deleted',0);
        $objects->orderBy('id', 'desc');

        $objects = $objects->paginate($limit)->toArray();
        if (isset($_GET['debug']) && $_GET['debug']==1) dd($objects);
        $this->data['objects'] = $objects;

        $status = array(
            0 => 'Chưa trả lời',
            1 => 'Đã trả lời',
            -1 => 'Không trả lời'
        );

        $status_btn = array(
            0 => '<span class="label label-warning">Chưa trả lời</span>',
            1 => '<span class="label label-success">Đã trả lời</span>',
            -1 => '<span class="label label-default">Không trả lời</span>',
        );
        
        $this->data['status']       = $status;
        $this->data['status_btn']   = $status_btn;
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

        if(!$id)
            return false;
        
        $data = \App\Helpers\General::get_data_fillable(new \App\Models\Contact(), $data);
        
        $rs = \App\Models\Contact::where('id', $id)
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
            //\App\Models\Contact::find($id)->delete();
            \App\Models\Contact::where('id', $id)
                ->update(['deleted' => 1]);
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
}
