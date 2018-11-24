<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCategories extends Model
{
    protected $table = 'notification_categories';
    protected $primaryKey = 'notification_category_id';
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
