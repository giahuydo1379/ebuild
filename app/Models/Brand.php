<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
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

    public static function get_status_options() {
        return [
            '0' => 'Chờ duyệt',
            '1' => 'Đã kích hoạt',
        ];
    }

    public static function getBrandsByIds($brand_ids=[]) {
        $table = 'brands';
        $locale = 'vi';

        $query = self::select('*', 'brand_descriptions.brand_id as id')
            ->join('brand_descriptions', function($join) use ($table, $locale) {
                $join->on('brand_descriptions.brand_id','=',$table.'.brand_id');
                $join->where('brand_descriptions.lang_code', $locale);
            })
            ->whereIn($table.'.brand_id', $brand_ids);
//            ->where($table.'.status', '=', 'A');

        $query->orderBy($table.'.position', 'asc');

        return $query->get()->toArray();
    }

    public static function getBrandsOptions() {
        $table = 'brands';
        $locale = 'vi';

        $query = self::select('brand_descriptions.brand_id', 'brand_descriptions.name')
            ->join('brand_descriptions', function($join) use ($table, $locale) {
                $join->on('brand_descriptions.brand_id','=',$table.'.brand_id');
                $join->where('brand_descriptions.lang_code', $locale);
            })
            ->where($table.'.status', '=', 'A');

        $query->orderBy($table.'.position', 'asc');

        return $query->pluck('name', 'brand_id')->toArray();
    }
}
