<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/2/2018
 * Time: 3:01 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CategoriesBrands extends Model
{
    protected $table = 'categories_brands';

    protected $primaryKey = 'id';

    protected $fillable = ['brand_id','category_id','position'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function primary(){
        return ['brand_id','category_id'];
    }

    public static function getCategoryByBrand_id($brand_id){
        $obj = self::select('category_descriptions.category_id','category_descriptions.category');

        $obj->join('category_descriptions',function($join){
                $join->on('category_descriptions.category_id','=','categories_brands.category_id');
                $join->where('category_descriptions.lang_code','vi');
            })
            ->where('categories_brands.brand_id',$brand_id);

        return $obj->get()->toArray();
    }
}