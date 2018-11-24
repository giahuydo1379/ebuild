<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'short_description',
        'full_description',
        'phone',
        'tax_code',
        'address',
        'ward_id',
        'district_id',
        'city_id',
        'province_id',
        'image_location',
        'image_url',
        'status',
        'is_deleted',
        'contact_name',
        'contact_email',
        'contact_phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function warehouses(){
        return $this->hasMany('App\Models\Warehouses', 'supplier_id', 'id');
    }
	public static function getData($params=[],$limit = 10){

        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('*');
        $objects->where('is_deleted',0);

        if(!empty($params['name']))
            $objects->where('name','like','%'.$params['name'].'%');

        if(!empty($params['phone']))
            $objects->where('phone',$params['phone']);

        if(!empty($params['email']))
            $objects->where('email',$params['email']);

        if(isset($params['status']))
            $objects->where('status',$params['status']);

        $objects->orderBy('id','desc');

        $objects = $objects->paginate($limit);

        $objects = $objects->toArray();

        return $objects;
    }
	public static function get_options(){
        $query  = self::select('*')
            ->where('status', 1)
            ->where('is_deleted', 0);

        $query->orderBy('updated_at', 'desc');

        return $query->pluck('name', 'id')->toArray();
    }
}
