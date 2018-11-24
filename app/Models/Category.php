<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const CREATED_AT = 'date';
    const UPDATED_AT = 'date_modified';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','parent_id','order','active','url','description','is_hot','is_home'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    static function get_status_options() {
        return [
            '0' => 'Chờ duyệt',
            '1' => 'Đã kích hoạt',
        ];
    }
}
