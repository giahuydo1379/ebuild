<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \App\Helpers\General;

class CouponsApply extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'coupons_apply';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['coupon_id','object_id','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public $timestamps = false;

    public static function getProductCodeByCouponID($coupon_id){

        $query = self::select('products.product_code')
                    ->where('coupons_apply.coupon_id',$coupon_id)
                    ->join('products','products.product_id','=','coupons_apply.object_id');
        $result = $query->get();
        if($result)
            return implode(',', $result->pluck('product_code')->toArray());
        return false;
    }

    public static function getBrandNameByCouponID($coupon_id){
        $query = self::select('brand_descriptions.name')
                    ->where('coupons_apply.coupon_id',$coupon_id)
                    ->join('brand_descriptions',function($join){
                        $join->on('brand_descriptions.brand_id','=','coupons_apply.object_id');
                        $join->where('brand_descriptions.lang_code','vi');
                    });
        $result = $query->get();
        if($result)
            return implode(',', $result->pluck('name')->toArray());
        return false;
    }

    public static function getBrandIDByCouponID($coupon_id){
        $query = self::select('object_id')
                    ->where('coupons_apply.coupon_id',$coupon_id);
        return $query->get()->pluck('object_id')->toArray();
    }
}