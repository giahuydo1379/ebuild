<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','parent_id','name','slug','type','position','class','link','page_id','ordering'];

    public static function getData($params){
        $query = self::select('menu_items.*','pages.title as page_title');
        $query->leftJoin('pages',function($join){
            $join->on('pages.id','=','menu_items.page_id');
            $join->where('menu_items.type','page_link');
        });

        if (isset($params['position_filter'])) {
            $query->where('menu_items.position', $params['position_filter']);
        }
        $limit = $params['limit']??10;
        return $query->paginate($limit)->toArray();
    }
}
