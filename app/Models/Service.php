<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Service extends Model
{
    use CrudTrait;
    use Sluggable, SluggableScopeHelpers;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'services';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name','parent_id', 'slug', 'image_location', 'image_url', 'position', 'status', 'user_created',
        'user_modified', 'is_deleted', 'deleted_at','title_description'];
    // protected $hidden = [];
    // protected $dates = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug_or_name',
            ],
        ];
    }
    // The slug is created automatically from the "title" field if no slug exists.
    public function getSlugOrNameAttribute()
    {
        if ($this->slug != '') {
            return $this->slug;
        }

        return $this->name;
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
    public function parent()
    {
        return $this->belongsTo('App\Models\Service', 'parent_id');
    }

    public function servicesUnitsServices()
    {
        return $this->hasMany('App\Models\ServicesUnits', 'service_id');
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
        $query = self::select('services.*','s2.name as parent_name')->where('services.is_deleted',0);
        $query->leftJoin(\DB::raw('services as s2'),'services.parent_id','=','s2.id');

        if(!empty($params['name']))
            $query->where('services.name','like','%'.$params['name'].'%');
        if(!empty($params['parent_id']))
            $query->where('services.parent_id',$params['parent_id']);

        $query->orderBy('services.position');

        return $query->paginate($limit)->toArray();
    }

    public static function getAllData(){
        return  self::select('*')->get()->toArray();
    }
}
