<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    /**
     * @param $filter
     * @return array
     * @author HaLV
     */
    static public function getAllShow($filter=[])
    {
        $sql = Roles::select("*");

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], 'desc')
            ->where('is_deleted', 0)
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

    public static function getAllData(){
        $query = self::select('*')->where('is_deleted',0);
        return $query->get()->toArray();
    }
}
