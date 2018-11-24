<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    protected $table = 'product_comments';
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

    static function getTopComments($limit=6) {
        return self::select('product_comments.*',
            'product_comments.fullname as fullname_visitor',
            'users.fullname',
            'users.avatar',
            'product_descriptions.product as product_name'
        )
                ->leftJoin('users', 'users.user_id', '=', 'product_comments.user_id')
                ->join('product_descriptions', 'product_descriptions.product_id', '=', 'product_comments.product_id')
                ->orderBy('product_comments.id', 'desc')
                ->limit($limit)
                ->get()->toArray();
    }
}
