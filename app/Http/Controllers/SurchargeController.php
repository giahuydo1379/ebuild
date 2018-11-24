<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Units;
use App\Models\Supplier;
use App\Models\Categories;
use App\Models\SurchargeCategories;
use Validator;

class SurchargeController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'surcharge';

        $this->data['title'] = 'Phụ phí';
    }

    public function index(Request $request){
        $params = $request->all();
        
        $list_units              = Units::getAllData();

        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');

        $objects = \App\Models\Surcharge::getData($params);
        $this->data['objects']      = $objects;
        $this->data['suppliers']  = Supplier::get_options();
        $this->data['list_units']      = $list_units;
        $this->data['category_options']     = $category_options;
        $this->data['params']       = $params;
        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function create()
    {
        $list_units              = Units::getAllData();

        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category();
        $this->data['list_categories']  = $list_categories;
        $this->data['suppliers']  = Supplier::get_options();
        $this->data['list_units']      = $list_units;
        $this->data['title']        = 'Thêm mới phụ phí';

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $id = $request->input('id', 0);
        $rules = [
            'name'          => 'required',
            'unit_id' => 'required',
            'supplier_id' => 'required',
            'supplier_id' => 'required',
            'category_id' => 'required',
        ];
        $messages = [
            'name.required'         => 'Nhập tên phụ phí',
            'unit_id.required'         => 'Nhập tên đơn vị',
            'supplier_id.required'         => 'Nhập tên nhà cung cấp',
            'category_id.required'         => 'Nhập tên danh mục',
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
            $surcharge_id = $data['id'];
            $surcharge = $data;
            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Surcharge(), $data);

            $rs = \App\Models\Surcharge::where('id', $surcharge_id)
                ->update($data);

            if ($rs) {
                $this->saveSurchargeCategories($surcharge, $surcharge_id, $update = true);
                $this->storeSurchargeDetail($surcharge, $surcharge_id, 1);
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật phụ phí thành công',
                ]);
            }
            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật phụ phí không thành công'
            ]);

        }

        $rs = \App\Models\Surcharge::create($data);

        if ($rs) {
            $surcharge_id = $rs->id;
            $this->saveSurchargeCategories($data, $surcharge_id);
            $this->storeSurchargeDetail($data, $surcharge_id);
            return response()->json([
                'rs'    => 1,
                'msg'   => 'Thêm mới phụ phí thành công',
                'id'    => $rs->id
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới phụ phí không thành công'
        ]);
    }

    public function edit($id)
    {
        $object = \App\Models\Surcharge::find($id);

        if(!$object)
            abort('404');
        $object = $object->toArray();
        $this->data['object'] = $object;

        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category();
        $list_units              = Units::getAllData();

        $this->data['list_categories']  = $list_categories;

        $category_ids = SurchargeCategories::where('surcharge_id', $id)->pluck('category_id')->toArray();

        $this->data['category_ids'] = $category_ids;
        $this->data['suppliers']  = Supplier::get_options();
        $this->data['list_units']      = $list_units;
        $this->data['title']        = 'Cập nhật phụ phí ';
        $this->data['surcharge_detail']     = \App\Models\SurchargeDetail::getSurchargeDetailById($id);

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id', 0);
        $object = \App\Models\Surcharge::find($id);
        $object_detail = \App\Models\SurchargeDetail::find($id);
        if($object){
            $object->delete();
            if($object_detail) {
                $object_detail->delete();
            }

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa phụ phí thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa phụ phí không thành công'
        ]);
    }

    public function storeSurchargeDetail($data, $surcharge_id, $update = false)
    {

        if($update){
            \App\Models\SurchargeDetail::where('surcharge_id',$surcharge_id)->delete();
        }
        $data = $data['surcharge']??[];
        
        foreach($data as $item){
            if(empty($item['from']) && empty($item['to'])) 
                continue;
            
            $item['surcharge_id'] = (int) $surcharge_id;
            $obj = \App\Models\SurchargeDetail::where([
                'surcharge_id'  => $item['surcharge_id'],
                'from'          => $item['from'],
                'to'            => $item['to'],
            ])->first();
            if($obj) continue;
            \App\Models\SurchargeDetail::create($item);
        }
    }

    public function saveSurchargeCategories($data, $surcharge_id, $update = false){
        if($update){
            \App\Models\SurchargeCategories::where('surcharge_id',$surcharge_id)->delete();
        }
        $data = $data['category_id']??[];
        foreach($data as $item){
            if(empty($item))
             continue; 

            $category['category_id'] = (int) $item;
            $category['surcharge_id'] = (int) $surcharge_id;
            \App\Models\SurchargeCategories::create($category);
        }
    }
}