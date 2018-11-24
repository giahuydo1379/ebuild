<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ServicesUnits extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'services_units';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['service_id','name','description','price', 'position', 'status', 'user_created', 'user_modified', 'is_deleted', 'deleted_at'];
    // protected $hidden = [];
    // protected $dates = [];


    public function servicesUnitsServices()
    {
        return $this->belongsTo('App\Models\Service', 'service_id','id');
    }
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public static function getData($params=[],$limit = 10){
        $query = self::select('services_units.*','services.name as service_name')->where('services_units.is_deleted',0);
        $query->leftJoin('services','services.id','=','services_units.service_id');

        if(!empty($params['name']))
            $query->where('services_units.name','like','%'.$params['name'].'%');
        if(!empty($params['service_id']))
            $query->where('services_units.service_id',$params['service_id']);

        $query->orderBy('services_units.position');

        return $query->paginate($limit)->toArray();
    }
}
