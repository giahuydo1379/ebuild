<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductsPackages extends Model
{
    protected $table = 'products_packages';

    protected $primaryKey = 'package_id';

    protected $fillable = ['package_id','product_id','position'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];    

}