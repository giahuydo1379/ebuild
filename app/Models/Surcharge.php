<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surcharge extends Model
{
    protected $table = 'surcharge';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_id',
        'supplier_id',
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getData($params=[],$limit = 10, $lang_code='vi'){

        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('surcharge.*', 'units.name as unit_name', 'suppliers.name as suppliers_name');
        $objects->leftJoin('units',function($join){
            $join->on('units.id','=','surcharge.unit_id');
        });
        $objects->leftJoin('suppliers',function($join){
            $join->on('suppliers.id','=','surcharge.supplier_id');
        });

        if(!empty($params['name']))
            $objects->where('surcharge.name','like','%'.$params['name'].'%');

        if(!empty($params['unit_id']))
            $objects->where('surcharge.unit_id','=',$params['unit_id']);

        if(!empty($params['supplier_id']))
            $objects->where('surcharge.supplier_id','=',$params['supplier_id']);

        if(!empty($params['category_id'])) {
            $objects->leftJoin('surcharge_categories',function($join){
            $join->on('surcharge_categories.surcharge_id','=','surcharge.id');
            });
            $objects->leftJoin('category_descriptions',function($join) use($lang_code, $params){
                $join->on('category_descriptions.category_id','=','surcharge_categories.category_id');
                $join->where('category_descriptions.lang_code', $lang_code);
            }); 
            $objects->where('surcharge_categories.category_id','=',$params['category_id']);
        }

        $objects->orderBy('surcharge.id','desc');

        $objects = $objects->paginate($limit);

        $objects = $objects->toArray();

        return $objects;
    }
}