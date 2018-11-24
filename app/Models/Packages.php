<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    protected $table = 'packages';

    protected $primaryKey = 'id';

    protected $fillable = ['category_id','package_name','duration','price','position'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];    

    public static function getData($limit=10){
        $query = self::select('packages.*','category_descriptions.category');
        $query->leftJoin('category_descriptions',function($join){
            $join->on('category_descriptions.category_id','=','packages.category_id');
            $join->where('category_descriptions.lang_code','vi');
        });

        return $query->paginate($limit)->toArray();
    }

    public static function getPackagesByCategoryIds($category_ids){
        $query = self::select('packages.*');
        $query->whereIn('category_id',$category_ids);
        $result = $query->get()->toArray();
        return $result;
    }
}