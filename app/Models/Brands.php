<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 1/30/2018
 * Time: 11:32 AM
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = 'brands';

    protected $primaryKey = 'brand_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['brand_id','status','product_count','position','image_location','image_url','timestamp','sync_data'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    static function get_status_options() {
        return [
            'A' => 'Active',
            'D' => 'Deactive',
            'H' => 'Hide',
        ];
    }

    public static function getBrandOptions(){
        $objects = self::select('brands.brand_id', 'brand_descriptions.name');

        $objects->leftJoin('brand_descriptions',function($join){
            $join->on('brand_descriptions.brand_id','=','brands.brand_id');
            $join->where('brand_descriptions.lang_code','vi');
        });

        $objects->where('brands.status', 'A');

        $objects->orderBy('brand_descriptions.name','asc');

        $rs = $objects->pluck('name', 'brand_id')->toArray();

        return $rs;
    }

    public static function getAllData(){
        $objects = self::select('brands.*', 'brand_descriptions.*');

        $objects->leftJoin('brand_descriptions',function($join){
            $join->on('brand_descriptions.brand_id','=','brands.brand_id');
            $join->where('brand_descriptions.lang_code','vi');
        });

        $objects->where('brands.status', 'A');

        $rs = $objects->get()->toArray();

        return $rs;
    }

    public static function getData($params=[],$limit = 10){

        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('brands.*','brand_descriptions.*', 'bd_en.name as name_en','bd_en.alias as alias_en','bd_en.description as description_en');

        $objects->leftJoin('brand_descriptions',function($join){
            $join->on('brand_descriptions.brand_id','=','brands.brand_id');
            $join->where('brand_descriptions.lang_code','vi');
        });

        $objects->leftJoin('brand_descriptions as bd_en',function($join){
            $join->on('bd_en.brand_id','=','brands.brand_id');
            $join->where('bd_en.lang_code','en');
        });

        if(!empty($params['category_id'])){
            $objects->join('categories_brands',function($join) use ( $params ) {
                $join->on('categories_brands.brand_id','=','brands.brand_id');
                $join->where('categories_brands.category_id',$params['category_id']);
            });
        }

        if(!empty($params['name']))
            $objects->where('brand_descriptions.name', 'like', '%'.$params['name'].'%');

        if(!empty($params['status']))
            $objects->where('brands.status',$params['status']);

        $objects->orderBy('brands.brand_id','desc');

        $objects = $objects->paginate($limit);

        $objects = $objects->toArray();

        return $objects;
    }

    public static function getDataById($brand_id){

        $objects = self::select('brands.*','brand_descriptions.*', 'bd_en.name as name_en','bd_en.alias as alias_en','bd_en.description as description_en');

        $objects->leftJoin('brand_descriptions',function($join){
            $join->on('brand_descriptions.brand_id','=','brands.brand_id');
            $join->where('brand_descriptions.lang_code','vi');
        });

        $objects->leftJoin('brand_descriptions as bd_en',function($join){
            $join->on('bd_en.brand_id','=','brands.brand_id');
            $join->where('bd_en.lang_code','en');
        });

        $objects = $objects->find($brand_id)->toArray();

        return $objects;
    }
}
