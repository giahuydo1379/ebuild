<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'orders_status';

    protected $primaryKey = 'status';//['status', 'lang_code'];

    public $incrementing = false;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_status_name', 'position', 'css_class', 'status', 'is_active'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getAllData(){
        $query  = self::select('*')
            ->where('is_active', 1)
            ->where('is_deleted', 0);

        $query->orderBy('position', 'asc');
        $query->orderBy('order_status_name', 'asc');

        $objects = $query->get()->toArray();
        $rs = [];
        foreach ($objects as $item) {
            $rs[$item['status']] = $item;
        }

        return $rs;
    }
}
