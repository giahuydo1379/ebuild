<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class MicrositeController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'microsites';
    }


    public function pre_order(Request $request)
    {
        $this->data['title'] = 'Landing page Pre Order';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\MicrositePreOrder::select('microsite_pre_order.*');
        $objects->orderBy('microsite_pre_order.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

        $this->data['objects'] = $objects;

        return view("{$this->data['controllerName']}.pre-order", $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function normal(Request $request)
    {
        $this->data['title'] = 'Landing page bình thường';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\Microsite::select('microsites.*');
        $objects->orderBy('microsites.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

        $this->data['objects'] = $objects;

        return view("{$this->data['controllerName']}.normal", $this->data);
    }

    public function exchange_points(Request $request)
    {
        $this->data['title'] = 'Tích điểm đổi quà - Landing page';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\MicrositeExchangePoint::select('microsite_exchange_points.*');
        $objects->orderBy('microsite_exchange_points.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

        $this->data['objects'] = $objects;

        return view("{$this->data['controllerName']}.exchange-points", $this->data);
    }

    public function gold_time(Request $request)
    {
        $this->data['title'] = 'Giờ vàng giá sốc - Landing page';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\MicrositeGoldTime::select('microsite_gold_time.*');
        $objects->orderBy('microsite_gold_time.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

        $this->data['objects'] = $objects;

        return view("{$this->data['controllerName']}.gold-time", $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Sản phẩm giãm giá - Landing page';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\MicrositeSaleOff::select('microsite_sale_off.*');
        $objects->orderBy('microsite_sale_off.updated_at', 'desc');

        $objects = $objects->paginate($limit)->toArray();

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
        $data = $request->all();
        $id = $request->input('id', 0);

        $data['alias'] = str_slug($data['name']);

        $rules = [
            'name' => 'required|max:255',
            'alias' => 'required|max:255',
            'from_time' => 'required',
            'from_date' => 'required|date_format:"d-m-Y"',
            'to_time' => 'required',
            'to_date' => 'required|date_format:"d-m-Y"',
            'banner' => 'required',
            'floors_products' => 'required',
        ];

        $messages = [
            'name' => 'Nhập tên chương trình khuyến mãi',
            'alias' => 'Nhập tên alias',
            'from_time' => 'Chọn thời gian bắt đầu',
            'from_date' => 'Chọn ngày bắt đầu (d-m-Y)',
            'to_time' => 'Chọn thời gian ngày kết thúc',
            'to_date' => 'Chọn ngày kết thúc (d-m-Y)',
            'banner.required' => 'Chọn banner cho trang khuyến mãi',
            'floors_products.required' => 'Nhập sản phẩm khuyến mãi',
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

        $data['from_date'] = \Carbon\Carbon::parse($data['from_date'])->format('Y-m-d').' '.$data['from_time'];
        $data['to_date'] = \Carbon\Carbon::parse($data['to_date'])->format('Y-m-d').' '.$data['to_time'];

        if (is_array($data['banner'])) {
            $tmp = [];
            foreach ($data['banner'] as $key => $item) {
                if (!isset($item['image_url']) || !$item['image_url']) {
                    $item['image_url'] = config('app.url_outside');
                }
                $tmp[$key] = $item;
            }
            $data['banner'] = json_encode($tmp);
        }

        if (isset($data['banners_other']) && is_array($data['banners_other'])) {
            $tmp = [];
            foreach ($data['banners_other'] as $item) {
                if (!isset($item['url']) || !$item['url']) {
                    $item['url'] = url('/');
                }
                $tmp[] = $item;
            }
            $data['banners_other'] = json_encode($tmp);
        } else {
            $data['banners_other'] = '';
        }

        if (is_array($data['floors_products'])) {
            $tmp = [];
            foreach ($data['floors_products'] as $item) {
                if (!isset($item['name']) || !$item['name']) {
                    continue;
                }

                $tmp[] = $item;
            }

            $data['floors_products'] = json_encode($tmp);
        }

        $title = 'Landing page sản phẩm giảm giá';
        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\MicrositeSaleOff(), $data);

            $object = \App\Models\MicrositeSaleOff::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            $rs = $object->update($data);

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
        $rs = \App\Models\MicrositeSaleOff::create($data);

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

    public function store_normal(Request $request)
    {
        $data = $request->all();
        $id = $request->input('id', 0);

        $data['alias'] = str_slug($data['name']);

        $rules = [
            'name' => 'required|max:255',
            'alias' => 'required|max:255',
            'from_time' => 'required',
            'from_date' => 'required|date_format:"d-m-Y"',
            'to_time' => 'required',
            'to_date' => 'required|date_format:"d-m-Y"',
            'banner' => 'required',
            'description' => 'required',
            'image_location' => 'required'
        ];

        $messages = [
            'name' => 'Nhập tên chương trình khuyến mãi',
            'alias' => 'Nhập tên alias',
            'from_time' => 'Chọn thời gian bắt đầu',
            'from_date' => 'Chọn ngày bắt đầu (d-m-Y)',
            'to_time' => 'Chọn thời gian ngày kết thúc',
            'to_date' => 'Chọn ngày kết thúc (d-m-Y)',
            'banner.required' => 'Chọn banner cho trang khuyến mãi',
            'description.required' => 'Nhập nội dung mô tả',
            'image_location.required' => 'Chọn hình thể hiện chương trình'
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

        $data['from_date'] = \Carbon\Carbon::parse($data['from_date'])->format('Y-m-d').' '.$data['from_time'];
        $data['to_date'] = \Carbon\Carbon::parse($data['to_date'])->format('Y-m-d').' '.$data['to_time'];

        if (is_array($data['banner'])) {
            $tmp = [];
            foreach ($data['banner'] as $key => $item) {
                if (!isset($item['image_url']) || !$item['image_url']) {
                    $item['image_url'] = config('app.url_outside');
                }
                $tmp[$key] = $item;
            }
            $data['banner'] = json_encode($tmp);
        }

        if (isset($data['banners_other']) && is_array($data['banners_other'])) {
            $tmp = [];
            foreach ($data['banners_other'] as $item) {
                if (!isset($item['url']) || !$item['url']) {
                    $item['url'] = config('app.url_outside');
                }
                $tmp[] = $item;
            }
            $data['banners_other'] = json_encode($tmp);
        } else {
            $data['banners_other'] = '';
        }

        if (!isset($data['image_url']) || !$data['image_url']) {
            $data['image_url'] = config('app.url_outside');
        }

        $title = 'Landing page sản phẩm giảm giá';
        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\Microsite(), $data);

            $object = \App\Models\Microsite::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            $rs = \App\Models\Microsite::where('id', $id)
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
        $rs = \App\Models\Microsite::create($data);

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

    public function store_exchange_points(Request $request)
    {
        $data = $request->all();
        $id = $request->input('id', 0);

        $data['alias'] = str_slug($data['name']);

        $rules = [
            'name' => 'required|max:255',
            'alias' => 'required|max:255',
            'from_time' => 'required',
            'from_date' => 'required|date_format:"d-m-Y"',
            'to_time' => 'required',
            'to_date' => 'required|date_format:"d-m-Y"',
            'banner' => 'required',
            'banners_other' => 'required',
            'description' => 'required',
            'rule' => 'required',
            'interest' => 'required',
            'guide' => 'required',
            'gifts_products' => 'required',
        ];

        $messages = [
            'name' => 'Nhập tên chương trình khuyến mãi',
            'alias' => 'Nhập tên alias',
            'from_time' => 'Chọn thời gian bắt đầu',
            'from_date' => 'Chọn ngày bắt đầu (d-m-Y)',
            'to_time' => 'Chọn thời gian ngày kết thúc',
            'to_date' => 'Chọn ngày kết thúc (d-m-Y)',
            'banner.required' => 'Chọn banner cho trang khuyến mãi',
            'banners_other.required' => 'Chọn banner khuyến mãi khác',
            'description.required' => 'Nhập nội dung mô tả',
            'products.required' => 'Nhập sản phẩm bán giá sốc',
            'gifts_products.required' => 'Nhập sản phẩm đổi quà',
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

        $data['from_date'] = \Carbon\Carbon::parse($data['from_date'])->format('Y-m-d').' '.$data['from_time'];
        $data['to_date'] = \Carbon\Carbon::parse($data['to_date'])->format('Y-m-d').' '.$data['to_time'];

        if (is_array($data['banner'])) {
            $tmp = [];
            foreach ($data['banner'] as $key => $item) {
                if (!isset($item['image_url']) || !$item['image_url']) {
                    $item['image_url'] = config('app.url_outside');
                }
                $tmp[$key] = $item;
            }
            $data['banner'] = json_encode($tmp);
        }

        if (is_array($data['banners_other'])) {
            $tmp = [];
            foreach ($data['banners_other'] as $item) {
                if (!isset($item['url']) || !$item['url']) {
                    $item['url'] = url('/');
                }
                $tmp[] = $item;
            }
            $data['banners_other'] = json_encode($tmp);
        }

        if (is_array($data['gifts_products'])) {
            if (!isset($data['gifts_products']['banner']['image_url']) || $data['gifts_products']['banner']['image_url']) {
                $data['gifts_products']['banner']['image_url'] = config('app.url_outside');
            }
            $data['gifts_products'] = json_encode($data['gifts_products']);
        }

        $title = 'Landing page - Tích điểm đổi quà';
        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\MicrositeExchangePoint(), $data);

            $object = \App\Models\MicrositeExchangePoint::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            $rs = \App\Models\MicrositeExchangePoint::where('id', $id)
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
        $rs = \App\Models\MicrositeExchangePoint::create($data);

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

    public function store_gold_time(Request $request)
    {
        $data = $request->all();
        $id = $request->input('id', 0);

        $data['alias'] = str_slug($data['name']);

        $rules = [
            'name' => 'required|max:255',
            'alias' => 'required|max:255',
            'from_time' => 'required',
            'from_date' => 'required|date_format:"d-m-Y"',
            'to_time' => 'required',
            'to_date' => 'required|date_format:"d-m-Y"',
            'banner' => 'required',
            'description' => 'required',
            'products' => 'required',
            'floors_products' => 'required',
        ];

        $messages = [
            'name' => 'Nhập tên chương trình khuyến mãi',
            'alias' => 'Nhập tên alias',
            'from_time' => 'Chọn thời gian bắt đầu',
            'from_date' => 'Chọn ngày bắt đầu (d-m-Y)',
            'to_time' => 'Chọn thời gian ngày kết thúc',
            'to_date' => 'Chọn ngày kết thúc (d-m-Y)',
            'banner.required' => 'Chọn banner cho trang khuyến mãi',
            'description.required' => 'Nhập nội dung mô tả',
            'products.required' => 'Nhập sản phẩm bán giá sốc',
            'floors_products.required' => 'Nhập sản phẩm khuyến mãi',
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

        $data['from_date'] = \Carbon\Carbon::parse($data['from_date'])->format('Y-m-d').' '.$data['from_time'];
        $data['to_date'] = \Carbon\Carbon::parse($data['to_date'])->format('Y-m-d').' '.$data['to_time'];

        if (is_array($data['banner'])) {
            $tmp = [];
            foreach ($data['banner'] as $key => $item) {
                if (!isset($item['image_url']) || !$item['image_url']) {
                    $item['image_url'] = config('app.url_outside');
                }
                $tmp[$key] = $item;
            }
            $data['banner'] = json_encode($tmp);
        }

        if (isset($data['banners_other']) && is_array($data['banners_other'])) {
            $tmp = [];
            foreach ($data['banners_other'] as $item) {
                if (!isset($item['url']) || !$item['url']) {
                    $item['url'] = url('/');
                }
                $tmp[] = $item;
            }
            $data['banners_other'] = json_encode($tmp);
        } else {
            $data['banners_other'] = '';
        }

        if (is_array($data['products'])) {
            $tmp = [];
            foreach ($data['products'] as $item) {
                if (!isset($item['product_id']) || !$item['product_id']) {
                    continue;
                }

                $tmp[] = $item;
            }

            $data['products'] = json_encode($tmp);
        }

        if (is_array($data['floors_products'])) {
            $tmp = [];
            foreach ($data['floors_products'] as $item) {
                if (!isset($item['name']) || !$item['name']) {
                    continue;
                }

                $tmp[] = $item;
            }

            $data['floors_products'] = json_encode($tmp);
        }

        $title = 'Landing page - Giờ vàng giá sốc';
        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\MicrositeGoldTime(), $data);

            $object = \App\Models\MicrositeGoldTime::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            $rs = \App\Models\MicrositeGoldTime::where('id', $id)
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
        $rs = \App\Models\MicrositeGoldTime::create($data);

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

    public function store_pre_order(Request $request)
    {
        $data = $request->all();
        $id = $request->input('id', 0);

        $data['alias'] = str_slug($data['name']);

        $rules = [
            'name' => 'required|max:255',
            'alias' => 'required|max:255',
            'from_time' => 'required',
            'from_date' => 'required|date_format:"d-m-Y"',
            'to_time' => 'required',
            'to_date' => 'required|date_format:"d-m-Y"',
            'banner' => 'required',
            'description' => 'required',
            'products' => 'required',
            'banner_content' => 'required',
            'logo' => 'required'
        ];

        $messages = [
            'name' => 'Nhập tên chương trình khuyến mãi',
            'alias' => 'Nhập tên alias',
            'from_time' => 'Chọn thời gian bắt đầu',
            'from_date' => 'Chọn ngày bắt đầu (d-m-Y)',
            'to_time' => 'Chọn thời gian ngày kết thúc',
            'to_date' => 'Chọn ngày kết thúc (d-m-Y)',
            'banner.required' => 'Chọn banner',
            'description.required' => 'Nhập nội dung mô tả',
            'products.required' => 'Nhập sản phẩm',
            'banner_content.required' => 'Nhập banner tab nội dung',
            'logo' => 'Chọn logo'
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

        $data['from_date'] = \Carbon\Carbon::parse($data['from_date'])->format('Y-m-d').' '.$data['from_time'];
        $data['to_date'] = \Carbon\Carbon::parse($data['to_date'])->format('Y-m-d').' '.$data['to_time'];

        if (is_array($data['banner'])) {
            $tmp = [];
            foreach ($data['banner'] as $key => $item) {
                if (!isset($item['image_url']) || !$item['image_url']) {
                    $item['image_url'] = url('/');
                }
                $tmp[$key] = $item;
            }
            $data['banner'] = json_encode($tmp);
        }

        if (isset($data['banner_content']) && is_array($data['banner_content'])) {
            $tmp = [];
            foreach ($data['banner_content'] as $item) {
                if (!isset($item['url']) || !$item['url']) {
                    $item['url'] = url('/');
                }
                $tmp[] = $item;
            }
            $data['banner_content'] = json_encode($tmp);
        } else {
            $data['banner_content'] = '';
        }

        if (is_array($data['products'])) {
            $tmp = [];
            foreach ($data['products'] as $item) {
                if (!isset($item['product_id']) || !$item['product_id']) {
                    continue;
                }

                $tmp[] = $item;
            }

            $data['products'] = json_encode($tmp);
        }

        if(isset($data['logo']) && is_array($data['logo'])){
            if (!isset($data['logo']['url']) || !$data['logo']['url']) {
                $data['logo']['url'] = url('/');
            }
            $data['logo'] = json_encode($data['logo']);
        }

        $title = 'Landing page - Pre-Order';
        $data['user_modified'] = $this->get_user_id();

        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\MicrositePreOrder(), $data);

            $object = \App\Models\MicrositePreOrder::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            $rs = \App\Models\MicrositePreOrder::where('id', $id)
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
        $rs = \App\Models\MicrositePreOrder::create($data);

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
            \App\Models\MicrositeSaleOff::find($id)->delete();

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

    public function update_status_sale_off(Request $request)
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
            $object = \App\Models\MicrositeSaleOff::find($id);

            $object->status = $status;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => $title.' chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => $title.' chương trình khuyến mãi không thành công'
        ]);
    }

    public function update_status_normal(Request $request)
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
            $object = \App\Models\Microsite::find($id);

            $object->status = $status;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => $title.' chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => $title.' chương trình khuyến mãi không thành công'
        ]);
    }

    public function update_status_exchange_points(Request $request)
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
            $object = \App\Models\MicrositeExchangePoint::find($id);

            $object->status = $status;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => $title.' chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => $title.' chương trình khuyến mãi không thành công'
        ]);
    }

    public function update_status_gold_time(Request $request)
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
            $object = \App\Models\MicrositeGoldTime::find($id);

            $object->status = $status;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => $title.' chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => $title.' chương trình khuyến mãi không thành công'
        ]);
    }

    public function update_status_pre_order(Request $request)
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
            $object = \App\Models\MicrositePreOrder::find($id);

            $object->status = $status;
            $object->save();

            return response()->json([
                'rs' => 1,
                'msg' => $title.' chương trình khuyến mãi thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => $title.' chương trình khuyến mãi không thành công'
        ]);
    }
}
