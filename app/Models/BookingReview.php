<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BookingReview extends Model
{
    protected $table = 'booking_review';

    protected $primaryKey = 'id';

    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];    

    public static function getData($limit=10){
        $query = self::select('booking_review.*', 'booking.code', 'customers.name as fullname');
        $query->leftJoin('booking',function($join){
            $join->on('booking.id', '=', 'booking_review.booking_id');
        });
        $query->leftJoin('customers',function($join){
            $join->on('customers.id','=','booking_review.customer_id');
        });
        $query->orderBy('booking_review.id','desc');
        return $query->paginate($limit)->toArray();
    }

    public static function getTopReviews($limit=6) {
        return self::select('booking_review.*',
            'customers.name as fullname',
            'customers.avatar'
        )
            ->leftJoin('customers', 'customers.id', '=', 'booking_review.customer_id')
            ->orderBy('booking_review.id', 'desc')
            ->limit($limit)
            ->get()->toArray();
    }
}