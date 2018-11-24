<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ServicesFreezerUnits extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'services_freezer_units';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['freezer_id','freezer_capacity_id','freezer_number','price', 'status', 'user_created', 'user_modified', 'is_deleted', 'deleted_at'];
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
    public function Freezer(){
        return $this->belongsTo('App\Models\Freezer', 'freezer_id');
    }
    public function FreezerCapacity(){
        return $this->belongsTo('App\Models\FreezerCapacity', 'freezer_capacity_id');
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

    public static function getData($params = [],$limit=10){
        $query = self::select('services_freezer_units.*','freezers.name as freezers_name','freezers_capacity.name as freezers_capacity_name');
        $query->leftJoin('freezers','freezers.id','=','services_freezer_units.freezer_id');
        $query->leftJoin('freezers_capacity','freezers_capacity.id','=','services_freezer_units.freezer_capacity_id');

        if(!empty($params['freezer_id']))
            $query->where('services_freezer_units.freezer_id',$params['freezer_id']);

        if(!empty($params['freezer_capacity_id']))
            $query->where('services_freezer_units.freezer_capacity_id',$params['freezer_capacity_id']);

        return $query->paginate($limit)->toArray();
    }
}
