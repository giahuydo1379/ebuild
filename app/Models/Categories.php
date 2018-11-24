<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 1/30/2018
 * Time: 11:32 AM
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $primaryKey = 'category_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id','parent_id','id_path','image_location','image_url',
        'status','product_count','position','timestamp','is_op','localization','age_verification',
        'age_limit','parent_age_verification','parent_age_limit','selected_layouts','default_layout',
        'product_details_layout','is_show_frontend'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    static function get_status_options() {
        return [
            'A' => 'Kích hoạt',
            'H' => 'Vô hiệu hoá',
            'D' => 'Đã xóa',
        ];
    }

    public static function getAllCategoriesShowFront($locale='vi')
    {
        $list_all_cate = self::select('categories.category_id', 'categories.parent_id',
            'category_descriptions.category'
        )
            ->join('category_descriptions', 'category_descriptions.category_id', '=', 'categories.category_id')
            ->where('status', 'A')
            ->where('is_show_frontend', 1)
            ->where('category_descriptions.lang_code', $locale)
            ->orderBy('position', 'asc')
            ->orderBy('category', 'asc')
            ->get()->toArray();

        $tmp = [];
        foreach($list_all_cate as $key => $value){
            $tmp['parent'][$value['parent_id']][]   = $value['category_id'];
            $tmp['item'][$value['category_id']]     = $value;
        }

        $options = [];

        \App\Helpers\General::handlingCategories($options, $tmp,0);

        return $options;
    }

    public static function getAllCategories($locale='vi')
    {
        $objects = self::select('categories.*',
            'category_descriptions.category as category_name'
        )
            ->join('category_descriptions', 'category_descriptions.category_id', '=', 'categories.category_id')
            ->whereIn('status', ['A', 'H'])
            ->where('category_descriptions.lang_code', $locale)
            ->orderBy('position', 'asc')
            ->get()->toArray();

        $groups = [];
        $list = [];
        foreach ($objects as $item) {
            $groups[$item['parent_id']][] = $item['category_id'];
            $list[$item['category_id']] = $item;
        }

        return ['groups' => $groups, 'list' => $list];
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function getData($params=[],$limit = 10){

        $table = self::getTableName();

        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select($table.'.*','category_descriptions.*','cd2.category as parent_name');

        if (isset($params['parent_id'])) {
            $objects->where($table.'.parent_id', $params['parent_id']);
        }

        if (isset($params['status-filter']) && $params['status-filter']!==null) {
            $objects->where($table.'.status', $params['status-filter']);
        }

        $objects->join('category_descriptions',function($join) use($table){
            $join->on('category_descriptions.category_id','=',$table.'.category_id');
            $join->where('category_descriptions.lang_code','vi');
        });

        $objects->leftJoin('category_descriptions as cd2',function($join) use($table){
            $join->on('cd2.category_id','=',$table.'.parent_id');
            $join->where('cd2.lang_code','vi');
        });

        $objects->orderBy($table.'.id_path', 'asc');
        $objects->orderBy($table.'.position', 'asc');

        $objects = $objects->paginate($limit);

        //$objects = $objects->appends($params)->toArray();

        $objects = $objects->toArray();

        $tmp = [];
        foreach($objects['data'] as $item){
            $tmp[$item['category_id']] = $item;
        }
        $objects['data'] = $tmp;

        return $objects;
    }

    public static function getFilterCategoryByIds($ids){
        return self::getDataByIds($ids, ['category_descriptions.category_id', 'category_descriptions.category']);
    }

    public static function getDataByIds($ids, $fields=""){
        $table = self::getTableName();

        $fields = $fields ? $fields : [$table.'.*', 'category_descriptions.*'];

        $objects = self::select($fields);
        $objects->whereIn($table.'.category_id',$ids);

        $objects->join('category_descriptions',function($join) use($table){
            $join->on('category_descriptions.category_id','=',$table.'.category_id');
            $join->where('category_descriptions.lang_code','vi');
        });

        $objects = $objects->get()->toArray();

        return $objects;
    }

    public static function getDataById($id){
        $table = self::getTableName();

        $objects = self::select($table.'.*','category_descriptions.*','cd2.category as parent_name');
        $objects->where($table.'.category_id',$id);

        $objects->join('category_descriptions',function($join) use($table){
            $join->on('category_descriptions.category_id','=',$table.'.category_id');
            $join->where('category_descriptions.lang_code','vi');
        });

        $objects->leftJoin('category_descriptions as cd2',function($join) use($table){
            $join->on('cd2.category_id','=',$table.'.parent_id');
            $join->where('cd2.lang_code','vi');
        });

        $objects = $objects->first()->toArray();

        return $objects;
    }

    public static function getAllData(){

        $table = self::getTableName();

        $objects = self::select($table.'.*', 'category_descriptions.category');

        $objects->join('category_descriptions',function($join) use($table){
            $join->on('category_descriptions.category_id','=',$table.'.category_id');
        });

        $objects->whereIn($table.'.status', ['A', 'H']);

        $objects->orderBy('position', 'asc');

        $objects = $objects->get()->toArray();

        return $objects;
    }
    public static function getRootCategoryId($category) {
        $tmp = explode('/', $category['id_path']);

        return $tmp[0];
    }
}
