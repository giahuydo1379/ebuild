<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'packages';
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // set the page title
        $this->data['title'] = 'Gói dịch vụ';

        $objects = \App\Models\Packages::getData();
        $categories = \App\Models\Categories::getAllCategoriesRoot();
        $this->data['categories'] = array_pluck($categories,'category','category_id');
        $this->data['objects'] = $objects;

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
        $params = $request->all();
        $id = $request->input('id', 0);

        $rules = [
            'package_name'  => 'required',
            'category_id'   => 'required',
            'duration'      => 'required',
            'price'         => 'required',
            'product_id'    => 'required'
        ];

        $valid = Validator::make($params, $rules);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $params
            ]);
        }

        if (isset($params['id']) && $params['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Packages(), $params);

            $rs = \App\Models\Packages::where('id', $id)
                            ->update($data);

            if ($rs) {
                \App\Models\ProductsPackages::where('package_id',$params['id'])->delete();
                foreach($params['product_id'] as $item){
                    \App\Models\ProductsPackages::create(['package_id' => $params['id'],'product_id' => $item]);
                }

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật gói dịch vụ thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật gói dịch vụ không thành công'
            ]);

        }

        $rs = \App\Models\Packages::create($params);

        if ($rs) {            
            foreach($params['product_id'] as $item){
                \App\Models\ProductsPackages::create(['package_id' => $rs->id,'product_id' => $item]);
            }

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm gói dịch vụ thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm gói dịch vụ không thành công'
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
    public function update(UpdateRequest $request, $id)
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
            \App\Models\Packages::find($id)->delete();
            \App\Models\ProductsPackages::where('package_id',$id)->delete();
            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa gói dịch vụ thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa gói dịch vụ không thành công'
        ]);
    }

    public function getService(Request $request){
        $params = $request->all();
        if(empty($params['category_id']))
            return response()->json([
                'rs'     => 0,
                'data'  => []
            ]);

        $category_id = $params['category_id'];
        $product_ids = \App\Models\ProductsCategories::getProductIdCategoryId($category_id);

        $data = \App\Models\Products::select('products.product_id','products.price',\DB::raw('concat(products.product_code," - ", product_descriptions.product) as product'))->whereIn('products.product_id',$product_ids)->leftJoin('product_descriptions',function($join){
            $join->on('products.product_id','=','product_descriptions.product_id');
            $join->where('product_descriptions.lang_code','vi');
        })->get()->toArray();

        return response()->json([
            'rs'     => 1,
            'data'  => $data
        ]);
    }
    
    public function getServicePackage(Request $request){
        $params = $request->all();
        if(empty($params['package_id']))
            return response()->json([
                'rs'     => 0,
                'data'  => []
            ]);
        $package_id = $params['package_id'];

        $product_ids = \App\Models\ProductsPackages::select('product_id')->where('package_id',$package_id)->pluck('product_id')->toArray();

        return response()->json([
            'rs'     => 1,
            'data'  => $product_ids
        ]);
    }    
}
