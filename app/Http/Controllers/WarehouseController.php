<?php

namespace App\Http\Controllers;

require_once app_path() . '/Helpers/Spout/src/Box/Spout/Autoloader/autoload.php';

use App\Models\Ward;
use App\Models\Warehouses;
use App\Models\WarehousesPlaces;
use Illuminate\Http\Request;
use App\Models\WarehousesCategories;
use Validator;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'warehouse';

        $this->data['title'] = 'Nhà kho';
    }

    public function import_places_delivery(Request $request)
    {
        $this->data['title'] = 'Import địa điểm giao hàng';
        $params = $request->all();
        $this->data['params'] = $params;

        return view("{$this->data['controllerName']}.import", $this->data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import_places_delivery_store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'newfile' => 'required|file|mimes:xlsx',
        ];

        $messages = [
            'newfile.required' => 'Chọn file danh sách sản phẩm mới',
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

        $file = $request->file('newfile');
        $filename = time().'-'.$file->getClientOriginalName();
        $filename = $file->storeAs('imports', $filename);
        $filename = storage_path('app') ."/". $filename;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($filename);

        $rs = 0;
        $warehouses = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                // do stuff with the row
                if ($index===1) continue;

                $data = [
                    'stt' => $index-1,
                    'district' => $row[1],
                    'ward' => $row[2],
                    'warehouse' => $row[3],
                ];

                $ward = Ward::select(['districts.province_id',
                    'districts.district_id', \DB::raw("CONCAT_WS(' ', districts.type, districts.name) as district_name"),
                    'wards.ward_id', \DB::raw("CONCAT_WS(' ', wards.type, wards.name) as ward_name")])
                    ->leftJoin('districts', 'wards.district_id', '=', 'districts.district_id')
                    ->where(\DB::Raw("'".$data['district']."'"), 'LIKE', \DB::Raw('CONCAT("%", districts.name, "%")'))
                    ->where(\DB::Raw("'".$data['ward']."'"), 'LIKE', \DB::Raw('CONCAT("%", wards.name, "%")'))
                    ->orderBy('wards.name', 'desc')
                    ->orderBy('districts.name', 'desc')
                    ->first();
//            ->toSql();
                if ($ward) {
                    $data['province_id'] = $ward['province_id'];
                    $data['district_id'] = $ward['district_id'];
                    $data['ward_id'] = $ward['ward_id'];
                    $data['ward_name'] = $ward['ward_name'].' '.$ward['district_name'];
                } else {
                    $data['message'] = 'Không tìm thấy địa điểm giao hàng';
                }

                if (isset($warehouses[$data['warehouse']])) {
                    $warehouse = $warehouses[$data['warehouse']];
                } else {
                    $warehouse = Warehouses::where(function ($query) use ($data) {
                        $query->where(\DB::Raw("'" . $data['warehouse'] . "'"), 'LIKE', \DB::Raw('CONCAT("%", warehouses.name, "%")'))
                            ->orWhere('warehouses.name', 'LIKE', '%' . $data['warehouse'] . '%');
                    })->first();
                }

                if ($warehouse) {
                    $warehouses[$data['warehouse']] = $warehouse;
                    $data['warehouse_id'] = $warehouse['id'];
                    $data['warehouse_name'] = $warehouse['name'];
                } else {
                    $data['message'] = (isset($data['message']) ? ', ' : '') .'Không tìm thấy kho hàng';
                }

                if(!isset($data['message'])){
                    $wp = WarehousesPlaces::firstOrCreate([
                        'province_id' => $data['province_id'],
                        'district_id' => $data['district_id'],
                        'ward_id' => $data['ward_id'],
                        'warehouse_id' => $data['warehouse_id']
                        ]);

                    if ($wp) {
                        $data['message'] = 'Thành công';
                        $rs++;
                    } else {
                        $data['message'] = 'Không thành công';
                    }
                }

                $data_imported[] = $data;
            }
        }
        $reader->close();

        $header = ['STT', 'Quận', 'Phường', 'Tram', 'Phường ID', 'Quận ID', 'Phường, Quận', 'Trạm ID', 'Trạm', 'Ghi chú'];
        $fields = ['stt', 'district', 'ward', 'warehouse', 'ward_id', 'district_id', 'ward_name', 'warehouse_id', 'warehouse_name', 'message'];

        \App\Helpers\Cexport::export('places-delivery', 'Danh sách địa điểm đã import', $header, $fields, $data_imported);
    }

    public function index(Request $request){
        $params = $request->all();

        $objects = \App\Models\Warehouses::getData($params);

        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');
        
        $this->data['category_options']     = $category_options;
        $this->data['objects']      = $objects;
        $this->data['params']       = $params;
        $this->data['supplier']     = \App\Models\Supplier::get_options();
        return view("{$this->data['controllerName']}.index", $this->data);
    }

    public function edit($id)
    {
        $object = \App\Models\Warehouses::find($id);
        if(!$object)
            abort('404');
        $object = $object->toArray();
        $this->data['object'] = $object;

        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');

        $category_ids = WarehousesCategories::where('warehouse_id', $id)->pluck('category_id')->toArray();

        $this->data['category_ids'] = $category_ids;

        $this->data['category_options']  = $category_options;
        $this->data['list_categories']  = $list_categories;
        $this->data['title']        = 'Cập nhật nhà kho';
        $this->data['supplier']     = \App\Models\Supplier::get_options();
        $this->data['warehouses_places']     = \App\Models\WarehousesPlaces::getWarehousesPlacesById($id);

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function create()
    {
        $this->data['title']        = 'Thêm mới nhà kho';

        $CategoriesController   = new \App\Http\Controllers\CategoriesController();
        $list_categories        = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');

        $this->data['category_options']  = $category_options;
        $this->data['list_categories']  = $list_categories;
        $this->data['supplier']     = \App\Models\Supplier::get_options();

        return view("{$this->data['controllerName']}.create", $this->data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $id = $request->input('id', 0);
        $rules = [
            //'supplier_id'   => 'required',
            'name'          => 'required',
            'phone'         => 'required',
            'category_id'   => 'required',
            'province_id'   => 'required',
            'district_id'   => 'required',
            'ward_id'       => 'required',
            'address'       => 'required',
            
        ];
        $messages = [
            //'supplier_id'           => 'Chọn nhà cung cấp',
            'name.required'         => 'Nhập tên nhà kho',
            'phone.required'        => 'Nhập Số điện thoại',
            'category_id.required'  => 'Chọn danh mục sản phẩm',
            'province_id.required'  => 'Chọn tỉnh thành phố',
            'district_id.required'  => 'Chọn quận huyện',
            'ward_id.required'      => 'Chọn phường xã',
            'address.required'      => 'Nhập địa chỉ',
            
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
            $warehouse_id = $data['id'];
            $warehouses = $data;
            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Warehouses(), $data);
            
            $rs = \App\Models\Warehouses::where('id', $id)
                ->update($data);

            if ($rs) {
                $this->saveWarehousesCategories($warehouses, $warehouse_id, $update = true);
                $this->storeWarehousePlaces($warehouses, $warehouse_id, 1);
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật nhà kho thành công',
                ]);
            }
            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật nhà kho không thành công'
            ]);

        }

        $rs = \App\Models\Warehouses::create($data);

        if ($rs) {
            $warehouse_id = $rs->id;
            $this->saveWarehousesCategories($data, $warehouse_id);
            $this->storeWarehousePlaces($data, $warehouse_id);
            return response()->json([
                'rs'    => 1,
                'msg'   => 'Thêm mới nhà kho thành công',
                'id'    => $rs->id
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới nhà kho không thành công'
        ]);
    }

    public function getOption(Request $request){
        $supplier_id = $request->input('supplier_id',0);
        $options = \App\Models\Warehouses::getOption($supplier_id);
        return response()->json([
            'rs' => 1,
            'msg' => 'Thành công',
            'data' => $options
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id', 0);
        $object = \App\Models\Warehouses::find($id);
        if($object){
            $object->is_deleted = 1;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa nhà kho thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa nhà kho không thành công'
        ]);
    }

    //store warehouse places
    public function storeWarehousePlaces($data, $warehouse_id, $update=false)
    {
        if($update){
            \App\Models\WarehousesPlaces::where('warehouse_id',$warehouse_id)->delete();
        }
        $data = $data['warehouses_places']??[];

        foreach($data as $item){
            if(empty($item['province_id'])
                || empty($item['district_id'])
            ) continue;
            $item['warehouse_id'] = (int) $warehouse_id;
            \App\Models\WarehousesPlaces::create($item);
        }
    }

    public function saveWarehousesCategories($data, $warehouse_id, $update = false){
        if($update){
            \App\Models\WarehousesCategories::where('warehouse_id',$warehouse_id)->delete();
        }
        $data = $data['category_id']??[];
        foreach($data as $item){
            if(empty($item))
             continue; 

            $category['category_id'] = (int) $item;
            $category['warehouse_id'] = (int) $warehouse_id;
            \App\Models\WarehousesCategories::create($category);
        }
    }
}