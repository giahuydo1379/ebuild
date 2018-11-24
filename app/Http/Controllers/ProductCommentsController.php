<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCommentsController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {     
        $this->middleware('auth');
        $this->data['controllerName'] = 'product-comments';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $this->data['title'] = 'Quản lý bình luận';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\ProductComment::select('product_comments.*','product_descriptions.product as product_name');

        $objects->leftJoin('product_descriptions', 'product_descriptions.product_id', '=', 'product_comments.product_id');

        $objects->orderBy('product_comments.id', 'desc');

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
        //
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
            \App\Models\ProductComment::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa bình luận thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa bình luận không thành công'
        ]);
    }

    public function change_status(Request $request)
    {
        $status = $request->input('status');
        $id = $request->input('id', 0);

        $arr_stt = array(
            0 => 1,
            1 => 0
        );

        if ($id) {
            \App\Models\ProductComment::where('id', $id)
                ->update(['status' => $arr_stt[$status]]);

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
}
