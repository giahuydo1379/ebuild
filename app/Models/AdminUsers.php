<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUsers extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['department_id', 'username', 'full_name', 'email', 'password', 'remember_token', 'gender', 'birthday',
        'personal_id', 'place_of_issue', 'date_of_issue', 'front_image_url','back_image_url', 'address', 'ward', 'district',
        'city', 'phone', 'skype', 'vcb_account_holder','vcb_account_number', 'vcb_account_branch', 'is_allow_cms', 'is_allow_report',
        'is_enabled', 'is_verified', 'last_login', 'created_at','updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getData(){
        $query = self::select(\DB::raw('CONCAT(`username`," - ",`full_name`) as name'),'id');
        $query->where('id', '!=', 1);
        $rs = $query->get()->toArray();

        return $rs;
    }

    public static function getSales() {
        $query = self::select(\DB::raw('CONCAT(`username`," - ",`full_name`) as name'), 'id');
        $query->where('id', '!=', 1);
        $query->where('department_id', 3);
        $list = $query->get()->toArray();

        $rs = array(["name" => "00005600 - Admin", "id" => 2]);
        foreach ($list as $item) {
            $rs[] = $item;
        }

        return $rs;
    }

    public static function getDataById($id){
        $query = self::select('*');

        $result = $query->find($id);

        if($result)
            return $result->toArray();
        
        return false;
    }
}
