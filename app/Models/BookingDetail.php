<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class BookingDetail extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'booking_detail';
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

    public static function getDataByBookingId($booking_id){
        $query = self::select('booking_detail.*','services_units.name');
        $query->leftJoin('services_units','services_units.id','=','booking_detail.service_unit_id');

        $query->where('booking_detail.booking_id',$booking_id);

        return $query->first()->toArray();

    }

    public static function getData($booking_ids, $lang_code='vi'){
        $query = self::select('booking_detail.*')
            ->whereIn('booking_detail.booking_id', $booking_ids);

        $rs = $query->get()->toArray();
        $data = [];
        foreach($rs as $item){
            $data[$item['booking_id']][] = $item;
        }

        return $data;
    }

    public static function getDataByOrderId($booking_id){
        $query = self::select('booking_detail.*', 'services_units.name as service_unit_name');

        $query->leftJoin('services_units', 'services_units.id', '=', 'booking_detail.service_unit_id');

        if(is_array($booking_id)){
            $query->whereIn('booking_detail.booking_id',$booking_id);

            $result = $query->get()->toArray();
            $data = [];
            foreach($result as $item){
                $data[$item['booking_id']][] = $item;
            }

            return $data;
        }

        $query->where('booking_detail.booking_id',$booking_id);

        $data = $query->get()->toArray();
        $result = [];
        foreach($data as $item){
            $result[] = $item;
        }

        return $result;
    }
}
