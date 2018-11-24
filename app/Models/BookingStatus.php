<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $table = 'booking_status';

    protected $primaryKey = 'id';

//    public $incrementing = false;
//    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['booking_status_id', 'booking_status_name', 'position', 'css_class', 'lang_code', 'status'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getAllData($lang_code='vi'){
        $query  = self::select('*')
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->where('lang_code', $lang_code);

        $query->orderBy('position', 'asc');
        $query->orderBy('booking_status_name', 'asc');

        $objects = $query->get()->toArray();
        $rs = [];
        foreach ($objects as $item) {
            $rs[$item['booking_status_id']] = $item;
        }

        return $rs;
    }
}
