<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierConfig extends Model
{
    protected $table = 'supplier_config';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'supplier_id',
        'category_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function supplierEmails(){
        return $this->hasMany('App\Models\SupplierEmail', 'supplier_config_id', 'id');
    }
}
