<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advices extends Model
{
    protected $table = 'advices';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'alias', 'category_id', 'keywords', 'description', 'content', 'image_url',
        'status', 'user_created', 'user_modified','brand_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
