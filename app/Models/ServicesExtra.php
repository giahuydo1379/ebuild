<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ServicesExtra extends Model
{
    use CrudTrait;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'services_extra';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['service_id', 'name','price', 'position', 'status', 'user_created',
        'user_modified', 'is_deleted', 'deleted_at'];
    // protected $hidden = [];
    // protected $dates = [];

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
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
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
        $query = self::select('services_extra.*','services.name as service_name')->where('services_extra.is_deleted',0);
        $query->leftJoin('services','services.id','=','services_extra.service_id');

        if(!empty($params['name']))
            $query->where('services_extra.name','like','%'.$params['name'].'%');
        if(!empty($params['service_id']))
            $query->where('services_extra.service_id',$params['service_id']);

        $query->orderBy('services_extra.position');

        return $query->paginate($limit)->toArray();
    }
}
