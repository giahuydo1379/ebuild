<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \App\Helpers\General;

class Coupons extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'coupons';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code','name','type','status','discount','is_percent','times','used','min_amount','date_from','date_to','created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getData($params=[],$limit=10){
        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('*');

        if(isset($params['list_coupons']))
            $objects->whereIn('code',$params['list_coupons']);

        if (isset($params['is_export'])) {
            return $objects->get();
        }
        $objects->orderBy('updated_at','desc');
        return $objects->paginate($limit)->toArray();
    }

}