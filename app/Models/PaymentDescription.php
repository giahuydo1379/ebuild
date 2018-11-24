<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/2/2018
 * Time: 2:04 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentDescription extends Model
{
    protected $table = 'payment_descriptions';

    protected $primaryKey = 'payment_id';

    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getAllData($lang_code='vi'){
        $query = self::select('*')->where('lang_code',$lang_code);
        $rs = $query->get()->toArray();
        return $rs;
    }

}