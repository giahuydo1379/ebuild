<?php

namespace App\Http\Controllers;


use App\Models\Warehouses;
use Illuminate\Http\Request;
use Validator;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'supplier';

        $this->data['title'] = 'Nhà cung cấp';
    }


	public function get_warehouses(Request $request){
        $supplier_id = $request->input('supplier_id', '');
        $options = $supplier_id ? Warehouses::getOption($supplier_id) : [];

        return response()->json([
            'rs' => 1,
            'msg' => 'Thành công',
            'options' => $options
        ]);
    }
    public function index(Request $request){

        $params = $request->all();

        $objects = \App\Models\Supplier::getData($params);
        $this->data['objects']      = $objects;
        $this->data['params']       = $params;

        return view("{$this->data['controllerName']}.index", $this->data);

    }

    public function edit($id)
    {

        $object = \App\Models\Supplier::with('warehouses')->find($id);
        if(!$object)
            abort('404');
        $object = $object->toArray();
        $this->data['object'] = $object;
        
        $this->data['title'] = 'Cập nhật nhà cung cấp';

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function create()
    {
        $this->data['title'] = 'Tạo nhà cung cấp mới';
        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request)
    {
        $params = $request->all();

        $id = $request->input('id', 0);

        $rules = [
            'name'          => 'required',
            'email'         => 'required',
            'phone'         => 'required',
            'province_id'   => 'required',
            'district_id'   => 'required',
            'ward_id'       => 'required',
            'address'       => 'required',
        ];
        $messages = [
            'name.required'         => 'Nhập tên nhà cung cấp',
            'email.required'        => 'Nhập Email',
            'phone.required'        => 'Nhập Số điện thoại',
            'province_id.required'  => 'Chọn tỉnh thành phố',
            'district_id.required'  => 'Chọn quận huyện',
            'ward_id.required'      => 'Chọn phường xã',
            'address.required'      => 'Nhập địa chỉ',
        ];

        $valid = Validator::make($params, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $params
            ]);
        }

        if (isset($params['image_location']) && !isset($params['image_url'])) {
            $params['image_url'] = config('app.url_outside');
        }

        
        if (isset($params['id']) && $params['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Supplier(), $params);
            
            $rs = \App\Models\Supplier::where('id', $id)
                ->update($data);

            if ($rs) {
                $this->updateWarehouse($params,$id);

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật nhà cung cấp thành công',
                ]);
            }
            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật nhà cung cấp không thành công'
            ]);

        }

        $rs = \App\Models\Supplier::create($params);

        if ($rs) {
            $this->updateWarehouse($params,$rs->id);
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm nhà cung cấp thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm nhà cung cấp không thành công'
        ]);
    }    

    private function updateWarehouse($params,$supplier_id){
        $warehouse_id = !empty($params['warehouse_id'])?explode(',', $params['warehouse_id']):[];
        \App\Models\Warehouses::where('supplier_id',$supplier_id)
            ->whereNotIn('id',$warehouse_id)
            ->update(['supplier_id' => 0]);

        \App\Models\Warehouses::whereIn('id',$warehouse_id)
            ->update(['supplier_id' => $supplier_id]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id', 0);
        $object = \App\Models\Supplier::find($id);
        if($object){
            $object->is_deleted = 1;
            $object->save();

            \App\Models\Warehouses::where('supplier_id',$id)
            ->update(['supplier_id' => 0]);
            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa nhà cung cấp thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa nhà cung cấp không thành công'
        ]);
    }
}