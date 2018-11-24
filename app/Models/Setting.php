<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'value', 'field'];

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

    public static function getObjectByKey($key = '') {
        $object = Setting::select('*')
                            ->where('key', $key)
                            ->where('active', 1)
                            ->first();
        if(!empty($object))
            return $object->toArray();
        return array();
    }

    public static function getObjectById($id) {
        $object = Setting::select('*')
            ->where('id', $id)
            ->where('active', 1)
            ->first();

        if(!empty($object))
            return $object->toArray();
        return array();
    }

    public static function getObjectsByKeys($keys) {
        $objects = Setting::select('id', 'key', 'value', 'field')
            ->whereIn('key', $keys)
            ->where('active', 1)
            ->get();

        $rs = [];
        foreach ($objects as $item) {
            $rs[$item->key] = $item->toArray();
        }

        return $rs;
    }
    public static function getAllSettings() {
        $tmp = self::all()->toArray();

        $objects = [];
        foreach ($tmp as $item) {
            $objects[$item['key']] = $item;
        }

        return $objects;
    }
}
