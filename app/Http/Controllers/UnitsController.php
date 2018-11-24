<?php
/**
 * Created by PhpStorm.
 * User: quocn_000
 * Date: 26/09/2018
 * Time: 11:31 SA
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Units;
use Validator;

class UnitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'units';

        $this->data['title'] = 'Đơn vị sản phẩm';
    }

    public function index(Request $request)
    {
        $params = $request->all();

        $this->data[ 'objects' ] = Units::getData($params);
        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function create()
    {
        $this->data['title'] = 'Tạo mới đơn vị sản phẩm';

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $unit_id = $request->input('unit_id', 0);

        if(!$unit_id)
        {
            $rules = [
                'name'      => 'required|unique:units',
            ];
            $messages = [
                'name.required'         => 'Nhập tên đơn vị',
                'name.unique'           => 'Tên đơn vị đã tồn tại',
            ];
        }
        else
        {
            $rules = [];
            $messages = [];
            if(!isset($data['description'])){
                $rules = [
                    'name' => 'required|unique:units,name,'.$unit_id.',id',
                ];

                $messages = [
                    'name.required'         => 'Nhập tên đơn vị',
                    'name.unique'           => 'Tên đơn vị đã tồn tại',
                ];
            }
            
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

        if (isset($data['unit_id']) && $data['unit_id']) {
            $data_units = \App\Helpers\General::get_data_fillable(new \App\Models\Units(), $data);
            
            $rs = \App\Models\Units::where('id', $unit_id)
                ->update($data_units);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật đơn vị thành công',
                ]);
            }
            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật đơn vị không thành công',
            ]);

        }
        $rs = \App\Models\Units::create($data);

        if($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm đơn vị thành công',
            ]);
        }
        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm đơn vị không thành công'
        ]);
    }

    public function edit($id) {
        $this->data['title'] = 'Cập nhật đơn vị sản phẩm';

        $data = Units::getDataById($id);
        $this->data['data'] = $data;

        return view("{$this->data['controllerName']}.edit", $this->data);
    }

    public function destroy(Request $request) {
        $id = $request->input('id', 0);
        $object = \App\Models\Units::find($id);
        if($object){
            $object->is_deleted = 1;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa đơn vị thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa đơn vị không thành công'
        ]);
    }
}