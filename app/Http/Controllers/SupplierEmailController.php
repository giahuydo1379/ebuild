<?php

namespace App\Http\Controllers;

use App\Models\SupplierEmail;
use App\Models\SupplierConfig;
use App\Models\Supplier;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierEmailController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'supplier-email';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param = $request->all();
        $this->data['title'] = 'cấu hình email';

        $supplier_options  = Supplier::get_options();
        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category();

        $limit = $param['limit']??10;
        $objects = SupplierConfig::with('supplierEmails')->paginate($limit)->toArray();

        $this->data['list_categories']  = $list_categories;
        $this->data['objects'] = $objects;
        $this->data['supplier_options']     = $supplier_options;

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
            'supplier_id' => 'required',
            'category_id' => 'required',
            'emailPost' => 'required'
        ];

        $messages = [
            'supplier_id' => 'required',
            'category_id' => 'required',
            'emailPost.required' => 'Nhập giá trị email.',
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

        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new SupplierConfig(), $data);

            $rs = SupplierConfig::find($id)->update($data);

            if ($rs) {
                $this->storeSupplierEmail($id);
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật email thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật email không thành công'
            ]);

        }
        $rs = SupplierConfig::create($data);
        if($rs) {
            $this->storeSupplierEmail($rs->id);
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm email thành công',
            ]);
        }
        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm email không thành công'
        ]);
        
        
    }

    public function storeSupplierEmail($supplier_config_id){
        $emailPost = request()->input('emailPost',[]);
        $supplier_id = request()->input('supplier_id',false);
        if(!$supplier_id){
            return false;
        }

        SupplierEmail::where('supplier_config_id',$supplier_config_id)->delete();
        foreach ($emailPost as $item) {
            // SupplierEmail::where('email',$item['email'])->delete();
            $data_item = array(
                'supplier_config_id'    => $supplier_config_id,
                'email'                 => $item['email'],
                'supplier_id'           => $supplier_id
            );
            SupplierEmail::firstOrCreate($data_item);
        }
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
        $data = $request->all();
        $id = $data['id'];

        if (isset($data['id']) && $data['id']) {
                $rs = SupplierConfig::where('id', (int) $id)->delete();

            if ($rs) {
                SupplierEmail::where('supplier_config_id', (int) $id)->delete();
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Xóa email thành công',
                ]);
            }
        }

    }
}
