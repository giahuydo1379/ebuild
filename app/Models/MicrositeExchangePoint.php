<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicrositeExchangePoint extends Model
{
    protected $table = 'microsite_exchange_points';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'alias', 'from_date', 'to_date', 'layout', 'banner', 'status', 'banners_other',
        'description', 'rule', 'interest', 'guide', 'gifts_products', 'user_created', 'user_modified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
