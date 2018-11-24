<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/2/2018
 * Time: 3:01 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductsCategories extends Model
{
    protected $table = 'products_categories';

    protected $primaryKey = 'product_id';

    protected $fillable = ['product_id', 'category_id', 'link_type', 'position'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function primary(){
        return ['category_id'];
    }


    public static function getDataByProductId($product_ids){
        $query = self::select('products_categories.*');

        $query->whereIn('products_categories.product_id',$product_ids);
        $query->where('products_categories.link_type','M');

        return $query->get()->toArray();
    }
}