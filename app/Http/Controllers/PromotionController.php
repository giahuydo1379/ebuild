<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'promotions';
    }

    public function order(Request $request)
    {
        $type='order';

        $this->data['title'] = 'Quà tặng cho đơn hàng - Khuyến mãi quà tặng';

        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Promotion::select('promotions.*')
                ->join('promotion_package', 'promotion_package.id', '=', 'promotions.package_id')
                ->where('promotion_package.type', 'order')
                ->where('promotion_package.is_active', 1);

        $objects->orderBy('promotions.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

        $this->data['objects'] = $objects;

        $packages = \App\Models\PromotionPackage::select('id', 'name', 'type')
            ->where('type', 'order')
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get()->toArray();
        $tmp = [];
        foreach ($packages as $item) {
            $tmp[$item['id']] = $item;
        }
        $this->data['packages'] = $tmp;

        return view("{$this->data['controllerName']}.order", $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Quà tặng cho sản phẩm - Khuyến mãi quà tặng';
        $params = $request->all();
        if (!array_key_exists('status-filter', $params)) $params['status-filter'] = 1;
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Promotion::select('promotions.*')
        ->join('promotion_package', 'promotion_package.id', '=', 'promotions.package_id')
        ->whereIn('promotion_package.type', ['product', 'brand'])
        ->where('promotion_package.is_active', 1);

        if (isset($params['status-filter']) && $params['status-filter']!==null) {
            $objects->where('promotions.status', $params['status-filter']);
        }

        $objects->orderBy('promotions.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

        $this->data['objects'] = $objects;

        $packages = \App\Models\PromotionPackage::select('id', 'name', 'type')
            ->whereIn('type', ['product', 'brand'])
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get()->toArray();
        $tmp = [];
        foreach ($packages as $item) {
            $tmp[$item['id']] = $item;
        }
        $this->data['packages'] = $tmp;

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
    public function get_list_products_by_ids(Request $request) {
        $ids = $request->input('ids', []);
        $brand_ids = $request->input('brand_ids', []);

        $tmp = [];
        if ($ids) {
            $ids = array_unique($ids);

            $products = \App\Models\Products::getProductsByIds($ids);

            foreach ($products as $item) {
                $tmp[$item['product_id']] = $item;
            }
        }

        $brands = [];
        if ($brand_ids) {
            $brand_ids = array_unique($brand_ids);

            $objects = \App\Models\Brand::getBrandsByIds($brand_ids);

            foreach ($objects as $item) {
                $brands[$item['id']] = $item;
            }
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Lấy danh sách đối tượng theo ids',
            'products' => $tmp,
            'brands' => $brands,
        ]);
    }
    public function get_list_brands(Request $request) {
        $ids = $request->input('ids', '');
        $ids = \App\Helpers\General::string_area_to_array( $ids );

        $objects = \App\Models\Brand::getBrandsByIds($ids);

        $tmp = [];

        foreach($objects as $item) {
            $tmp[$item['id']] = $item;
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Lấy danh sách thương hiệu theo ids',
            'objects' => $tmp,
            'ids' => $ids
        ]);
    }
    public function get_list_products(Request $request) {
        $sku = $request->input('sku', '');
        $sku = \App\Helpers\General::string_area_to_array( $sku );

        $products = \App\Models\Products::getProductsByProductCode($sku);

        return response()->json([
            'rs' => 1,
            'msg' => 'Lấy danh sách sản phẩm theo sku',
            'products' => $products,
            'sku' => $sku
        ]);
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
            'package_id' => 'required|integer',
            'name' => 'required|max:255',
            'from_date' => 'required|date_format:"d-m-Y"',
            'to_date' => 'required|date_format:"d-m-Y"',
            'apply_objects' => 'required',
        ];

        $messages = [
            'package_id' => 'Vui lòng chọn gói khuyến mãi',
            'name' => 'Nhập tên chương trình khuyến mãi',
            'from_date' => 'Chọn ngày bắt đầu (d-m-Y)',
            'to_date' => 'Chọn ngày kết thúc (d-m-Y)',
            'apply_objects.required' => 'Nhập danh sách sản phẩm khuyến mãi',
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

        $data['from_date'] = \Carbon\Carbon::parse($data['from_date']);
        $data['to_date'] = \Carbon\Carbon::parse($data['to_date']);
        if (is_array($data['apply_objects'])) {
            $data['apply_objects'] = json_encode($data['apply_objects']);
        }

        if (isset($data['gift_products'])) $data['gift_products'] = json_encode($data['gift_products']);

        $title = 'chương trình khuyến mãi';
        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Promotion(), $data);

            $object = \App\Models\Promotion::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            $rs = \App\Models\Promotion::where('id', $id)
                ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật '.$title.' thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật '.$title.' không thành công'
            ]);

        }

        $data['user_created'] = $data['user_modified'];
        $rs = \App\Models\Promotion::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm '.$title.' thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm '.$title.' không thành công'
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
            \App\Models\Promotion::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa chương trình khuyến mãi không thành công'
        ]);
    }

    public function stop(Request $request)
    {
        $id = $request->input('id', 0);

        if ($id) {
            $object = \App\Models\Promotion::find($id);

            $object->status = 0;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Dừng chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Dừng chương trình khuyến mãi không thành công'
        ]);
    }

    public function start(Request $request)
    {
        $id = $request->input('id', 0);

        if ($id) {
            $object = \App\Models\Promotion::find($id);

            $object->status = 1;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Kích hoạt chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Kích hoạt chương trình khuyến mãi không thành công'
        ]);
    }
}
