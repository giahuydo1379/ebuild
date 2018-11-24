<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Features extends Model
{
    protected $table = 'product_features';

    protected $primaryKey = 'id';

//    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name_vi', 'name_en', 'position', 'type',
        'status', 'is_range', 'is_show_frontend', 'description'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];


    public function FeatureVariants()
    {
        return $this->hasMany('App\Models\FeatureVariants','feature_id','id')->where('is_deleted',0);
    }

    public static function getDataById($id) {
        $table = 'product_features';

        $objects = self::select($table.'.*');

        $objects->where($table.'.id', $id);

        $objects = $objects->first()->toArray();

        return $objects;
    }

    public static function getParentFeatures($params=[],$limit = 10){

        $table = 'product_features';

        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select($table.'.*', $table.'.name_vi as name');

        if (isset($params['group-status-filter']) && $params['group-status-filter']!==null) {
            $objects->where($table.'.status', $params['group-status-filter']);
        }

        $objects->where($table.'.parent_id', 0);

        $objects->orderBy('position', 'ASC');

        $objects = $objects->paginate($limit);

        $objects = $objects->toArray();

        return $objects;
    }

    public static function getFeaturesByCategory($category_id) {
        $table = 'product_features';

        $objects = self::select($table.'.*');

        $objects->where($table.'.status', 1);

        $objects->where($table.'.categories_path', 'LIKE', '%'.$category_id.'%');

        $objects->orderBy('position', 'ASC');

        $objects = $objects->get()->toArray();
        return $objects;

        $rs = [];
        foreach ($objects as $item) {
            $rs[$item['parent_id']][] = $item;
        }

        return $rs;
    }

    public static function getFeatures($params=[]) {
        $table = 'product_features';

        $objects = self::select($table.'.*', $table.'.name_vi as name');

        if (isset($params['status-filter']) && $params['status-filter']!==null) {
            $objects->where($table.'.status', $params['status-filter']);
        }

        $objects->whereIn($table.'.parent_id', $params['parent_ids']);

        $objects->orderBy('position', 'ASC');

        $objects = $objects->get()->toArray();
        $rs = [];
        foreach ($objects as $item) {
            $rs[$item['parent_id']][] = $item;
        }

        return $rs;
    }

    public static function getOptionsFeatures($type='') {
        $query = self::select('product_features.*', 'product_features.name_vi as name')
            ->where('status', 1)
            ->where('is_deleted', 0);

        if ($type) {
            $query->where('type', $type);
        }

        $objects = $query->get()->toArray();
        $rs = [];
        foreach ($objects as $item) {
            $rs['options'][$item['id']] = $item['name'];
            $rs['list'][$item['id']] = $item;
        }

        return $rs;
    }

    public static function getOptionsForJobs($re_cache=false) {
        $key = 'Features:Jobs';

        $objects = Cache::get( $key );

        if ($re_cache || !$objects) {

            $query = self::select('product_features.*', 'product_features.name_vi as name')
                ->where('status', 1);
            $objects = $query->pluck('name', 'id')->toArray();

            Cache::forever($key, $objects);
        }

        return $objects;
    }
}