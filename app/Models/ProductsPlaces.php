<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsPlaces extends Model
{
	protected $table = 'products_places';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id',
        'province_id',
        'district_id',
        'ward_id'
    ];
    public static function getProductsPlacesById($product_id)
    {
    	$query = self::select('products_places.*')
                ->where('products_places.product_id', (int)$product_id);

        $result = $query->get()->toArray();
        return $result;      	
    }
}