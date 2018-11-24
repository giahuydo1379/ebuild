<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Freezer extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'freezers';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'position', 'status', 'user_created', 'user_modified', 'is_deleted', 'deleted_at'];
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
        $query = self::select('freezers.*')->where('freezers.is_deleted',0);
        if(!empty($params['name']))
            $query->where('freezers.name','like','%'.$params['name'].'%');

        $query->orderBy('freezers.position');

        return $query->paginate($limit)->toArray();
    }

    public static function getAllData(){
        $query = self::select('*')->where('is_deleted',0);
        return $query->get()->toArray();
    }
}
