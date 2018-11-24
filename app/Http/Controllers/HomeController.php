<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'home';
    }

    private function handling_brand_popular_data($data){

        $result = array();

        if (is_array($data['content'])) {
            foreach ($data['content'] as $key => $item) {
                $item['url'] = empty($item['url']) ? url('/') : $item['url'];
                if(isset($data['products'][$key]))
                    $item['product_ids'] = $data['products'][$key];
                
                $result[] = $item;
            }
        }
        return json_encode($result);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function block_product(Request $request, $sort=1)
    {        
        $block = 'block_product_v2';
        $this->data['block'] = $block;
        $this->data['sort'] = $sort;

        $this->data['title'] = 'Block sản phẩm '.$sort.' - Trang chủ';

        $object = \App\Models\HomeBlock::select('home_blocks.*');
        $object->where('home_blocks.block', $block);
        $object->where('home_blocks.sort', $sort);
        $object = $object->first();

        $this->data['object'] = $object;
        $products   =   [];
        $banner     =   [];
        $content    =   [];

        if (!empty($object['content'])) {
            
            $content    = json_decode($object['content'], 1);            
            $products   = \App\Models\Products::getProductsShow($content['products']);
            if(!empty($content['banner']))
                $banner     = $content['banner'];
        }

        $this->data['products'] = $products;
        $this->data['content']  = $content;
        $this->data['banner']  = $banner;

        return view("{$this->data['controllerName']}.block-product", $this->data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function brand_popular(Request $request)
    {
        $block = 'brand_popular';
        $this->data['block'] = $block;

        $this->data['title'] = 'Thương hiệu yêu thích - Trang chủ';

        $object = \App\Models\HomeBlock::select('home_blocks.*');
        $object->where('home_blocks.block', $block);
        $object = $object->first();

        if ($object) {
            $object['content'] = json_decode($object['content'], 1);
            
            $list_product = [];
            $list_product_all = [];

            foreach ($object['content'] as $key => $item) {
                $tab_brand[$key+1] = $item;
                $list_product[$key+1] = $item['product_ids'];

                $list_product_all = array_merge($list_product_all, $item['product_ids']);
            }   
            
            $list_product_all = \App\Models\Products::getProductsByIds($list_product_all);
            
            $list_product_name = [];
            $list_product_sku = [];
            foreach($list_product_all as $item){
                $list_product_name[$item['product_id']] = $item['name'];
                //$list_product_sku[$item['product_id']] = $item['sku'];
                $list_product_sku[$item['product_id']] = $item['product_code'];
            }

            
            $this->data['tab_brand']            = $tab_brand;
            $this->data['list_product']         = $list_product;
            $this->data['list_product_name']    = $list_product_name;
            $this->data['list_product_sku']     = $list_product_sku;
        }
        $this->data['object'] = $object;
        
        return view("{$this->data['controllerName']}.brand-popular", $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function only_at_dmth(Request $request)
    {
        $block = 'only_at_dmth';
        $this->data['block'] = $block;

        $this->data['title'] = 'Chỉ có tại Thiên Hòa - Trang chủ';

        $object = \App\Models\HomeBlock::select('home_blocks.*');
        $object->where('home_blocks.block', $block);
        $object = $object->first();

        $this->data['object'] = $object;

        return view("{$this->data['controllerName']}.only-at-dmth", $this->data);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function brand_hot(Request $request)
    {
        $block = 'brand_hot';
        $this->data['block'] = $block;

        $this->data['title'] = 'Thương hiệu nổi bật - Trang chủ';

        $object = \App\Models\HomeBlock::select('home_blocks.*');
        $object->where('home_blocks.block', $block);
        $object = $object->first();

        $this->data['object'] = $object;
        $tab_brand = [];
        if ($object) {
            $object['content'] = json_decode($object['content'], 1);
            foreach ($object['content']['tab_products'] as $key => $item) {
                $tab_brand[$key+1] = $item;
            }            
        }

        $this->data['tab_brand'] = $tab_brand;
        $this->data['banner'] = $object['content']['banner'];

        return view("{$this->data['controllerName']}.brand-hot", $this->data);
    }

    private function handling_brand_only_at_dmth_data($data){
        $result = array();

        if (is_array($data)) {
            foreach ($data as $item) {
                if (!isset($item['url']) || $item['url']) {
                    $item['url'] = url('/');
                }
                $result[] = $item;
            }
        }
        return json_encode($result);
    }

    private function handling_block_product($data){

        $result = array();
        $result['products']     = $data['products'];
        $result['link_more']    = $data['link_more'];
        $result['banner']       = [];
        if (!empty($data['banner'])) {
            $tmp = [];
            foreach ($data['banner'] as $key => $item) {
                if (!isset($item['image_url']) || !$item['image_url']) {
                    $item['image_url'] = config('app.url_outside');
                }
                $tmp[$key] = $item;
            }
            $result['banner'] = $tmp;
        }

        return json_encode($result);
    }

    private function handling_brand_hot_data($data){

        $result = array();
        if (empty($data['image_url'])) $data['image_url'] = config('app.url_outside');
        $result['banner'] = array(
            'name'      => $data['banner_name'],
            'image_url' => $data['image_url'],
            'image_location' => $data['image_location'],
            'link'      => $data['banner_link'],
        );

        if (is_array($data['content'])) {
            foreach ($data['content'] as $item) {
                $result['tab_products'][] = $item;
            }
        }
        return json_encode($result);
    }

    private function handling_content_default($content){

        if (is_array($content)) {
            $tmp = [];
            foreach ($content as $item) {
                $tmp[] = $item;
            }

            $content = json_encode($tmp);
        }

        return $content;        
    }

    private function handling_content_top_products($data){

        $result = [];
        if(!empty($data['tabs']))
            $result = $data['tabs'];

        return json_encode($result);        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shock_more(Request $request)
    {
        $block = 'shock_more';
        $this->data['block'] = $block;

        $this->data['title'] = 'Không thể sốc hơn - Trang chủ';

        $object = \App\Models\HomeBlock::select('home_blocks.*');
        $object->where('home_blocks.block', $block);
        $object = $object->first();

        $this->data['object'] = $object;

        $shock_products = [];
        if ($object) {
            $object['content'] = json_decode($object['content'], 1);
            foreach ($object['content'] as $item) {
                $shock_products[$item['product_id']] = $item;
            }
            $products = \App\Models\Products::getProductsShow(array_keys($shock_products), $object['sort']);
        } else {
            $products = [];
        }

        $this->data['shock_products'] = $shock_products;
        $this->data['products'] = $products;

        return view("{$this->data['controllerName']}.shock-more", $this->data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $block = 'top_products_v2';        
        $this->data['title'] = 'Top Sản phẩm giá tốt nhất - Trang chủ';
        $this->data['block'] = $block;

        $object = \App\Models\HomeBlock::select('home_blocks.*');
        $object->where('home_blocks.block', $block);
        $object = $object->first();

        $this->data['object'] = $object;

        $tabs = [];
        if ($object) {
            $content = json_decode($object['content'], 1);

            foreach ($content as $key => $value) {
                # code...
                $tabs[]     = $value;
            }           
        }
        $this->data['tabs']     = $tabs;

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
            'block' => 'required|max:255',
            'ordering' => 'required',
            //'sort' => 'required',
            'content' => 'required'
        ];

        $messages = [
            'name' => 'Nhập tiêu đề block',
            'block' => 'Loại block',
            'ordering' => 'Chọn thứ tự hiển thị block',
            //'sort' => 'Chọn hiển thị sản phẩm theo',
            'content' => 'Nhập đối tượng cho block',
        ];

        switch ($data['block']) {
            case 'brand_hot':
                $title = "Thương hiệu nổi bật";
                unset($rules['sort']);
                unset($messages['sort']);
                $data['content'] = $this->handling_brand_hot_data($data);
                break;
            case "only_at_dmth":
                $title = 'Chỉ có tại Thiên Hòa';
                $data['content'] = $this->handling_brand_only_at_dmth_data($data['content']);
                break;
            case "brand_popular":
                $title = 'Thương hiệu yêu thích';
                unset($rules['sort']);
                unset($messages['sort']);
                $data['content'] = $this->handling_brand_popular_data($data);
                break;
            case "shock_more":
                $title = 'Không thể sốc hơn';
                $data['content'] = $this->handling_content_default($data['content']);
                break;
            case "block_product_v2":
                $title = 'Block sản phẩm';
                $data['content'] = $this->handling_block_product($data);
                break;
            default:
                $title = 'Top sản phẩm giá tốt';
                $data['content'] = $this->handling_content_top_products($data);
                
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

        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\HomeBlock(), $data);

            $object = \App\Models\HomeBlock::find($id);

            if (!$object) {
                return response()->json([
                    'rs' => 0,
                    'msg' => 'Không tìm thấy '.$title
                ]);
            }

            $rs = $object->update($data);
//            $rs = \App\Models\HomeBlock::where('id', $id)->update($data);

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
        $rs = \App\Models\HomeBlock::create($data);

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
}

