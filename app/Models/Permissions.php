<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = ['name', 'route', 'created_at', 'updated_at'];

    public static function getPermissionsByUser($user_id)
    {
        \Log::info('getPermissionsByUser');

        $role_ids = \App\Models\UserHasRole::where('user_id', $user_id)
            ->join('roles', function($join)
            {
                $join->on('roles.id', '=', 'admin_user_has_roles.role_id')
                    ->where('roles.is_deleted', '=', 0);
            })
            ->pluck('role_id')->toArray();

        if ($role_ids) {
            $objects = self::select('permissions.route', 'role_has_permissions.permission_id')
                ->leftJoin('role_has_permissions', function($join) use ($role_ids)
                {
                    $join->on('role_has_permissions.permission_id', '=', 'permissions.id')
                        ->whereIn('role_has_permissions.role_id', $role_ids);
                })
                ->where('permissions.parent_id', '>', 0)
                ->where('permissions.is_deleted', '=', 0)
                ->pluck('permission_id', 'route')->toArray();
        } else {
            $objects = self::select('permissions.route', 'is_deleted')
//                ->where('permissions.parent_id', '>', 0)
                ->where('permissions.is_deleted', '=', 0)
                ->pluck('is_deleted', 'route')->toArray();
        }

        return $objects;
    }

    /**
     * @param $filter
     * @return array
     * @author HaLV
     */
    static public function getAllShow($filter)
    {
        $sql = Permissions::select("*");

        $sql->where('is_deleted', 0);

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('route', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], 'desc')
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }
}
