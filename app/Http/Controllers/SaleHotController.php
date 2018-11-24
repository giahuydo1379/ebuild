<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/7/2018
 * Time: 10:17 AM
 */

namespace App\Http\Controllers;
use App\Models\Brands;
use App\Models\Features;
use App\Models\FeaturesValues;
use Doctrine\Common\Annotations\Annotation\Required;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Auth;

class SaleHotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'sale-hot';
    }

    public function index(Request $request){    
        $params = $request->all();     
        
        $objects = \App\Models\SaleHot::getData($params);

        $this->data['objects'] = $objects;
        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function store(Request $request){
        $params = $request->all();
        $id = $request->input('id', 0);
     
        $rules = [
            'name'              => 'required',
            'description'       => 'required',
            'link'              => 'required',
            'image_location'    => 'required',
        ];

        $messages = [];
        $valid = Validator::make($params, $rules, $messages);

        if(!empty($params['from_date'])){
            if(!isset($params['from_time']))
                $params['from_time'] = '';

            $params['date_from']  = \Carbon\Carbon::parse($params['from_date'])->format('Y-m-d').' '.$params['from_time'];
        } else $params['date_from'] = NULL;

        if(!empty($params['to_date'])){
            if(!isset($params['to_time']))
                $params['to_time'] = '';

            $params['date_to']    = \Carbon\Carbon::parse($params['to_date'])->format('Y-m-d').' '.$params['to_time'];
        } else $params['date_to'] = NULL;

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $params
            ]);
        }

        $params['user_modified'] = Auth::user()->id;
        if (empty($params['image_url'])) $params['image_url'] = config('app.url_outside');

        if($id) {
            $data = \App\Helpers\General::get_data_fillable(new \App\Models\SaleHot(), $params);
                        
            $banner = \App\Models\SaleHot::find($id);

            if ($banner) $banner->update($data);

            if($banner)
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thành công'
                ]);

            return response()->json([
                'rs' => 1,
                'msg' => 'Cập nhật không thành công'
            ]);

        }

        $params['user_created'] = $params['user_modified'];

        $rs = \App\Models\SaleHot::create($params);

        return response()->json([
            'rs'    => 1,
            'msg'   => 'Tạo khuyến mãi hot thành công'
        ]);
    }

    public function update_status(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => 'required|integer',
            'status' => 'required|integer'
        ];

        $messages = [
            'id' => 'Vui lòng truyền id',
            'status' => 'Vui lòng truyền trạng thái',
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

        $id = $request->input('id', 0);
        $status = $request->input('status', 0);

        $title = $status ? 'Kích hoạt' : 'Dừng';
        if ($id) {
            $object = \App\Models\SaleHot::find($id);

            $object->status = $status;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => $title.' khuyến mãi hot thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => $title.' khuyến mãi hot không thành công'
        ]);
    }

    public function destroy(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => 'required|integer',
        ];

        $messages = [
            'id' => 'Vui lòng truyền id',
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

        $id = $request->input('id', 0);

        if ($id) {
            $object = \App\Models\SaleHot::find($id);

            $object->is_deleted = 1;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xoá khuyến mãi hot thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xoá khuyến mãi hot không thành công'
        ]);
    }
}