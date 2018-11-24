<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audits extends Model
{
    protected $table = 'audits';
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

    public static function getOptionsEvent() {
        return [
            'created' => 'Thêm mới',
            'updated' => 'Cập nhật',
            'deleted' => 'Xóa',
        ];
    }

    public static function getAudits($auditable_type, $auditable_id) {
        $objects = Audits::select('audits.*',
            'admin_users.full_name')
            ->where('auditable_id', $auditable_id);

        if (is_array($auditable_type)) {
            $objects->whereIn('auditable_type', $auditable_type);
        } else {
            $objects->where('auditable_type', $auditable_type);
        }

        $objects->leftJoin('admin_users',function($join){
            $join->on('admin_users.id', '=', 'audits.user_id');
        });

        $objects->orderBy('audits.created_at','desc');
        $objects->orderBy('audits.id','desc');

        return $objects->get()->toArray();
    }
}
