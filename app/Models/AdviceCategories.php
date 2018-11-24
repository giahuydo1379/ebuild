<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdviceCategories extends Model
{
    protected $table = 'advice_categories';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'alias','brand_id', 'ordering', 'status', 'user_created', 'user_modified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
