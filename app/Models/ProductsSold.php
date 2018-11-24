<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsSold extends Model
{
    protected $table = 'products_sold';
    //protected $primaryKey = 'id';
    //public $timestamps = [ "created_at" ];
    public $timestamps = false;
    protected $dates = ['created_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'product_sold_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
