<?php
/**
 * Created by PhpStorm.
 * User: quocn_000
 * Date: 25/09/2018
 * Time: 4:56 CH
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WarehousesPlaces extends Model
{
    protected $table = 'warehouses_places';
    protected $primaryKey = 'id';
    protected $fillable = [
        'warehouse_id',
        'province_id',
        'district_id',
        'ward_id'
    ];

    public static function getWarehousesPlacesById($warehouse_id)
    {
    	$query = self::select('warehouses_places.*')
                ->where('warehouses_places.warehouse_id', (int)$warehouse_id);

        $result = $query->get()->toArray();
        return $result;     
    }

    public static function getWarehouseId($ward_id,$district_id){
        $result = self::select('warehouse_id')
        ->where('ward_id',$ward_id)
        ->first();

        if($result) return $result->warehouse_id;

        $result = self::select('warehouse_id')
        ->where('district_id',$district_id)
        ->first();

        if($result) return $result->warehouse_id;

        return false;
    }
}