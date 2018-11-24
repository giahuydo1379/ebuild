<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [];

    protected $fillable = ['username', 'group_id', 'language_id', 'email', 'password', 'fullname', 'avatar', 'name_facebook',
        'phone', 'phone_type', 'promotion_name', 'fax','birthday', 'gender', 'address', 'address2',
        'zip', 'card_id', 'city_id', 'district_id','ward_id', 'street', 'lane', 'country_id',
        'job_type', 'status', 'tax', 'order_count','notes', 'ip', 'sync_crm','itl_user_id','crm_is_sync', 'crm_id', 'my_xu','sync_xu_promotion'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
