<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Microsite extends Model
{
    protected $table = 'microsites';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'alias', 'from_date', 'to_date', 'image_location', 'image_url', 'banner', 'status', 'banners_other',
        'description', 'user_created', 'user_modified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
