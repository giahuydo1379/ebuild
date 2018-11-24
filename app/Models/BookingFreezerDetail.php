<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class BookingFreezerDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'booking_freezer_detail';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
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
    public function customer(){
        return $this->belongsTo('App\Models\Freezer', 'freezer_id');
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
    public static function getDataByOrderId($booking_id){
        $query = self::select('booking_freezer_detail.*',
            'services_freezer_units.freezer_id', 'services_freezer_units.freezer_capacity_id');
        $query->leftJoin('services_freezer_units', 'services_freezer_units.id', '=', 'booking_freezer_detail.services_freezer_units_id');

        if(is_array($booking_id)){
            $query->whereIn('booking_freezer_detail.booking_id', $booking_id);

            $result = $query->get()->toArray();
            $data = [];
            foreach($result as $item){
                $data[$item['booking_id']][] = $item;
            }

            return $data;
        }

        $query->where('booking_freezer_detail.booking_id', $booking_id);

        $data = $query->get()->toArray();
        $result = [];
        foreach($data as $item){
            $result[] = $item;
        }

        return $result;
    }
}
