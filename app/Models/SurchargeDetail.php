<?php
/**
 * Created by PhpStorm.
 * User: quocn_000
 * Date: 17/10/2018
 * Time: 1:26 CH
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SurchargeDetail extends Model
{
    protected $table = 'surcharge_detail';

    protected $primaryKey = 'surcharge_id';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'surcharge_id',
        'from',
        'to',
        'price'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getSurchargeDetailById($surcharge_id)
    {
        $query = self::select('surcharge_detail.*')
                ->where('surcharge_detail.surcharge_id', (int)$surcharge_id)
                ->orderBy('from');
        $result = $query->get()->toArray();
        return $result;     
    }
}