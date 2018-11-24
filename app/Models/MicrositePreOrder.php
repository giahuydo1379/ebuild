<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicrositePreOrder extends Model
{
    protected $table = 'microsite_pre_order';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'alias', 'from_date', 'to_date', 'layout', 'banner', 'status', 'banner_content',
        'description','technical_specifications', 'products', 'user_created', 'user_modified','logo'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
