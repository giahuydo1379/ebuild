<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategories extends Model
{
    protected $table = 'news_categories';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'alias', 'ordering', 'status', 'user_created', 'user_modified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
