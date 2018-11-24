<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \App\Helpers\General;

class Products extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'products';

    protected $primaryKey = 'product_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'parent_id',
        'product_code',
        'product_type',
        'supplier_id',
        'brand_id',
        'status',
        'list_price',
        'amount', 'amount_of_priority',
        'has_gift',
        'weight',
        'length',
        'width',
        'height',
        'shipping_freight',
        'timestamp',
        'image_location',
        'image_url',
        'is_edp',
        'edp_shipping',
        'unlimited_download',
        'tracking',
        'tracking_status',
        'free_shipping',
        'feature_comparison',
        'zero_price_action',
        'is_exchange', 'is_new', 'is_installment', 'is_good_price',
        'return_period',
        'avail_since',
        'buy_in_advance',
        'date_start_sell',
        'min_qty',
        'max_qty',
        'qty_step',
        'unit_id',
        'list_qty_count',
        'warehouse_id', 'province_id', 'district_id', 'ward_id',
        'options_type',
        'exceptions_type',
        'price'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function get_status_options()
    {
        return [
            'A' => 'Active',
            'D' => 'Deactive',
            'H' => 'Hide',
        ];
    }

    public static function fillable_no_auditable() {
        return [
            'full_description', 'short_description', 'specifications'
        ];
    }

    public static function getOptionsFieldName() {
        return [
            'product_code' => 'SKU',
            'status' => 'Trạng thái',
            'list_price' => 'Giá thị trường',
            'amount' => 'Số lượng',
            'weight' => 'Trọng lượng',
            'price' => 'Giá bán',
            'brand_id' => 'Thương hiệu',
            'product' => 'Tên sản phẩm',
            'alias' => 'Alias',
            'has_gift' => 'Hiển thị icon quà tặng',
            'page_title' => 'Tiêu đề trang',
            'meta_description' => 'Mô tả SEO',
            'meta_keywords' => 'Từ khóa SEO',
        ];
    }

    public static function getData($params=[],$limit = 10,$lang_code='vi'){

        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $prduct_type = $params['product_type']?? 'P';

        $objects = self::select('products.*','product_descriptions.*','category_descriptions.category','brand_descriptions.name as brand_name');

        $objects->join('product_descriptions',function($join) use ($lang_code, $params){
            $join->on('product_descriptions.product_id','=','products.product_id');
            if (isset($params['name'])) {
                $join->where('product_descriptions.product', 'like', '%'.$params['name'].'%');
            }
        });

        $objects->leftJoin('products_categories',function($join){
            $join->on('products_categories.product_id','=','products.product_id');
            $join->where('products_categories.link_type', 'M');
            $join->where('products_categories.position', 0);
        });

        if (isset($params['category_ids']) || isset($params['category_id'])) {
            $objects->join('category_descriptions',function($join) use($lang_code, $params){
                $join->on('category_descriptions.category_id','=','products_categories.category_id');
                $join->where('category_descriptions.lang_code', $lang_code);

                if (isset($params['category_ids'])) {
                    $join->whereIn('category_descriptions.category_id', $params['category_ids']);
                } elseif (isset($params['category_id'])) {
                    $join->where('category_descriptions.category_id', $params['category_id']);
                }
            });
        } else {
            $objects->leftJoin('category_descriptions',function($join) use($lang_code, $params){
                $join->on('category_descriptions.category_id','=','products_categories.category_id');
                $join->where('category_descriptions.lang_code', $lang_code);
            });
        }

        if (isset($params['brand_id'])) {
            $objects->join('brand_descriptions', function ($join) use ($lang_code, $params) {
                $join->on('brand_descriptions.brand_id', '=', 'products.brand_id');
                $join->where('brand_descriptions.lang_code', $lang_code);
                $join->where('brand_descriptions.brand_id', $params['brand_id']);
            });
        } else {
            $objects->leftJoin('brand_descriptions', function ($join) use ($lang_code, $params) {
                $join->on('brand_descriptions.brand_id', '=', 'products.brand_id');
                $join->where('brand_descriptions.lang_code', $lang_code);
            });
        }
        //condition supplier
        if (isset($params['supplier_id'])) {
            $objects->join('suppliers', function ($join) use ($params) {
                $join->on('suppliers.id', '=', 'products.supplier_id');
                $join->where('suppliers.id', $params['supplier_id']);
            });
        }

        if (isset($params['sku'])) {
            $objects->where('products.product_code', 'like', '%'.$params['sku'].'%');
        }

        if (isset($params['status'])) {
            if ($params['status']=='A') {
                $objects->where('products.status', 'A');
            } elseif ($params['status']=='D') {
                $objects->where('products.status', '!=', 'A');
            }
        }

        if(isset($params['from_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['from_date'])->format('Y-m-d 00:00:00');
            $objects->where('products.updated_at','>=', $tmp);
        }

        if(isset($params['to_date'])) {
            $tmp = \DateTime::createFromFormat('d-m-Y', $params['to_date'])->format('Y-m-d 23:59:59');
            $objects->where('products.updated_at','<=', $tmp);
        }

        if (isset($params['price_from'])) {
            $objects->where('products.price', '>=', $params['price_from']);
        }
        if (isset($params['price_to']) && (!isset($params['price_from']) || $params['price_from'] <= $params['price_to']) && $params['price_to']) {
            $objects->where('products.price', '<=', $params['price_to']);
        }

        if (isset($params['number_from'])) {
            $objects->where('products.amount', '>=', $params['number_from']);
        }
        if (isset($params['number_to']) &&
            (!isset($params['number_to']) || $params['number_from'] <= $params['number_to']) && $params['number_to']) {
            $objects->where('products.amount', '<=', $params['number_to']);
        }
        $objects->where('products.product_type', $prduct_type);

        if (isset($params['sku'])) {
            $objects->orderBy('products.product_code', 'asc');
        }

        $objects->orderBy('products.product_id', 'desc');

//        $builder = str_replace(array('?'), array('\'%s\''), $objects->toSql());
//        $builder = vsprintf($builder, $objects->getBindings());

        if (isset($params['is_export'])) {
            return $objects->get();
        }

        return $objects->paginate($limit)->toArray();
    }

    public static function getDataById($id,$lang_code='vi'){
        $objects = self::select(['products.*',
            'product_descriptions.*','products_categories.category_id',
            'category_descriptions.category','brand_descriptions.name as brand_name'
        ]);

        $objects->leftJoin('product_descriptions',function($join)use($lang_code){
            $join->on('product_descriptions.product_id','=','products.product_id');
            $join->where('product_descriptions.lang_code',$lang_code);
        });

        $objects->leftJoin('products_categories',function($join){
            $join->on('products_categories.product_id','=','products.product_id');
            $join->where('products_categories.link_type', 'M');
            $join->where('products_categories.position', 0);
        });

        $objects->leftJoin('category_descriptions',function($join3) use($lang_code){
            $join3->on('category_descriptions.category_id','=','products_categories.category_id');
            $join3->where('category_descriptions.lang_code',$lang_code);
        });

        $objects->leftJoin('brand_descriptions',function($join) use($lang_code){
            $join->on('brand_descriptions.brand_id','=','products.brand_id');
            $join->where('brand_descriptions.lang_code',$lang_code);
        });

        $rs = $objects->find($id);

        if($rs){
            $rs = $rs->toArray();

            $rs['images'] = \App\Models\ProductFiles::getDataByProductId($rs['product_id']);
        }

        return $rs;
    }
    public static function search($kw='',$limit=10,$lang_code='vi', $except_pids=null){

        $query = self::select('products.*','product_descriptions.product',\DB::raw("CONCAT(products.image_url, products.image_location) as image"));

        $query->where('products.status', 'A');

        $query->join('product_descriptions',function($join) use($lang_code) {
            $join->on('product_descriptions.product_id','=','products.product_id');
            $join->where('product_descriptions.lang_code',$lang_code);
        });

        $query->leftJoin('product_files',function($join){
            $join->on('product_files.pid','=','products.product_id');
            $join->where('product_files.is_main',1);
        });

        $query->where(function ($query) use($kw) {
            $query->where('products.product_code', 'like' ,"%$kw%")
                  ->orWhere('products.product_id','like' ,"%$kw%")
                  ->orWhere('product_descriptions.product', 'like' ,"%$kw%");
        });

        if ($except_pids && is_array($except_pids)) {
            $query->whereNotIn('products.product_id', $except_pids);
        }
        $query->where('products.product_type', 'P');
        $query->orderBy('products.product_id','desc');

        $rs = $query->limit($limit)->get()->toArray();

        return $rs;
    }
    public static function getProductsShow($ids, $sort = 'price-desc', $width = 270, $height = 270)
    {
        $objects = self::select('products.*',
            'products.product_code as sku',
            'product_descriptions.product as name',
            'product_descriptions.page_title', 'product_descriptions.meta_keywords', 'product_descriptions.meta_description',
            'product_descriptions.short_description',
            'brand_descriptions.name as brand_name'
        )
            ->whereIn('products.product_id', $ids);

        $objects->join('product_descriptions', function ($join) {
            $join->on('product_descriptions.product_id', '=', 'products.product_id');
            $join->where('product_descriptions.lang_code', "vi");
        });

        $objects->leftJoin('brand_descriptions', function ($join) {
            $join->on('brand_descriptions.brand_id', '=', 'products.brand_id');
            $join->where('brand_descriptions.lang_code', "vi");
        });


        $objects->where('products.status', 'A');

        $objects = $objects->get()->toArray();

        $images = \App\Models\ProductFiles::getImagesByProductIds($ids);

        $rs = [];
        foreach ($objects as $item) {
            $item['image'] = isset($images[$item['product_id']]) ? General::get_link_image($images[$item['product_id']], $width, $height) : '';
            $rs[] = $item;
        }
        return $rs;
    }

    public static function getProductAddCartById($product_id, $width = 270, $height = 270)
    {
        $object = self::select('products.*', 'products.product_code as sku',
            'products_categories.category_id',
//            'brand_descriptions.name as brand_name',
            'product_descriptions.product as product_name');

        $object->leftJoin('products_categories', function ($join) {
            $join->on('products_categories.product_id', '=', 'products.product_id');
            $join->where('products_categories.link_type', "M");
        });
//        $object->leftJoin('brand_descriptions', function ($join) {
//            $join->on('brand_descriptions.brand_id', '=', 'products.brand_id');
//            $join->where('brand_descriptions.lang_code', "vi");
//        });
        $object->join('product_descriptions', function ($join) {
            $join->on('product_descriptions.product_id', '=', 'products.product_id');
            $join->where('product_descriptions.lang_code', "vi");
        });
        $object = $object->find($product_id)->toArray();
        if(is_array($product_id)){
            foreach($object as &$item){
                $item['image'] = $item['image_url'].$item['image_location'];
            }
        }else{
            $object['image'] = $object['image_url'].$object['image_location'];
        }

        return $object;
    }

    public static function getProductsByIds($ids)
    {

        $objects = self::select('products.*', 'products.product_code as sku',
            'product_descriptions.product as name'
        )->whereIn('products.product_id', $ids);

        self::joinProduct($objects);

        $objects = $objects->get()->toArray();

        return $objects;
    }

    public static function getProductsByProductCode($product_code)
    {

        $objects = self::select('products.product_code as sku', 'products.product_id',
            'product_descriptions.product as name'
        )
            ->whereIn('products.product_code', $product_code);

        self::joinProduct($objects);

        $objects = $objects->get()->toArray();

        return $objects;
    }

    public static function joinProduct(&$objects)
    {

        $objects->join('product_descriptions', function ($join) {
            $join->on('product_descriptions.product_id', '=', 'products.product_id');
            $join->where('product_descriptions.lang_code', "vi");
        });

        $objects->where('products.status', 'A');
    }

    public static function getProductsShowForReport($ids)
    {
        $objects = self::select('products.*',
            'products.product_code as sku',
            'product_descriptions.product as name',
            'product_descriptions.page_title', 'product_descriptions.meta_keywords', 'product_descriptions.meta_description',
            'product_descriptions.short_description',
            'brand_descriptions.name as brand_name'
        )
            ->whereIn('products.product_id', $ids);

        $objects->join('product_descriptions', function ($join) {
            $join->on('product_descriptions.product_id', '=', 'products.product_id');
            $join->where('product_descriptions.lang_code', "vi");
        });

        $objects->leftJoin('brand_descriptions', function ($join) {
            $join->on('brand_descriptions.brand_id', '=', 'products.brand_id');
            $join->where('brand_descriptions.lang_code', "vi");
        });

        $objects = $objects->get()->toArray();

        $rs = [];
        foreach($objects as $item){
            $rs[$item['product_id']] = $item;
        }

        return $rs;
    }

    public static function getProductsByCategoryId($category_id,$params=array(),$limit=30){
        $product_type = $params['product_type']??'P';
        $objects = \App\Models\ProductsCategories::select('products.*', 'product_descriptions.product as name');

        if (is_array($category_id)) {
            $objects->whereIn('products_categories.category_id', $category_id);
        } else {
            $objects->where('products_categories.category_id', $category_id);
        }

        $objects->join('products','products_categories.product_id','=','products.product_id');
        $objects->join('product_descriptions','product_descriptions.product_id','=','products.product_id');
        $objects->where('products.product_type',$product_type);

        if(!empty($params['sort'])){
            self::sortProducts($objects,$params['sort']);
        }else{
            $objects->orderBy('products.product_id','desc');
        }

        $objects = $objects->get()->toArray();

        return $objects;
    }

    public static function getOptions() {
        $query = self::select(
            'products.product_id',
            \DB::raw('CONCAT(products.product_code," - ",product_descriptions.product) as name')
        )
            ->join('product_descriptions','product_descriptions.product_id','=','products.product_id')
            ->where('products.status', 'A')
            ->where('products.product_type', 'P')
            ->where('products.parent_id', 0);

        return $query->pluck('name','product_id')->toArray();
    }

    public static function getSurcharge($product_id,$quantity){
        $query = self::select('surcharge_detail.*')
                        ->where('products.product_id',$product_id);
        $query->join('surcharge',function($join){
            $join->on('surcharge.unit_id','=','products.unit_id');
            $join->on('surcharge.supplier_id','=','products.supplier_id');
        });
        $query->join('products_categories',function($join) use($product_id){
            $join->on('products_categories.category_id','=','surcharge.category_id');
            $join->where('products_categories.link_type','M')
                ->where('products_categories.position',0)
                ->where('products.product_id',$product_id);
        });
        $query->join('surcharge_detail','surcharge.id','=','surcharge_detail.surcharge_id');

        $result = $query->get()->toArray();
        if(empty($result)) return 0;
        foreach($result as $item){
            
            if($quantity > $item['from']){
                if($quantity <= $item['to'] || $item['to'] == 0)
                    return $item['price'];
            }
        }

        return 0;
    }
}