<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/7/2018
 * Time: 10:17 AM
 */

namespace App\Http\Controllers;
use Doctrine\Common\Annotations\Annotation\Required;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FreezerCapacity;
use Illuminate\Validation\Rule;
use Validator;

class FreezerCapacityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'freezer-capacity';
        $this->data['title'] = 'Công suất máy lạnh';
    }     

    
    public function index(Request $request){
        $params         = $request->all();
        $objects        = FreezerCapacity::getData($params);
        $this->data['objects']      = $objects;
        $this->data['params']       = $params;
        return view("{$this->data['controllerName']}.index", $this->data);        
    }

    public function create(Request $request){
        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function edit(Request $request, $id){
        
        $object    = FreezerCapacity::find($id)->toArray();
        $this->data['data'] = $object;
        $this->data['id']   = $id;
        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request){
        $data = $request->all();
        
        $id = $request->input('id', 0);
        $status = $request->input('status', 1);

        $rules = [
            'name'          => 'required',
        ];
        $messages = [
            'name.required'         => 'Nhập tên loại máy lạnh',
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
        $data['user_modified'] = Auth::user()->id;
        if($id){

            $data_products = \App\Helpers\General::get_data_fillable(new FreezerCapacity(), $data);

            $object = FreezerCapacity::find($id);

            $rs = $object ? $object->update($data_products) : false;

            if($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thành công',
                    'data' => $data,
                    'redirect' => route($this->data['controllerName'].'.edit',['id' => $id])
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật không thành công',
                'data' => $data,
            ]);

        }
        $data['user_created'] = Auth::user()->id;
        $rs = FreezerCapacity::create($data);

        if($rs){
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới thành công',
                'data' => $data,
                'redirect' => route($this->data['controllerName'].'.edit',['id' => $id])
            ]);

        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới không thành công',
            'data' => $data,
        ]);
    }
    public function change_status(Request $request){
        $data = $request->all();
        $product_ids = $request->input('product_ids', []);

        $rules = [
            'status'            => 'required',
            'product_ids'       => 'required|array',
        ];
        $messages = [
            'status.required'      => 'Truyền trạng thái sản phẩm',
            'product_ids.required'      => 'Truyền trạng ids sản phẩm',
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

        $rs = 0;
        foreach ($product_ids as $product_id) {
            $object = FreezerCapacity::find($product_id);
            if ($object) {
                $object->update([
                    'status' => $data['status'],
                    'user_modified' => Auth::user()->id
                ]);
                $rs++;
            }
        }

        if($rs)
            return response()->json([
                'rs' => 1,
                'msg' => 'Chuyển trạng thái thành công',
                'data' => $data
            ]);

        return response()->json([
            'rs' => 0,
            'msg' => 'Chuyển trạng thái không thành công',
            'data' => $data,
        ]);

    }
    public function destroy(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id) {
            FreezerCapacity::find($id)->delete();
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