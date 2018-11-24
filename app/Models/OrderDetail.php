<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class OrderDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'orders_details';
    protected $primaryKey = 'order_id';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['item_id', 'order_id', 'product_id', 'product_code', 'price', 'amount', 'discount', 'extra','feature_value'];

    protected $casts = [
        'feature_value' => 'array',
    ];

    public static function getData($order_ids, $lang_code='vi'){
    	$query = self::select('orders_details.*','products.amount as number_in_store','products.brand_id','products.weight','products.product_type',
            'product_descriptions.product', \DB::raw("CONCAT(products.image_url, products.image_location) as image"),\DB::raw("CONCAT(brands.image_url, brands.image_location) as image_brand"),'brand_descriptions.name as name_brand','units.name as unit_name')
        ->whereIn('orders_details.order_id',$order_ids);

    	$query->join('product_descriptions',function($join) use ($lang_code){
    		$join->on('product_descriptions.product_id','=','orders_details.product_id');
    		$join->where('product_descriptions.lang_code',$lang_code);
    	});

        $query->join('products','products.product_id','=','orders_details.product_id');
        $query->leftJoin('brands','brands.brand_id','=','products.brand_id');
        $query->leftJoin('brand_descriptions','brand_descriptions.brand_id','=','brands.brand_id');

        $query->leftJoin('product_files',function($join){
            $join->on('product_files.pid','=','orders_details.product_id');
            $join->where('product_files.is_main', 1);
        });
        $query->leftJoin('units','units.id','=','products.unit_id');
    	$rs = $query->get()->toArray();
    	$data = [];
    	foreach($rs as $item){
    		$data[$item['order_id']][] = $item;
    	}

    	return $data;
    }

    public static function getProductsReport($order_ids=[],$limit=false){

        $query = self::select('orders_details.product_id',\DB::raw('sum(orders_details.amount) as sum_amount'))
                ->whereIn('order_id',$order_ids)
                ->groupBy('orders_details.product_id')
                ->orderBy('sum_amount','desc');

        $query->join('products','products.product_id','=','orders_details.product_id');

        if($limit)
            $query->limit($limit);

        $result = $query->get()->toArray();        
        return $result;
    }
}