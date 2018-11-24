<?php
/**
 * Created by PhpStorm.
 * User: phoenix.phung
 * Date: 2/2/2018
 * Time: 2:04 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BrandDescriptions extends Model
{
    protected $table = 'brand_descriptions';

    protected $primaryKey = 'brand_id';

    protected $fillable = ['brand_id','lang_code','name','description','meta_keywords','meta_description','page_title','alias'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function primary(){
        return ['lang_code'];
    }

}