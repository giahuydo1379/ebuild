<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCenter extends Model
{
    protected $table = 'service_center';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','brand_id', 'province_id', 'district_id', 'ward_id', 'address', 'phone', 'opening_time','embed_map', 'is_deleted'];

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
}
