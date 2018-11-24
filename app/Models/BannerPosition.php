<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPosition extends Model
{
    protected $table = 'banner_positions';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
