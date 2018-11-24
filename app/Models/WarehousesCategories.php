<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/2/2018
 * Time: 3:01 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WarehousesCategories extends Model
{
    protected $table = 'warehouses_categories';

    protected $primaryKey = 'warehouse_id';

    protected $fillable = ['warehouse_id', 'category_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}