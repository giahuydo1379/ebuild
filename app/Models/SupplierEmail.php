<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierEmail extends Model
{
    protected $table = 'supplier_emails';

    protected $primaryKey = 'supplier_id';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id',
        'email',
        'supplier_config_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public static function getData($params=[],$limit = 10) {
        if(!empty($params['limit']))
            $limit = (int)$params['limit'];

        $objects = self::select('supplier_emails.*', 'suppliers.name', 'suppliers.status');
        $objects->leftJoin('suppliers', 'suppliers.id', '=', 'supplier_emails.supplier_id');
        $objects->where('is_deleted',0);
        $objects->orderBy('supplier_emails.supplier_id');

        return $objects->paginate($limit)->toArray();
    }
}
