<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    protected $table = 'warehouses';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id',
        'shipping_cost',
        'name',
        'phone',
        'latitude',
        'longitude',
        'short_description',
        'full_description',
        'address',
        'ward_id',
        'district_id',
        'city_id',
        'province_id',
        'status',
        'is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getOption($supplier_id=null){
        $query = self::select('id','name')
            ->whereNull('supplier_id')
            ->orWhere('supplier_id',0);
        if(!empty($supplier_id))
            $query->orWhere('supplier_id',$supplier_id);

        return $query->pluck('name','id')->toArray();
    }

    public static function getData($params=[],$limit = 10){
        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('warehouses.*','suppliers.name as supplier_name');
        $objects->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','warehouses.supplier_id');
            $join->where('suppliers.is_deleted',0);
        });

        $objects->where('warehouses.is_deleted',0);

        if(!empty($params['supplier_id']))
            $objects->where('warehouses.supplier_id',$params['supplier_id']);

        if(!empty($params['category_id'])) {
            $objects->leftJoin('warehouses_categories',function($join){
            $join->on('warehouses_categories.warehouse_id','=','warehouses.id');
            });
            $objects->leftjoin('category_descriptions',function($join){
                $join->on('category_descriptions.category_id','=','warehouses_categories.category_id');
            });
            $objects->where('warehouses_categories.category_id',$params['category_id']);
        }

        if(!empty($params['name']))
            $objects->where('warehouses.name','like','%'.$params['name'].'%');

        if(!empty($params['phone']))
            $objects->where('warehouses.phone',$params['phone']);

        if(isset($params['status']))
            $objects->where('warehouses.status',$params['status']);

        $objects->orderBy('warehouses.id','desc');
        
        $objects = $objects->paginate($limit);

        $objects = $objects->toArray();

        return $objects;
    }

    public static function getDataById($id){
        $object = self::find($id);
        if($object)
            return $object->toArray();

        return [];
    }

    public static function getShippingCost($warehouses_id,$product_id){
        if(!$warehouses_id) return 0;
        $query = self::where('warehouses.id',$warehouses_id);
        $query->join('products_categories',function($join) use($product_id){
            $join->on('products_categories.category_id','=','warehouses.category_id');
            $join->where('products_categories.link_type','M')
                ->where('products_categories.position',0)
                ->where('products_categories.product_id',$product_id);
        });
        $result = $query->first();
        if(!empty($result))
            return $result->shipping_cost;
        return 0;
    }

}
