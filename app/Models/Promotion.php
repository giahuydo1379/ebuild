<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['package_id', 'name', 'from_date', 'to_date', 'apply_objects', 'gift_products', 'status',
        'user_created', 'user_modified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getAllPromotion(){
        $now = \Carbon\Carbon::now();
        
        $select = self::select('promotions.*', 'promotion_package.name as ppname', 'promotion_package.type as package_type')
                    ->where('promotions.from_date','<=',$now)
                    ->where('promotions.to_date','>=',$now)
                    ->where('promotions.status', 1);

        $select->join('promotion_package', function($join) {
            $join->on('promotion_package.id','=','promotions.package_id');
        });

        $select->orderBy('promotions.updated_at', 'desc');

        return $select->get()->toArray();
    }
}
