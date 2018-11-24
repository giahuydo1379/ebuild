<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['template','title','slug','content','image_location','image_url','image_link','page_name',
        'extras','seo_title','seo_description','seo_keyword','status'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function findBySlug($slug) {
        return self::where('slug', $slug)->first();
    }
}
