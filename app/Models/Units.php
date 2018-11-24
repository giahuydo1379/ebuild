<?php
/**
 * Created by PhpStorm.
 * User: quocn_000
 * Date: 26/09/2018
 * Time: 11:22 SA
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    protected $fillable = ['id','name'];

    protected $hidden = [];

    public static function getData($params=[],$limit = 10)
    {
    	if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('units.*');

        $objects->where('units.is_deleted',0);

        if(!empty($params['name']))
            $objects->where('units.name','like', '%'.$params['name'].'%');

        $objects = $objects->paginate($limit);

        $objects = $objects->toArray();

        return $objects;
    }

    public static function getAllData(){
        $objects = self::select('units.*');

        $objects->where('units.is_deleted', 0);

        $rs = $objects->get()->toArray();

        return $rs;
    }

    public static function getDataById($id) 
    {
    	$objects = self::select('units.*');

    	$objects = $objects->find($id)->toArray();

    	return $objects;
    }
}