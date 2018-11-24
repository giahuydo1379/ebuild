<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageBusinessLine extends Model
{
    protected $table = 'page_business_lines';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'link', 'logo', 'type'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    static function get_type_options() {
        return [
            'amortization_bank' => 'Trả góp - Logo ngân hàng',
            'amortization_partner' => 'Trả góp - Logo đối tác',
            'amortization_line' => 'Trả góp - Ngành hàng kinh doanh',
            'introduction' => 'Giới thiệu công ty - Ngành hàng kinh doanh',
            'sale_b2b' => 'Giới thiệu công ty - Ngành hàng kinh doanh',
        ];
    }

    public static function getObjectsByKeys($types) {
        $objects = self::select('id', 'name', 'link', 'type')
            ->whereIn('type', $types)
            ->where('is_deleted', 0)
            ->get();

        $rs = [];
        foreach ($objects as $item) {
            $rs[$item->type][] = $item->toArray();
        }

        return $rs;
    }
}
