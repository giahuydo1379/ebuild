<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \App\Helpers\General;

class SaleHot extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'sale_hot';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','description','position','link','image_location','image_url','date_from','date_to','status','user_created','user_modified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getData($params=[], $limit=10) {
        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('*')
            ->where('is_deleted', 0);

        if(isset($params['status_filter'])) {
            $objects->where('status', $params['status_filter']);
        }

        $objects->orderBy('updated_at','desc');

        return $objects->paginate($limit)->toArray();
    }
}