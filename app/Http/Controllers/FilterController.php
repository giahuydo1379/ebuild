<?php

namespace App\Http\Controllers;

use App\Models\Features;
use App\Models\FeatureVariants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilterController extends Controller
{
    protected $data = []; // the information we send to the view    
    protected $_arr  = [];
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
        $this->data['controllerName'] = 'filters';
        // set the page title
        $this->data['title'] = 'Danh mục filter';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Features::select('product_features.*')
            ->where('is_deleted', 0)
            ->orderBy('position', 'asc')
            ->paginate($limit)->toArray();
        $this->data['objects'] = $objects;

        $job_category_ids = [];
        foreach ($objects['data'] as $item) {
            $job_category_ids[] = $item['id'];
        }

        if ($job_category_ids) {
            $counts = \App\Models\FeatureVariants::select('feature_id',
                \DB::raw('COUNT(product_features_variants.id) as count_filters'))
                ->whereIn('feature_id', $job_category_ids)
                ->groupBy('feature_id')
                ->pluck('count_filters', 'feature_id')->toArray();
        } else {
            $counts = [];
        }
        $this->data['counts'] = $counts;

        return view("{$this->data['controllerName']}.index", $this->data);
    }
    public function list_filter(Request $request, $id)
    {
        // set the page title
        $this->data['title'] = 'Danh sách filter';

        $feature = Features::getDataById($id);
        $this->data['feature'] = $feature;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\FeatureVariants::select('product_features_variants.*')
            ->where('feature_id', $id)
            ->where('is_deleted', 0)
            ->orderBy('product_features_variants.position', 'asc')
            ->paginate($limit)->toArray();

        $this->data['objects'] = $objects;
        return view("{$this->data['controllerName']}.list-filter", $this->data);
    }

    public function categories(Request $request)
    {
        $categories = Categories::getAllCategories();
        $this->data['groups'] = $categories['groups'];
        $this->data['list'] = $categories['list'];

        if($request->ajax()){
            return view("{$this->data['controllerName']}.categories-ajax", $this->data);
        }

        return view("{$this->data['controllerName']}.categories", $this->data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->data['title'] = 'Tạo mới filter';

        return view("{$this->data['controllerName']}.create", $this->data);
    }
    public function create_filter(Request $request)
    {
        $features = Features::getOptionsFeatures();
        $this->data['features'] = $features;

        return view("{$this->data['controllerName']}.create-filter", $this->data);
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
            'name_vi'  => 'required',
        ];
        $messages = [
            'name_vi.required'     => 'Nhập tên',
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

        if ($id) {
            $data_filter = \App\Helpers\General::get_data_fillable(new Features(), $data);

            $rs = Features::where('id', $id)
                ->update($data_filter);

            if ($rs >= 0) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật không thành công',
                'data' => $data_filter
            ]);
        }

        $rs = Features::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới không thành công'
        ]);
    }

    public function store_filter(Request $request)
    {
        $data = $request->all();

        $id = $request->input('id', 0);

        $rules = [
            'name'  => 'required',
            'feature_id'  => 'required',
        ];
        $messages = [
            'feature_id.required'     => 'Chọn danh mục',
            'name.required'     => 'Nhập tên',
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

        if ($id) {
            $data_filter = \App\Helpers\General::get_data_fillable(new FeatureVariants(), $data);

            $rs = FeatureVariants::where('id', $id)
                ->update($data_filter);

            if ($rs >= 0) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật filter thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật filter không thành công',
                'data' => $data_filter
            ]);
        }

        $rs = FeatureVariants::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới filter thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới filter không thành công'
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
    public function edit(Request $request, $id)
    {
        $this->data['title'] = 'Cập nhật filter';

        $data = Features::getDataById($id);
        $this->data['object'] = $data;

        return view("{$this->data['controllerName']}.create", $this->data);
    }
    public function edit_filter(Request $request, $id)
    {
        $data = FeatureVariants::find($id);
        $this->data['object'] = $data;

        $features = Features::getOptionsFeatures();
        $this->data['features'] = $features;

        return view("{$this->data['controllerName']}.create-filter", $this->data);
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
            $object = Features::find($id);
            $object->is_deleted = 1;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa danh mục filter thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa danh mục filter không thành công'
        ]);
    }

    public function destroy_filter(Request $request)
    {
        $id = $request->input('id', 0);

        if ($id) {
            $object = FeatureVariants::find($id);
            $object->is_deleted = 1;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa filter thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa filter không thành công'
        ]);
    }

    public function get_feature_variants(Request $request)
    {
        $feature_id = $request->input('feature_id', 0);

        $lang_code = 'vi';

        if ($feature_id) {
            $variants = FeatureVariants::select('product_features_variants.*',
                'product_features_variants.name as variant')
                ->where('feature_id', $feature_id)
                ->orderBy('position', 'asc')->get()->toArray();

            return response()->json([
                'rs' => 1,
                'msg' => 'Lấy giá trị thuộc tính thành công',
                'feature_id' => $feature_id,
                'variants' => $variants
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Không tìm thấy giá trị của thuộc tính',
            'feature_id' => $feature_id,
            'variants' => []
        ]);
    }
}
