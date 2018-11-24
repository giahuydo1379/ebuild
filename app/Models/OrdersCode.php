<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrdersCode extends Model
{
    protected $table = 'orders_code';

    protected $primaryKey = 'order_date';

    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_date', 'code', 'is_used'];
}
